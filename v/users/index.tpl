<a href="{$smarty.const.site_addr}/users/register">Add a new user</a> <br />

<table width="400px">
	<tr>
		<td><strong>Username</strong></td>
		<td><strong>Administrator</strong></td>
		<td><strong>Delete</strong></td>
	<tr>
	{foreach from=$users item=v}
	<tr>
		<td><a href="{$smarty.const.site_addr}/users/edit/{$v.id}">{$v.username}</a></td>
		<td>{$v.admin}</td>
		<td>delete</td>
	</tr>
	{/foreach}
</table>
