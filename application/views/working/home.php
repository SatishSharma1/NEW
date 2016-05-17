            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Working Hours</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Working Hours</a>
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
                        <h5>Working Hours <small>Listing of Working Hours</small></h5>
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
                                <th>Called Number</th>
                                <th>Day Id</th>
                                <th>Day Name</th>
                                 <th>Start Time</th>
                                  <th>End Time</th>
                                <th>Action </th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($agentMap as $blacklist) {
                        
                       ?>

                                                                                                            <tr>
                                                            <td>
                                                                <?php echo $blacklist->called_number; ?>                                                         </td>
                                                            <td>
                                                                <?php echo $blacklist->day_id; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $blacklist->day_name; ?></td>

                                                                   <td>
                                                                <?php echo $blacklist->start_time; ?></td>

                                                                   <td>
                                                                <?php echo $blacklist->end_time; ?></td>

                                                                
                                                     
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteWorking(<?php echo $blacklist->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updateWorking('<?php echo $blacklist->called_number; ?>','<?php echo $blacklist->day_id; ?>','<?php echo $blacklist->day_name; ?>','<?php echo $blacklist->start_time; ?>','<?php echo $blacklist->end_time; ?>','<?php echo $blacklist->id; ?>')"><i class="fa fa-edit"></i></button>
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
                            <h5>Create Working Hours</h5>
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('working'); ?>">
                                <p>Create Working Hours.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Called Number</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Called Number" id="Wcalled_number" name="Wcalled_number" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Day ID</label>
                                    <div class="col-lg-10"><input type="number" placeholder="Day Id" id="dayid" name="dayid" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Day Name</label>
                                    <div class="col-lg-10">
                                    <select id="day_name" name="day_name" required class="form-control">
                                         <option value="">Select</option> 
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                        </div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">Start Time</label>
                                    <div class="col-lg-10"><input type="time" id="start_time" name="start_time" placeholder="Start Time" required class="form-control"></div>
                                </div>

                                <div class="form-group"><label class="col-lg-2 control-label">End Time</label>
                                    <div class="col-lg-10"><input type="time" id="end_time" name="end_time" placeholder="End Time" required class="form-control"></div>
                                </div>
                               
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="workingbutton" type="submit">Create Working Hours</button>
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

       <div class="modal inmodal fade" id="openWorkingModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteWorking" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteWorking()">Delete</button>
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
            var oTable = $('#working').dataTable();
            });


           function showDeleteWorking(ID){
            $('#deleteWorking').val(ID);
            $('#openWorkingModel').modal('show');
            
         }

          




                        function DeleteWorking()
            {
           var getID=$("#deleteWorking").val();
        //  alert('two'+getID);
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>working/deleteWorking',data,function(msg){
                   //    alert(msg);
                    
                    $('#openWorkingModel').modal('hide');
                  
                  
                window.location.href= '<?php echo base_url()?>working';
                   return false;
              
                    // window.location.href = '<?php echo base_url()?>booking/ShowBooking/delete';
                    // return false;

                });  

            }

        
            

    
    function updateWorking(CalledNumber,DayID,DayName,StartTime,EndTime,Id){
        
        $('#Wcalled_number').val(CalledNumber);
        $('#dayid').val(DayID);
        $('#day_name').val(DayName);
        $('#start_time').val(StartTime);
        $('#end_time').val(EndTime);
         $('#updateid').val(Id);
          $('#workingbutton').html('Update Working Hours');
        }


  

        </script>
</body>

</html>
