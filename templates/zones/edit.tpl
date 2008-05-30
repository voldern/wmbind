<h2>Viewing zone {$zone.name}</h2>

{if $error != NULL}
<p>
<strong>The following errors prevented the zone from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/zones/edit/{$zone.id}">
<strong>Zone:</strong> {$zone.name} <br />
<strong>Serial:</strong> {$zone.serial} <br />

<strong>Refresh:</strong> <input type="text" name="refresh" value="{$zone.refresh}" /> 
<strong>Retry:</strong> <input type="text" name="retry" value="{$zone.retry}" /> <br />

<strong>Expire:</strong> <input type="text" name="expire" value="{$zone.expire}" />
<strong>TTL:</strong> <input type="text" name="ttl" value="{$zone.ttl}" /> <br />

<strong>NS1:</strong> <input type="text" name="pri_dns" value="{$zone.pri_dns}" />
<strong>NS2:</strong> <input type="text" name="sec_dns" value="{$zone.sec_dns}" /> <br />
<strong>Owner:</strong> <select name="owner">{html_options options=$users selected=$zone.owner}</select> <br /> <br />
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
	<td>&nbsp;</td>
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
