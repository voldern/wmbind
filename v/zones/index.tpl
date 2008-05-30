<h2>Zones</h2>
<p><a href="{$smarty.const.site_addr}/zones/register">Create a new zone</a></p>
<br />
<table width="400">
	<tr>
		<td><strong>Name</strong></td>
		<td><strong>Serial</strong></td>
		<td><strong>Changed</strong></td>
		<td><strong>Valid</strong></td>
		<td><strong>Delete</strong></td>
	</tr>
	{foreach from=$zones item=v}
	<tr>
		<td><a href="{$smarty.const.site_addr}/zones/edit/{$v.id}">{$v.name}</a></td>
		<td>{$v.serial}</td>
		<td>{$v.updated}</td>
		<td>{$v.valid}</td>
		<td><a href="{$smarty.const.site_addr}/zones/delete/{$v.id}">Delete</a></td>
	</tr>
	{/foreach}
</table>
