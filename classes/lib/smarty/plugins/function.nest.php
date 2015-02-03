<?php
/**
 * Smarty plugin
 *
 * This plugin is only for Smarty3
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {math} function plugin
 *
 * Type:     function<br>
 * Name:     nest<br>
 * Purpose:  handle math computations in template
 * <br>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @param array $params parameters
 * @param object $template template object
 * @return render template|null
 */
function smarty_function_nest($params, $template){
 
		if (!isset($params['ns'])) {
        trigger_error("nest: missing 'ns' parameter");
        return;
    }
  
    if(!$template->tpl_vars["ns"]){
    	$template->tpl_vars["ns"] = $template->smarty->smarty->tpl_vars["ns"];   	
    }

    $nsValue = $template->tpl_vars["ns"]->value;
		$pmValue = $template->tpl_vars["pm"]->value;

    $include_file = $nsValue["inc"][$params["ns"]]["filename"];
    
		$_tpl = $template->smarty->createTemplate($include_file, null, null, $nsValue["inc"][$params["ns"]]["params"]);

		foreach($template->smarty->smarty->tpl_vars as $key=>$tplVars){
    	$_tpl->assign($key, $tplVars);
    }
    $_tpl->assign("ns", $nsValue["inc"][$params["ns"]]["params"]);
		
		$_tpl->assign("pm", $pmValue);
 
		if ($_tpl->mustCompile()) {
    	$_tpl->compileTemplateSource();                  
    }
    //$_tpl->renderTemplate();
    $_output = $_tpl->display();;

    return $_output;
   
}

?>