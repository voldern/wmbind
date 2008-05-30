{if $error != NULL}
<p>
<strong>The following errors prevented the user from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/users/register/">
<label for="username">Username:</label> <input type="text" name="username" id="username" value="{$smarty.post.username}" /> <br />
<label for="password">Password:</label> <input type="password" name="password" id="password" /> <br />
<label for="password2">Confirm password:</label> <input type="password" name="password2" id="password2" /> <br />
<label for="admin">Admin:</label> 
<input type="radio" name="admin" value="yes" />yes <input type="radio" name="admin" value="no" checked="checked" />no <br />
<input type="submit" value="Save" />
</form>
