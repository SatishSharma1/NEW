<!--test -->
<script type="text/javascript" src="<?php echo base_url()?>assets/js/bootstrap-timepicker.min.js"></script>
		
<!-- new form   -->
<div class="modal inmodal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Please fill the following form fields</h4>
                </div>
                <form class="form span3" action="home" method="post" id="updateForm" enctype="multipart/form-data">
                	<input type="hidden" id="leadId" name="leadId" value="">
                <div class="modal-body">
                <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="form-field-name">Full Name </label>
                                                <input type="text" placeholder="Full Name " id="form-field-name" name="name" class="form-control input-sm" value="">

                                            </div>
                                            <input type="hidden" name="id" id="id" value="<?=$profile_info->id?>"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="form-field-phone">Mobile </label>
                                                <input type="tel" placeholder="Mobile " id="form-field-phone" name="Phone" class="form-control input-sm" value="">

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="form-field-email">Email </label>
                                                <input type="email" placeholder="Email " id="form-field-email" name="Email" class="form-control input-sm" value="<?=$leadsInfo->email?>">

                                            </div>
                                        </div>
                                    </div>


                                      <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editFullName">Country </label>
                                             <select class="form-control" onchange="citypopulate();" data-placeholder="Choose a Country..." name="InterestedCountry" id="InterestedCountry">
								<option value="0">Select Country</option>
								<?php foreach ($countries as $country) : ?>
									<option value="<?php echo $country->id;?>"><?php echo $country->countryName; ?></option>
								<?php endforeach; ?>

								</select>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="leadCity">City </label>
                                         <select class="form-control" data-placeholder="Choose a City..." name="leadCity" id="leadCity">
								<option value="">Select City</option>
								<?php foreach ($cities as $city) : ?>
									<option value="<?php echo $city->id;?>"><?php echo $city->cityName; ?></option>
								<?php endforeach; ?>

								</select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="leadStatus">Status </label>
                                          <select class="form-control" data-placeholder="Choose a status..." name="leadStatus" id="leadStatus">
										<option value="">Select Status</option>
										<?php if($leadStatus):
											foreach($leadStatus as $status): ?>
												<optgroup label="<?php echo $status->detail; ?>" >
												<?php $childStatus = $this->leadmodel->getChildStatus($status->id);
													foreach($childStatus as $cs): ?>
														<option value="<?php echo $cs->id?>">
																<?php echo $cs->detail;?>
														</option>
												<?php endforeach; ?>	
												</optgroup>
											<?php endforeach; endif; ?>
									</select>
								<input type="hidden" value="" name="statusNameToCheck" id="statusNameToCheck">
                                            </div>
                                        </div>
                                    </div>
               
                     <div class="row-fluid">
				
										
					<div class="span4">
						
						
						
						
										

						<div class="control-group">
							<label class="control-label" for="form-field-notes">Notes</label>

							<textarea class="form-control" id="form-field-notes" placeholder="Notes"name="notes"></textarea>
						</div>
					</div>
				</div>                 
                       




 </div>
                <div class="modal-footer">
                  <input type="hidden"  id="editLead" value="">
                            <button class="btn btn-mini btn-info" name="submitUpdateLead"  type="submit">Update</button>

				
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<!-- end of new form   -->
	
		
		<!-- Modal box starts-->

						<div id="betaModal" class="modal hide fade">
						<div class="modal-header">
									<button class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:14px;"></i></button>
									<h4>Are You Sure Want To Delete Lead?</h4>
							</div>
						<div class="modal-body">
							Once you click on delete You will not able recover it again..!							
						</div>
							<div class="modal-footer">
							<input type="hidden"  id="deleteLead" value="">
							<button class="btn btn-mini btn-info" type="button" onclick="DeleteLeadData()">Delete</button>
							<button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
							</div>
						</div>

						<!-- Modal box ends-->
						
						<!-- success Modal box starts-->

						<div id="SuccessModal" class="modal hide fade">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:18px;"></i></button>		
									<h2>Successfully Updated...</h2>
							</div>

						</div>

						<!-- success Modal box ends-->
						<!-- success Delete Modal box starts-->

						<div id="SuccessDeleteModal" class="modal hide fade">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><i class="icon-remove" style="font-size:18px;"></i></button>		
									<h2>Successfully Deleted...</h2>
							</div>

						</div>
						<input type="hidden" value="<?php echo $pagename;?>" id="leadPageName">
						<!-- success Modal box ends-->
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
						<script>
						$('#timepicker1').timepicker({
								minuteStep: 1,
								showSeconds: true,
								showMeridian: false
							});
						$('#updateForm').validate({
								errorElement: 'span',
								errorClass: 'help-inline',
								focusInvalid: false,
								rules: {
									name: {
										required: true
									},
									Phone: {
										required: true,
										minlength: 10
									},
									Email: {
										required: true,
										email: true
									},
									lastPercentage: {
										range: [0, 100]
									}
								},
						
								messages: {
									name: {
										required: "Please provide a valid name",
									},
									Phone: {
										required: "Please specify a password.",
										minlength: "Please specify a valid phone"
									},
									Email: {
										required: "Please provide Email",
										email: "Please provide a valid Email",
									},
									lastPercentage:{
										range: "Percentage Not Valid"
									}
								},
						
								invalidHandler: function (event, validator) { //display error alert on form submit   
									$('.alert-error', $('.login-form')).show();
								},
						
								highlight: function (e) {
									$(e).closest('.control-group').removeClass('info').addClass('error');
								},
						
								success: function (e) {
									$(e).closest('.control-group').removeClass('error').addClass('info');
									$(e).remove();
								},
						
								errorPlacement: function (error, element) {
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
								//form.submit();
								//alert("Varified");
								updateLeadData();
								},
								invalidHandler: function (form) {
								}
							});
						</script>
						
						<!--Delete Edit lead script starts here -->		
					<script>
						function showDeleteModal(getID)
						{
							$('#deleteLead').val(getID);
							$('#betaModal').modal('show');		
						}
						function showEditModal(getID)
						{


							$('#editLead').val(getID);
							$('#modal-form').modal('show');
							var leadName=$('#name-'+getID).text();
							var leadNotes=$('#notes-'+getID).text();
							var leadPhone=$('#phone-'+getID).text();
							var leadStatus=$('#status-'+getID).text();
							var leadEmail=$('#leadEmail-'+getID).val();
							var city = $('#city-'+getID).text();

							$('#leadCity option').each(function(){
								$(this).attr('selected',false);
								 if($(this).val() == city)
								 {
								 $(this).attr('selected',true);
								 }
							});
							$("#leadCity").trigger('chosen:updated'); 
							
							
							
							/* code of getting extra info of lead */
							
							$.ajax({
							url:'<?php echo base_url()?>lead/getExtendedInfo',
							data: 'leadId='+getID,
							dataType: 'json',
							success:function(profile){
								
								//profile = $.parseJSON(profile);
								//console.log(profile.interestedCountry);
								
								$('#InterestedCountry option').each(function(){
									$(this).attr('selected',false);
									 if($(this).val() == profile.interestedCountry)
									 {
									 $(this).attr('selected',true);
									 }
								});
								$("#InterestedCountry").trigger('chosen:updated'); 
								
								$("#InterestedCourse").val(profile.interestedCourse);
								$("#lastPercentage").val(profile.lastPercentage);
								$("#lastQualification").val(profile.lastQualification);
								$("#timepicker1").val(profile.bestCallTime);
								
								console.log(profile);
								
							}
							
							});
							/******end********/
							$('#form-field-email').val(leadEmail);
							$('#form-field-phone').val(leadPhone);
							$('#form-field-name').val(leadName);
							$('#form-field-notes').val('');
							var checkStatusName=$('#checkStatusName-'+getID).val(); // for selected option
							$('#statusNameToCheck').val(checkStatusName);// saving id
							$('#leadStatus option').each(function(){
							$(this).attr('selected',false);
								 if($(this).val() == checkStatusName)
								 {
								 $(this).attr('selected',true);
								 }
								 
								});	
								
								$('#leadStatus').change(function(){
								if(($('#leadStatus').val()==4))
								 {
								 $('#AsignedTo').attr('disabled',true);
								 } 
								 else
								 {
								 $('#AsignedTo').attr('disabled',false);
								 } 
								});				
						}
						function updateLeadData()
						{ 

						var getID=$('#editLead').val();
						var leadNotes=$('#form-field-notes').val();
						var leadUpdateTime=$('#lastUpdatedTime').val();
						var leadEmail=$('#form-field-email').val();
						var leadPhone=$('#form-field-phone').val();
						var leadName=$('#form-field-name').val();
						var leadCity=$('#leadCity').val();
						//var leadCallBackDate=$('#id-date-picker-1').val();
						var leadCallBackDate=$('#timepicker1').val();
						//alert(leadCallBackDate);return;
						var leadStatus=$('#leadStatus').val();
						var AsignedTo=$('#AsignedTo').val();
						var lastQualification=$('#lastQualification').val();
						var lastPercentage=$('#lastPercentage').val();
						var InterestedCourse=$('#InterestedCourse').val();
						
						var InterestedCountry=$('#InterestedCountry').val(); 	
						var dataString = 'leadId=' + getID + '&notes=' + leadNotes + '&leadEmail=' + leadEmail + '&leadPhone=' + leadPhone + '&leadUpdateTime=' + leadUpdateTime + '&leadName=' + leadName + '&leadCity=' + leadCity + '&leadCallBackDate='+leadCallBackDate + '&leadStatus='+leadStatus+'&lastQualification='+lastQualification+ '&lastPercentage=' +lastPercentage+ '&InterestedCourse=' +InterestedCourse + '&InterestedCountry=' +InterestedCountry+'&AsignedTo='+AsignedTo;
							alert(dataString);
							$.post('<?php echo base_url('lead/updateLeadDetail');?>',dataString,function(data){
								alert(data);//this is tracking response
								if(data=='1')
								{
									$("#modal-form").modal('hide');
									$("#SuccessModal").modal('show');
									$('#name-'+getID).text(leadName);
									$('#city-'+getID).text(leadCity);
									if(leadNotes!='')
									{
									$('#notes-'+getID).text(leadNotes);
									}
									$('#phone-'+getID).text(leadPhone);
									var selectedoption =$('#leadStatus option:selected').text();
									if(selectedoption=='Select Status')
									{
									$('#status-text-'+getID).text(' ');
									}
									if(selectedoption!='Select Status'){
									$('#status-text-'+getID).text(selectedoption);
									}
									
									$('#leadEmail-'+getID).val(leadEmail);
									var checkStatusName=$('#statusNameToCheck').val();
									$('#checkStatusName-'+getID).val($('#leadStatus option:selected').val());
									var AsignedTo=$('#AsignedTo').val();
									var leadPageName=$('#leadPageName').val();
									console.log(leadPageName);//this is for current page
									if(leadPageName=='alllead')
									{
										if(AsignedTo!='')
										{
													$('#leadID-'+getID).fadeOut();
													for(var i=getID;i<(getID+10);i++)
													{
														var count=parseInt($('#serial-'+i).text());
													$('#serial-'+i).text(--count);
													}
													/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
											setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
										}
										else if(AsignedTo=='')
										{
											setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											
										}
										
									}
									if(leadPageName=='freshlead')//  if page is freshlead
									{ 
										if(AsignedTo!='')
										{
													$('#leadID-'+getID).fadeOut();
													for(var i=getID;i<(getID+10);i++)
													{
														var count=parseInt($('#serial-'+i).text());
													$('#serial-'+i).text(--count);
													}
													/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
										}
										else if(AsignedTo=='')
										{
											
											 if((leadStatus==1)||(leadStatus==0))
											{	
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											}
											else 
											{
												$('#leadID-'+getID).fadeOut();
												for(var i=getID;i<(getID+10);i++)
												{
													var count=parseInt($('#serial-'+i).text());
												$('#serial-'+i).text(--count);
												}
												/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											}
										}
										
									}
									if(leadPageName=='attemptedlead')//  if page is attempted
									{ 
										if(AsignedTo!='')
										{
													$('#leadID-'+getID).fadeOut();
													for(var i=getID;i<(getID+10);i++)
													{
														var count=parseInt($('#serial-'+i).text());
													$('#serial-'+i).text(--count);
													}
													/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
										}
										else if(AsignedTo=='')
										{
											
											 if((leadStatus==2)||(leadStatus==3))
											{	
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											}
											else 
											{
												$('#leadID-'+getID).fadeOut();
												for(var i=getID;i<(getID+10);i++)
												{
													var count=parseInt($('#serial-'+i).text());
												$('#serial-'+i).text(--count);
												}
												/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											}
										}
										
									}
									if(leadPageName=='invalidlead')//  if page is invalidlead
									{ 
										if(AsignedTo!='')
										{
													$('#leadID-'+getID).fadeOut();
													for(var i=getID;i<(getID+10);i++)
													{
														var count=parseInt($('#serial-'+i).text());
													$('#serial-'+i).text(--count);
													}
													/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
										}
										else if(AsignedTo=='')
										{
											
											 if(leadStatus==4)
											{	
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
											}
											else 
											{
												$('#leadID-'+getID).fadeOut();
												for(var i=getID;i<(getID+10);i++)
												{
													var count=parseInt($('#serial-'+i).text());
												$('#serial-'+i).text(--count);
												}
										/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
												setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
												
											}
										}
										
									}
									
								} 
									});
						
						}
						function DeleteLeadData()
						{
						var getID=$('#deleteLead').val();
						alert(getID);
							var dataString = 'id=' + getID;
									$.post('<?php echo base_url('lead/removeLead');?>',dataString,function(data){
										alert(data);
										if(data=='1')
										{
											$('#leadID-'+getID).fadeOut();
											// for(var i=getID;i<(getID+10);i++)
											// {
												// var count=parseInt($('#serial-'+i).text());
												// $('#serial-'+i).text(--count);
											// }
										}
										$('#betaModal').modal('hide');
										$("#SuccessDeleteModal").modal('show');
										/* change text of showing list of leads starts*/
										var gettext=$('#sample-table-2_info').text();
										var arr = gettext.split(" ");
										var thirdArray=(arr[3]-1);
										var fifthArray=(arr[5]-1);
										if(thirdArray==0)
										{
										var newString=arr[0]+" "+0+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+ arr[6];
										}
										else{
										var newString=arr[0]+" "+arr[1]+" "+arr[2]+" "+thirdArray+" "+arr[4]+" "+fifthArray+" "+arr[6];
										}
										$('#sample-table-2_info').text(newString);
										/* change text of showing list of leads ends*/
										setTimeout(function(){$("#SuccessDeleteModal").modal('hide');},1200);
									});
									
						}


						function citypopulate(){
							var leadUrl= "<?php echo base_url('lead') ?>/";
						var countryId = $('#InterestedCountry').val();


						$.post("<?php echo base_url('lead') ?>/getCityByCountryId", "cid=" + countryId, function(d) {
                $('#leadCity').html(d);       
                
            });
						}


					
			</script>
<!--Delete lead script starts here -->
<!--test -->