            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Blacklist</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Blacklist</a>
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
                        <h5>Blacklist <small>Listing of Blacklists</small></h5>
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
                        <table class="table table-striped" id="blacklists">
                            <thead>
                            <tr>
                                <th>Called Number</th>
                                <th>Caller Number</th>
                                <th>Date Time</th>
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
                                                                <?php echo $blacklist->caller_number; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $blacklist->created_on; ?></td>

                                                                
                                                     
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteBlacklist(<?php echo $blacklist->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updateBlacklist('<?php echo $blacklist->called_number; ?>','<?php echo $blacklist->caller_number; ?>','<?php echo $blacklist->id; ?>')"><i class="fa fa-edit"></i></button>
                                </td>


                                                            







                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                          <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> blacklisting found!
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('blacklist'); ?>">
                                <p>Create BlackList.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Called Number</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Called Number" id="called" name="called" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Caller Number</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Caller Number" id="caller" name="caller" required class="form-control"></div>
                                </div>
                              
                               
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="blacklistbutton" type="submit">BlackList</button>
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

       <div class="modal inmodal fade" id="openBlacklistModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteBlacklist" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteBlacklist()">Delete</button>
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
            var oTable = $('#blacklists').DataTables();
            });


            function showDeleteBlacklist(ID){
            
            $('#deleteBlacklist').val(ID);
            $('#openBlacklistModel').modal('show');
         }

          




                        function DeleteBlacklist()
            {
            var getID=$("#deleteBlacklist").val();
            //alert('two'+getID);
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>blacklist/deleteBlacklist',data,function(msg){
                   //    alert(msg);
                    
                    $('#openBlacklistModel').modal('hide');
                  
                  
                window.location.href= '<?php echo base_url()?>blacklist';
                   return false;
              
                    // window.location.href = '<?php echo base_url()?>booking/ShowBooking/delete';
                    // return false;

                });  

            }

        

            

     function updateBlacklist(called,caller,id){
        
         $('#called').val(called);
        $('#caller').val(caller);
       
        $('#updateid').val(id);

        $('#blacklistbutton').html('Update Blacklist');


// alert(id);
     //   $('#showAgentMappingUpdateModel').modal('show');
        
    }


  

        </script>
</body>

</html>
