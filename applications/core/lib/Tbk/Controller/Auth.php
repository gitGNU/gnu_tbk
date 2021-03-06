<?php
/**
 *  +----------------------------------------------------------------------+
 *  | This file is part of TABARNAK - PHP Version 5                        |
 *  +----------------------------------------------------------------------+
 *  | Copyright (C) 2008-2009 Libricks                                     |
 *  +----------------------------------------------------------------------+
 *  | Ce programme est un logiciel libre distribue sous licence GNU/GPL.   |
 *  | Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne. |
 *  |                                                                      |
 *  | This program is free software; you can redistribute it and/or        |
 *  | modify it under the terms of the GNU General Public License          |
 *  | as published by the Free Software Foundation; either version 2       |
 *  | of the License, or (at your option) any later version.               |
 *  |                                                                      |
 *  | This program is distributed in the hope that it will be useful,      |
 *  | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 *  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
 *  | GNU General Public License for more details.                         |
 *  |                                                                      |
 *  | You should have received a copy of the GNU General Public License    |
 *  | along with this program; if not, write to                            |
 *  | the Free Software Foundation, Inc.,                                  |
 *  | 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA         |
 *  |                                                                      |
 *  +----------------------------------------------------------------------+
 *  | Authors: 	* Marc-Henri PAMISEUX <marc-henri.pamiseux@libricks.org>   |
 *  | 			* Jean-Baptiste BLANC <blanc.j.baptiste@gmail.com>		   |
 *  +----------------------------------------------------------------------+
 */
/**
 * Plugin d'authentification
 * 
 * Largement inspiré de :
 * http://julien-pauli.developpez.com/tutoriels/zend-framework/atelier/auth-http/?page=modele-MVC
**/

class Tbk_Controller_Auth extends Zend_Controller_Plugin_Abstract	{
	/**
	 * @var Zend_Auth instance 
	 */
	private $_auth;
	
	/**
	 * @var Zend_Acl instance 
	 */
	private $_acl;
	
	/**
	 * Chemin de redirection lors de l'échec d'authentification
	 */
	const FAIL_AUTH_MODULE     = 'login';
	const FAIL_AUTH_ACTION     = 'index';
	const FAIL_AUTH_CONTROLLER = 'index';
	
	/**
	 * Chemin de redirection lors de l'échec de contrôle des privilèges
	 */
	const FAIL_ACL_MODULE     = 'pub';
	const FAIL_ACL_ACTION     = 'index';
	const FAIL_ACL_CONTROLLER = 'index';
	
	/**
	 * Constructeur
	 */
	public function __construct(Zend_Acl $acl)	{
		$this->_acl  = $acl ;
		$this->_auth = Zend_Auth::getInstance() ;
	}
	
	/**
	 * Vérifie les autorisations
	 * Utilise _request et _response hérités et injectés par le FC
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)	{
		// is the user authenticated
		if ($this->_auth->hasIdentity()) {
		  // yes ! we get his role
		  $user = $this->_auth->getStorage()->read() ;
		  $role = $user['role'] ;
		} else {
		  // no = guest user
		  $role = 'invite';
		}
		
		$module 	= $request->getModuleName() ;
		$controller = $request->getControllerName() ;
		$action     = $request->getActionName() ;
		
		$front = Tbk_Controller_Front::getInstance() ;
		$default = $front->getDefaultModule() ;
		
		// compose le nom de la ressource
		$resource = $module.'_'.$controller ;
    
		// est-ce que la ressource existe ?
		if (!$this->_acl->has($resource)) {
		  $resource = null;
		}
		
		// contrôle si l'utilisateur est autorisé
		if (!$this->_acl->isAllowed($role, $resource, $action)) {
			// l'utilisateur n'est pas autorisé à accéder à cette ressource
			// on va le rediriger
			if (!$this->_auth->hasIdentity()) {
				// il n'est pas identifié -> module de login
				$module = self::FAIL_AUTH_MODULE ;
				$controller = self::FAIL_AUTH_CONTROLLER ;
				$action = self::FAIL_AUTH_ACTION ;
			} else {
				// il est identifié -> error de privilèges
				$module = self::FAIL_ACL_MODULE ;
				$controller = self::FAIL_ACL_CONTROLLER ;
				$action = self::FAIL_ACL_ACTION ;
			}
		}

		$request->setModuleName($module) ;
		$request->setControllerName($controller) ;
		$request->setActionName($action) ;
	}
}
