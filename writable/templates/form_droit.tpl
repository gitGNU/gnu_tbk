{php}
      $subs = $form->getSubForms();
      $compte_array = getRolesArray();
      
      echo "<table>
		<tr>
			<td></td>";
      
      foreach($compte_array as $name){
      	echo "<td>$name</td>";
      }
      
      echo "</tr>";
      
      $nbCompte = count($compte_array);
      
      foreach($subs as $k=>$v){
        echo '<tr><td class="cell_module" colspan="'.($nbCompte+1).'">'.$k.'</td></tr>';
        foreach($v as $b=>$d){
        	echo '<tr><td class="cell_controller" colspan="'.($nbCompte+1).'">'.$b.'</td></tr>';
          foreach($d as $w=>$c){
          	echo "<tr>
          		<td>$w</td>
          		<td>$c</td>
          	</tr>";
          }
        }
      }
      
      echo "<tr><td colspan="'.($nbCompte+1).'">".$form->submit."</td></td></table>";
{/php}