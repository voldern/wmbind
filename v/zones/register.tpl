<h2>New zone</h2>

{if $error != NULL}
<p>
<strong>The following errors prevented the zone from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/zones/register/">
<label for="name">Zone:</label> <input type="text" name="name" id="name" value="{$smarty.post.name}" /> <br />
<label for="refresh">Refresh:</label> <input type="text" name="refresh" id="refresh" value="28800" /> <br />
<label for="retry">Retry:</label> <input type="text" name="retry" id="retry" value="7200" /> <br />
<label for="expire">Expire:</label> <input type="text" name="expire" id="expire" value="1209600" /> <br />
<label for="ttl">Time to live:</label> <input type="text" name="ttl" id="ttl" value="86400" /> <br />
<label for="pri_dns">Primary NS:</label> <input type="text" name="pri_dns" id="pri_dns" value="{$smarty.post.pri_dns}" value="" /> <br />
<label for="sec_dns">Secondary NS:</label> <input type="text" name="sec_dns" id="sec_dns" value="{$smarty.post.sec_dns}" value="" /> <br />
<label for="www">Web Server IP:</label> <input type="text" name="www" id="www" value="{$smarty.post.www}" value="" /> <br />
<label for="mail">Mail Server IP:</label> <input type="text" name="mail" id="mail" value="{$smarty.post.mail}" value="" /> <br />
<label>Owner:</label> {html_options name=owner options=$users} <br />

<input type="submit" id="submit" value="Add zone" />
</form>
