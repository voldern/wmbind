{if $error != NULL}
<p>
<strong>The following errors prevented the user from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/users/register/">
Username: <input type="text" name="username" value="{$smarty.post.username}" /> <br />
Password: <input type="password" name="password" /> <br />
Confirm password: <input type="password" name="password2" /> <br />
Admin: <input type="radio" name="admin" value="yes" />yes <input type="radio" name="admin" value="no" checked="checked" />no <br />
<input type="submit" value="Save" />
</form>
