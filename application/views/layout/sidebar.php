   <?php 
 $logged = $this->session->userdata('loggedIn');
 

   $ci =&get_instance();
   $ci->load->model('usermodel');
   $ci->load->model('logmodel');
   $ci->load->model('leadmodel');
   $ci->load->model('smsmodel');
   
 
	   $userPhone = $logged['userPhone'];
	//echo $logged['organizationId'];
	
	 $activeUserLevel = $logged['userLevel'];
   
      $Knowlarity = $ci->usermodel->get_knowlerity_api_by_orgId($logged['organizationId']);
	 // var_dump($Knowlarity);
	   $Knowlarity_api = $Knowlarity[0][0];
	   $dnd = $Knowlarity[0][1];
   $user_status_data= $ci->usermodel->get_user_status_data($logged['id']);

   if($user_status_data == "B") { $status_text="Busy"; } elseif($user_status_data == "BR") { $status_text="Break";  } else {$status_text="Available"; }

 ?>

	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element"> <span>
						<?php if(empty($logged['img_path'])){    ?>
									<img alt="image" class="img-circle" src="<?=base_url('assets')?>/img/profile_small.png"/> 
							<?php }else{   ?>
								<img alt="image" class="img-circle" src="<?php echo base_url();?>uploads/user_pic/<?=$loggedUser['img_path'];?>" height="50"/>
								<?php } ?>
						   
							 </span>
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $logged['userName']?></strong>
							 </span> <span class="text-muted text-xs block"><?=$status_text;?> <b class="caret"></b></span> </span> </a>
						<ul class="dropdown-menu animated fadeInRight m-t-xs">
						   <li>
									<a  href="#" id="changePassword">
									   
										Settings
									</a>
								</li>
								 
								  <li>
									<a href="<?php echo base_url('user/get_user_available')?>">
									   
										Available
									</a>
								</li>
								
						   <li>
									<a href="<?php echo base_url('user/get_user_busy')?>">
									   
										Busy
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('user/get_user_break')?>">
									   
										Break
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('user/logout')?>">
									   
										Logout
									</a>
								</li>
						</ul>
					</div>
					<div class="logo-element">
						IN+
					</div>
				</li>
				<li>
					<center>
					<button id="call-modal" class="btn btn-success btn-l" title="Call"><i class="fa fa-phone-square"></i></button>
					<button id="sms-modal" class="btn btn-warning btn-l" title="SMS"><i class="fa fa-envelope"></i></button>
				   </center>
				</li>
				<li>
					<a href="<?=base_url('home')?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> <span class="label label-primary pull-right">NEW</span></a>
				</li>

	
			 <li>

					<a href="<?=base_url('allLead')?>"><i class="fa fa-briefcase fa-lg"></i> <span class="nav-label">Leads</span> <span class="label label-primary pull-right">NEW</span></a>
				</li>         
 

				<li>
					<a href="#"><i class="fa fa-fax fa-lg"></i> <span class="nav-label">Call Logs</span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li><a href="<?=base_url('connectedLogs')?>">Connected Logs</a></li>
						 <li><a href="<?=base_url('missedLogs')?>">Missed Logs</a></li>
						  <li><a href="<?=base_url('allLogs')?>">All Logs</a></li>
					</ul>
				</li>
				<li>
					<a href="<?=base_url('blacklist')?>"><i class="fa fa-ban"></i> <span class="nav-label">Blacklisting</span> </a>
				</li>
				<li>
					<a href="<?=base_url('working')?>"><i class="fa fa-clock-o"></i> <span class="nav-label">Working Hours</span> </a>
				</li>
				<li>
					<a href="<?=base_url('agent')?>"><i class="fa fa-users"></i> <span class="nav-label">Agent Mapping</span> </a>
				</li>
				<li>
					<a href="<?=base_url('holiday')?>"><i class="fa fa-plane"></i> <span class="nav-label">Holiday</span> </a>
				</li>
			<!--	<li <?php if(($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="<?=base_url('popup')?>"><i class="fa fa-external-link"></i> <span class="nav-label">Popup Intigration</span> </a>
				</li>  -->
				<li <?php if(($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="<?=base_url('sms/template')?>"><i class="fa fa-mobile"></i> <span class="nav-label">SMS</span> </a>
				</li>
				
					<li <?php if(($this->config->item('DepartmentHead')==$activeUserLevel)||($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="<?=base_url('audio') ?>"><i class="fa fa-language fa-lg"></i> <span class="nav-label">Audio Upload</span> </a>
				</li>  
				
				<li <?php if(($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="<?=base_url('shift')?>"><i class="fa fa-headphones"></i> <span class="nav-label">Shift Management</span> </a>
				</li>
			   <!-- <li>
					<a href="<?=base_url('plugins')?>"><i class="fa fa-gear"></i> <span class="nav-label">Plugins</span> </a>
				</li> -->
				<li <?php if(($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="#"><i class="fa fa-gears"></i> <span class="nav-label">Organisation Setup</span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
					   <li <?php if($this->config->item('Admin')==$activeUserLevel) {?>style="display:none;" <?php } ?>><a href="<?php echo base_url('master_user/manage_organization');?>"> Manage Organisation</a></li>
							<li <?php if($this->config->item('MasterAdmin')==$activeUserLevel) {?>style="display:none;" <?php } ?> class='<?php if(isset($active)&&$active == 'manageUsers'){echo 'active'; }?>'><a href="<?php echo base_url('manageUsers');?>"> Manage Users</a></li>
							<li <?php if($this->config->item('Admin')==$activeUserLevel) {?>style="display:none;" <?php } ?>><a href="<?php echo base_url('master_user/manage_users');?>"> Manage Users</a></li>
					</ul>
				</li>
				<li <?php if(($this->config->item('CounslorLevel')==$activeUserLevel)||($this->config->item('TelecallerLevel')==$activeUserLevel)) {?>style="display:none;" <?php } ?>>
					<a href="mailbox.html"><i class="fa fa-laptop"></i> <span class="nav-label">System Setup </span><span class="fa arrow"></a>
					<ul class="nav nav-second-level">
						<li class='<?php if(isset($active)&&$active == 'status'){echo 'active'; }?>' ><a href="<?php echo base_url('status')?>"> Status</a></li>
							<li <?php if($this->config->item('Admin')==$activeUserLevel) {?>style="display:none;" <?php } ?>><a href="<?php echo base_url('master_user/Country')?>">Country</a></li>
							<li <?php if($this->config->item('Admin')==$activeUserLevel) {?>style="display:none;" <?php } ?>><a href="<?php echo base_url('master_user/city')?>">City</a></li>
					</ul>
				</li>
			</ul>

		</div>
	</nav>





  <div class="modal inmodal fade" id="showCallPopUpModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"> <strong class="popcallerNumber"></strong></h4>
				</div>
				<div class="modal-body">                      
  <form class="form-horizontal" method="post" action="<?php echo base_url('popup/insertUpdateLead'); ?>">
								<p>Lead Information.</p>
								<div class="form-group"><label class="col-lg-2 control-label">Name</label>

									<div class="col-lg-10"><input type="text" placeholder="Name" id="name" name="name" required value="" class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

									</div>
								</div>
								<div class="form-group"><label class="col-lg-2 control-label">Email</label>
									<div class="col-lg-10"><input type="text" placeholder="Email" id="email" name="email" value="" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-lg-2 control-label">Phone</label>
									<div class="col-lg-10"><input type="text" id="phone" name="phone" placeholder="Phone" required value="" class="form-control"></div>
								</div>
							   <input type="hidden" id="updateidpop" name="updateidpop" value="">
							    <input type="hidden" id="uuid" name="uuid" value="">
							   
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button class="btn btn-sm btn-white" id="buttonid"  type="submit">Save Lead</button>
									</div>
								</div>
							</form>
 </div>
			</div>
		</div>
	</div> 



<div class="modal inmodal fade" id="AgentConnectedModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">Call to <span class="leadCountTxt"></span></h4>
		</div>
		<div class="modal-body" id="enter_call_number">
			<?php $agentList=$ci->logmodel->getAgentList($logged);
			$codes = $ci->leadmodel->getCountryCode();
			?>
			<div class="form-group">
				<label class="control-label col-sm-2">TO</label>
				<div class="col-sm-10">
					<div class="input-group m-b">
						<div class="col-sm-4">
							<div class="input-group-btn">
								<select name="" id="code" class="form-control" style="height:30px">
					
					<?php foreach($codes as $code): ?>
					<option><?php echo $code->code; ?></option>
					<?php endforeach; ?>
				</select>
							</div>
						</div>
						<div class="col-sm-8">
							
							<input class="form-control" placeholder="To" type="text" id="customerNumber" maxlength="10">
						</div>
					</div>
				</div>
			</div>

           
            <div class="form-group">
				<label class="control-label col-sm-2">EXTRA</label>
				<div class="col-sm-10">
					<div class="input-group m-b">
						
						<div class="col-sm-12">
							
							<input class="form-control" placeholder="Extra" type="text" id="extra">
						</div>
					</div>
				</div>
			</div>
   


			<div class="form-group">
				<label class="control-label col-sm-2">From</label>
				<div class="col-sm-10">
					<div class="input-group m-b">
						<div class="col-sm-12">
							<div class="input-group-btn">
								<?php if(gettype($agentList) == 'string'): ?>
			<input type="text" disabled="true" id="customerAgent" value="<?php echo $agentList; ?>">
			<?php else: ?>
			<select id="customerAgent" class="form-control">
			<option>FROM</option>
			<?php foreach($agentList as $agent):?>
			<option value="<?php echo $agent->userPhone ?>"><?php echo $agent->userName ?></option>
			<?php endforeach; ?>
			</select>
			<?php endif; ?>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			
			<input type="button" class="btn" id="callSendAgentRequest" value="Call">
		</div>
		<div class="row-fluid" id="callPluginError" style="color:red;display:none;padding:0px 20px;"></div>
		<div class="modal-footer">
			
		</div>

	</div>
	</div>
</div> 

	<!-- Modal box starts-->
		

				 <div class="modal inmodal fade" id="customerConnectedModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Call to <span class="leadCountTxt" id="customerConnectedNumber"></span></h4>
				</div>
				 <div class="modal-body">
					<div id="customerConnectedResponse">Connecting .....</div>     
				 </div>
			   
			</div>
		</div>
	</div> 
			<!-- Modal box ends-->




<div class="modal inmodal fade" id="smsModalBoxToAnyCustomer" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">SEND SMS</h4>
				</div>
			   

				<div class="modal-body">
					  <div class="row-fluid" id="smsPluginError" style="color:red;display:none;"></div>
			<div class="row-fluid" id="enter_sms_number">
			<div class="span4">
				<div class="input-prepend">
				<?php $agentList=$ci->logmodel->getAgentList($logged);
				
							$codes = $ci->leadmodel->getCountryCode();
						?>
		  <select name=""  class="add-on code" style="height:30px">
						 <option value="0">+00</option>
							<?php foreach($codes as $code): ?>
							<option><?php echo $code->code; ?></option>
						<?php endforeach; ?>
						</select>
		  <input type="hidden" id="smsLeadId" value="0">
		  <input class="input-medium" type="text" placeholder="Enter Customer" id="customerSmsNo">
		</div>
				<div id="smsCusomerNumberError" style="color:red"></div>
			</div>
			<div class="span4">
				<select id="smsTemplate" class="input_field">
				<?php $orgId = $logged['organizationId'];
					$result = $ci->smsmodel->getAllSmsTemplates($orgId);
				?>
					<option value="0">Select Template</option>
					<?php   if(isset($result[0]->name)){foreach($result as $value){?>
					<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
					<?php } } ?>
				</select>
				<div id="smsContentSelectError" style="color:red"></div>
			</div>
			</div>
 </div>
			   
					<div class="modal-footer">
						  <button class="btn btn-mini btn-info" type="button" id="customerNumberSmsAny">Send</button>
							<button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
					</div>
				
			</div>
		</div>
	</div> 

	<!-- Modal box starts-->
		

				 <div class="modal inmodal fade" id="sentSmsModalBoxAny" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">SENT SMS</h4>
				</div>
				 <div class="modal-body">
					 your message successfully sent.    
				 </div>
			   
			</div>
		</div>
	</div> 





   


<?php $this->load->view('user/changePassword');?>


<script type="text/javascript">
// hit knowlarity APi in url and show popup to the agent number who is logged in 

var Knowlarity_api = "<?php echo $Knowlarity_api; ?>"
//URL = "https://konnect.knowlarity.com:8100/update-stream/b19afce5-7e5c-4180-a893-4044d0e58a8a/konnect"
URL = "https://konnect.knowlarity.com:8100/update-stream/"+Knowlarity_api+"/konnect"
//alert(URL);
source = new EventSource(URL);
source.onmessage = function(event) {
 var data = JSON.parse(event.data);
 console.log('Received an event.........');
 console.log(data);
 var type =  data.event_type;
  console.log('Received an event type.........');
 console.log(type);
 //window.alert("Call Event");
var Edata = 'number=' + data.customer_number;
//alert(Edata);
				$.post('<?php echo base_url()?>popup/get_lead_name_by_number',Edata,function(msg){
					  // alert(msg);
					  msg = JSON.parse(msg);
					
				if(type=="ORIGINATE" || type=="HANGUP"){
					var statuscall="";
					if(type=="ORIGINATE"){
					 statuscall =" In Progress";
					}
					else if(type=="HANGUP"){
					 statuscall =" Disconnected";
					}
if(msg ==0){
$(".popcallerNumber").text(data.customer_number+ statuscall);
$("#phone").val(data.customer_number);


}else{
$(".popcallerNumber").text(msg['name']+ statuscall); 
$("#phone").val(data.customer_number);
$("#name").val(msg['name']);
$("#email").val(msg['email']);
$("#updateidpop").val('1');
$("#buttonid").text("Update Lead");

}
//alert(data.agent_number);
$('#uuid').val(data.uuid);
$('#phone').attr("readonly", true);

if(data.agent_number=="<?php echo $userPhone;?>")
$("#showCallPopUpModal").modal('show');
}else{
   // $("#showCallPopUpModal").modal('hide');
}   

				}); 
}





$('#call-modal').click(function(){
	

						$.get("<?php echo base_url('call/checkCallPlugin')?>", function(data){
							if(data == 'notactivated'){
								var plugin = 'Your call plugin is not activated';
							}else if(data == 'noplugin'){
								var plugin = 'You don\'t have any call plugin';
							}
							//console.log(data);
							if(plugin){
								$('#callPluginError').html(plugin);
								$('#callPluginError').css('display', 'block');
								$('#enter_call_number').css('display', 'none');
								
							}
							$('#AgentConnectedModal').modal('show');
						});
					});

	$('#callSendAgentRequest').click(function(){
							var callerno=$('#customerNumber').val(),
								agentno=$('#customerAgent').val()
								code = $('#code').val();
								extra = $('#extra').val();
								//alert(extra);
								
								if(code == '0') {
									alert("Choose Contry Code");
									return false;
								}
								if(callerno == '' || agentno == '') {
									alert("Agent number and Caller number is required");
									return false;
								}
								if(!callerno.match(/^\d+/))   
								{
									alert("Please Enter Digits Only. (0-9)");
									return false;
								}
								
								  //alert(callerno);
							$('#AgentConnectedModal').modal('hide');

						  $.get("<?php echo base_url('call/checkDND')?>/" + callerno
								, function(data){
										// alert(data);
										var check ="<?=$dnd?>";
										if(check ==0){
											data = 'No';
										}
									if(data =='Yes'){
										var state =confirm("This number is in NDNC, Still want to call?");
												 if(state){
													 $.get("<?php echo base_url('call/makeCall')?>/" + code + callerno
													   + '/' + agentno+ '/' + extra, function(data){
														   $('#customerConnectedResponse').html(data);
														   //console.log(typeof data);
														   console.log(data);
														    var testRE = data.match("<call_id>(.*)</call_id>");
                                                              if(testRE!=""){
                                                              console.log(testRE[1]); 
														      $('#uuid').val(testRE[1]);
														      } 

														   // 
														  
														  //setTimeout(function(){location.reload();},5000); 
                                // start called phone
 
                    var CallPhone = 'number=' + callerno;
				$.post('<?php echo base_url()?>popup/get_lead_name_by_number',CallPhone,function(msg){
					  msg = JSON.parse(msg);
									
if(msg ==0){
$(".popcallerNumber").text(code + callerno+ " In Progress");
$("#phone").val(code + callerno);


}else{
$(".popcallerNumber").text(msg['name']+ " In Progress"); 
$("#phone").val(code + callerno);
$("#name").val(msg['name']);
$("#email").val(msg['email']);
$("#updateidpop").val('1');
$("#buttonid").text("Update Lead");

}
//alert(data.agent_number);
$('#phone').attr("readonly", true);
//$('#uuid').val("2nd input");

//if(data.agent_number=="<?php echo $userPhone;?>")
$("#showCallPopUpModal").modal('show');
 

				}); 
                                  
                             // end of called phone
							}); 
												 }else{
													 
													  setTimeout(function(){location.reload();},5000);
												 }
									}else{
										   $.get("<?php echo base_url('call/makeCall')?>/" + code + callerno
													   + '/' + agentno+ '/' + extra, function(data){
														   $('#customerConnectedResponse').html(data);
														   console.log(data);
														 var testRE = data.match("<call_id>(.*)</call_id>");
                                                              if(testRE!=""){
                                                              console.log(testRE[1]); 
														      $('#uuid').val(testRE[1]);
														      }  
								      // start called phone
 
                    var CallPhone1 = 'number=' + callerno;
				$.post('<?php echo base_url()?>popup/get_lead_name_by_number',CallPhone1,function(msg){
					  msg = JSON.parse(msg);
					 //alert(msg);
									
if(msg ==0){
$(".popcallerNumber").text(CallPhone1+ " In Progress");
$("#phone").val(code + callerno);


}else{
$(".popcallerNumber").text(msg['name']+ " In Progress"); 
$("#phone").val(code + callerno);
$("#name").val(msg['name']);
$("#email").val(msg['email']);
$("#updateidpop").val('1');
$("#buttonid").text("Update Lead");

}
//alert(data.agent_number);
//$('#uuid').val('clicktocall');
$('#phone').attr("readonly", true);

//if(data.agent_number=="<?php echo $userPhone;?>")
$("#showCallPopUpModal").modal('show');
 

				}); 
                                  
                             // end of called phone					   
														  
														  //setTimeout(function(){location.reload();},5000);
							}); 

													 
												 }
								
								
							});
						 


							
						//	$('#customerConnectedModal').modal('show');
					});


   $('#sms-modal').click(function(){
						$.get("<?php echo base_url('sms/checkSMSPlugin')?>", function(data){
								if(data == 'notactivated'){
									var plugin = 'Your SMS plugin is not activated';
								}else if(data == 'noplugin'){
									var plugin = 'You don\'t have any SMS plugin';
								}
								//console.log(data);
								if(plugin){
									$('#smsPluginError').html(plugin);
									$('#enter_sms_number').css('display', 'none');
									$('#smsPluginError').css('display', 'block');
									 $('#customerSmsNo').prop('disabled', 'disabled');
									 $('#smsTemplate').prop('disabled', 'disabled');
								}
								$('#smsModalBoxToAnyCustomer').modal('show');
							});
						
					});

					$('#customerNumberSmsAny').click(function(){
						var cuNu=$('#customerSmsNo').val(),
							tempId = $('#smsTemplate').val(),
							leadId = $('#smsLeadId').val(),
							send = true;
						var code = $('.code').val();                            
						if(code == '0') {
							alert("Choose Contry Code");
							return false;
						}
						$('#smsContentSelectError').html('');
						$('#smsCusomerNumberError').html('');                       
						if(cuNu == '') {
							$('#smsCusomerNumberError').html('Customer number is required');
							return false;
							send = false;
						}
						if(!$.isNumeric( cuNu ))
						{
							$('#smsCusomerNumberError').html('Please Enter Digits Only');
							return false;
						}
						if(tempId == '0') {
							$('#smsContentSelectError').html('Please select template');
							return false;
							send = false;
						}
						if(send) {
							$(this).addClass('disabled');
							$.get("<?php echo base_url('sms/sendSMS')?>/"+cuNu + "/" + tempId + "/" + leadId, function(data){
								$('#smsModalBoxToAnyCustomer').modal('hide');
								$('#sentSmsModalBoxAny').modal('show');
								$('#sentSmsModalBoxAny .modal-body').html(data);
								/*setTimeout(function() {
									location.reload();
								}, 1000);*/
							});
						}
					});
			
			

</script>

<script>
   var userStatus = "<?=$status_text;?>";
   		if(userStatus=="Busy" || userStatus=="Break"){
  document.getElementById("call-modal").disabled=true;
  document.getElementById("sms-modal").disabled=true;
  }  
</script>
