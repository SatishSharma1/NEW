            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Shift Management</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Shift Management</a>
                        </li>
                        <li class="active">
                            <strong>Listing</strong>
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
                        <table class="table table-striped" id="agents">
                            <thead>
                            <tr>
                                <th>Id </th>
                                <th>Shift Name </th>
                                <th>Start Time </th>
                                <th>End Time </th>
                                <th>Action </th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($shift as $shift) {
                        
                       ?>

                                                                                                            <tr>
                                                            <td>
                                                                <?php echo $shift->shift_id; ?>                                                         </td>
                                                            <td>
                                                                <?php echo $shift->shift_name; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $shift->start_time; ?></td>

                                                                
                                                                <td>
                                                                <?php echo $shift->end_time; ?></td>
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteShift(<?php echo $shift->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updateShift('<?php echo $shift->shift_id; ?>','<?php echo $shift->shift_name; ?>','<?php echo $shift->start_time; ?>','<?php echo $shift->end_time; ?>','<?php echo $shift->id; ?>')"><i class="fa fa-edit"></i></button>
                                </td>


                                                            







                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                         <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> shifts found!
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('shift'); ?>">
                               
                                <div class="form-group"><label class="col-lg-2 control-label">Shift ID</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Shift ID" id="shift_id" name="shift_id" required  class="form-control">

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Shift Name</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Shift Name" id="shift_name" name="shift_name" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Start Time</label>
                                    <div class="col-lg-10"><input type="time" id="start_time" name="start_time" placeholder="start_time" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">End Time</label>
                                    <div class="col-lg-10"><input type="time" placeholder="End Time" id="end_time" name="end_time" required class="form-control"></div>
                                </div>
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="shiftbutton" type="submit">Create Shift</button>
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

       <div class="modal inmodal fade" id="openShiftModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteShift" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteShift()">Delete</button>
                            <button class="btn btn-mini" type="button" data-dismiss="modal">Cancel</button>
                </div>
                </form>
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
            var oTable = $('#agents').dataTable();
            });



            function showDeleteShift(getID){
            $('#deleteShift').val(getID);
           // alert(getID);
            $("#openShiftModel").modal('show');

         }

         function DeleteShift()
            {
            var getID=$("#deleteShift").val();
         
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>shift/deleteShift',data,function(msg){
                $('#openShiftModel').modal('hide');        
                window.location.href= '<?php echo base_url()?>shift';
                return false;
               });
                }

            

      function updateShift(shift_id,shift_name,start_time,end_time,id){
        
        $('#shift_id').val(shift_id);
        $('#shift_name').val(shift_name);
        $('#start_time').val(start_time);
        $('#end_time').val(end_time);
        $('#updateid').val(id);

        $('#shiftbutton').html('Update Shift');


// alert(id);
     //   $('#showAgentMappingUpdateModel').modal('show');
        
    }
  

        </script>
</body>

</html>
