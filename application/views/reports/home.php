            <?php $this->load->view('layout/head') ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Major Reporting</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Major Reporting</a>
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
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Major Reporting</h5>
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
                    <form method="post" action="<?=base_url('reports');?>">
                    <div class="ibox-content">
                    
                    
                    	<select class="form-action" name="reporttype" id="reporttype" onChange="reporttype1();">
                    		<option>SELECT</option>
                    		<option value="leads">LEADS</option>
                    		<option value="logs">LOGS</option>
                    		<option value="agents">AGENTS</option>
                    	</select>
                    	
                    	<label>From:</label>
                    	<input class="form-control" type="date" name="fromdate" id="fromdate">
                    	<label>To:</label>
                    	<input class="form-control" type="date" name="todate" id="todate">
                    	
                    
                   
                    </div>
                    
                    <div class="ibox-content">
                    	<h3>Select fields in Reports</h3>
                  <div class="checkbox" id="fields">                
                  </div>
                    </div>
                    <input id="submitreport" type="submit" value="generate report" />
                   </form>
                    
                    
                   <div class="ibox-content">
                    	<h3>Generated Report</h3>
                                
                  </div>
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

      

        <script>
          


function reporttype1(){
	
	var reporttype2 = $('#reporttype').val();
//	alert(reporttype2);
	/*
	if(reporttype =='logs'){
			// select columns from log list view table in human redable form
			
			
		}else if(reporttype =='leads'){
			// select from leads table in human readable form
					  }else if(reporttype =='agents'){
			// select from dashboard stats in human readable form
			
		}*/
		// var data="satsh";
	
	
	            var data = 'reporttype=' + reporttype2;
                $.post('<?php echo base_url()?>reports/get_columns',data,function(msg){
             $('#fields').html(msg);
               });
	
	
}

        </script>
</body>

</html>
