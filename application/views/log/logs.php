            <?php $this->load->view('layout/head') ;
      // $agentNumber = $this->logmodel->getAgentNumberById($loggedUser['id']);
// dd($agentNumber);
$logged = $this->session->userdata('loggedIn');
$agentList = $this->logmodel->getAgentList($logged);
$agentnNumArr = array(); 


foreach($agentList as $agentsplit){
	
	$agentnNumArr[$agentsplit->userPhone] = $agentsplit->userName;
	
    // $agentnNum ="'".$agentsplit->userPhone."'".'=>'."'".$agentsplit->userName."'"; 
	

}

    $search = ($this->uri->segment(2))?$this->uri->segment(2):'key';

if($search!='key')
        {
            $customer=$agent=$datefrom=$dateto=$ivr='';
            $data['additionalParameter']=$search;
            $searchdata=explode(",",$search);
            //var_dump($searchdata);exit();  //----array(1) { [0]=> string(5) "ivr:I" }
            for($i=0;$i<sizeof($searchdata);$i++)
            {
                $searchfield=explode(":",$searchdata[$i]);
                if($searchfield[0]=='cu')
                {
                    $customer=$searchfield[1];
                }
                if($searchfield[0]=='ag')
                {
                   $agentNam=$searchfield[1];
                    //var_dump($agent);
                    //exit();
                }
                 if($searchfield[0]=='datefrom')
                {
                    $datefrom=$searchfield[1];
                }

                 if($searchfield[0]=='dateto')
                {
                    $dateto=$searchfield[1];
                }
                  if($searchfield[0]=='ivr')
                {
                     $ivr=$searchfield[1];
                //var_dump($ivr); ----string(1) "I"
                //exit();
                }
                
            } 
        }


//$agentnNumArr = array($agentnNum);


     //var_dump($agentnNumArr);
                                    		
    ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?=$active_page;?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Logs</a>
                        </li>
                        <li class="active">
                            <strong><?=$active_page;?></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
           
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?=$active_page;?></h5>
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
                       
                                <div class="row">
                                    <table><tr>

                                       
                                    <div class="col-sm-1 m-b-xs">
                                        <select size="1" name="per_page" id="per_page" aria-controls="sample-table-2" class="input-sm form-control input-s-sm inline">
                                            <option value="10" <?php echo ($per_page==10)?'selected="selected"':'';?>>10</option>
                                            <option value="25" <?php echo ($per_page==25)?'selected="selected"':'';?>>25</option>
                                            <option value="50" <?php echo ($per_page==50)?'selected="selected"':'';?>>50</option>
                                            <option value="100" <?php echo ($per_page==100)?'selected="selected"':'';?>>100</option>
                                        </select>
                                        </div>
                                    
                                      
                                   <div class="col-sm-1 m-b-xs">       <button>
                                    <a style="text-decoration: none;color: #000;" href="<?php echo base_url('log/downloadAllLogcsv')."/?pagename=".$pagename; ?>">download CSV</a>
                                  </button></div>
                         
            
        


                                   
                                  
                                   <form method="get" action="" id="search-form">
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="customersearch" class="sr-only">Customer Number</label>
                                        <input type="text"name="customersearch" id="customersearch" placeholder="Customer Number" value="<?php echo $customer; ?>" class="form-control input-sm"></div>
                                    </div>
                                     
                                    <div class="col-sm-2">
                                        <div class="form-group"><b>From:</b>
                                        <input type="date" placeholder="Date" name="date" id="datesearchfrom" value="<?=  $datefrom ;?>"class="form-control input-sm"></div>
                                    </div>
                                     <div class="col-sm-2">
                                        <div class="form-group"><b>To:</b>
                                        <input type="date" placeholder="Date" name="date" id="datesearchto"  value="<?=  $dateto ;?>" class="form-control input-sm"></div>
                                    </div>
                                      <div class="col-sm-1">
                                     <select id="ivrsearch" name="ivrsearch"  class="form-control">
                                     <option value="">Ivr Type</option>
                                     <option value="I">Inbound</option>
                                     <option value="O">Outbound</option>
                                           </select>
                                        </div>
                                    
                                    
                                   <div class="col-sm-1">
                                 
                                  
                                    		<?php 
                                             

                                    		if(gettype($agentList) == 'string'): ?>
			<input type="text" disabled="true" id="customerAgent" value="<?php echo $agentList; ?>">
			<?php else: ?>
			<select id="agentsearch" name="agentsearch"  class="form-control">
			<option value="">Agent Name  </option>
			<?php foreach($agentList as $agent):?>
			<option value="<?php echo $agent->userPhone ?>"><?php echo $agent->userName ?></option>
			<?php endforeach;?>
            </select>
			<?php endif; ?>
                                </div> 
                                  
                                 
                                    <!--//If user is an Admin then only show-->
                                   
                                    <!--//If user is an Admin then only showing above block-->
                                    <div class="col-sm-1 m-b-xs pull-right">
                                        <button class="btn btn-sm btn-primary pull-right" type="submit"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></button>
                                    </div> 
                                      </form>
                                  

                                 <tr> </table>
                                </div>
                                
                          <div class="table-responsive">
                          <table id="sample-table-2" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="center">
                                                <label><input type="checkbox" /></label>
                                            </th>
                                            <th class="center">
                                                <label><span class="lbl"><br>Log Id</span></label>
                                            </th>
                                            <?php $enum="";$enum = $this->admin_model->getEnumTitles();
                                             foreach ($listTitle as $title): 
                                             if(!(in_array($title, $enum))){
                                             ?>
                                                <th class="center"><?php echo $title; ?></th>
                                            <?php } 
                                            endforeach; ?>
                                            <th class="center">Action</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                    
                                    <?php $i = 1;$count = -1; foreach($allCalls as $logData):
                                        $count++;
                                    //var_dump($logData);
                                        $id = array_shift($logData);
                                         $logId = array_shift($logData);
                                         $leadId = array_shift($logData);
                                       // var_dump($logData);
                                         $agentNumber = array_shift($logData);
										 array_shift($logData);
										 array_shift($logData);
										 array_shift($logData);
										 array_shift($logData);
										 array_shift($logData);
										  array_shift($logData);
										 array_shift($logData);
                                           // var_dump($logData);
                                        $callRecordingurl = array_shift($logData);
                                          //$date = array_shift($logData);
                                        //$callRecordingurl = array_shift($logData);
                                       // $ivrType = array_shift($logData);
                                        $customerNumber = $logData['customerNumber'];
                                        $customerStatus = $logData['customerStatus'];
                                    ?>
                                        <tr id="<?php echo $id;?>">
                                            
                                            <td class='center'>
                                                <label>
                                                <input type='checkbox' id="leadCheckBox-<?php echo $id;?>"/><span class="lbl"></span></label>
                                                <input type="hidden" id="lead-<?php echo $id;?>" value=""/>
                                                <input type="hidden" id="leadEmail-<?php echo $id;?>" value="<?php echo $id;?>">
                                            </td>

                                            <td class='center'>
                                                <?php echo $id;  ?>
                                            </td>
                                            <?php foreach($logData as $key => $myLog): 
                                                
                                                if(in_array($key, array('field_enum_1','field_enum_2','field_enum_3','field_enum_4','field_enum_5'))){
                                                        //echo $key.in_array($key, array('field_enum_1','field_enum_2','field_enum_3','field_enum_4','field_enum_5'))."<br>";
                                                        continue;
                                                    }
                                                ?>
                                                <td id="<?php echo $key . "-" . $id; ?>">
                                                <?php 
                                                
                                                if($key == 'customerNumber'){ 
                                                    if($logData['ivrType'] == 'I'): ?>
                                                        <span data-rel="tooltip" title="InBound"><i class="icon-arrow-down margin_left green"></i></span>
                                                    <?php else: ?>
                                                        <span data-rel="tooltip" title="OutBound"><i class="icon-arrow-up margin_left yellow"></i></span>
                                                    <?php endif;
                                                    ?>          
                                                    <span id="field-<?php echo "$key-$id"; ?>"><?php echo $myLog ?></span><br>
                                                    <?php //foreach($enum_fields as $e){
                                                    $a = 0;
                                                        if($enum_fields){ 
                                                            foreach($enum_fields[$count] as $f=>$v){ 
                                                                if($v==1){ ?>
                                                                    <a  class="btn btn-minier btn-primary" href="#" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php echo $enum[$a]; ?>"><span><i class="fa fa-heart"></i></span> </a>
                                                                <?php }else{ ?>
                                                                    <a  class="btn btn-minier btn-default" href="#" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php echo $enum[$a]; ?>"><span><i class="fa fa-ban"></i></span> </a>
                                                                <?php }
                                                        $a++; } }
                                                        
                                                        ?>
                                                    <audio controls preload="none" style="width:150px">
                                                        <source src="<?php echo $callRecordingurl;?>"
                                                                 type='audio/mp4'>
                                                         <p>Your user agent does not support the HTML5 Audio element.</p>
                                                    </audio>
                                                    <?php
                                                    if($callRecordingurl!='None'){?>
                                                   <a href="<?=$callRecordingurl?>"><i class="fa fa-cloud-download"></i></a> 
                                                 
                                                      <?php  } 
													echo "<br>";
													echo 'Agent: '.$agentnNumArr[$agentNumber];
													 ?>
                                                 <?php
                                                        
                                                    //}
                                                }else{
                                                ?>
                                                    <span id="field-<?php echo "$key-$id"; ?>"><?php echo $myLog ?></span>
                                                <?php 
                                                    }
                                                //endif; ?>
                                            <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <div class="inline position-relative">
                                                <a target="_blank" class="btn btn-success btn-xs" href="<?php echo base_url('lead/profile').'/'.$leadId; ?>" data-rel="tooltip" title="" data-placement="left" data-original-title="Profile"><span><i class="fa fa-eye"></i></span> </a>
                                                <button class="btn btn-success btn-xs phone" rel="<?php echo $id;?>" data-rel="tooltip" title="Call No."><i class="fa fa-phone"></i></button>
                                                <button class="btn btn-primary btn-xs sendSms" data-leadid="<?php echo $leadId; ?>" data-rel="tooltip" title="Send SMS" rel="<?php echo $customerNumber;?>"><i class="fa fa-envelope"></i></button>

                                                 <button class="btn btn-primary btn-xs updateProfiel" data-lead="<?php echo $leadId; ?>" data-rel="tooltip" title="Update"><i class="fa fa-edit"></i></button>
                                               
                                                 
                                                



                                                
                                            </div>
                                                </div>
                                            </td>
                                            
                                        </tr>
                                        <?php endforeach; ?>
                                            
                                    
                                    </tbody>                        
                                </table>
                                <input type="hidden" id="leadsCount"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?=$total?> Logs found!
                                        <br>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $links;?>
                                    </div>
                                </div>
                            </div>
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


       <?php $this->load->view('log/update_view_modal');//load councelorModal ?>


 <script>
 var handleUpdateModal = {
    leadUrl: "<?php echo base_url('lead') ?>/",
    init: function() {
        var self = this;
        self.bindChangeCountry();
        $('.updateProfiel').click(function(e) {
            e.preventDefault();
            leadId = $(this).data('lead');
            self.leadId = leadId;
            $.get(self.leadUrl + "leadUpdateDetail/" + leadId, self.getUpdateDetails);
        });
    },

    getUpdateDetails: function(d) {
        var self = handleUpdateModal;
        $('#modal-form').modal('show');
        self.data = JSON.parse(d);
        var data = self.data;
        $('#form-field-name').val(data.name);
        $('#form-field-phone').val(data.phone);
        $('#form-field-email').val(data.email);
        $('#leadStatus').val(data.status);
        $('#leadId').val(self.leadId);
        self.changeCounty();
    },

    changeCounty: function() {
        var self = this;
        $.get(self.leadUrl + "getCountryIdByCityName/" + self.data.city, function(d) {
            countryId = d;
            $('#InterestedCountry').find('option[value=' + countryId + ']').attr('selected', true).trigger('change');
        });
    },

    bindChangeCountry: function() {
        var self = this;
        $('#InterestedCountry').change(function(e) {
            var $this = $(this);
            countryId = $this.val();
            //alert(countryId);
            $.post(self.leadUrl + "getCityByCountryId", "cid=" + countryId, function(d) {
                $('#leadCity').html(d);
               // alert(d);
                if(self.data.city !== undefined) {
                    $('#leadCity').find("option[value='" + self.data.city + "']").attr('selected', true);
                }
            });
        });
    }
}

handleUpdateModal.init();
 </script>


        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
            /* Init DataTables for Agent Stats */
        </script>

<script>
 $("#per_page").change(function(){
            var location1 = $(this).val();
            window.location.href = '<?php echo base_url()?><?=$this->uri->segment(1)?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;    
        });
</script>



<script>
    
    //Setting Base Url For Script
    
    var base_url = '<?php echo base_url()?>';
    var totalLeads = <?php echo $per_page?>;
    
        //on change function for choosing option
                    $('#leads').change(function()
                        {   
                        var location1 = $('#leads').val();
                        window.location.href = location1;
                        }); 
                        
                    $("#per_page").change(function(){
                        //$('#per_page_form').submit();
                        var location1 = $(this).val();
                        var path=window.location.pathname.split( '/' );
                        if(path[2]=='connectedLogs'){
                            window.location.href = '<?php echo base_url('connectedLogs')?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;
                            return false;
                        }
                        else if(path[2]=='missedLogs')
                        {
                            window.location.href = '<?php echo base_url('missedLogs')?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;
                            return false;
                        }
                        else{
                            window.location.href = '<?php echo base_url('allLogs')?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;
                            return false;
                        }
                            
                    });
$(document).ready(function() {
 
  $("#ivrsearch").val("<?=$ivr;?>");

  var agentNam = "<?=$agentNam?>";
  //alert(ageds);
$("#agentsearch").val(agentNam);

    $("#search-form").submit(function(){

        var search="";
        var customersearch = $('#customersearch').val().trim();
        var datesearchfrom = $('#datesearchfrom').val().trim();
        var datesearchto = $('#datesearchto').val().trim();
        var agentsearch = $('#agentsearch').val().trim();
        var ivrsearch = $('#ivrsearch').val().trim();
        if(customersearch!="") {
            search+="cu:"+customersearch;
        }
        
        if(agentsearch!="") {
            if(search!="")
                search+=",ag:"+agentsearch;
            else
                search+="ag:"+agentsearch;
        }  
        
        
        if(datesearchfrom !="") {
            if(search!="")
                search+=",datefrom:"+datesearchfrom;
            else
                search+="datefrom:"+datesearchfrom;
        }               


        if(datesearchto !="") {
            if(search!="")
                search+=",dateto:"+datesearchto;
            else
                search+="dateto:"+datesearchto;
        }               
 if(ivrsearch!=""){

    if(search!="")
        search+=",ivr:"+ivrsearch;
    else
        search+="ivr:"+ivrsearch;
}
        // alert('hihi');
        var path=window.location.pathname.split( '/' );
       // alert(path[2]);
        if(path[2]=='connectedLogs'){
            window.location.href = '<?php echo base_url('connectedLogs')?>/'+search;
            return false;
        } 
        else if(path[2]=='missedLogs')
        {
            window.location.href = '<?php echo base_url('missedLogs')?>/'+search;
            return false;
        }
        else{
            window.location.href = '<?php echo base_url('allLogs')?>/'+search;
            return false;
        }
        
      

   
 
    });
        
    $('[data-rel=tooltip]').tooltip();
    
    
    $('.phone').click(function(){
        var id = $(this).attr('rel');
        var callerno = $('#field-customerNumber-' + id).text().trim();

        $('#customerConnectedModal').modal('show');
        $.get("<?php echo base_url('call/makeCall');?>/" + callerno + "/<?php echo $agentNumber;?>", 
        
                    function(data){
                        $('#customerConnectedResponse').html(data);
                         //location.reload();
                    });

        
    });
    $('.sendSms').click(function() {
        var cuNu=$(this).attr('rel');
        var leadId = $(this).data('leadid');
        $('#smsLeadId').val(leadId);
        $('#customerSmsNo').attr('disabled', true).val(cuNu);
        $('#smsModalBoxToAnyCustomer').modal('show');
    });


     /* $('.updateProfiel').click(function() {
        leadUrl: "<?php echo base_url('lead') ?>/";
         leadId = $(this).data('lead');

       // alert('satish');
        //var cuNu=$(this).attr('rel');
        //var leadId = $(this).data('leadid');
        //$('#smsLeadId').val(leadId);
        //$('#customerSmsNo').attr('disabled', true).val(cuNu);
        $('#modal-form').modal('show');
    }); */








});
</script>

