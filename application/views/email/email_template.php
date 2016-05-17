<!-- Modal box starts-->

	<div id="removeEmailModal" class="modal hide fade">
	<div class="modal-header">
				<button class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:14px;"></i></button>
				<h4>Are You Sure Want To Delete Email?</h4>
		</div>
	<div class="modal-body">
		Once you click on delete You will not able recover it again..!							
	</div>
		<div class="modal-footer">
		<input type="hidden"  id="removeEmailId" value="">
		<button class="btn btn-mini btn-info" type="button" onclick="removeEmail()">Delete</button>
		<button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
		</div>
	</div>

<!-- Modal box ends-->
<!-- success Delete Modal box starts-->

	<div id="SuccessDeleteModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:18px;"></i></button>		
				<h2>Successfully Deleted...</h2>
		</div>

	</div>
<!-- success Modal box ends-->


		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

			<?php $this->load->view('layout/sidebar');//load side bar?>

			<div class="main-content">
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
							<i class="icon-home home-icon"></i>
							<a href="#">Home</a>

							<span class="divider">
								<i class="icon-angle-right arrow-icon"></i>
							</span>
						</li>

						<li>
							Email

							<span class="divider">
								<i class="icon-angle-right arrow-icon"></i>
							</span>
						</li>
						<li class="active">Templates</li>
					</ul><!--.breadcrumb-->

					<!--<div class="nav-search" id="nav-search">
						<form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="input-small nav-search-input" id="nav-search-input" autocomplete="off" />
								<i class="icon-search nav-search-icon"></i>
							</span>
						</form>
					</div>--><!--#nav-search-->
				</div>

				<div class="page-content">
					<div class="page-header position-relative">
						<h1>
							Email Template
						</h1>
					</div><!--/.page-header-->
					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->
							<div class="row-fluid">
								<div class="span7 widget-container-span">
									<div class="widget-box">
										<div class="widget-header header-color-blue">
											<h5 class="bigger lighter">
												<i class="icon-mobile-phone"></i>
												Organisation Email Templates
											</h5>

											<div class="widget-toolbar">
												<span class="label label-warning"><?php echo $usedemail;?> Used <i class="icon-arrow-up"></i></span>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main no-padding">
												<table class="table table-striped table-bordered table-hover" id="emailTemplateTable">
													<thead>
														<tr>
															<th>
																Name
															</th>
															<th class="hidden-480">Template</th>
															<th class="hidden-480">Status</th>
															<th class="hidden-480">Actions</th>
														</tr>
													</thead>

													<tbody>
													<?php
													if (!empty($emailTemplates))
													{
													foreach($emailTemplates as $email)
													{
													?>
														<tr id="emailRow-<?php echo $email->id?>">
															<td class="">
																<?php echo $email->name;?>
															</td>
															<td class="hidden-480">
																<?php echo $email->template?>
															</td>
															<td class="hidden-480">
																<?php
																if($email->approved)
																{
																?>
																<span class="label label-success arrowed-in arrowed-in-right">
																Approved
																</span>
																<?php
																}else{?>
																<span class="label label-warning">
																Pending
																</span>
																<?php
																}?>
															</td>
															<td class="center" id="action-id" >
																<div class="hidden-phone visible-desktop btn-group">
																	<!--<button class="btn btn-mini btn-success" onclick="" title="Approve">
																		<i class="icon-unlock bigger-120"></i>
																	</button>
																	<button class="btn btn-mini btn-danger" onclick="" title="Dis-Approve">
																		<i class="icon-lock bigger-120"></i>
																	</button>-->
																	<button class="btn btn-mini btn-danger" onclick="openModelRemoveSms(<?php echo $email->id;?>)" title="Remove">
																		<i class="icon-trash bigger-120"></i>
																	</button>
																</div>
															</td>
														</tr>
													<?php
													}//end of foreach
													}//end of if
													?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="span5 widget-container-span">
									<div class="widget-box">
										<div class="widget-header">
											<h4>Create New</h4>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<form id="importEmailForm" class="form-horizontal" name="importleadForm" method="post" action="<?php echo base_url('email/template');?>" enctype="multipart/form-data">
													<div class="control-group">
														<label class="control-label" for="form-field-name">Name</label>
														<div class="controls">
															<input type="text" id="name" placeholder="Name" name="name" />
															<span class=""><?php
															echo "<font color=red>".form_error('name')."</font>";?></span>
														</div>
													</div>
													
													<div class="control-group">
														<label class="control-label" for="form-field-notes">Template</label>
														<div class="controls">
															<textarea name="template" id="template" placeholder="Default Text"></textarea>
															<span class=""><?php
															echo "<font color=red>".form_error('template')."</font>";?></span>
														</div>
													</div>
													<div class="space-4"></div>
													<div class="controls">
														<button  type="submit" class="btn btn-small btn-success" name="submit">
															Save
															<i class="icon-arrow-right icon-on-right bigger-110"></i>
														</button>
														<!--<input type="submit" value="submit" name="submit">-->
														<?php 
														if(isset($success))
														{
														echo $success;
														}
														 ?>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>	
							</div>
							<!--PAGE CONTENT ENDS-->
						</div><!--/.span-->
					</div><!--/.row-fluid-->
				</div><!--/.page-content-->

				<div class="ace-settings-container" id="ace-settings-container">
					<div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
						<i class="icon-cog bigger-150"></i>
					</div>

					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="default" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; Choose Skin</span>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>
					</div>
				</div><!--/#ace-settings-container-->
			</div><!--/.main-content-->
		</div><!--/.main-container-->

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only bigger-110"></i>
		</a>

		<!--basic scripts-->

		<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>

		<!--page specific plugin scripts-->
		<script src="<?php echo base_url()?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/chosen.jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.dataTables.bootstrap.js"></script>

		<!-- form validation script starts-->
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
		
		<!-- form validation script ends-->
		<!--ace scripts-->

		<script src="<?php echo base_url()?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace.min.js"></script>

		<!--inline scripts related to this page-->
		
		<script type="text/javascript">
	
		$(function(){

		var oTable1 = $('#emailTemplateTable').dataTable( {
					"aoColumns": [
					  null,
					  null,
					   null,
					  null
					] } );
		
		var base_url  = '<?php echo base_url();?>';
			$('#importEmailForm').validate({
				errorElement: 'span',
				errorClass: 'help-inline',
				focusInvalid: false,
				rules: {
					name: {
						required: true,
						minlength: 3,
						remote: {
						url: base_url+'email/checkEmailExist',
						type: 'GET',
						data:
							{
								name: function()
								{
								return $('#name').val();
								}
							}
						}
					},
					template: {
						required: true,
						minlength: 10
					}
				},
		
				messages: {
					name: {
						required: "Please specify a Name.",
						minlength: "Please specify Name of minimum 3 character.",
						remote: "This name is already taken."
					},
					template: {
						required: "Please provide a valid Template.",
						minlength: "Please provide a valid Template Format."
					}
				},
		
				invalidHandler: function (event, validator) { //display error alert on form submit   
					$('.alert-error', $('#importEmailForm')).show();
				},
		
				highlight: function (e) {
					$(e).closest('.control-group').removeClass('info').addClass('error');
				},
		
				success: function (e) {
					$(e).closest('.control-group').removeClass('error').addClass('info');
					$(e).remove();
				},
		
				errorPlacement: function (error, element) {
					//console.log(error[0]['innerHTML']);
					if(element.is(':checkbox') || element.is(':radio')) {
						var controls = element.closest('.controls');
						if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
						else error.insertAfter(element.nextAll('.lbl').eq(0));
					} 
					else if(element.is('.chzn-select')) {
						error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
					}
					else error.insertAfter(element);
				},
		
				submitHandler: function (form) {
				form.submit();
				},
				invalidHandler: function (form) {
				}
			});
		});
		
			function removeEmail()
			{
				var emailId = $('#removeEmailId').val();
				var data = 'emailId='+emailId;
				$('#removeEmailModal').modal('hide');
				$.post('<?php echo base_url()?>email/removeEmail',data,function(msg){
					$('#emailRow-'+emailId).remove();
					$("#SuccessDeleteModal").modal('show');
					setTimeout(function(){$("#SuccessDeleteModal").modal('hide');},1200);
				});
			}
			function openModelRemoveSms(smsId)
			{
				$('#removeEmailId').val(smsId);
				$('#removeEmailModal').modal('show');
			}
		</script>
	</body>
</html>
