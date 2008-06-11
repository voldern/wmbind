<h2>Commit</h2>
{if $badZones}
	{section name=i loop=$badZones}
<strong>Error:</strong> The following zone is invalid/bad:
<a href="{$smarty.const.site_addr}/zones/edit/{$badZones[i].id}"><strong>{$badZones[i].name}</strong></a> <br />
	{/section}
{elseif $badRecords}
	{section name=j loop=$badRecords}
<strong>Error:</strong> The following zone contains bad or uncommitted records: 
<a href="{$smarty.const.site_addr}/zones/edit/{$badRecords[j].id}"><strong>{$badRecords[j].name}</strong></a> <br />
	{/section}
{else}
Zones has been added and BIND9 has been reloaded successfully.
{/if}

