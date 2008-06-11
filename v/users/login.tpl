<h2>Login</h2>

{if $error != NULL}
<p>
<strong>The following errors prevented you from logging in:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/users/login">
<label>Username:</label> <input type="text" name="username" value="{$smarty.post.username}" /> <br />
<label>Password:</label> <input type="password" name="password" /> <br />
<input type="submit" id="submit" value="Login" />
</form>
