<?php
/**
* Smarty plugin
* @package Smarty
* @subpackage plugins
*/

/**
* Smarty {assign} compiler function plugin
*
* Type:     compiler function<br>
* Name:     assign<br>
* Purpose:  assign a value to a template variable
* @link http://smarty.php.net/manual/en/language.custom.functions.php#LANGUAGE.FUNCTION.ASSIGN {assign}
*       (Smarty online manual)
* @author Monte Ohrt <monte at ohrt dot com> (initial author)
* @auther messju mohr <messju at lammfellpuschen dot de> (conversion to compiler function)
* @param string containing var-attribute and value-attribute
* @param Smarty_Compiler
*/
function smarty_function_nestLoad($params, &$smarty)
{

	if (!isset($params['ns'])) {
		$smarty->trigger_error("nest: missing 'ns' parameter");
		return;
	}

	if (!isset($params['load'])) {
		$smarty->trigger_error("nest: missing 'load' parameter");
		return;
	}
	
	if (!isset($params['args'])) {
		$smarty->trigger_error("nest: missing 'args' parameter");
		return;
	}
	
	$loadArr = array();  
	$loadArr["load"] = $params['load'];
	$loadArr["args"] = $params['args'];
	$loadArr["loads"] = array();
	$loadObj = $smarty->_tpl_vars["ns"]["_cl"];
	$loadObj->nest($params["ns"], $loadArr);
	$loadParams = $loadObj->getParams();
	$smarty->_tpl_vars["ns"]["inc"] = $loadParams["inc"];
	$includeStr = "{include file=\"".$smarty->_tpl_vars["ns"]["inc"][$params["ns"]]["filename"]."\"";
	$includeStr .= ' ns=$ns.inc.'.$params["ns"].'.params';
	$includeStr .= "}";
	$params['var'] = $includeStr;

	$smarty->_compile_source('evaluated template', $params['var'], $_var_compiled);

	ob_start();
	$smarty->_eval('?>' . $_var_compiled);
	$_contents = ob_get_contents();
	ob_end_clean();

	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'], $_contents);
	} else {
		return $_contents;
	}
}

/* vim: set expandtab: */

?>