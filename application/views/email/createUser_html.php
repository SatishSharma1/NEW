<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Welcome to <?php echo $siteName; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome <?php echo $userName; ?> to <?php echo $siteName; ?>!</h2>
<br />Your Account Created Successfully .<br />
<br />We listed your sign in details below, make sure you keep them safe.<br />
Email: <?php echo $userEmail; ?><br />
Password: <?php echo $userPassword; ?>
<br />
Have fun!<br />
The <?php echo $siteName; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>