            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Country</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>System Setup</a>
                        </li>
                        <li class="active">
                            <strong>Country</strong>
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
                        <h5>Country <small>Listing of Working Countrys</small></h5>
                        <div class="ibox-tools">
                        
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
                        <table class="table table-striped" id="Blacklist">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                 
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($countryList as $key => $country) {
                                $key++
                        
                       ?>

                                                 <tr>
                                                            <td>
                                                                <?php echo $key; ?>                                                         </td>
                                                            <td>
                                                                <?php echo $country->countryName; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $country->code; ?></td>

                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <div class="col-lg-5">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Create Country</h5>
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
                            <form class="form-horizontal" id="CreateUserForm" method="post" action="" enctype='multipart/form-data'>
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Country Name</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Countryname" id="countryname" name="countryname" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Country Code</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Country Code" id="countrycode" name="countrycode" required class="form-control"></div>
                                </div>
                               
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="submitCreateUser" type="submit">Submit</button>
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

     

        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            /* Init DataTables for Agent Stats */
            var oTable = $('#working').dataTable();
            });




    $('#CreateUserForm').validate({
                errorElement: 'span',
                errorClass: 'help-inline',
                focusInvalid: false,
                rules: {
                    countryname: {
                        required: true,
                        minlength: 3
                    },
                    countrycode: {
                        required: true
                        
                    }
                },
        
                messages: {
                    countryname: {
                        required: "Please specify a Name.",
                        minlength: "Please specify Name of minimum 3 character."
                    },
                    countrycode: {
                        required: "Please provide a valid code."
                        
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
                    else error.insertAfter(element);
                },
        
                submitHandler: function (form) {
                form.submit();
                },
                invalidHandler: function (form) {
                }
            });
          
        </script>
</body>

</html>
