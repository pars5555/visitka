<form method="POST" action="{$SITE_PATH}/dyn/admin/do_login">
    <div class="login-wrapper">
        <div class="form-group">
            <label class="input_label">Username:</label>
            <input class="text"  type="text" name="username"/>
        </div>
        <div class="form-group">
            <label class="input_label">Password:</label>
            <input class="text" type="password" name="password"/>  
        </div>
        {if isset($ns.error_message)}
            <div class="error" >
                {$ns.error_message}
            </div>
        {/if}  
        <div class="login_btn">
            <input class="button grey" type="submit" value="Login" />    
        </div>
    </div>
</form>