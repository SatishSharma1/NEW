            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Popup</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Popup</a>
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
                        <h5>Popup <small>Listing of Popup</small></h5>
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
                        <table class="table table-striped" id="popup">
                            <thead>
                            <tr>
                              <th>Knowlarity Number</th>
                                                            <th>API Key</th>
                                                            <th>Knowlarity API</th>
                                                        
                                                            
                                                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($agentMap as $blacklist) {
                        
                       ?>

                                                                                                            <tr>
                                                            <td>
                                                                <?php echo $blacklist->knowlarity_number; ?>                                                         </td>
                                                            <td>
                                                                <?php echo $blacklist->api_key; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $blacklist->knowlarity_api; ?></td>

                                                                
                                                     
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeletePopup(<?php echo $blacklist->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updatePopup('<?php echo $blacklist->knowlarity_number; ?>','<?php echo $blacklist->api_key; ?>','<?php echo $blacklist->knowlarity_api; ?>','<?php echo $blacklist->id; ?>')"><i class="fa fa-edit"></i></button>
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
                            <h5>Configure Popup</h5>
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
                            <form class="form-horizontal" id="popup-table" method="post" action="<?php echo base_url('popup'); ?>">
                                <p>Configure Popup.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Knowlarity Number</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Knowlarity Number" id="PKnowlarity_number" name="PKnowlarity_number" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">API Key</label>
                                    <div class="col-lg-10"><input type="text" placeholder="API Key" id="PAPI_key" name="PAPI_key" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Knowlarity API</label>
                                    <div class="col-lg-10"><input type="text" id="Pknowlarity_api" name="Pknowlarity_api" placeholder="Knowlarity API" required class="form-control"></div>
                                </div>
                               
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="popupbutton" type="submit">Create Popup</button>
                                    </div>
                                </div>
                                <?=$message?>
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

       <div class="modal inmodal fade" id="openPopup" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deletePopup" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeletePopup()">Delete</button>
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
            var oTable = $('#popup').dataTable();
            });


            function showDeletePopup(getID){
        //  alert('one'+getID);
            $('#deletePopup').val(getID);
            $("#openPopup").modal('show');
        }

          




                        function DeletePopup()
            {
            var getID=$("#deletePopup").val();
            //alert('two'+getID);
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>popup/deletePopup',data,function(msg){
                   //    alert(msg);
                    
                    $('#openPopup').modal('hide');
                  
                  
                window.location.href= '<?php echo base_url()?>popup';
                   return false;
              
                    // window.location.href = '<?php echo base_url()?>booking/ShowBooking/delete';
                    // return false;

                });  

            }

        

            

     function updatePopup(PKnowlarity_number,PAPI_key,Pknowlarity_api,id){
        
         $('#PKnowlarity_number').val(PKnowlarity_number);
        $('#PAPI_key').val(PAPI_key);
        $('#Pknowlarity_api').val(Pknowlarity_api);
        $('#updateid').val(id);

        $('#popupbutton').html('Update Popup');


// alert(id);
     //   $('#showAgentMappingUpdateModel').modal('show');
        
    }


  

        </script>
</body>

</html>
