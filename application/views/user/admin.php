            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Manage Call Log List View</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Admin</a>
                        </li>
                        <li class="active">
                            <strong>Manage Call Log List View</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
           
                <div class="col-lg-9">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Create Mapping</h5>
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
                          <form id="admin" class="form-horizontal" method="post" action="" onsubmit="return validate();">
                                                <?php foreach($fieldList as $fieldName): ?>
                                                <div class="form-group">
                                                    <label class="col-lg-2 control-label" for="<?php echo $fieldName; ?>"><?php echo $fieldName; ?></label>
                                                     <div class="col-lg-10">
                                                        <input type="text"  id="<?php echo $fieldName; ?>" placeholder="Name it" name="<?php echo $fieldName; ?>_title" 
                                                        <?php echo isset($_POST[$fieldName]) ?  "value='" . $_POST[$fieldName . "_title"] ."'" : "" ?> 
                                                        <?php //echo isset($_POST[$fieldName]) ?  "value='" . $_POST[$fieldName . "_title"] ."'" : "value='".$fieldName."'"; ?> 
                                                        <?php if(in_array($fieldName, $this->admin->getDefaults())){ echo "value='$fieldName'"; } ?> />
                                                        <?php if(in_array($fieldName, $this->admin->getDefaults())){ ?>
                                                            <input type="checkbox" class="ace notEnum" name="<?php echo $fieldName; ?>" checked disabled  /><span class="lbl"></span>
                                                        <?php }else if(in_array($fieldName, $this->admin->getEnum())){ ?>
                                                            <input type="checkbox" class="ace enum" name="<?php echo $fieldName; ?>"
                                                            <?php echo isset($_POST[$fieldName]) ?  "checked" : "" ?> /><span class="lbl"></span> 
                                                        <?php }else{ ?> 
                                                            <input type="checkbox" class="ace notEnum" name="<?php echo $fieldName; ?>" 
                                                            <?php echo isset($_POST[$fieldName]) ?  "checked" : "" ?> /><span class="lbl"></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                                
                                                <div class="space-4"></div>
                                                <div class="controls">
                                                    <button  type="submit" class="btn btn-small btn-success" name="submit" >
                                                    Submit
                                                    <i class="icon-arrow-right icon-on-right bigger-110"></i>
                                                    </button>
                                                    <!--<input type="submit" value="submit" name="submit">-->
                                                    
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
        </script>

<script>
    $(document).ready(function(){
            
        $('input[type=text]').focusout(function(){
            $(this).removeClass('error');
        });
    });
    
    var checkboxes = $('#admin').find('.notEnum');
    var checkboxes1 = $('#admin').find('.enum');
    function countCheck(checkboxes) {
        var i = 0;
        checkboxes.each(function() {
            if($(this).is(":checked")) {
                i++;
            }
        })
        return i;
    }

   /* checkboxes.change(function(e) {
        var $this = $(this);
        if($this.is(':checked')) {
            if(countCheck(checkboxes) > 8) {
                $this.prop('checked', false);
                alert("Your are not allowed to check more than 8 fields");
            }
        }
    });  */
    function validate(){
        var count = 0;var msg = '';
            $('.notEnum').each(function(){
                    if($(this).is(':checked')==true){
                        count++;
                        if($(this).siblings('input').val()==''){
                            $(this).siblings('input').addClass('error');
                            
                            msg = 'Please provide a name for the field you have selected';
                            //return false;
                        }else{
                                $(this).siblings('input').removeClass('error');
                        }
                    }
                    
            });
            
            if(msg!=''){
                    alert(msg);
                    return false;
            }
            var msg1 = '';
            $('.enum').each(function(){
                    if($(this).is(':checked')==true){
                        count++;
                            var name = $(this).siblings('input').val();
                            if(name==''){
                                msg1 = 'Please provide a name for the field you have selected';
                                return false;
                            }
                    }
                    
            });
            if(count<7){
                    alert('You have to select at least 7 fields');
                    return false;
            }
            if(msg1!=''){
                alert(msg1);
                    return false;
            }
            return true;
    }
</script>



</body>

</html>
