{if $error != NULL}
<p>
<strong>The following errors prevented the user from beeing updated:</strong> <br />
{$error}
</p>
<br />
{/if}
<h2>Viewing user {$user.username }</h2>

<strong>Username: </strong> {$user.username}
<form method="POST" action="{$smarty.const.site_addr}/users/edit/{$user.id}">

<strong>Administrator:</strong> 
<input type="radio" name="admin" value="yes" {if $user.admin eq 'yes'}checked="checked"{/if} />yes 
<input type="radio" name="admin" value="no" {if $user.admin eq 'no'}checked="checked"{/if} />no <br />

<strong>New password:</strong> <input type="password" name="password" /> <br />
<strong>Repeate new password:</strong> <input type="password" name="password2" /> <br />
<input type="submit" value="Update" />
</form>
<br />


<h3>Zones owned by this user</h3>
<table width="400">
	<tr>
		<td><strong>Name</strong></td>
		<td><strong>Serial</strong></td>
	</tr>
</table>
