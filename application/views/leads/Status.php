            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Manage Status</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>System Setup</a>
                        </li>
                        <li class="active">
                            <strong>Status</strong>
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
                        <h5>Status <small>Listing of Status</small></h5>
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
                        <table class="table table-striped" id="status">
                            <thead>
                            <tr>
                                <th>Status Name</th>
                                <th>Parent</th>
                                <th>User Level</th>
                                <th>State</th>
                                <th>Actions</th>
                                 
                            </tr>
                            </thead>
                            <tbody>

                             <?php 
                                                
                                                    if(isset($manage_status))
                                                    {
                                                        foreach($manage_status as $manage_statuses)
                                                        {
                                                    ?>
                                                    <tr id="tr_<?php echo $manage_statuses['childId'];?>">
                                                        <td><?php echo $manage_statuses['detail'];?></td>
                                                        <td><?php echo $manage_statuses['parent'];?></td>
                                                        <td><?php echo $manage_statuses['userLevel'];?></td>
                                                        <td id="status-<?php echo $manage_statuses['childId'];?>">
                                                        <?php if($manage_statuses['removed']==0){
                                                        echo '<span class="label label-success">Active</span>';
                                                        }
                                                        else if($manage_statuses['removed']==1){
                                                        echo '<span class="label label-warning">Inactive</span>';
                                                        }
                                                        else if($manage_statuses['removed']==3){
                                                    echo'<span class="btn btn-mini btn-danger">Removed</span>';
                                                        }
                                                        else{
                                                        echo '<span class="label">Freezed</span>';
                                                        }
                                                        ?>
                                                        
                                                        <span class="label"></span></td>
                                                        <td class="center" id="action-<?php echo $manage_statuses['childId'];?>" >
                                                    <?php  
                                                    /* if($manage_statuses['id']=="4" || $manage_statuses['id'] == "9")
                                                    {
                                                        echo "Access Denied";
                                                    } */
                                                     if($manage_statuses['removed']=="1")
                                                    {
                                                        ?>
                                                        <button class="btn btn-mini btn-success" onClick="openMarkActiveModel('<?php echo $manage_statuses["childId"]?>');" ><i class="icon-unlock bigger-120"></i></button>
                                                        <?php
                                                    } 
                                                    else if($manage_statuses['removed']=="2")
                                                    {
                                                        echo '<i class="icon-lock bigger-120"></i>';
                                                    }
                                                    else if($manage_statuses['removed']=="3")
                                                    {
                                                    echo'<span class="btn btn-mini btn-danger">Removed</span>';
                                                    } 
                                                    else
                                                        {
                                                    ?>
                                                    <button class="btn btn-danger btn-xs" onClick="openMarkInactiveModel('<?php echo $manage_statuses["childId"]?>');">
                                                        <i class="fa fa-trash-o"></i>
                                                            </button>
                                                    <?php
                                                    }
                                                    ?>
                                                    </td>
                                                        </tr>
                                                        <?php
                                                }
                                                        }
                                                        ?>  
                                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <div class="col-lg-5">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Manage Status</h5>
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
                            <form class="form-horizontal" name="addUserStatus" id="addUserStatus"method="post" action="<?php echo base_url();?>lead/Status">
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Select user level</label>

                                    <div class="col-lg-10">
                                       
                                        <input name="userLevel" type="radio" class="ace" id="Telecaller" value="<?php echo $this->config->item('TelecallerLevel');?>">
                                                        <span class="lbl"> Telecaller</span>

                                                        <input name="userLevel" type="radio" class="ace" id="Counselor" value="<?php echo $this->config->item('CounslorLevel');?>">
                                                        <span class="lbl"> Counselor</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Name</label>
                                    <div class="col-lg-10">
                                        <input  name="StatusName" type="text" id="form-field-name" placeholder="Name" class="form-control"/>
                                                    <span class="help-inline"><?php echo "<font color=red>".form_error('StatusName')."</font>" ;?></span>
                                                    <span id="status_span" style="color:red;display:none;"></span>
                                    </div>
                                </div>


                                 <div class="form-group"><label class="col-lg-2 control-label"></label>
                                    <div class="col-lg-10">
                                       <input name="nest_it_under" type="checkbox" class="ace" id="nest_it_under"  value="1">
                                                        <span class="lbl"> &nbsp;Nest it under</span>
                                    </div>
                                </div>

                                 <div class="form-group"><label class="col-lg-2 control-label">Parent Status</label>
                                    <div class="col-lg-10">
                                        <select id="form-field-select-1" name="parentId" class="form-control">
                                                        <option value="0">Select</option>
                                                        
                                                    </select>
                                                    <span class="help-inline"><?php if(isset($leadsStatusError)){echo "<font color=red>".$leadsStatusError."</font>" ;}?></span>
                                    </div>
                                </div>
                               
                               
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white"  name="userStatusDetails" type="submit">Submit</button>
                                    </div
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



  <div class="modal inmodal fade" id="openMarkInactiveModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="openMarkInactive" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="MarkInactive()">Delete</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 


     <div class="modal inmodal fade" id="openMarkActiveModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="openMarkActive" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="MarkActive()">Delete</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div> 



      

     

        <script>
            <script type="text/javascript">
            jQuery(function($) {
                var oTable1 = $('#status').dataTable( {
                "aoColumns": [
                  null, null,null, null,null
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
        
        <script>
        
$("#form-field-name").focusout(function()
            {
                var value = $("#form-field-name").val();
                if(value.length > 20)
                {
                    $("#status_span").text("  Status should not be longer than 20 chars");
                    $("#status_span").show();
                }
                else
                {
                    $("#status_span").hide();
                }
                
            }); 
    
$(document).ready(function()
{   
    $('#form-field-select-1').attr("disabled", "disabled");
    $('#form-field-name').attr("readonly", "readonly");
    $('input:radio[name="userLevel"]').change(function()
    {
    
        $('#form-field-name').removeAttr("readonly");
        var userLevel = $('input:radio[name=userLevel]:checked').val();
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>lead/Status/',
            async:false,
            data: {userLevelByAjax:userLevel},
            cache: false,
            success: function(msg)
            {
                $('#form-field-select-1').html(msg);    
            }           
        }); 
        if(userLevel==3)
        {
            $('#form-field-select-1').removeAttr("disabled");
            $('#nest_it_under').prop('checked', false);
            $('#nest_it_under').attr("disabled", "disabled");
        }
        if(userLevel!=3)
        {
            $('#form-field-select-1').attr("disabled", "disabled");
            $('#nest_it_under').removeAttr("disabled");
            
        }       
    }); 
    
     $('#nest_it_under').click(function()
     {  
    var userLevel = $('input:radio[name=userLevel]:checked').val(); 
        if(($(this).is(':checked')) && userLevel)
        {
            $('#form-field-select-1').removeAttr("disabled");
        }
        else
        {
            $('#form-field-select-1').attr("disabled", "disabled");
        }
    }); 
    
});

       function openMarkInactiveModel(statusId)
{
    $("#openMarkInactive").val(statusId);
    $("#openMarkInactiveModel").modal('show');  
}  


function MarkInactive()
{       
var statusId=$("#openMarkInactive").val();
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>lead/MarkStatusInactiveByAjax/',
            async:false,
            data: {id:statusId},
            cache: false,
            success: function(msg)
            { 
            if(msg==1)
            {
                var changeAction=$("#action-"+statusId).html('<button class="btn btn-mini btn-success" onClick="openMarkActiveModel('+statusId+');"><i class="icon-unlock bigger-120"></i></button>');
                // change html of status
                var changeStatus=$("#status-"+statusId).html('<span class="label label-warning">Inactive</span>');
                    //change html of action
            }
            if(msg==2)
            {
            var changeAction=$("#action-"+statusId).html('<span class="btn btn-mini btn-danger">Removed</span>');
                // change html of status
            var changeStatus=$("#status-"+statusId).html('<span class="btn btn-mini btn-danger">removed</span>');
                    //change html of action
            }
                $("#openMarkInactiveModel").modal('hide');
                $('#SuccessMarkInactiveModal').modal('show');
                setTimeout(function(){$("#SuccessMarkInactiveModal").modal('hide');},1200);
                
            }           
        }); 
}

function openMarkActiveModel(statusId)
{
    $("#openMarkActive").val(statusId);
    $("#openMarkActiveModel").modal('show');
}
function MarkActive()
{       
    var statusId=$("#openMarkActive").val();
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>lead/MarkStatusActiveByAjax/',
            async:false,
            data: {id:statusId},
            cache: false,
            success: function(msg)
            {
                //change html of action
                var changeAction=$("#action-"+statusId).html('<button class="btn btn-mini btn-danger" onClick="openMarkInactiveModel('+statusId+');"><i class="icon-trash bigger-120"></i></button>');
                // change html of status
                var changeStatus=$("#status-"+statusId).html('<span class="label label-success">Active</span>');
                $("#openMarkActiveModel").modal('hide');
                $('#SuccessMarkActiveModal').modal('show'); 
                setTimeout(function(){$("#SuccessMarkActiveModal").modal('hide');},1200);
            }           
        }); 
}   

  </script>
