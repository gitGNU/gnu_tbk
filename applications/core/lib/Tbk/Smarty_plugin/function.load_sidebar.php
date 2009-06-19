<?php
function smarty_function_load_sidebar($params,&$smarty)
{
	$output = "\n<ul>";
	$array = $params['from'];
	foreach($array as $head=>$sub){
		$output.="\n\t<li>";
		if(is_array($sub)){
			$output.="\n\t\t<h2>$head</h2>
			\n\t\t<ul>
			\n\t\t\t";
			foreach($sub as $texte=>$lien){
				$output.="<li><a href=\"$lien\">$texte</a></li>\n\t\t\t";
			}
			$output.="\n\t\t</ul>";
		}
		else{
			$output.="\n\t\t<h2><a href=\"$sub\">$head</a></h2>\n";
		}
		$output.="\n\t</li>";
	}
	$output.="\n</ul>";
	return $output;
}
?>
