            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Uploaded Audio</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Uploaded Audio</a>
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
                                
                                <th>Recoding</th>
                                <th>Download </th>
                                <th>Description</th>
                                <th>Action </th>
                            </tr>
                            </thead>
                            <tbody>

                             <?php foreach ($audio as $audio) {
                             	$callRecordingurl = base_url().'uploads/audio/'.$audio->audio_link;
                        
                       ?>

                                                                                                            <tr>
                                                            <td>
                                                                <?php //echo $audio->audio_link; ?>
                                                                <audio controls preload="none" style="width:45px">
                                                        <source src="<?php echo $callRecordingurl;?>"
                                                                 type='audio/mp4'>
                                                         <p>Your user agent does not support the HTML5 Audio element.</p>
                                                    </audio>
                                                  
                                                               
                                                                                                                         </td>
                                                            <td>
                                                                 <?php
                                                    if($callRecordingurl!='None'){?>
                                                   <a href="<?=$callRecordingurl?>" download="<?=$audio->audio_link?>" ><i class="fa fa-cloud-download fa-2"></i></a>
                                                 
                                                      <?php } ?>                                                           </td>
                                                            <td>
                                                                <?php echo $audio->description; ?></td>

<td>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="showDeleteAudio(<?php echo $audio->id; ?>)"><i class="fa fa-trash-o"></i></button>
                         <!--           <button class="btn btn-primary btn-xs" title="Edit" onclick="updateShift('<?php echo $shift->shift_id; ?>','<?php echo $shift->shift_name; ?>','<?php echo $shift->start_time; ?>','<?php echo $shift->end_time; ?>','<?php echo $shift->id; ?>')"><i class="fa fa-edit"></i></button>
                         -->       </td>


                                                            







                                                        </tr>
                                                        <?php }?>
                            
                           
                            </tbody>
                        </table>
                         <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> audio found!
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
                            <h5>Upload Audio</h5>
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
                            <form class="form-horizontal" method="post" action="<?php echo base_url('audio'); ?>" enctype="multipart/form-data"> 
                               
                                <div class="form-group"><label class="col-lg-2 control-label">Upload Audio</label>
                                	

                                    <div class="col-lg-10"><input type="file" placeholder="audiolink" id="audiolink" name="audiolink" required  class="form-control">

                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-10"><input type="text" placeholder="Description" id="description" name="description" required class="form-control"></div>
                                </div>
                                
                                <input type="hidden" id="updateid" name="updateid" value="">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" id="shiftbutton" type="submit">Upload Audio</button>
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

       <div class="modal inmodal fade" id="openAudioModel" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden"  id="deleteAudio" value="">
                            <button class="btn btn-mini btn-info" type="button" onclick="DeleteAudio()">Delete</button>
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



            function showDeleteAudio(getID){
            $('#deleteAudio').val(getID);
           // alert(getID);
            $("#openAudioModel").modal('show');

         }

         function DeleteAudio()
            {
            var getID=$("#deleteAudio").val();
         
            var data = 'id=' + getID;
                $.post('<?php echo base_url()?>audio/deleteAudio',data,function(msg){
                $('#openAudioModel').modal('hide');        
                window.location.href= '<?php echo base_url()?>audio';
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
