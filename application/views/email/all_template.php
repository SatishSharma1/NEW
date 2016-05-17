<!-- Modal box starts-->

	<div id="approveTemplateModal" class="modal hide fade">
	<div class="modal-header">
				<button class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:14px;"></i></button>
				<h4>Are You Sure Want To Approve this Template?</h4>
		</div>
	<div class="modal-body">
		If you approve Email template. Its your responsibility to make you able to send this Message.						
	</div>
		<div class="modal-footer">
		<input type="hidden"  id="approveTemplateId" value="">
		<button class="btn btn-mini btn-info" type="button" onclick="approveTemplate()">Approve</button>
		<button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
		</div>
	</div>

		<!-- Modal box ends-->
<!-- Modal box starts-->

	<div id="discardTemplateModal" class="modal hide fade">
	<div class="modal-header">
				<button class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:14px;"></i></button>
				<h4>Are You Sure Want Discard this Template.</h4>
		</div>
	<div class="modal-body">
		<label for="discardReason">Give specific reason of Discarding this template:</label>
		<textarea id="discardReason" class='autosize-transition span6' placeholder="eg: Template not is correct format."></textarea>
	</div>
		<div class="modal-footer">
		<input type="hidden"  id="discardTemplateId" value="">
		<button class="btn btn-mini btn-info" type="button" onclick="discardTemplate()">Delete</button>
		<button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
		</div>
	</div>

<!-- Modal box ends-->
<!-- success Discard Modal box starts-->

	<div id="successDiscardModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:18px;"></i></button>		
				<h2>Successfully Updated...</h2>
		</div>
	</div>

<!-- success Discard Modal box ends-->

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
							<a href="#">email</a>

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
							All Email Template
						</h1>
					</div><!--/.page-header-->
					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->
							<div class="row-fluid">
								<div class="span8 widget-container-span">
									<div class="widget-box">
										<div class="widget-header header-color-blue">
											<h5 class="bigger lighter">
												<i class="icon-mobile-phone"></i>
												Email Templates
											</h5>

											<div class="widget-toolbar">
												<a href="#" data-action="collapse"><i class="icon-chevron-down"></i></a>
												<a href="#" data-action="close"><i class="icon-remove"></i></a>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<table id="emailTemplateTable" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>
																Name
															</th>
															<th class="hidden-480">Template</th>
															<th class="hidden-480">Created On</th>
															<th class="hidden-480">Organization</th>
															<th class="hidden-480">Status</th>
															<th class="hidden-480">Actions</th>
														</tr>
													</thead>

													<tbody>
													<?php 
													foreach($emailTemplates as $email)
													{
													?>
														<tr id="">
															<td class="">
																<?php echo $email->name;?>
															</td>
															<td class="hidden-480">
																<?php echo $email->template;?>
															</td>
															<td class="hidden-480">
																<?php echo $email->createdOn;?>
															</td>
															<td class="hidden-480">
																<?php echo $email->orgName;?>
															</td>
															<td class="hidden-480" id="status-<?php echo $email->id?>">
															<?php 
															if($email->approved == 0)
															{
															?>	
																<span class="label label-warning">
																Pending
																</span>
															<?php
															}
															else if($email->approved == 1){
															?>
																<span class="label label-success arrowed-in arrowed-in-right">
																Approved
																</span>
															<?php
															}
															else{?>
																<span class="label label-inverse arrowed">
																Discard
																</span>
																<?php
																}?>
															</td>
															<td class="center" id="action-<?php echo $email->id;?>" >
																<div class="hidden-phone visible-desktop btn-group">
																<?php 
																if($email->approved == 0)
																{
																?>	
																	<button class="btn btn-mini btn-success" onclick="openApproveTemplateModel(<?php echo $email->id?>)" title="Approve">
																		<i class="icon-unlock bigger-120"></i>
																	</button>
																<?php
																}if($email->approved != 2){?>
																	<button class="btn btn-mini btn-danger" onclick="openDiscardTemplateModal(<?php echo $email->id;?>)" title="Discard">
																		<i class="icon-trash bigger-120"></i>
																	</button>
																<?php }?>
																	<!--<button class="btn btn-mini btn-danger" onclick="" title="Remove">
																		<i class="icon-trash bigger-120"></i>
																	</button>-->
																</div>
															</td>
														</tr>
													<?php
													}
													?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="span4 widget-container-span">
									<div class="widget-box transparent">
										<div class="widget-header widget-header-flat">
											<h5 class="bigger lighter">
												<i class="icon-mobile-phone"></i>
												Organization
											</h5>

											<div class="widget-toolbar">
												<a href="#" data-action="collapse"><i class="icon-chevron-down"></i></a>
												<a href="#" data-action="close"><i class="icon-remove"></i></a>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main no-padding">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>
																<i class="icon-caret-right blue"></i>
																Name
															</th>
															<th class="hidden-480">
																<i class="icon-caret-right blue"></i>
																Used
															</th>
															<th class="hidden-480">
																<i class="icon-caret-right blue"></i>
																Total
															</th>
														</tr>
													</thead>

													<tbody>
													<?php
													foreach($orgEmailDetails as $org)
													{
													$orgEmailLimit = $this->emailmodel->getPackageEmail($org->package);
													?>	
														<tr id="">
															<td class="">
																<?php echo $org->name;?>
															</td>
															<td class="hidden-480">
																<b class="blue"><?php echo $org->email?></b>
															</td>
															<td class="hidden-480">
																<b class="blue"><?php echo $orgEmailLimit;?></b>
															</td>
														</tr>
													<?php
													}
													?>
													</tbody>
												</table>
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
			jQuery(function($) {
				var oTable1 = $('#emailTemplateTable').dataTable( {
					"aoColumns": [
					  null,
					  null,
					  { "bSortable": false },
					  null,
					  { "bSortable": false },
					  { "bSortable": false }
					] } );
				
				
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
			}) 
		</script>
		<script type="text/javascript">
				
			function openDiscardTemplateModal(id)
			{
				$('#discardTemplateId').val(id);
				$("#discardTemplateModal").modal('show');
				$("#discardReason").val('');
			}
			function openApproveTemplateModel(id)
			{
				$('#approveTemplateId').val(id);
				$('#approveTemplateModal').modal('show');
			}
			function discardTemplate()
			{
				var reason = $("#discardReason").val();
				if(reason)
				{
					var emailId = $('#discardTemplateId').val();
					var data = 'emailId='+emailId+'&reason='+reason;
					$.post('<?php echo base_url()?>email/discardTemplate',data,function(msg)
					{
						$('#status-'+emailId).html('<span class="label label-inverse arrowed">Discard</span>');
						$('#action-'+emailId).html('');
						$("#discardTemplateModal").modal('hide');
						$('#successDiscardModal').modal('show');	
						setTimeout(function(){$("#successDiscardModal").modal('hide');},1200);
					});
				}
			}
			function approveTemplate()
			{
				var emailId = $('#approveTemplateId').val();
				var data = 'emailId='+emailId;
				$.post("<?php echo base_url()?>email/approveTemplate",data,function(msg){
				{
					$('#status-'+emailId).html('<span class="label label-success arrowed-in arrowed-in-right">Approved</span>');
					$('#action-'+emailId).html('<div class="hidden-phone visible-desktop btn-group"><button class="btn btn-mini btn-danger" onclick="openDiscardTemplateModal('+emailId+')" title="Discard"><i class="icon-trash bigger-120"></i></button></div>');
					$("#approveTemplateModal").modal('hide');
					$('#successDiscardModal').modal('show');	
					setTimeout(function(){$("#successDiscardModal").modal('hide');},1200);
				}
				});
			}
		</script>
	</body>
</html>