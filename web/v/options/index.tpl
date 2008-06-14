<form name="records" method="POST" action="{$smarty.const.site_addr}/options/edit">
<h2>Record types</h2>

<table width="400" border="0">
	{section name=x loop=$records}
	<tr>
		{section name=y loop=$records[x]}
		<td align="right">
			<input type="checkbox" name="{$records[x][y].prefkey}" {if $records[x][y].prefval eq "on"}checked{/if} />
		</td>
		<td align="left">
			<strong>&nbsp;{$records[x][y].prefkey}</strong>
		</td>
		{/section}
	</tr>
	{/section}
</table>

<hr /> <br />

<label>Hostmaster address: </label> <input type="text" name="hostmaster" value="{$options.hostmaster}" /> <br />
<label>Default Primary NS: </label> <input type="text" name="prins" value="{$options.prins}" /> <br />
<label>Default Secondary NS: </label> <input type="text" name="secns" value="{$options.secns}" /> <br />
<input type="submit" id="submit" value="Save" />
