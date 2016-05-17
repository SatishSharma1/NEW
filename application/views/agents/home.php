            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Agent Mapping</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Agent Mapping</a>
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
                        <h5>Agent Mapping <small>Listing of Agent Mapping</small></h5>
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
                                <th>Called No. </th>
                                <th>Agent List </th>
                                <th>Extension </th>
                                <th>Menu </th>
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
                                                                <?php echo $blacklist->Agentlist; ?>                                                                </td>
                                                            <td>
                                                                <?php echo $blacklist->Extension; ?></td>

                                                                
                                                                <td>
                                                                <?php echo $blacklist->Menu; ?></td>
                                                                                                                        
                                                        

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteAgentMap(<?php echo $blacklist->id; ?>)"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-primary btn-xs" title="Edit" onclick="updateAgentMapping('<?php echo $blacklist->CalledNumber; ?>','<?php echo $blacklist->Agentlist; ?>','<?php echo $blacklist->Extension; ?>','<?php echo $blacklist->Menu; ?>','<?php echo $blacklist->id; ?>')"><i class="fa fa-edit"></i></button>
                                </td>


                                                            







                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                         <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> agents found!
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('agent'); ?>">
                                <p>Create Agent Mapping.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Called No.</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Called Number" id="called_number" name="CalledNumber" required  class="form-control"> <span class="help-block m-b-none">Example block-level help text here.</span>

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Agent List</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Agent List" id="agent_list" name="Agentlist" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Extension</label>
                                    <div class="col-lg-10"><input type="text" id="Extension" name="Extension" placeholder="Extension" required class="form-control"></div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Menu</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Menu" id="region_name" name="Menu" required class="form-control"></div>
                                </div>
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="agentbutton" type="submit">Create Mapping</button>
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

       <div class="modal inmodal fade" id="openAgentModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteAgent" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteAgentMap()">Delete</button>
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



            function showDeleteAgentMap(getID){
            $('#deleteAgent').val(getID);
            alert(getID);
            $("#openAgentModel").modal('show');

         }

         function DeleteAgentMap()
            {
            var getID=$("#deleteAgent").val();
         
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>agent/deleteAgentMap',data,function(msg){
                $('#openAgentModel').modal('hide');        
                window.location.href= '<?php echo base_url()?>agent';
                return false;
               });
                }

            

      function updateAgentMapping(calledNumber,agent_list,regioncode,region_name,id){
        
        $('#called_number').val(calledNumber);
        $('#agent_list').val(agent_list);
        $('#Extension').val(regioncode);
        $('#region_name').val(region_name);
        $('#updateid').val(id);

        $('#agentbutton').html('Update Mapping');


// alert(id);
     //   $('#showAgentMappingUpdateModel').modal('show');
        
    }
  

        </script>
</body>

</html>
