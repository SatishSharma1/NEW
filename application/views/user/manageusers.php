            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Manage Users</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Organization Setup</a>
                          </li>
                        <li class="active">
                            <strong>Manage Users</strong>
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
                        <h5>Organisation Users</h5>
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
                        <table class="table table-striped" id="holiday">
                            <?php 
                                                if($users)
                                                {
                                                ?>
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($users as $user) {
                                $roleTxt = $this->usermodel->getUserRole($user->userLevel);
                        
                       ?>

                                             <tr id="user-<?php echo $user->id;?>">
                                                            <td class="">
                                                                <?php echo $user->userName;?>
                                                                <input type="hidden" id="name-<?php echo $user->id;?>" value="<?php echo $user->userName;?>">
                                                            </td>
                                                            <td><a href="#">
                                                                <?php echo $user->userEmail?>
                                                                <input type="hidden" id="email-<?php echo $user->id;?>" value="<?php echo $user->userEmail;?>">
                                                            </a></td>
                                                            <td>
                                                                <?php echo $user->userPhone?>
                                                                <input type="hidden" id="phone-<?php echo $user->id;?>" value="<?php echo $user->userPhone;?>">
                                                            </td>
                                                            <td class="hidden-480">
                                                                <?php echo $roleTxt?>
                                                                <input type="hidden" id="level-<?php echo $user->id;?>" value="<?php echo $roleTxt;?>">
                                                            </td>
                                                                
                                                     
                                                                                                                        
                                                        

                                   <td class="center" id="action-<?php echo $user->id;?>" >
                                                                <div class="hidden-phone visible-desktop btn-group">
                                                                    
                                                                <?php
                                                                    if($user->userStatus!=0){   
                                                                ?>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteUser(<?php echo $user->id.",'".$user->userEmail."'"; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="update(<?php echo $user->id.",".$user->userStatus; ?>)"><i class="fa fa-edit"></i></button>
                                 <?php
                                      }
                                     ?>
                                         </div>
                                </td>


                                          </tr>
                                                    <?php 
                                                    }
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                        <tr>
                                                            <td class="">No User Created Yet!</td>
                                                        </tr>
                                                    <?php 
                                                    }
                                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <div class="col-lg-5">
                    <div class="ibox float-e-margins"  id="updateUserDetail" style="display:none">
                        <div class="ibox-title">
                            <h5>Update User</h5>

                            <div class="col-lg-offset-6">
                                                <button class="btn btn-small btn-success"  id="createUser">Create</button>
                                            </div>

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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('user/manageusers')?>" enctype='multipart/form-data'>
                                <p>Update User.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Name</label>

                                    <div class="col-lg-10"><input type="text" name="name" id="name" placeholder="Name" class="form-control" value="<?php echo set_value('name')?>"/>
                                                            <?php if(form_error('name')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('name')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Profile Pic</label>
                                    <div class="col-lg-10">
                                       <input type="file" name="profile_pic" id="id-input-file-2" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="email" id="email" placeholder="Email" class="form-control"/>
                                                            <?php if(form_error('email')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('email')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" name="password" id="password" placeholder="password" class="form-control" />
                                                            <?php if(form_error('password')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('password')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Phone</label>
                                                                  
								   <div class="col-lg-10">
                                       <input type="text" name="phone" id="phone" placeholder="Phone" maxlength="13" class="form-control"/>
 <span class="help-inline" style="color:green;">* Country Code is mandatory (eg. +919876543210)</span>                                                      
													  <?php if(form_error('phone')){?>
                                            
											 <span class="help-inline"><?php echo "<font color=red>".form_error('phone')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Role</label>
                                    <div class="col-lg-10">
                                       <select name="role" id="role" style="display:none;">
                                                                <option value="<?php echo $this->config->item('TelecallerLevel')?>">Telecaller</option>
                                                                <option value="<?php echo $this->config->item('CounslorLevel')?>">Counselor</option>
                                                            </select>
                                                            <input type="text" id="roleText" value="" readonly="readonly" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group">
                                                        <label class="col-lg-2 control-label">Ban User</label>

                                                        <div class="col-lg-10">
                                                            
                                                                <input name="ban_user" id="ban_user_upd" class="js-switch_2" class="form-control" type="checkbox" />
                                                                <span class="lbl"></span>
                                                            
                                                        </div>
                                                    </div>
                               
                               <div class="space-4"></div>

                               <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                                        <input type="hidden" id="updateUserId" name="updateUserId" value="0"/>
                                                        <button type="submit" class="btn btn-small btn-success" name="submitUpdateUser" id="submitUpdateUser" value="1">
                                                            Update
                                                            <i class="icon-arrow-right icon-on-right bigger-110"></i>
                                                        </button>
                                                   </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>

<!-- create form   -->

                 <div class="ibox float-e-margins"  style="display:block" id ="CreateUserDetail">
                        <div class="ibox-title">
                            <h5>Create User</h5>
       <div class="upload_csv" style="width: 20%;float: right;margin-right: 6%;background-color: #307ecc;color: #fff;text-align: center;border-radius: 4px;margin-top:2%;cursor:pointer;" onclick="showUploadCSV()">Upload CSV</div>

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
                            <?php
                                                if($createPermission)
                                                {
                                            ?>
                            <form id="CreateUserForm" class="form-horizontal" method="post" action="<?php echo base_url('user/manageusers')?>" enctype='multipart/form-data'>
                                <p>Create User.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Name</label>

                                    <div class="col-lg-10"><input type="text" name="name" id="name" placeholder="Name" class="form-control" value="<?php echo set_value('name')?>"/>
                                                            <?php if(form_error('name')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('name')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Profile Pic</label>
                                    <div class="col-lg-10">
                                       <input type="file" name="profile_pic" id="id-input-file-2" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="email" id="email" placeholder="Email" class="form-control"/>
                                                            <?php if(form_error('email')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('email')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" name="password" id="password" placeholder="password" class="form-control" />
                                                            <?php if(form_error('password')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('password')."</font>" ;?></span>
                                                            <?php }?>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Phone</label>
                                    <div class="col-lg-10">
                                        <div class="col-lg-2">
                                            <div class="row">
                                         <select class="add-on phone" style="height:30px" name="code" id="code1" class="form-control">
                                                               <option value="">Code</option>  
                                                               <?php foreach($countryCode as $code): ?>  
                                                                <option><?php echo $code->code; ?></option>  
                                                               <?php endforeach; ?>  
                                                               </select>  
                                                           </div>
                                                           </div>
                                                 <div class="col-lg-8">          
                                       <input type="text" name="phone" id="phone" placeholder="Phone" maxlength="10" class="form-control"/>
                                                            <?php if(form_error('phone')){?>
                                                            <span class="help-inline"><?php echo "<font color=red>".form_error('phone')."</font>" ;?></span>
                                                            <?php }?>
                                    </div> </div>
                                </div>

                               

                                <div class="form-group"><label class="col-lg-2 control-label">Role</label>
                                    <div class="col-lg-10">
                                       <select name="role" id="role" class="form-control">
                                                                <option value="<?php echo $this->config->item('TelecallerLevel')?>">Agent/Operator</option>
                                                              
                                                            </select>
                                                          
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                                        <label class="col-lg-2 control-label">Ban User</label>

                                                        <div class="col-lg-10">
                                                            
                                                                <input name="ban_user" id="ban_user" class="js-switch_2" class="form-control" type="checkbox" />
                                                                <span class="lbl"></span>
                                                            
                                                        </div>
                                                    </div>


                               
                               <div class="space-4"></div>

                               <div class="col-lg-offset-2 col-lg-10">
                                                        <input type="hidden" id="updateUserId" name="updateUserId" value="0"/>
                                                        <button type="submit" class="btn btn-small btn-success" name="submitCreateUser" id="submitCreateUser" value="1">
                                                            Submit
                                                            <i class="icon-arrow-right icon-on-right bigger-110"></i>
                                                        </button>
                                                    </div>



                                
                            </form>
                                <?php 
                                                if(!empty($updateUser))
                                                {
                                                ?>
                                                <div class="space-6"></div>
                                                <div class="alert alert-block alert-success">
                                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                                    <strong><i class="icon-ok"></i> Well done!</strong>
                                                    User Updated successfully.
                                                </div>
                                            <?php
                                            }?>
                                                <?php 
                                                if(!empty($createdUserId))
                                                {
                                                ?>
                                                <div class="space-6"></div>
                                                <div class="alert alert-block alert-success">
                                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                                    <strong><i class="icon-ok"></i> Well done!</strong>
                                                    User created successfully.
                                                </div>
                                                <?php }
                                                if(!empty($uploadError))
                                                {
                                                ?>
                                                <div class="space-6"></div>
                                                <div class="alert alert-error">
                                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                                    <?php echo $uploadError;?>
                                                </div>
                                            <?php
                                                }
                                                }
                                                else
                                                {
                                            ?>
                                            
                                            <div>You Have Crossed Limit You can not create user anymore!</div>
                                            <?php 
                                                if(!empty($updateUser))
                                                {
                                                ?>
                                                <div class="space-6"></div>
                                                <div class="alert alert-block alert-success">
                                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                                    <strong><i class="icon-ok"></i> Well done!</strong>
                                                    User Updated successfully.
                                                </div>
                                            <?php
                                            }
                                                }
                                            ?>
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

       

       <div class="modal inmodal fade" id="betaModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteUser" value="">
                    <input type="hidden"  id="deleteEmail" value="">
                            <div class="btn btn-mini btn-info" type="button" onclick="deleteUser()">Delete</div>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 


  <div class="modal inmodal fade" id="uploadCSVModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Choose CSV file to Upload</h4>
                </div>
                <form class="form span3" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                        <form style="float:right; width:70%;" method="post" action="" enctype="multipart/form-data">
                <input type="file" name="upload_csv" id="upload_csv" style="width:65%;" />
                <button type="submit" class="btn btn-primary pull-right" name="submit" style="margin-right:30px;padding:0;">Upload CSV</button>
            </form>                     
 </div>
               
                </form>
            </div>
        </div>
    </div>

  
    



       <script>
            jQuery(function($) {
                
            $('#CreateUserForm').validate({
                errorElement: 'span',
                errorClass: 'help-inline',
                focusInvalid: false,
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email:true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    code: {  
                        //required: true       
                    },
                    phone: {
                        required: true
                        //number: true
                    }
                },
        
                messages: {
                    name: {
                        required: "Please specify a Name.",
                        minlength: "Please specify Name of minimum 3 character."
                    },
                    email: {
                        required: "Please provide a valid email.",
                        email: "Please provide a valid email."
                    },
                    password: {
                        required: "Please provide password.",
                        minlength: "Please specify a secure Password."
                    },
                    code: {  
                        required: "Please Select a Country code."  
        
                    },
                    phone: {
                        required: "Please Provide Valid Phone Number",
                        number: "Please Enter Digits Only"
                    }
                },
        
                invalidHandler: function (event, validator) { //display error alert on form submit   
                    $('.alert-error', $('#CreateUserForm')).show();
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
                    else if(element.is('.phone')) {  
                        element.closest('.controls').append(error);
                    }
                    else error.insertAfter(element);
                },
        
                submitHandler: function (form) {
                form.submit();
                },
                invalidHandler: function (form) {
                }
            });
            
            
                $('#id-input-file-1 , #id-input-file-2').ace_file_input({
                    no_file:'No File ...',
                    btn_choose:'Choose',
                    btn_change:'Change',
                    droppable:false,
                    onchange:null,
                    thumbnail:false //| true | large
                    //whitelist:'gif|png|jpg|jpeg'
                    //blacklist:'exe|php'
                    //onchange:''
                    //
                });
                
                $('#id-input-file-3').ace_file_input({
                    style:'well',
                    btn_choose:'Drop files here or click to choose',
                    btn_change:null,
                    no_icon:'icon-cloud-upload',
                    droppable:true,
                    thumbnail:'small'
                    //,icon_remove:null//set null, to hide remove/reset button
                    /**,before_change:function(files, dropped) {
                        //Check an example below
                        //or examples/file-upload.html
                        return true;
                    }*/
                    /**,before_remove : function() {
                        return true;
                    }*/
                    ,
                    preview_error : function(filename, error_code) {
                        //name of the file that failed
                        //error_code values
                        //1 = 'FILE_LOAD_FAILED',
                        //2 = 'IMAGE_LOAD_FAILED',
                        //3 = 'THUMBNAIL_FAILED'
                        //alert(error_code);
                    }
            
                }).on('change', function(){
                    //console.log($(this).data('ace_input_files'));
                    //console.log($(this).data('ace_input_method'));
                });
                
            
                //dynamically change allowed formats by changing before_change callback function
                $('#id-file-format').removeAttr('checked').on('change', function() {
                    var before_change
                    var btn_choose
                    var no_icon
                    if(this.checked) {
                        btn_choose = "Drop images here or click to choose";
                        no_icon = "icon-picture";
                        before_change = function(files, dropped) {
                            var allowed_files = [];
                            for(var i = 0 ; i < files.length; i++) {
                                var file = files[i];
                                if(typeof file === "string") {
                                    //IE8 and browsers that don't support File Object
                                    if(! (/\.(jpe?g|png|gif|bmp)$/i).test(file) ) return false;
                                }
                                else {
                                    var type = $.trim(file.type);
                                    if( ( type.length > 0 && ! (/^image\/(jpe?g|png|gif|bmp)$/i).test(type) )
                                            || ( type.length == 0 && ! (/\.(jpe?g|png|gif|bmp)$/i).test(file.name) )//for android's default browser which gives an empty string for file.type
                                        ) continue;//not an image so don't keep this file
                                }
                                
                                allowed_files.push(file);
                            }
                            if(allowed_files.length == 0) return false;
            
                            return allowed_files;
                        }
                    }
                    else {
                        btn_choose = "Drop files here or click to choose";
                        no_icon = "icon-cloud-upload";
                        before_change = function(files, dropped) {
                            return files;
                        }
                    }
                    var file_input = $('#id-input-file-3');
                    file_input.ace_file_input('update_settings', {'before_change':before_change, 'btn_choose': btn_choose, 'no_icon':no_icon})
                    file_input.ace_file_input('reset_input');
                });
                
            });
            $('#modal_table tbody tr').click(function(){
            var the=$(this);
            var img_src=$(this).find('img').attr('src');
            var res_name=the.find("td:eq(1)").text();
            $('#r_img').attr('src',img_src);
            $('#r_txt').text(res_name);
            $('#res_edit').css('display','none');
            });
            
            $("#createUser").click(function(){
                $("#CreateUserDetail").css("display", "block");// added by sharma
                $("#updateUserDetail").css("display", "none");// added by sharma
            });
            function showUploadCSV()
            {
                //alert("sdfdsd");
                $("#uploadCSVModal").modal('show');
            }
            function update(userId,userState)
            { 

                $("#updateUserId").val(userId);
                $("#CreateUserDetail").css("display", "none");// added by sharma
                $("#updateUserDetail").css("display", "block");// added by sharma
                $("#submitUpdateUser").show('1');
                $("#name").val($("#name-"+userId).val());
                $("#email").val($("#email-"+userId).val());
                $("#phone").val($("#phone-"+userId).val());
                var userLevel = $("#level-"+userId).val();
                $("#roleText").val(userLevel);
                if (userState=='2')
                {
                    $('#ban_user_upd').prop('checked', true);
                }
                else
                {
                    $('#ban_user_upd').prop('checked', false);
                }
                

            }
        
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            /* Init DataTables for Agent Stats */
            var oTable = $('#holiday').dataTable();
            });


           function showDeleteUser(getID,DelEmail)
            {
                $('#deleteUser').val(getID);
                $('#deleteEmail').val(DelEmail);
                $('#betaModal').modal('show');      
            }

          




                function deleteUser()
            {

            var getID=$("#deleteUser").val();
            var Email=$("#deleteEmail").val();
           // alert(getID);
             var dataString = 'id=' + getID+'&email='+Email;
                $.post('<?php echo base_url('user/deleteUser');?>',dataString,function(data){
                  
                  alert(data);
                    if(data=='1')
                    {
                    $("#action-"+getID).html('');
                    }
                    $('#betaModal').modal('hide');
                   // $("#SuccessModal").modal('show');
                   // setTimeout(function(){$("#SuccessModal").modal('hide');},1200);
                });
            }

        

    

        </script>
</body>

</html>
