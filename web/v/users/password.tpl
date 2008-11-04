<h2>Change password</h2>

{if $error != NULL}
<p>
<strong>The following error(s) prevented you from changing password:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/users/password">
<label>Current password:</label> <input type="password" name="current" /> <br />
<label>New password:</label> <input type="password" name="password" /> <br />
<label>Repeate password:</label> <input type="password" name="password2" /> <br />
<input type="submit" id="submit" value="Change password" />
</form>
