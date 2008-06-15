<strong>Welcome, {$user}.</strong> {if $smarty.session.admin eq true}<u>(administrator)</u>{/if} <br />
DNS Services are <strong>{if $bindStatus eq 0}started{else}stopped{/if}</strong>. You maintain <strong>{$zones}</strong> zones. 
<br /> <br />
{section name=i loop=$bad}
<strong>WARNING:</strong> The following zone contains bad or uncommited records: <a href="{$smarty.const.site_addr}/zones/edit/{$bad[i].id}"><strong>{$bad[i].name}</strong></a> <br />
{/section}
