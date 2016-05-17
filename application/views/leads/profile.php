             <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Leads</a>
                        </li>
                        <li class="active">
                            <strong>Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?=$profile_info->name?></h5>
                            <div class="ibox-tools">
                              <!--  <span class="label label-primary">90 IR</span> -->
                            </div>
                        </div>
                        <div>
                            <div class="ibox-content ibox-heading">
                            <!--    <h3>The Lead was connected <?=count($notes)?> times.</h3>
                                <small>Neha Sharma updated on 2015-08-30</small>  -->
                            </div>
                        </div>
                    </div>
                  <!--  <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>SMS</h5>
                        </div>
                        <div>
                            <div class="ibox-content">
                                <?php foreach($sms as $_sms) : ?>
                                <button class="btn btn-xs btn-warning" onclick="sms_lead('<?=$_sms['id']?>')"><i class="fa fa-mobile"></i>&nbsp;&nbsp;<?=$_sms['name']?></button>
                                <?php endforeach; ?>
                                <div id="sms_success"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Emails</h5>
                        </div>
                        <div>
                            <div class="ibox-content">
                                <?php foreach($email as $_email) : ?>
                                <button class="btn btn-xs btn-success" onclick="email_lead('<?=$_email['id']?>')"><i class="fa fa-mobile"></i>&nbsp;&nbsp;<?=$_email['name']?></button>
                                <?php endforeach; ?>
                                <div id="email_success"></div>
                            </div>
                        </div>
                    </div>     -->
                    
                   
                </div>
                <div class="col-md-8">
                    

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <button class="btn btn-primary btn-xs pull-right" id="edit"><i class="fa fa-edit"></i> Edit</button>
                            <h5>Profile Snapshot <span class="badge badge-success"><?=count($notes)?></span></h5>
                        </div>
                        <div>
                            <div class="ibox-content" id="snapshot">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <small class="stats-label">Name</small>
                                        <h4><?php echo $leadsInfo->name;?></h4>
                                        
                                    </div>

                                    <div class="col-xs-4">
                                        <small class="stats-label">Mobile</small>
                                        <h4><?php echo $leadsInfo->phone;?></h4>
                                    </div>
                                    <div class="col-xs-4">
                                        <small class="stats-label">Email</small>
                                        <h4><?php echo $leadsInfo->email;?></h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <small class="stats-label">Location</small>
                                        <h4><?php echo $leadsInfo->city;?></h4>
                                        
                                    </div>

                                    
                                </div>
                               
                               
                               
                              
                               
                                
                            </div>
                            <div class="ibox-content" id="lead-edit-all">
                                <form role="form" action="<?php echo base_url('')?>lead/profile/<?php echo $leadId;?>" method="post" id="submitData">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editFullName">Full Name </label>
                                                <input type="text" placeholder="Full Name " id="editFullName" name="editFullName" class="form-control input-sm" value="<?=$leadsInfo->name;?>">

                                            </div>
                                            <input type="hidden" name="id" id="id" value="<?=$profile_info->id?>"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editPhone">Mobile </label>
                                                <input type="tel" placeholder="Mobile " id="editPhone" name="editPhone" class="form-control input-sm" value="<?=$leadsInfo->phone?>">

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editEmail">Email </label>
                                                <input type="email" placeholder="Email " id="editEmail" name="editEmail" class="form-control input-sm" value="<?=$leadsInfo->email?>">

                                            </div>
                                        </div>
                                    </div>

                  <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editFullName">Country </label>
                                                 <select data-placeholder="Interested Country" name="editInterestedCountry" id="editInterestedCountry"  onChange="changecountry();" style="width:207px">
                                                            <option value="">Select Country</option>
                                                                <?php 
                                                                foreach($country as $countryname)
                                                                {
                                                                ?>
                                                                <?php if($countryname->countryName == $selectedCountry['name']){ ?>
                                                                    <option value="<?php echo $selectedCountry['id']; ?>" <?php echo 'selected';?>><?php echo $selectedCountry['name']; ?></option>
                                                                <?php }else{ ?>
                                                                    <option value="<?php echo $countryname->id?>"><?php echo $countryname->countryName?></option>
                                                                <?php } ?>
                                                                
                                                                <?php
                                                                }
                                                                ?>
                                                        </select>
                                            </div>
                                            <input type="hidden" name="id" id="id" value="<?=$profile_info->id?>"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editPhone">City </label>
                                                 <select data-placeholder="City" name="editCity" id="editCity" style="width:207px">
                                                            <option value="">Select City</option>
                                                            <?php if(isset($leadsInfo->city)) { ?>
                                                                <option selected><?php echo $leadsInfo->city;?></option>
                                                            <?php } ?>
                                                        </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="editEmail">Status </label>
                                               <select data-placeholder="Edit Status" name="editStatus" id="editStatus" onChange="virtualtime()" style="width:207px">
                                                            <option value="">Select Status</option>
                                                            <?php

                                                            if($leadStatus)
                                                            {
                                                                foreach($leadStatus as $status)
                                                                {?>
                                                                    <optgroup label="<?php echo $status->detail;?>">
                                                                    <?php
                                                                    $childStatus = $this->leadmodel->getChildStatus($status->id);
                                                                    foreach($childStatus as $cs)
                                                                    {
                                                                    ?>
                                                                        <option value="<?php echo $cs->id?>" <?php if($leadsInfo->status==$cs->id){echo "selected";if($cs->id=='33')$vcstatus=1;if($cs->id=='23' || $cs->id=='24')$aptstatus=1;}?>>
                                                                            <?php echo $cs->detail;?>
                                                                        </option>
                                                                        
                                                                    <?php
                                                                    }?>
                                                                    </optgroup>
                                                                <?php
                                                                }
                                                            }?>
                                                        </select>
                                            </div>
                                        </div>
                                    </div>


               <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="editFullName">Call Back Time </label>
                                                <input type="datetime-local" placeholder="Call Back TIme" id="editCallBackTime" name="editCallBackTime" class="form-control input-sm" value="">

                                            </div>
                                            <input type="hidden" name="id" id="id" value="<?=$profile_info->id?>"/>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="editPhone">Notes </label>
                                                <textarea type="tel" placeholder="Notes " id="editNotes" name="editNotes" class="form-control input-sm" rows="3" value=""></textarea>
                                                  
                                            </div>
                                        </div>
                                        
                                    </div>                       
                                


                                    <div class="row">
                                        <div class="col-sm-2 pull-right">
                                            <button type="submit" name="submitUpdateLead" id= "submitUpdateLead" class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Save</button>
                                              
                                        </div>
                                    </div>
                                </form>


                                <div>
                                    <h4>Quick Updates</h4>
                                    <div id="shortcuts"></div>
                                    <br/>
                                </div>
                                <div id="lead_success"></div>                            
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Activites</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
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

                            <div>
                                <div class="feed-activity-list">
                                   <?php if($recordingTimeline):
                                                                                    foreach ($recordingTimeline as $label => $records): ?>
                                                                                     <span class="timeline-label"><b><?php echo $label ?></b></span>
                                                                                      <?php foreach($records as $record): ?>
                                    <div class="feed-element">
                                        <div class="media-body">
                                            
                                               <span class="timeline-date"><?php echo $record['time'];?></span>
                                            
                                            <!--<small class="pull-right text-navy">1m ago</small>-->
                                            <strong><?php echo $record['notes']; ?></strong>  <span class="label label-success"></span><br>
                                            <small class="text-muted"></small>
                                            <div class="well">
                                                <div>
                                                                                                        <audio controls preload="none" style="width:150px">
                                                                                                        <source src="<?php echo $record['url'];?>"
                                                                                                                 type='audio/mp4'>
                                                                                                         <p>Your user agent does not support the HTML5 Audio element.</p>
                                                                                                        </audio>
                                                                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endforeach; endif; ?>
                                </div>

                                <button class="btn btn-primary btn-block m"><i class="fa fa-arrow-down"></i> Show More</button>

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
        <script type="text/javascript">
            $( document ).ready(function() {
                $("#lead-edit-all").hide();

                var base_url = '<?=base_url()?>';
                $.post(base_url+'shortcuts/get_quick_status',
                function(msg){
                    var obj = JSON.parse(msg);
                    for(i=0; i <= obj.length; i++)  {
                        $('#shortcuts').append('<button type="button" class="btn btn-outline btn-danger btn-xs" onclick="quick_update('+obj[i].id+')">'+obj[i].name+'</button>&nbsp;&nbsp;');
                    }
                });
            });


                $("#edit").click(function() {
                $("#snapshot").hide( "slow" );
                $("#lead-edit-all").show( "slow" );
            });

            function quick_update(id) {
                var base_url = '<?=base_url()?>';
                var dataString = 'id='+id+'&leadid='+'<?=$profile_info->id?>';
                $.post(base_url+'shortcuts/quick_update',dataString,
                function(msg){
                    $('#lead_success').html('<div class="alert alert-success">Template successfully created!</div>');
                });
            }

            function sms_lead(sms_id) {
                var base_url = '<?=base_url()?>';
                dataString = "leads="+'<?=$profile_info->id?>'+"&smsId="+sms_id;
                //alert(dataString);
                $.post(base_url+'plugins/sms_multi_lead',dataString,
                function(msg){
                    
                    $("#sms_success").html('<div class="alert alert-success">SMS Successfully Sent.</div>');
                });                
            }

            function email_lead(email_id) {
                var base_url = '<?=base_url()?>';
                dataString = "leads="+'<?=$profile_info->id?>'+"&emailId="+email_id;
                //alert(dataString);
                $.post(base_url+'plugins/email_multi_lead',dataString,
                function(msg){
                    if(msg=='success') {
                    $("#email_success").html('<div class="alert alert-success">Email successfully sent.</div>');  }
                    else {
                    $("#email_success").html('<div class="alert alert-success">Email successfully sent.</div>');                        
                    }
                });                
            }
            var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }

                document.getElementById('docs_checked').addEventListener('change', function(event){
                var elem = event.target;
                var data_value = elem.checked ? elem.value : '';
                var data_name = elem.name;
                //alert(data_name);alert(data_value);
                var dataString = 'name='+data_name+'&value='+data_value+'&lead_id='+'<?=$profile_info->id?>'+'&user_id='+'<?=$userid?>';
                //alert(dataString);
                $.post("<?php echo base_url('leads/docs_checked')?>",dataString,function(msg){
                    //alert(msg);
                    //$('#transfer_success').text('Successfully lead transfered!');
                    //setTimeout(function(){$("#transfer_individual").modal('hide');location.reload();} ,1200);
                });
            });

            function transfer_individual_lead() {
                var toMail = $('#org_email_name').val();
                var t_phone = $("#t_phone:checked").length ? $("#t_phone:checked").val() : '';
                var t_email = $("#t_email:checked").length ? $("#t_email:checked").val() : '';
                var t_doc = $("#t_doc:checked").length ? $("#t_doc:checked").val() : '';
                var org_transfer_name = $("#org_transfer_name").val();
                var leads = '<?=$profile_info->id?>';
                var comments = $("#comments").val();
                if(!comments.trim()) { alert("Error: Please add a note to forward the interest!"); }
                //alert(leads);
                //alert(t_doc+t_email+t_phone);
                var dataString = 't_phone='+t_phone+'&t_email='+t_email+'&t_doc='+t_doc+'&leads='+leads+'&org_transfer_name='+org_transfer_name+'&comments='+comments+'&tomail='+toMail;
                //alert(dataString);
                $.post("<?php echo base_url('campaign/transferMultiLeadToAdmin')?>",dataString,function(msg){
                    $('.transfer_success').html('<div class="alert alert-success">Lead successfully transfered.</div>');
                   // setTimeout(function(){$("#forward").modal('hide');location.reload();} ,1200);
                });
            }

             function transfer_individual_docs() {
                var toMail = $('#org_email_name_docs').val();
                var t_phone = $("#t_phone:checked").length ? $("#t_phone:checked").val() : '';
                var t_email = $("#t_email:checked").length ? $("#t_email:checked").val() : '';
                var t_doc = $("#t_doc:checked").length ? $("#t_doc:checked").val() : '';
                var org_transfer_name = $("#org_transfer_name_docs").val();
                var leads = '<?=$profile_info->id?>';
                var comments = $("#comments").val();
                if(!comments.trim()) { alert("Error: Please add a note to forward the interest!"); }
                //alert(leads);
                //alert(t_doc+t_email+t_phone);
                var dataString = 't_phone='+t_phone+'&t_email='+t_email+'&t_doc='+t_doc+'&leads='+leads+'&org_transfer_name='+org_transfer_name+'&comments='+comments+'&tomail='+toMail;
                //alert(dataString);
                $.post("<?php echo base_url('campaign/transferDocsToAdmin')?>",dataString,function(msg){
                    $('.transfer_success_docs').html('<div class="alert alert-success">'+msg+'.</div>');
                    //setTimeout(function(){$("#forward").modal('hide');location.reload();} ,1200);
                });
            }
        </script>
        <script>
// just for the demos, avoids form submit
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
    $('#lead_info').validate({
        rules: {
            name: {
                required: true
            },
            mobile: {
                required: true,
                number: true
            },
            status: {
                required: true
            },
            notes: {
                required: true
            }
        },
        submitHandler: function(form) {
    var postData = $('#lead_info').serialize();
    //alert(postData);
    var formURL = '<?php echo base_url("leads/save_leads_info"); ?>';
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            $('#lead_info').hide();
            $('#snapshot').show();
            $('#lead_success').html('<div class="alert alert-success">Lead successfully updated!</div>');
            window.setTimeout(function(){location.reload()},3000);
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            alert('Failed');     
        }
    });
    e.preventDefault(); //STOP default action
    e.unbind(); //unbind. to stop multiple form submit.

    },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

                $('#campaignlist').on('change', function(){
                    var CampaignId =$("#campaignlist").val();
                        var dataString = 'leadId=' + <?php echo $profile_info->id;?> + '&CampaignId=' + CampaignId;
                        //alert(dataString);
                        $.post("<?php echo base_url('leads/update_bucket')?>",dataString,function(msg){
                            //alert(msg);
                        });
                    
                });


                function delete_doc(doc)    {
                //alert(doc);
                var dataString = 'name='+doc+'&leadid='+'<?=$profile_info->id?>';
                //alert(dataString);
                $.post("<?php echo base_url('leads/docs_delete')?>",dataString,function(msg){
                    alert(msg);
                    //$('#transfer_success').text('Successfully lead transfered!');
                    setTimeout(function(){location.reload();} ,1200);
                });             
            }

            function showUpload()
            {
                //alert("sdfdsd");
                $("#docs_upload").modal('show');
            }

            function show_forward()
            {
                //alert("sdfdsd");
                $("#forward").modal('show');
                $.post("<?php echo base_url('plugins/get_admin_list')?>",function(msg){
                    //alert(msg);
                    $('#org_transfer_name').html(msg);
                });             
            }

             function show_forward_docs()
            {
                //alert("sdfdsd");
                $("#forward_docs").modal('show');
                $.post("<?php echo base_url('plugins/get_admin_list')?>",function(msg){
                    //alert(msg);
                    $('#org_transfer_name_docs').html(msg);
                });             
            }


             function leadmentor_juno_predict()
            {
               var url = 'http://localhost/LMS/leads/profile/<?php echo $profile_info->id;?>';
           

             var countries = [];
$('input[name="icountry[]"]:checked').each(function () {
    countries.push($(this).val());
});
            // alert(yourarray);
              var optedCourse ="Undergraduate";
              var Highaverage =$('#ielts').val();
              var Bachaverage =$('#ielts').val();
              var Masteraverage=$('#ielts').val();
              var GMAT =$('#gmat').val();
              var GRE =$('#gre').val();
              var TOEFL=$('#toefl').val();
              var IELTS=$('#ielts').val();
              var SAT=$('#sat').val();
              var PTE=$('#pte').val(); 
              var twelfthEng=$('#twelfth').val(); 
              var leadID ="<?php echo $profile_info->id;?>";

              var form = $('<form action="' + url + '" method="post">' +
                 '<input type="hidden" name="countries" value="' + countries + '" />' +
                  '<input type="hidden" name="optedCourse" value="' + optedCourse + '" />' +
                   '<input type="hidden" name="High-average" value="' + Highaverage + '" />' +
                   '<input type="hidden" name="Bach-average" value="' + Bachaverage + '" />' +
                    '<input type="hidden" name="Master-average" value="' + Masteraverage + '" />' +
                     '<input type="hidden" name="GMAT" value="' + GMAT + '" />' +
                      '<input type="hidden" name="GRE" value="' + GRE + '" />' +
                       '<input type="hidden" name="TOEFL" value="' + TOEFL + '" />' +
                        '<input type="hidden" name="IELTS" value="' + IELTS + '" />' +
                         '<input type="hidden" name="SAT" value="' + SAT + '" />' +
                          '<input type="hidden" name="PTE" value="' + PTE + '" />' +
                          '<input type="hidden" name="hschool_eng" value="' + twelfthEng + '" />' +
                          '<input type="hidden" name="leadid" value="' + leadID + '" />' +
                           
                             '</form>');
                       $('body').append(form);
                          form.submit();           
            }







             function show_reply_mail(id)
            {
                //alert(id);
               // ajax hit to get to emails from incoming emails 
               var dataString = 'notesID='+id;
                $.post("<?php echo base_url('leads/get_to_emails_by_notes_id')?>",dataString,function(msg){
                    //alert(msg);
                     $("#to").val(msg);
                     $("#notesID").val(id);
                     $("#reply_mail").modal('show');
                    
                });  





               
            /*    $.post("<?php echo base_url('plugins/get_admin_list')?>",function(msg){
                    //alert(msg);
                    $('#org_transfer_name').html(msg);
                });  */           
            }


              function mail_university_student(){
                     var universities = [];
                      var countries = [];
                     var to ="<?=$profile_info->email?>";
$('input[name="selectedUniversity[]"]:checked').each(function () {
    universities.push($(this).val());
});

//$('input[name="countryName[]"]').each(function () {
//    countries.push($(this).val());
//});

  //var dataString = 'universities='+universities+'&to='+to;
 /* alert(dataString);
                $.post("<?php echo base_url('leads/lead_universities_student')?>",dataString,function(msg){
                    //alert(msg);
                   // console.log(msg);
                     //$("#to").val(msg);
                     //$("#notesID").val(id);
                     //$("#reply_mail").modal('show');
                     alert('successfully mailed');
                    
                });  */

               var base_url = '<?=base_url()?>';
                dataString = "leads="+'<?=$profile_info->id?>'+"&emailId=48"+'&universities='+universities+'&countries='+countries;
                //alert(dataString);
                $.post(base_url+'plugins/email_multi_lead',dataString,
                function(msg){
                    if(msg=='success') {
                          alert('successfully mailed');
                 //   $("#email_success").html('<div class="alert alert-success">Email successfully sent.</div>');  }
                   } ;
                });                



                }
               


               function changecountry()
            {
                //alert('sa');
                
                url="<?php echo base_url()?>lead/getCityByCountryId";
                   data = {cid:$("#editInterestedCountry").val()};
                    $.ajax({
                    type : 'POST',
                    data : data,
                    url  : url,
                    
                    success: function(data){
                      $("#editCity").html(data);
                    },
                    
                   });
            
            
    
            
            }
            </script>
<?php //$this->load->view('leads/docs_upload'); ?>
<?php //$this->load->view('leads/forward'); ?>
<?php //$this->load->view('leads/reply_mail'); ?>
</body>

</html>
