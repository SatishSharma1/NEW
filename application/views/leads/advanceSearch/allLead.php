            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Leads</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Leads</a>
                        </li>
                        <li class="active">
                            <strong>All Leads</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Leads <small>Listing of Leads</small></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                           
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                    	<div class="row">
                                    <table><tr>

                                       
                                    <div class="col-sm-1 m-b-xs">
                                        <select size="1" name="per_page" id="per_page" aria-controls="sample-table-2" class="input-sm form-control input-s-sm inline">
                                            <option value="10" <?php echo ($per_page==10)?'selected="selected"':'';?>>10</option>
                                            <option value="25" <?php echo ($per_page==25)?'selected="selected"':'';?>>25</option>
                                            <option value="50" <?php echo ($per_page==50)?'selected="selected"':'';?>>50</option>
                                            <option value="100" <?php echo ($per_page==100)?'selected="selected"':'';?>>100</option>
                                        </select>
                                        </div>
                                    
                                      
                                   <div class="col-sm-1 m-b-xs">       <button>
                                    <a style="text-decoration: none;color: #000;" href="<?php echo base_url('lead/downloadAllLeadcsv')?>">Download CSV</a>
                                </button>  </div>
                         
          

                                   
                                  
                                   <form method="get" action="" id="search-form">
                                  <div class="col-sm-2">
                                        <div class="form-group">
                                        <input type="text"name="phonesearch" id="phonesearch" placeholder="Phone Search"  class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                        <input type="text" placeholder="Source Search" name="sourcesearch" id="sourcesearch"  class="form-control input-sm"></div>
                                    </div>
                                     <div class="col-sm-2">
                                        <div class="form-group">
                                        <input type="text" placeholder="Status Search" name="statussearch" id="statussearch"  class="form-control input-sm"></div>
                                    </div>
                                     <div class="col-sm-2">
                                        <div class="form-group">
                                        <input type="text" placeholder="City Search" name="citysearch" id="citysearch"  class="form-control input-sm"></div>
                                    </div>
                                  
                                 
                                    <!--//If user is an Admin then only show-->
                                   
                                    <!--//If user is an Admin then only showing above block-->
                                    <div class="col-sm-2 m-b-xs pull-right">
                                        <button class="btn btn-sm btn-primary pull-right" type="submit" value="Advance Search" name="submitadvancesearch" id="submitadvancesearch"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></button>
                                    </div> 
                                      </form>
                                  

                                 <tr> </table>
                                </div>
                        <table class="table table-striped" id="holiday">
                            <thead>
                            <tr>
                               <th class="center">
												<label><input type="checkbox" /><span class="lbl"></span></label>
											</th>
											<!--<th>Unique Id.</th>-->
											<th>Created On</th>
											<th class="hidden-480">Name</th>
											<th class="hidden-phone">Phone</th>
											<th class="hidden-480">City</th>
											<th>Status</th>
											<th>Source</th>
											<th>Notes</th>
											<th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

									<?php 
									$i=1;
									foreach($getAllLeads as $leadData)
									{
									//$obd = $this->leadmodel->checkodbverify($leadData['phone']); below code by debal
									$obd = $this->leadmodel->checkodbverify($leadData['id']);
									$SMSVerified = $this->leadmodel->checkSmsverify($leadData['id']);
									$transfered=$this->leadmodel->getLeadFromTransferedHistory($leadData['id']);
									$verified=$this->leadmodel->getOptedTimeAndSmsVerification($leadData['id']);
									?>
									<tr id="leadID-<?php echo $leadData['id'];?>">
										<td class="center <?php  if(!empty($verified)){echo 'verified';}?>"  >
										<label><input type='checkbox' id="leadCheckBox-<?php echo $leadData['id'];?>" /><span class="lbl"></span></label>
										<input type="hidden" class="leadValue" id="lead-<?php echo $leadData['id'];?>" value=""/>
										<input type="hidden" id="leadEmail-<?php echo $leadData['id'];?>" value="<?php echo $leadData['email'];?>">
										</td>
										<!--<td><?php //echo $leadData['id'];?></td>-->
										<td class="<?php  if(!empty($verified)){echo 'verified';}?>"><?php echo $leadData['leadCreatedTime'];?></td>
										<td class="hidden-480 <?php  if(!empty($verified)){echo 'verified';}?>" id="name-<?php echo $leadData['id'];?>"><?php echo $leadData['name'];?></td>
										<td class="hidden-phone <?php  if(!empty($verified)){echo 'verified';}?>" >
										<span id="phone-<?php echo $leadData['id'];?>"><?php echo $leadData['phone'];?></span>
										<?php 
										if($obd)
										{
										?>
										<span class="badge badge-success" title="OBD verified"><i class="icon icon-phone"></i></span>
										<?php
										}
										?>
										<!-- transfered histroy-->
										<?php 
										if(!empty($transfered))
										{
										?>
										<span class="badge badge-primary" title="Transfered"><i class="icon-ok"></i></span>
										<?php
										}
										
										?>
										<!-- transfered histroy-->
										<?php 
										if($SMSVerified)
										{
										?>
										<span class="badge badge-success" title="Sms verified"><i class="icon-mobile-phone"></i></span>
										<?php
										}
										?>
										</td>
										<td class="<?php  if(!empty($verified)){echo 'verified';}?>"><span id="city-<?php echo $leadData['id'];?>"><?php echo $leadData['city'];?></span>
										<?php
										$city = $this->leadmodel->checklookupcity($leadData['id']);
										if($city)
										{
										?>
										<br/>
										<span class="label label-info arrowed-right arrowed-in"><?php echo $city?></span>
										<?php
										}?>
										</td>
										<td id="status-<?php echo $leadData['id'];?>" class="<?php  if(!empty($verified)){echo 'verified';}?>">
										<input type="hidden" value="<?php echo $leadData['status']; ?>" id="checkStatusName-<?php echo $leadData['id']; ?>">
										<span id="status-text-<?php echo $leadData['id'];?>">
										<?php
										$statusName=$this->leadmodel->getLeadStatusdata();
										foreach($statusName as $detailName)
										{
											if($leadData['status']==$detailName['id'])
											{
												echo $detailName['detail'];
											} 
											else
											{
												echo " ";
											}
										}
										?>
										</span>
										</td>
										<td class="<?php  if(!empty($verified)){echo 'verified';}?>">
											<?php echo $leadData['source']?>
										</td>
										<td class="<?php  if(!empty($verified)){echo 'verified';}?>" id="notes-<?php echo $leadData['id'];?>"><?php //echo $leadData['notes'];?>										
										<!--<span class="badge badge-success" id="notesCount-<?php echo $leadData['id'];?>"></span>
										<span id="notes1-<?php echo $leadData['id'];?>" style="margin-left: 12px;">
										<i class="icon-spinner icon-spin orange bigger-125"></i>										
										</span>										
										<!--<span class="time pull-right">										
										<i class="icon-time"></i>&nbsp;
										<span class="orange" id="notesupdatedTime-<?php //echo $leadData['id'];?>" style="font-size: 9px;">
										</span>										
										</span>-->
										</td>
										<td class="<?php  if(!empty($verified)){echo 'verified';}?>">
											<div class="inline position-relative">
												<a target="_blank" class="btn btn-minier btn-primary" href="<?php echo base_url('lead/profile').'/'.$leadData['id'];?>" data-rel="tooltip" title="Profile" data-placement="left"><span><i class="fa fa-eye"></i></span> </a>
												<button class="btn btn-minier btn-success dropdown-toggle phoneLead" rel="<?php echo $leadData['id'];?>" data-rel="tooltip" title="Call No."><i class="fa fa-phone"></i></button>
												<button class="btn btn-minier btn-primary dropdown-toggle sendSmsLead" data-leadid="<?php echo $leadData['id'];?>" data-rel="tooltip" title="Send SMS" rel="<?php echo $leadData['phone'];?>"><i class="fa fa-envelope"></i></button>
												<button class="btn btn-minier btn-primary"  onclick="showEditModal(<?php echo $leadData['id'];?>);"><i class="fa fa-edit"></i></button>
												
												
											</div>
										</td>
									</tr>	
									<?php 
									}		
									?>
									</tbody>
                        </table>
                        <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> leads found!
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $links;?>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
                
            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
              
            </div>
            <div>
              
            </div>
        </div>

        </div>
        </div>

       <div class="modal inmodal fade" id="openHolidayModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <form class="form span3" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                            Once you click on delete You will not able recover it again..!                          
                       




 </div>
                <div class="modal-footer">
                    <input type="hidden"  id="deleteHoliday" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteHoliday()">Delete</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 

     <script>
	
	//Setting Base Url For Script
	
	var base_url = '<?php echo base_url()?>';
	
	
		//on change function for choosing option
			$("#per_page").change(function(){
			var location1 = $(this).val();
			window.location.href = '<?php echo base_url('allLead')?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;	
		});
		
		$("#search-form").submit(function(){
			var search="";
			var phonesearch = $('#phonesearch').val().trim();
			var sourcesearch = $('#sourcesearch').val().trim();
			var statussearch = $('#statussearch').val().trim();
			var citysearch = $('#citysearch').val();
			
			if(phonesearch!="") {
				search+="ph:"+phonesearch;
			}
			if(sourcesearch!="") {
				
				if(search!="")
				search+=",so:"+sourcesearch;
				else
				search+="so:"+sourcesearch;
			}
			if(statussearch!="") {
				if(search!="")
				search+=",st:"+statussearch;
				else
				search+="st:"+statussearch;
			}
			if(citysearch!="") {
				if(search!="")
				search+=",ci:"+citysearch;
				else
				search+="ci:"+citysearch;
			}
		window.location.href = '<?php echo base_url('allLead')?>/'+search;
			
			return false;
	});

		
			$('.phoneLead').click(function(){
				var id = $(this).attr('rel');
				var callerno = $('#phone-' + id).text().trim();
				
				$('#customerConnectedModal').modal('show');
				$.get("<?php echo base_url('call/makeCall');?>/" + callerno + "/<?php echo $agentNumber;?>", 
				
							function(data){
								$('#customerConnectedResponse').html(data);
								 
								 setTimeout(function(){location.reload();},5000);
							});

		
			});
			$('.sendSmsLead').click(function() {
				var cuNu=$(this).attr('rel');
				var leadId = $(this).data('leadid');
				$('#smsLeadId').val(leadId);
				$('#customerSmsNo').attr('disabled', true).val(cuNu);
				$('#smsModalBoxToAnyCustomer').modal('show');
			});
				</script>		
		<input type="hidden" value="<?php if(isset($additionalParameter)){echo $additionalParameter; };?>" id="first">
		<!--Table shorting jquery-->
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/juqery.tableshorter.min.js"></script>
		<!--<script type="text/javascript" src="<?php //echo base_url()?>assets/js/custom/admin/allLead.min.js"></script>-->		
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/custom/admin/advanceSearch/allLead.js"></script>
		<script>
			
			/*single checkbox click event*/
			$('table td input:checkbox').on('click' , function(){
				var that = this;
				var temp = $(this).closest('tr').attr('id');
				var temp1 = temp.split('-');
				$('#lead-'+temp1[1]).val(that.checked);
				
				var leadCount = parseInt($('#leadsCount').val());
				leadCount = (that.checked)?leadCount:leadCount;
				$('#leadsCount').val(leadCount);
			});
			/*end*/
			
			$("#action").change(function(){
				if($("#action option:selected").val()=='transfer')
				{
					$("#counselor").show();
					$("#selectTelecaller").hide();
					$("#smsTemplates").hide();
				}
				else if($("#action option:selected").val()=='telecallerTransfer')
				{
					$("#counselor").hide();
					$("#selectTelecaller").show();
					$("#smsTemplates").hide();;
				}
				else if($("#action option:selected").val()=='delete')
				{
					$("#counselor").hide();
					$("#selectTelecaller").hide();
					$("#smsTemplates").hide();
				}
				else
				{
					$("#counselor").hide();
					$("#selectTelecaller").hide();
					$("#smsTemplates").show();
				}
			});
			function performAction()
			{
				if($("#action option:selected").val()=='transfer')
				{
					if($("#counselor option:selected").val()!='')
					{
						showTransferMultiModal();
					}
					else
					{
						$("#error").text("Select Counselor First.");
					}
				}
				else if($("#action option:selected").val()=='delete')
				{
					showDeleteMultiModal();
				}
				else if($("#action option:selected").val()=='sms')
				{
					if($("#smsTemplates option:selected").val()!='')
					{
						showSmsMultiModal();
					}
					else
					{
						$("#error").text("Select Template First.");
					}
				}
				else
				{
					$("#error").text("Select Action First.");
				}
			}
			
			function showSmsMultiModal()
			{
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#smsMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select At-least one record to SMS.");
				}
			}
			function showTransferMultiModal()
			{
				//alert($('#leadsCount').val());
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#transferMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select At-least one record to Transfer.");
				}
			}
			function showTransferToTelecallerMultiModal()
			{
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#showTransferToTelecallerMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select At-least one record to Transfer.");
				}
			}
			function showDeleteMultiModal()
			{
				//alert($('#leadsCount').val());
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#deleteMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select Atleast one record to Delete.");
				}
			}
			function performAction()
			{
				if($("#action option:selected").val()=='transfer')
				{
					if($("#counselor option:selected").val()!='')
					{
						showTransferMultiModal();
					}
					else
					{
						$("#error").text("Select Counselor First.");
					}
				}
				else if($("#action option:selected").val()=='telecallerTransfer')
				{
					
					if($("#selectTelecaller option:selected").val()!='')
					{
						showTransferToTelecallerMultiModal();
					}
					else
					{
						$("#error").text("Select Telecaller First.");
					}
				}
				else if($("#action option:selected").val()=='delete')
				{
					showDeleteMultiModal();
				}
				else if($("#action option:selected").val()=='sms')
				{
					if($("#smsTemplates option:selected").val()!='')
					{
						showSmsMultiModal();
					}
					else
					{
						$("#error").text("Select Template First.");
					}
				}
				else
				{
					$("#error").text("Select Action First.");
				}
			}
			function transferMultiLeads()
			{
				var leadsCsv = '';
				var counselorId = $('#counselor option:selected').val();
				if(counselorId=="")
					return;
				$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					/* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
					var checkBox = $('#leadCheckBox-'+temp1[1]);
					console.log(checkBox.attr('id')); */
					if($('#leadCheckBox-'+temp1[1]).prop('checked'))
					{
					leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
					}
				});
				dataString = "leads="+leadsCsv+"&counselorId="+counselorId;
				$.post(base_url+'lead/transferMultiLead',dataString,
				function(msg){
					//alert(msg);
					$("#transferMultiModal").modal('hide');
					$('#sample-table-2').find('tr > td:first-child input:checkbox')
					.each(function(){
					if(this.checked)
						$(this).closest('tr').remove();
					});
					$("#SuccessTransferModal").modal('show');
					setTimeout(function(){$("#SuccessTransferModal").modal('hide');location.reload();},1200); 
				});
			}
			function transferTelecallerMultiLeads()
			{
				var leadsCsv = '';
				var counselorId = $('#selectTelecaller option:selected').val();
				if(counselorId=="")
					return;
				$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					/* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
					var checkBox = $('#leadCheckBox-'+temp1[1]);
					console.log(checkBox.attr('id')); */
					if($('#leadCheckBox-'+temp1[1]).prop('checked'))
					{
					leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
					}
				});
				dataString = "leads="+leadsCsv+"&counselorId="+counselorId;
				$.post(base_url+'lead/transferMultiLead',dataString,
				function(msg){
					//alert(msg);
					$("#showTransferToTelecallerMultiModal").modal('hide');
					$('#sample-table-2').find('tr > td:first-child input:checkbox')
					.each(function(){
					if(this.checked)
						$(this).closest('tr').remove();
					});
					$("#SuccessTransferToTelecallerMultiModal").modal('show');
					setTimeout(function(){$("#SuccessTransferToTelecallerMultiModal").modal('hide');location.reload();},1200); 
				});
			}
			
			function smsMultiLeads()
			{
				var leadsCsv = '';
				$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					/* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
					var checkBox = $('#leadCheckBox-'+temp1[1]);
					console.log(checkBox.attr('id')); */
					if($('#leadCheckBox-'+temp1[1]).prop('checked'))
					{
					leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
					}
				});
				dataString = "leads="+leadsCsv+"&smsId="+$("#smsTemplates option:selected").val();
				$.post(base_url+'lead/smsMultiLead',dataString,
				function(msg){
					//alert(msg);
					$("#smsMultiModal").modal('hide');
					$("#SuccessSmsModal").modal('show');
					$("#responseSms").text(msg);
					setTimeout(function(){$("#SuccessSmsModal").modal('hide');},3200);
				});
			}
			function deleteMultiLeads()
			{
				var leadsCsv = '';
				
				$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					/* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
					var checkBox = $('#leadCheckBox-'+temp1[1]);
					console.log(checkBox.attr('id')); */
					if($('#leadCheckBox-'+temp1[1]).prop('checked'))
					{
					leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
					//alert(temp1[1]);
					}
				});
				dataString = "leads="+leadsCsv;
				//alert(dataString);
				$.post(base_url+'lead/deleteMultiLead',dataString,
				function(msg){
					//alert(msg);return;
					$("#deleteMultiModal").modal('hide');
					$('#sample-table-2').find('tr > td:first-child input:checkbox')
					.each(function(){
					if(this.checked)
						$(this).closest('tr').remove();
					});
					$("#SuccessDeleteModal").modal('show');
					setTimeout(function(){$("#SuccessDeleteModal").modal('hide');location.reload();} ,1200);
				});
			}
		</script>
		<?php $this->load->view('leads/telecallerModal');//load telecallerModal ?>
</body>

</html>
