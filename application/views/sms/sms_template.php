            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>SMS Template</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>SMS</a>
                        </li>
                        <li class="active">
                            <strong>Templates</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Organisation SMS Templates</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
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
                        <table class="table table-striped" id="Blacklist">
                            <thead>
                            <tr>
                                                            <th>Name</th>
                                                            <th class="hidden-480">Template</th>
                                                            <th class="hidden-480">Status</th>
                                                            <th class="hidden-480">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($smsTemplates as $sms) {
                        
                       ?>

                                                                                                            <tr id="smsRow-<?php echo $sms->id?>">
                                                            <td class="">
                                                                <?php echo $sms->name;?>
                                                            </td>
                                                            <td class="hidden-480">
                                                                <?php echo $sms->template?>
                                                            </td>
                                                            <td class="hidden-480">
                                                                <?php
                                                                if($sms->approved)
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
                                                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="openModelRemoveSms(<?php echo $sms->id;?>)" title="Remove">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>               
                                                        
                                  
                                                        <?php }?>
                            
                           
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
                <div class="col-lg-5">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Create New</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
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
                            <form class="form-horizontal" id="importSmsForm"  name="importleadForm" method="post" action="<?php echo base_url('sms/template');?>">
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Name</label>

                                    <div class="col-lg-10"><input type="text" id="name" placeholder="Name" name="name" required  class="form-control"> 

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Template</label>
                                    <div class="col-lg-10"><input type="text" name="template" id="template" placeholder="Default Text" required class="form-control"></div>
                                </div>
                                
                               
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" name="submit" type="submit">Submit</button>
                                          <?php 
                                                        if(isset($success))
                                                        {
                                                        echo $success;
                                                        }
                                                         ?>
                                    </div>
                                </div>
                            </form>
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

       <div class="modal inmodal fade" id="removeSmsModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="removeSmsId" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="removeSms()">Delete</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 

  

 <div class="modal inmodal fade" id="SuccessDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h2>Successfully Deleted...</h2>
                </div>
                
            </div>
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


            <script type="text/javascript">
    
        $(function(){
        
        var oTable1 = $('#smsTemplateTable').dataTable( {
                    "aoColumns": [
                      null,
                      null,
                       null,
                      null
                    ] } );
        
        var base_url  = '<?php echo base_url();?>';
            $('#importSmsForm').validate({
                errorElement: 'span',
                errorClass: 'help-inline',
                focusInvalid: false,
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    template: {
                        required: true,
                        minlength: 1
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
                        minlength: "Please enter at least one character."
                    }
                },
        
                invalidHandler: function (event, validator) { //display error alert on form submit   
                    $('.alert-error', $('#importSmsForm')).show();
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
        
            function removeSms()
            {
                var smsId = $('#removeSmsId').val();
                var data = 'smsId='+smsId;
                $('#removeSmsModal').modal('hide');
                $.post('<?php echo base_url()?>sms/removeSms',data,function(msg){
                    $('#smsRow-'+smsId).remove();
                    $("#SuccessDeleteModal").modal('show');
                    setTimeout(function(){$("#SuccessDeleteModal").modal('hide');},1200);
                });
            }
            function openModelRemoveSms(smsId)
            {
                $('#removeSmsId').val(smsId);
                $('#removeSmsModal').modal('show');
            }
            
            
        </script>
</body>

</html>
