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
function smarty_function_load_paginator($params,&$smarty)
{
  $paginator = $params['paginator'];
  $total = $paginator->pageCount;
  $first = $paginator->first;
  $last = $paginator->last;
  $current = $paginator->current;
  $inRange = $paginator->pagesInRange;
  $firstPageInRange = $paginator->firstPageInRange;
  $lastPageInRange = $paginator->lastPageInRange;
  
  $items = array();
  if($total > 1){
    if($firstPageInRange != $first){
      array_push($items,$first);
      if($firstPageInRange != $first+1)
        array_push($items,'...');
    }

    foreach($inRange as $item){
      array_push($items,$item);
    }

    if($last == $lastPageInRange+1)
      array_push($items,$last);
    else if(!in_array($last,$inRange)){
      array_push($items,'...');
      array_push($items,$last);
    }
  }
  
  $smarty->assign($params['assign'],$items);
}
?>