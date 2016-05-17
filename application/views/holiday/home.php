            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Holiday</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Holiday</a>
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
                        <h5>Holiday <small>Listing of Holidays</small></h5>
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
                        <table class="table table-striped" id="holiday1">
                            <thead>
                            <tr>
                                <th>Called Number</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Action </th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($agentMap as $blacklist) {
                        
                       ?>

                                                                                                            <tr>
                                                            <td>
                                                                <?php echo $blacklist->CalledNumber; ?>                                                         </td>
                                                            <td>
                                                                <?php echo $blacklist->HolidayDate; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $blacklist->Holiday_Description; ?></td>

                                                                
                                                     
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteHoliday(<?php echo $blacklist->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updateHoliday('<?php echo $blacklist->CalledNumber; ?>','<?php echo $blacklist->HolidayDate; ?>','<?php echo $blacklist->Holiday_Description; ?>','<?php echo $blacklist->id; ?>')"><i class="fa fa-edit"></i></button>
                                </td>


                                                            







                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                        <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> Holidays found!
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
                            <h5>Create Holiday</h5>
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('holiday'); ?>">
                                <p>Create Holiday.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Called No.</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Called Number" id="Hcalled_number" name="Hcalled_number" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Date</label>
                                    <div class="col-lg-10"><input type="date" placeholder="Date" id="Hdate" name="Hdate" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-10"><input type="text" id="holiday" name="holiday" placeholder="Description" required class="form-control"></div>
                                </div>
                               
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="holidaybutton" type="submit">Create Holiday</button>
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
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Leadmentor &copy; 2016
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
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            /* Init DataTables for Agent Stats */
            var oTable = $('#holiday').dataTable();
            });


            function showDeleteHoliday(ID){
            
            $('#deleteHoliday').val(ID);
            $('#openHolidayModel').modal('show');
         }

          




                        function DeleteHoliday()
            {
            var getID=$("#deleteHoliday").val();
            //alert('two'+getID);
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>holiday/deleteHoliday',data,function(msg){
                   //    alert(msg);
                    
                    $('#openHolidayModel').modal('hide');
                  
                  
                window.location.href= '<?php echo base_url()?>holiday';
                   return false;
              
                    // window.location.href = '<?php echo base_url()?>booking/ShowBooking/delete';
                    // return false;

                });  

            }

        

            

     function updateHoliday(calledNumber,date,holiday,id){
        
         $('#Hcalled_number').val(calledNumber);
        $('#Hdate').val(date);
        $('#holiday').val(holiday);
        $('#updateid').val(id);

        $('#holidaybutton').html('Update Holiday');


// alert(id);
     //   $('#showAgentMappingUpdateModel').modal('show');
        
    }


  

        </script>
</body>

</html>
