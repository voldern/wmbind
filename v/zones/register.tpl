<h2>New zone</h2>

{if $error != NULL}
<p>
<strong>The following errors prevented the zone from beeing saved:</strong> <br />
{$error}
</p>
<br />
{/if}

<form method="POST" action="{$smarty.const.site_addr}/zones/register/">
Zone: <input type="text" name="name" value="{$smarty.post.name}" /> <br />
Refresh: <input type="text" name="refresh" value="28800" /> <br />
Retry: <input type="text" name="retry"  value="7200" /> <br />
Expire: <input type="text" name="expire" value="1209600" /> <br />
Time to live: <input type="text" name="ttl" value="86400" /> <br />
Primary NS: <input type="text" name="pri_dns" value="{$smarty.post.pri_dns}" value="" /> <br />
Secondary NS: <input type="text" name="sec_dns" value="{$smarty.post.sec_dns}" value="" /> <br />
Web Server IP: <input type="text" name="www" value="{$smarty.post.www}" value="" /> <br />
Mail Server IP: <input type="text" name="mail" value="{$smarty.post.mail}" value="" /> <br />
Owner: {html_options name=owner options=$users} <br />

<input type="submit" value="Add zone" />
</form>
