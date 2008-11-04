<h2>Viewing zone {$zone.name}</h2>

{if $error != NULL}
<p>
<strong>The following errors prevented the zone from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/zones/edit/{$zone.id}">
<table id="zone">
<tr>
	<td><strong>Zone:</strong></td>
	<td><input type="text" value="{$zone.name}" READONLY></td>
	<td><strong>Serial:</strong></td>
	<td><input type="text" value="{$zone.serial}" READONLY /></td>
</tr>

<tr>
	<td><strong>Refresh:</strong></td>
	<td><input type="text" name="refresh" id="refresh" value="{$zone.refresh}" /></td>
	<td><strong>Retry:</strong></td>
	<td><input type="text" name="retry" id="retry" value="{$zone.retry}" /></td>
</tr>

<tr>
	<td><strong>Expire:</strong></td>
	<td><input type="text" name="expire" id="expire" value="{$zone.expire}" /></td>
	<td><strong>TTL:</strong></td>
	<td><input type="text" name="ttl" id="ttl" value="{$zone.ttl}" /></td>
</tr>

<tr>
	<td><strong>NS1:</strong></td>
	<td><input type="text" name="pri_dns" id="pri_dns" value="{$zone.pri_dns}" /></td>
	<td><strong>NS2:</strong></td>
	<td><input type="text" name="sec_dns" id="sec_dns" value="{$zone.sec_dns}" /></td>
</tr>

<tr>
	<td><strong>Allow-transfer</strong></td>
	<td><input type="text" name="transfer" id="transfer" value="{$zone.transfer}" /></td>
	{if $smarty.session.admin eq true}
	<td><strong>Owner:</strong></td>
	<td><select name="owner" style="float: left;">{html_options options=$users selected=$zone.owner}</select></td>
	{/if}
</tr>

</table>
<hr />

<table width="600">
<tr>
	<td><strong>Host</strong></td>
	<td><strong>Type</strong></td>
	<td><strong>Destination</strong></td>
	<td><strong>Valid</strong></td>
	<td><strong>Delete</strong></td>
</tr>
{section name=i loop=$records}
<input type="hidden" name="host_id[{$smarty.section.i.index}]" value="{$records[i].id}" />
<tr>
	<td><input type="text" name="host[{$smarty.section.i.index}]" value="{$records[i].host}" /></td>
	<td><select name="type[{$smarty.section.i.index}]">{html_options options=$options selected=$records[i].type}</select>
	{if $records[i].type eq "MX"}
	<td>
	<input type="text" name="pri[{$smart.section.i.index}]" value="{$records[i].pri}" size="1" />
	<input type="text" name="destination[{$smarty.section.i.index}]" value="{$records[i].destination}" size="17" />
	</td>
	{else}
	<input type="hidden" name="pri[{$smart.section.i.index}]" value="0" />
	<td><input type="text" name="destination[{$smarty.section.i.index}]" value="{$records[i].destination}" /></td>
	{/if}
	<td>{$records[i].valid}</td>
	<td><input type="checkbox" name="delete[{$smarty.section.i.index}]" value="true" /></td>
</tr>
{/section}
<tr><td colspan="3"><hr size="1" noshade /></td></tr>
<tr>
	<td><input type="text" name="newhost" /></td>
	<td><select name="newtype">{html_options options=$options}</select></td>
	<td><input type="text" name="newdestination" /></td>
</tr>
<input type="hidden" name="total" value="{$smarty.section.i.total}" />
<tr><td><input type="submit" value="Save" /></td></tr>
</table>
</form>
