<!DOCTYPE html>
<html>
<head></head>
<body>
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
<tr>
	<td align=center>
	<img src="https://actiknow.com/images/logo.png" style="height: 67px;">
	<hr>
	</td>
</tr>
	                      
<tr>
<td style="font-size: 14px;">
<p style="line-height: 1.5;word-spacing: 3px;">Hi <?= $client_name ?>,</p>
<p style="line-height: 1.5;word-spacing: 3px;">A new response has been received from <strong><?= $manager_name;?></strong>  on the ticket with title <strong><?= $title;?></strong>.</p>
<p style="line-height: 1.5;word-spacing: 3px;"><a href="http://44.230.62.131/bug-reporting">Click here</a> to access the website for more information about the ticket.
</p>
<p style="word-spacing: 3px;"><b>Best Regards,
<br>Team Actiknow</b></p>
</td>
</tr>
</table>
</body>
</html>