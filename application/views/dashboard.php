 <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Data Quality Analysis'
        },
        subtitle: {
            text: 'Call Performance'
        },
        xAxis: [{
            categories: [
             <?php foreach($stats_graph as $_la): ?>
            '<?=$_la->date?>',
            <?php endforeach; ?>
            ],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: 'Calls',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: 'Total Calls',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} Calls',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: 'Qualified',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} Calls',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Total Connected',
            type: 'column',
            yAxis: 1,
            data: [
            <?php foreach($stats_graph as $_la): ?>
            <?=$_la->TotalConnectedCalls;?>,
            <?php endforeach; ?>
            ],
            tooltip: {
                valueSuffix: ' Calls'
            }

        }, {
            name: 'Total Missed',
            type: 'spline',
            yAxis: 2,
            data: [
             <?php foreach($stats_graph as $_la): ?>
            <?=$_la->TotalMissedCalls;?>,
            <?php endforeach; ?>
            ],
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                valueSuffix: ' Calls'
            }

        }, {
            name: 'Total Not Connected',
            type: 'spline',
            data: [
            <?php foreach($stats_graph as $_la): ?>
            <?=$_la->TotalNotConnectedCalls;?>,
            <?php endforeach; ?>
            ],
            tooltip: {
                valueSuffix: ' Calls'
            }
        }]
    });
});
</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
  <?php $this->load->view('layout/head') ?>
            <div class="wrapper wrapper-content">
      <div class="row">
         <form method="post" action="<?php echo base_url('home') ?>">
       <div class="form-group"><label class="col-lg-1 control-label">FROM:</label>
                                    <div class="col-lg-4"><input type="date" placeholder="From Date" name="fromDate" class="form-control">
                                    </div>
                                </div>


          <div class="form-group"><label class="col-lg-1 control-label">TO:</label>
                                    <div class="col-lg-4"><input type="date" placeholder="To Date" name="toDate" class="form-control">
                                    </div>
                                </div>
                                
       

       <div class="col-lg-2"> 
           <button type="submit" class="form-control">Submit</button>
       </div>


           
         </form>
         </div>            
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Inbound</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($InAllcallsCount) ? "0" : $InAllcallsCount; ?></h1>
                               <!-- <div class="stat-percent font-bold <?php echo ($lead_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$lead_perc?>% <i class="fa fa-bolt"></i></div> -->
                                <small>Total NotConnected Calls</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Inbound </h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($InConnectedcallCount)  ? "0" : $InConnectedcallCount; ?></h1>
                              <!--  <div class="stat-percent font-bold <?php echo ($wmd_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$wmd_perc?>%</div>  -->
                                <small>Total Connected Calls</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Inbound</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($InMissedcallCount)  ? "0" : $InMissedcallCount; ?></h1>
                               <!-- <div class="stat-percent font-bold <?php echo ($doc_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$doc_perc?>%</div>  -->
                                <small>Total Missed Calls</small>
                            </div>
                        </div>
                    </div>
                   
                </div>



     <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Outbound</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($OutAllcallsCount) ? "0" : $OutAllcallsCount; ?></h1>
                               <!-- <div class="stat-percent font-bold <?php echo ($lead_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$lead_perc?>% <i class="fa fa-bolt"></i></div> -->
                                <small>Total NotConnected Calls</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Outbound </h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($OutConnectedcallCount)  ? "0" : $OutConnectedcallCount; ?></h1>
                              <!--  <div class="stat-percent font-bold <?php echo ($wmd_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$wmd_perc?>%</div>  -->
                                <small>Total Connected Calls</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Monthly</span>
                                <h5>Outbound</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo empty($OutMissedcallCount)  ? "0" : $OutMissedcallCount; ?></h1>
                               <!-- <div class="stat-percent font-bold <?php echo ($doc_perc>0) ? 'text-navy' : 'text-danger'; ?>"><?=$doc_perc?>%</div>  -->
                                <small>Total Missed Calls</small>
                            </div>
                        </div>
                    </div>
                   
                </div>




                 <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Conneccted vs Missed vs Not Connected</h5>
                                <div class="pull-right">
                                    <span class="label label-primary pull-right">Monthly</span>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                <div class="col-lg-12">
                                    <div id="container" style="min-width: 400px; height: 250px; margin: 0 auto"></div>
                                </div>
                               <!-- <div class="col-lg-3">
                                    <ul class="stat-list">
                                        <li>
                                            <h2 class="no-margins "><?php echo empty($today_apps)  ? "0" : count($today_apps); ?></h2>
                                            <small>Total applications today</small>
                                            <div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 48%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins "><?php echo empty($today_doc)  ? "0" : $today_doc; ?></h2>
                                            <small>Total docs recieved today</small>
                                            <div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 60%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins"><?php echo empty($today_wmd)  ? "0" : $today_wmd; ?></h2>
                                            <small>Total WMD's today</small>
                                            <div class="stat-percent">0% <i class="fa fa-bolt text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 22%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        </ul>
                                    </div>  -->
                                </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <div class="row">
                   

                    <div class="col-lg-12">                  
                            <?php //if($this->session->userdata('loggedIn')['userLevel'] === '2') { ?>
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Stats</h5> <button id="exporttable">Export</button>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                    	<div class="table-responsive">
                                        <table id="userstats" class="table table-hover margin bottom">
                                            <thead>
                                            <tr>
                                                <th style="width: 1%" class="text-center">Date</th>
                                                <th style="width: 1%" class="text-center">Name</th>
                                                <th>First Logged In Time</th>
                                                <th class="text-center">Logout Time</th>
                                                <th class="text-center">Total Connected Calls</th>
                                                <th class="text-center">Total Missed Calls</th>
                                                <th class="text-center">Total Not Connected Calls</th>
                                                <th class="text-center">Total logged in time</th>
                                                <th class="text-center">Productive Hours</th>
                                                 <th class="text-center">Not Available</th>
                                                  <th class="text-center">Aux Time</th>
                                                  <th class="text-center">Wrap Time</th>
                                             <!--   <th class="text-center">Inbound Total Talktime</th>
                                                <th class="text-center">Outbound Total Talktime</th>  -->
                                                
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($stats as $stats) :
											 if($stats->wrap_time>0){
											 $wrpTime= 	gmdate("H:i:s",$stats->wrap_time);
											 }else{
											 $wrpTime= $stats->wrap_time;
											 }
											
											 ?>
                                            <tr>
                                                <td> <?php echo empty($stats->date) ? '<i>NULL</i>' : $stats->date ?></td>
                                                <td> <?php echo empty($stats->AgentName) ? '<i>NULL</i>' : $stats->AgentName ?></td>
                                                <td class="text-center text-success"><?php echo empty($stats->FirstLoggedInTime) ? '<i>0</i>' : $stats->FirstLoggedInTime ?></td>
                                                <td class="text-center"><?php echo empty($stats->LogoutTime) ? '<i>0</i>' : $stats->LogoutTime ?></td>
                                                <td class="text-center"><?php echo empty($stats->TotalConnectedCalls) ? '<i>0</i>' : $stats->TotalConnectedCalls ?></td>
                                                <td class="text-center text-danger"><?php echo empty($stats->TotalMissedCalls) ? '<i>0</i>' : $stats->TotalMissedCalls ?></td>
                                                <td class="text-center text-info"><?php echo empty($stats->TotalNotConnectedCalls) ? '<i>0</i>' : $stats->TotalNotConnectedCalls ?></td>
                                                 <td class="text-center text-info"><?php echo empty($stats->TotalLoggedInTime) ? '<i>0</i>' : $stats->TotalLoggedInTime ?></td>
                                                  <td class="text-center text-info"><?php echo empty($stats->productive_hours) ? '<i>NA</i>' : gmdate("H:i:s",$stats->productive_hours) ?></td>
                                                <td class="text-center text-info"><?php echo empty($stats->notAvailable) ? '<i>NA</i>' : gmdate("H:i:s",$stats->notAvailable) ?></td>
                                                 <td class="text-center text-info"><?php echo empty($stats->auxTime) ? '<i>NA</i>' : gmdate("H:i:s",$stats->auxTime) ?></td>
                                                 <td class="text-center text-info"><?php echo empty($stats->wrap_time) ? '<i>NA</i>' :$wrpTime  ?></td>
                              
                                      <!--            <td class="text-center text-info"><?php echo empty($stats->InboundTotalTalktime) ? '<i>0</i>' : $stats->InboundTotalTalktime ?></td>
                                                   <td class="text-center text-info"><?php echo empty($stats->OutboundTotalTalktime) ? '<i>0</i>' : $stats->OutboundTotalTalktime ?></td>  -->
                                            </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php // } ?>
                       

                    </div>


                </div>

  <!--  <div class="row">
                   

                    <div class="col-lg-12">  
                      <div class="col-lg-6">                  
                            <?php //if($this->session->userdata('loggedIn')['userLevel'] === '2') { ?>
                            <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>User Stats</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Connected Calls</th>
                                        <th>Last Call</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0; foreach($user_stats as $u) : ?>
                                    <tr><?php $i++; ?>
                                        <td><?=$i?></td>
                                        <td><?=$u->userName?></td>
                                        <td><?=$u->count?></td>
                                        <td class="text-navy"><?=$u->time?> </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            <?php // } ?>
                  
                   </div>   
                     <div class="col-lg-6">    
                   <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Recent Activities</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content ibox-heading">
                                <h3><i class="fa fa-comment-o"></i> New updates</h3>
                                <small><i class="fa fa-tim"></i></small>
                            </div>
                            <div class="ibox-content">
                                <div class="feed-activity-list">
                                    <?php foreach($rescent_activities as $activity) { ?>
                                    <div class="feed-element">
                                        <a href="<?=base_url('leads/profile')?>/<?=$activity['leadsId']?>" target="_blank">
                                            <div>
                                                <small class="pull-right text-navy"><?=date_difference($activity['statusTime'])?> mins ago</small>
                                                <strong class="text-primary"><?=$activity['userName']?></strong> updated
                                                <div class="text-muted"><?=$activity['name']?> as <span class="label label-info"><?=@$activity['detail']?></span><br/><?=$activity['notes']?></div>
                                                <small class="text-muted"><?=$activity['statusTime']?></small>
                                            </div>
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <div class="text-center link-block">
                                       <!-- <a href="<?=base_url('recent')?>">
                                            <i class="fa fa-info"></i> <strong>Check All Updates</strong>
                                        </a> -->
                         <!--           </div>                                    
                                </div>
                            </div>
                        </div> 
                        </div>                    

                    </div>


                </div>    -->



                </div>
       <!-- <div class="footer">
            <div>
                <strong>Copyright</strong> Leadmentor &copy; 2016
            </div>
       </div>  -->
        </div>
    </div>

    <script> $("#exporttable").click(function(){
     	//alert('satish');
  $("#userstats").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "User Stats" //do not include extension
  });
});

    
        $(document).ready(function() {
        	
        
   var ttbl= $('#userstats').DataTable({
    	 aLengthMenu: [
        [10, 50, 100, 200, -1],
        [10, 50, 100, 200, "All"]
    ],
    iDisplayLength: 10
    });
    
    
    
            $('.chart').easyPieChart({
                barColor: '#f8ac59',
//                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            $('.chart2').easyPieChart({
                barColor: '#1c84c6',
//                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            var data2 = [
                [gd(2012, 1, 1), 7], [gd(2012, 1, 2), 6], [gd(2012, 1, 3), 4], [gd(2012, 1, 4), 8],
                [gd(2012, 1, 5), 9], [gd(2012, 1, 6), 7], [gd(2012, 1, 7), 5], [gd(2012, 1, 8), 4],
                [gd(2012, 1, 9), 7], [gd(2012, 1, 10), 8], [gd(2012, 1, 11), 9], [gd(2012, 1, 12), 6],
                [gd(2012, 1, 13), 4], [gd(2012, 1, 14), 5], [gd(2012, 1, 15), 11], [gd(2012, 1, 16), 8],
                [gd(2012, 1, 17), 8], [gd(2012, 1, 18), 11], [gd(2012, 1, 19), 11], [gd(2012, 1, 20), 6],
                [gd(2012, 1, 21), 6], [gd(2012, 1, 22), 8], [gd(2012, 1, 23), 11], [gd(2012, 1, 24), 13],
                [gd(2012, 1, 25), 7], [gd(2012, 1, 26), 9], [gd(2012, 1, 27), 9], [gd(2012, 1, 28), 8],
                [gd(2012, 1, 29), 5], [gd(2012, 1, 30), 8], [gd(2012, 1, 31), 25]
            ];

            var data3 = [
                [gd(2012, 1, 1), 800], [gd(2012, 1, 2), 500], [gd(2012, 1, 3), 600], [gd(2012, 1, 4), 700],
                [gd(2012, 1, 5), 500], [gd(2012, 1, 6), 456], [gd(2012, 1, 7), 800], [gd(2012, 1, 8), 589],
                [gd(2012, 1, 9), 467], [gd(2012, 1, 10), 876], [gd(2012, 1, 11), 689], [gd(2012, 1, 12), 700],
                [gd(2012, 1, 13), 500], [gd(2012, 1, 14), 600], [gd(2012, 1, 15), 700], [gd(2012, 1, 16), 786],
                [gd(2012, 1, 17), 345], [gd(2012, 1, 18), 888], [gd(2012, 1, 19), 888], [gd(2012, 1, 20), 888],
                [gd(2012, 1, 21), 987], [gd(2012, 1, 22), 444], [gd(2012, 1, 23), 999], [gd(2012, 1, 24), 567],
                [gd(2012, 1, 25), 786], [gd(2012, 1, 26), 666], [gd(2012, 1, 27), 888], [gd(2012, 1, 28), 900],
                [gd(2012, 1, 29), 178], [gd(2012, 1, 30), 555], [gd(2012, 1, 31), 993]
            ];


            var dataset = [
                {
                    label: "Doc-Recieved",
                    data: data3,
                    color: "#1ab394",
                    bars: {
                        show: true,
                        align: "center",
                        barWidth: 24 * 60 * 60 * 600,
                        lineWidth:0
                    }

                }, {
                    label: "Applications",
                    data: data2,
                    yaxis: 2,
                    color: "#464f88",
                    lines: {
                        lineWidth:1,
                            show: true,
                            fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.2
                            }, {
                                opacity: 0.2
                            }]
                        }
                    },
                    splines: {
                        show: false,
                        tension: 0.6,
                        lineWidth: 1,
                        fill: 0.1
                    },
                }
            ];


            var options = {
                xaxis: {
                    mode: "time",
                    tickSize: [3, "day"],
                    tickLength: 0,
                    axisLabel: "Date",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 10,
                    color: "#d5d5d5"
                },
                yaxes: [{
                    position: "left",
                    max: 1070,
                    color: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 3
                }, {
                    position: "right",
                    clolor: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: ' Arial',
                    axisLabelPadding: 67
                }
                ],
                legend: {
                    noColumns: 1,
                    labelBoxBorderColor: "#000000",
                    position: "nw"
                },
                grid: {
                    hoverable: false,
                    borderWidth: 0
                }
            };

            function gd(year, month, day) {
                return new Date(year, month - 1, day).getTime();
            }

            var previousPoint = null, previousLabel = null;

            $.plot($("#flot-dashboard-chart"), dataset, options);

        });
    </script>
    <?php function date_difference($date_f)   {
        $date_m = strtotime($date_f);
        $date_n = strtotime(date('Y-m-d H:i:s'));
        $minutes = round(abs($date_n - $date_m) / 60,2);
        if($minutes > '60') {
            print gmdate("H:i", ($minutes * 60));
        } else {
            print $minutes;
        }
    }
    ?>
</body>
</html>
