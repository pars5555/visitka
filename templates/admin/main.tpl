<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {include file="$TEMPLATE_DIR/admin/util/headerControls.tpl"}
    </head>
    <body>
            <header>
                {include file="$TEMPLATE_DIR/admin/util/header.tpl"} 
            </header>
        <div class="wrapper">
            <input type="hidden" id="initialLoad" name="initialLoad" value="admin_main" />		
            <input type="hidden" id="contentLoad" value="{$ns.contentLoad}" />
            <div id="main_content">
                {nest ns=content}
            </div>

        </div>
            <footer>
            {include file="$TEMPLATE_DIR/admin/util/footer.tpl"} 
            </footer>
    </body>
</html>