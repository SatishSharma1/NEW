  <div class="modal inmodal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <form class="form span3" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                        <table>
					<tr><td>Old Password</td><td><input type="password"  id="oldPassword" value="" class="form-control"></td><td><span style="color:red;" id="ErroroldPassword"></span></td><tr>
					<tr><td>New Password</td><td><input type="password"  id="newPassword" value="" class="form-control"></td><td><span style="color:red;" id="ErrornewPassword"></span></td><tr>
					<tr><td>Confirm New Password</td><td><input type="password"  id="confirmPassword" value="" class="form-control"></td><td><span style="color:red;" id="ErrorconfirmPassword"></span></td><tr>
					</table>                  
                       




 </div>
                <div class="modal-footer">
                    <input type="hidden"  id="deleteAgent" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="changePassword()">Change Password</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 



    <div class="modal inmodal fade" id="successChangePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Password Successfully Changed ..</h4>
                </div>
               
            </div>
        </div>
    </div> 


			
<!-- success Modal box ends-->
					<script>
						$('#changePassword').on('click',function()
						{
							$('#oldPassword').val('');
							$('#newPassword').val('');
							$('#confirmPassword').val('');
							$("#changePasswordModal").modal('show');
							
						});
						function changePassword()
						{
							var error=0;
							var baseurl="<?php echo base_url();?>";
							var oldPassword=$("#oldPassword").val();
							var newPassword=$("#newPassword").val();
							var confirmPassword=$("#confirmPassword").val();
							var dataString = 'oldPassword='+ oldPassword;
							$('#ErroroldPassword').text('');
							$('#ErrornewPassword').text('');
							$('#ErrorconfirmPassword').text('');
							if(!oldPassword)
							{
								$('#ErroroldPassword').text('Please Enter Old Password');
								error++;
							}
							else if(oldPassword.length<6)
							{
								$('#ErroroldPassword').text('Password must be at least 6 character long');
								error++;
							}
							 if(!newPassword)
							{
								$('#ErrornewPassword').text('Please Enter New Password');
								error++;
							}
							 else if(newPassword.length<6)
							{
								$('#ErrornewPassword').text('Password must be at least 6 character long');
								error++;
							}
							 if(!confirmPassword)
							{
								$('#ErrorconfirmPassword').text('Please Enter Confirm New Password');
								error++;
							}
							 else if(confirmPassword.length<6)
							{
								$('#ErrorconfirmPassword').text('Password must be at least 6 character long');
								error++;
							}
							
							if(error==0)
							{	
							$.post(baseurl+'user/checkPassword',dataString,function(data){
											
											if(data=='error')
											{
												$('#ErroroldPassword').text('Old Password Do not Match');
												error++;
											
											}
											if(newPassword!=confirmPassword)
											{
												$('#ErrorconfirmPassword').text('New Password And confirmPassword Do not Match');
												error++;
												return;
											}
											if(error==0)
											{
												var dataString1 = 'newPassword='+ newPassword;
												$.post(baseurl+'user/changePassword',dataString1,function(data)
												{
														$("#changePasswordModal").modal('hide');
														$('#successChangePasswordModal').modal('show');
														setTimeout(function(){$("#successChangePasswordModal").modal('hide');//window.location.reload();
														},1600);
												});
												$("#changePasswordModal").modal('hide');
											}
										});
							}
						}
						</script>
			<!-- Modal box-->