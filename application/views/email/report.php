<html><head><title></title></head>
<body style="margin:0;">

<table style="width:600px; border:0; font-family:Arial; font-size:14px;" cellpadding="0" cellspacing="0" align="center">
	<!-- head -->
	<tr>
		<td>
			<table style="width:600px; border:0;" cellpadding="0" cellspacing="0" >
				<tr>
					<td align="left" valign="top" style="padding-top:29px; width:78%"><h3 style="margin:0;font-size:22px;letter-spacing:2px; color:#1f497d;">REPORT</h3><p style="margin:0; padding-top:5px;font-weight:bold; font-size:18px">Total Lead analysis till date:<?= date("F j, Y"); ?></p></td>
					<td align="center"  style="padding-top:27px;"><img src="<?= base_url()?>assets/images/report/logo.jpg"></td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- tyroo tables description -->
	<tr>
		<td>
			<table style="width:380px; border:0; margin:26px auto 0" cellpadding="0" cellspacing="0" > 
				<!-- heading-->
				<tr>
					<td align="center" style="background:#1f497d; color:#fff; font-size:18px;padding:7px;"><?= $SourceName; ?></td>
				</tr>
				<!-- description fields -->
				<tr>
					<td align="left" valign="top">
							<table style="width:100%; border-left:1px solid #ccc;" cellpadding="0" cellspacing="0"> 
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" ><h4>Total Leads</h4></td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><h4><?php echo  count($totalSMSverified); ?></h4></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" >Fresh </td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><?php echo count($freshLead); ?></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" >Attempted  </td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><?php echo  count($AttemptedLead); ?></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" > - <small>Retrial </small> </td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><small><?php echo count($retrial); ?></small></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" >Qualified </td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><?php echo  count($Qualified);?></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" > Invalid </td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><?php echo  count($invalid); ?></td>
								</tr>
								
								
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" > - <small>Not Interested </small></td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><small><?php echo  count($NotInterested); ?></small></td>
								</tr>
								<tr>
									<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;width:50%; padding:5px;" > - <small>Switched Off</small></td>
									<td style="border-right:1px solid #ccc; width:50%; padding:5px; ;border-bottom:1px solid #ccc;"><small><?php echo count($SwitchOff);?></small></td>
								</tr>
							</table>
					</td>
					
				</tr>
				<!-- download -->
				<tr>
					<td style="padding-top:10px;">
						<h3 style="margin:0;font-size:22px;letter-spacing:2px; color:#1f497d;text-align:center">DOWNLOAD</h3>
					</td>
				</tr>
				<!-- buttons -->
				 <tr>
					<td>
						<table style="width:372px; border:0; margin:26px auto 0" cellpadding="0" cellspacing="0">
							
							<tr>
								<td> <a href="<?= base_url();?>report/downloadInvalid/<?=$SourceName.'/'.$orgId;?>"><img src="<?= base_url()?>assets/images/report/invalid.jpg"></a></td>
								<td>
									<a href="<?= base_url();?>report/downloadValidLead/<?=$SourceName.'/'.$orgId;?>"><img src="<?= base_url();?>assets/images/report/valid.jpg"></a>
								</td>
								<td>
									<a href="<?= base_url();?>report/downloadNotInterested/<?=$SourceName.'/'.$orgId;?>"><img src="<?= base_url();?>assets/images/report/not.jpg"></a>
								</td> 
								<td>
									<a href="<?= base_url();?>report/downloadSwitchedOff/<?=$SourceName.'/'.$orgId;?>"><img src="<?= base_url();?>assets/images/report/switch-off.jpg"></a>
								</td> 
							</tr>
							
						</table>
						
					</td>
					
				</tr> 
				
			</table>
		</td>
	</tr>
</table>

</body>