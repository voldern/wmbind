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
		{if $v.id eq 1}
		<td>Delete</td>
		{else}
		<td><a href="{$smarty.const.site_addr}/users/delete/{$v.id}/">Delete</a></td>
		{/if}
	</tr>
	{/foreach}
</table>
