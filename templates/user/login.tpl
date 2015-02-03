<form method="POST" action="{$SITE_PATH}/dyn/user/do_login">
    <span>Username:</span><input  type="text" name="username"/>
    <span>Password:</span><input type="password" name="password"/>  
    {if isset($ns.error_message)}
        <div class="error" >
            {$ns.error_message}
        </div>
    {/if}  
    <input  type="submit" />    
</form>