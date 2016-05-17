            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Leads</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?=base_url('dashboard')?>">Dasboard</a>
                        </li>
                        <li class="active">
                            <strong>Leads</strong>
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
                            <h5>
                                Leads
                                <?php if(!empty($keywords)) {  foreach($keywords as $_k) : ?>
                                <span class="label label-info pull-right"><?=ucwords($_k)?></span>
                                <?php endforeach; } ?>
                            </h5>
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
                            <form method="get" action=""  id="search-form">
                                <div class="row">
                                    <div class="col-sm-1 m-b-xs">
                                        <select size="1" name="per_page" id="per_page" aria-controls="sample-table-2" class="input-sm form-control input-s-sm inline">
                                            <option value="10" <?php echo ($per_page==10)?'selected="selected"':'';?>>10</option>
                                            <option value="25" <?php echo ($per_page==25)?'selected="selected"':'';?>>25</option>
                                            <option value="50" <?php echo ($per_page==50)?'selected="selected"':'';?>>50</option>
                                            <option value="100" <?php echo ($per_page==100)?'selected="selected"':'';?>>100</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Phone</label>
                                        <input type="number" name="phonesearch" id="phonesearch" placeholder="Phone" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Email</label>
                                        <input type="email" name="emailsearch" id="emailsearch" placeholder="Email" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Source</label>
                                        <input type="text" name="sourcesearch" id="sourcesearch" placeholder="Source" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Status</label>
                                        <input type="status" name="statussearch" id="statussearch" placeholder="Status" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a class="btn btn-primary btn-bitbucket" title="Transfer to Team" onclick="showTransferMultiModal()"><i class="fa fa-users"></i></a>
                                        <a class="btn btn-info btn-bitbucket" title="Bulk SMS" onclick="showSmsMultiModal()"><i class="fa fa-mobile"></i></a>
                                         <a class="btn btn-info btn-bitbucket" title="Bulk Mail" onclick="showMailMultiModal()"><i class="fa fa-mobile"></i></a>
                                        <?php if($this->session->userdata('loggedIn')['userLevel']==='2') { ?>
                                        <a class="btn btn-danger btn-bitbucket" title="Delete Leads" onclick="showDeleteMultiModal()"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                        <?php if($this->session->userdata('loggedIn')['userLevel']==='2') { ?>
                                        <a class="btn btn-warning btn-bitbucket" title="Status Change" onclick="showStatusMultimodal()"><i class="fa fa-recycle"></i></a>
                                        <?php } ?>
                                        <?php if($this->session->userdata('loggedIn')['userLevel']==='2') { ?>
                                        <a class="btn btn-success btn-bitbucket" title="Add to Bucket" onclick="showAddBucket()"><i class="fa fa-share-alt"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 col-sm-offset-1">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Counsellor</label>
                                        <input type="text" name="counsellorsearch" id="counsellorsearch" placeholder="Counsellor" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">City</label>
                                        <input type="text" name="citysearch" id="citysearch" placeholder="City" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Co Status</label>
                                            <select name="cstatussearch" id="cstatussearch" placeholder="Co Status" id="exampleInputEmail2" class="form-control input-sm">
                                                <option value=''>Select</option>
                                                <option value='AppProcNeha'>AppProcNeha</option>
                                                <option value='AppProcKimmi'>AppProcKimmi</option>
                                                <option value='AppProcSumbul'>AppProcSumbul</option>
                                                <option value='AppProcTanmoy'>AppProcTanmoy</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"><label for="exampleInputEmail2" class="sr-only">Name</label>
                                        <input type="text" name="namesearch" id="namesearch" placeholder="Lead Name" id="exampleInputEmail2" class="form-control input-sm"></div>
                                    </div>
                                    <!--//If user is an Admin then only show-->
                                    <?php if($this->session->userdata('loggedIn')['userLevel']==='2') { ?>
                                    <a class="btn <?php echo strpos($this->uri->segment(2),'vi:on')===false ? 'btn-white btn-bitbucket' : 'btn-success'; ?>" onclick="verified()">
                                        <i class="fa fa-bolt"></i>
                                    </a>
                                    <a class="btn <?php echo strpos($this->uri->segment(2),'lu:on')===false ? 'btn-white btn-bitbucket' : 'btn-success'; ?>" onclick="lookup()">
                                        <i class="fa fa-heart"></i>
                                    </a>
                                    <a class="btn <?php echo strpos($this->uri->segment(2),'do:on')===false ? 'btn-white btn-bitbucket' : 'btn-success'; ?>" onclick="docs()">
                                        <i class="fa fa-paperclip"></i>
                                    </a>
                                    <?php } ?>
                                    <!--//If user is an Admin then only showing above block-->
                                    <div class="col-sm-2 m-b-xs pull-right">
                                        <button class="btn btn-sm btn-primary pull-right" type="submit"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="leads_data">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox"></th>
                                        <th>Created</th>
                                        <th>Name </th>
                                        <th>Phone </th>
                                        <th>City </th>
                                        <th>Status </th>
                                        <th>Source </th>
                                        <th>Counselor </th>
                                        <th>Opted Time </th>
                                        <th>Notes </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $leadcsv=''; ?>
                                    <?php foreach($lead_data as $lead) : ?>
                                    <tr id="leadID-<?=$lead['id']?>">
                                        <td><input type="checkbox" id="leadCheckBox-<?=$lead['id']?>" class="check"><input type="hidden" class="leadValue" id="lead-<?php echo $lead['id'];?>" value=""/></td>
                                        <td><?php echo str_replace(' ', '<br/>', $lead['leadCreatedTime']) ?></td>
                                        <td>
                                            <?=ucwords($lead['name'])?><br/>
                                            <?php echo ($lead['isVerified']=='1') ? '<span class="badge badge-success" title="SMS Verified"><i class="fa fa-mobile"></i></span>' : ''; ?>
                                            <?php echo ($lead['optedTime']) ? '<span class="badge badge-primary" title="OBD Verified"><i class="fa fa-phone"></i></span>' : ''; ?>
                                            <?php echo ($lead['doc']) ? '<span class="badge badge-warning" title="Docs Uploaded"><i class="fa fa-paperclip"></i></span>' : ''; ?>
                                        </td>
                                        <td><?=$lead['phone']?></td>
                                        <td><?=ucwords($lead['city'])?><br/><span class="label label-primary"><?=ucwords($lead['lookupCity'])?></span></td>
                                        <td><?=ucwords($lead['detail'])?></td>
                                        <td><?php echo $this->session->userdata('loggedIn')['userLevel']==='2' ? $lead['source'] : base64_encode($lead['source']); ?></td>
                                        <td><?=ucwords($lead['userName'])?></td>
                                        <td><?=ucwords($lead['optedTime'])?></td>
                                        <td style="width:300px" onclick="window.open('<?=base_url('leads/profile/'.$lead['id'])?>','_blank');">
                                            <span id="notes-<?=$lead['id']?>"></span><br/>
                                            <span class="text-success" id="counselor-<?=$lead['id']?>"></span>
                                            <span class="message-date pull-right" id="time-<?=$lead['id']?>"></span>
                                        </td>
                                    </tr>
                                    <?php $leadcsv .= $lead['id'].','; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <input type="hidden" id="leadsCount"/>
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
    <?php $this->load->view('plugins/modal') ?>
    <script type="text/javascript">
        var base_url = '<?php echo base_url()?>';

        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            var dataString = "leads="+'<?=$leadcsv?>';
        $.post(base_url+'leads/get_last_notes',dataString,
        function(msg){
            var obj = JSON.parse(msg);
            for(i=0; i <= obj.length; i++)  {
                $('#notes-'+obj[i].leadsId).text(obj[i].notes);
                $('#counselor-'+obj[i].leadsId).text(obj[i].userName);
                $('#time-'+obj[i].leadsId).text(obj[i].statusTime);
            }
        });
    });

        $("#per_page").change(function(){
            var location1 = $(this).val();
            window.location.href = '<?php echo base_url()?><?=$this->uri->segment(1)?>/<?php echo ($this->uri->segment(2))?$this->uri->segment(2):'key'?>/'+location1;    
        });
        //record leads count on single row selection
        $('table td input:checkbox').on('click' , function(){
            //alert('input selected');
            var that = this;
            var temp = $(this).closest('tr').attr('id');
            //alert(temp);
            var temp1 = temp.split('-');
            //alert(temp1[1]);
            $('#lead-'+temp1[1]).val(that.checked);
            //alert(temp);
            //var leadCount = parseInt($('#leadsCount').val());
            var leadCount = $('.check:checked').size();
            leadCount = (that.checked)?leadCount:leadCount;
            $('#leadsCount').val(leadCount);
        });
        //record leads count on multiple selection
        $('table th input:checkbox').on('click' , function(){        
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function(){
                var temp = $(this).closest('tr').attr('id');
                var temp1 = temp.split('-');
                $('#lead-'+temp1[1]).val(that.checked);
                this.checked = that.checked;
                //alert(temp1[1]);
                var leadCount = $('#leadsCount').val();
                if(that.checked)
                    ++leadCount;
                else if(!that.checked && leadCount>0)
                    --leadCount;
                //alert(leadCount);
                $('#leadsCount').val(leadCount);
                //$(this).closest('tr').toggleClass('selected');
            });
        });
        $("#search-form").submit(function(){
            var search="";
            var phonesearch = $('#phonesearch').val().trim();
            var emailsearch = $('#emailsearch').val().trim();
            var sourcesearch = $('#sourcesearch').val().trim();
            var statussearch = $('#statussearch').val().trim();
            var counsellorsearch = $('#counsellorsearch').val().trim();
            var citysearch = $('#citysearch').val();
            var namesearch = $('#namesearch').val().trim();
            var cstatussearch = $('#cstatussearch').val().trim();
            var first = $('#first').val();

            if(first!='')
            {
                var fieldsplit;
                var firstsplit=first.split('+');
                for (i=0;i<firstsplit.length;i++)
                {
                    fieldsplit=firstsplit[i].split(':');
                    if(fieldsplit[0]=='ph')
                    {
                        if(phonesearch!="")
                        {
                            phonesearch=phonesearch+","+fieldsplit[1];
                        }
                        else
                        {
                            phonesearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='em')
                    {
                        if(emailsearch!="")
                        {
                            emailsearch=emailsearch+","+fieldsplit[1];
                        }
                        else
                        {
                            emailsearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='so')
                    {
                        if(sourcesearch!="")
                        {
                            sourcesearch=sourcesearch+","+fieldsplit[1];
                        }
                        else
                        {
                            sourcesearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='st')
                    {
                        if(statussearch!="")
                        {
                            statussearch=statussearch+","+fieldsplit[1];
                        }
                        else
                        {
                            statussearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='co')
                    {
                        if(counsellorsearch!="")
                        {
                            counsellorsearch=counsellorsearch+","+fieldsplit[1];
                        }
                        else
                        {
                            counsellorsearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='na')
                    {
                        if(namesearch!="")
                        {
                            namesearch=namesearch+","+fieldsplit[1];
                        }
                        else
                        {
                            namesearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='cs')
                    {
                        if(cstatussearch!="")
                        {
                            cstatussearch=cstatussearch+","+fieldsplit[1];
                        }
                        else
                        {
                            cstatussearch=fieldsplit[1];
                        }
                    }
                    else if(fieldsplit[0]=='ci')
                    {
                        if(citysearch!="")
                        {
                            citysearch=citysearch+","+fieldsplit[1];
                        }
                        else
                        {
                            citysearch=fieldsplit[1];
                        }
                    }
                }
            }
            if(phonesearch!="")
            {
                search+="ph:"+phonesearch;
            }
            if(emailsearch!="")
            {
                if(search!="")
                    search+="+em:"+emailsearch;
                else
                    search+="em:"+emailsearch;
            }
            if(sourcesearch!="")
            {
                if(search!="")
                    search+="+so:"+sourcesearch;
                else
                    search+="so:"+sourcesearch;
            }
            if(statussearch!="")
            {
                if(search!="")
                    search+="+st:"+statussearch;
                else
                    search+="st:"+statussearch;
            }
            if(counsellorsearch!="")
            {
                if(search!="")
                    search+="+co:"+counsellorsearch;
                else
                    search+="co:"+counsellorsearch;
            }
            if(namesearch!="")
            {
                if(search!="")
                    search+="+na:"+namesearch;
                else
                    search+="na:"+namesearch;
            }
            if(cstatussearch!="")
            {
                if(search!="")
                    search+="+cs:"+cstatussearch;
                else
                    search+="cs:"+cstatussearch;
            }
            if(citysearch!="")
            {
                if(search!="")
                    search+="+ci:"+citysearch;
                else
                    search+="ci:"+citysearch;
            }

            /*if(first)
            {
                window.location.href = '<?php echo base_url('allLead')?>/'+search;
            }
            else{*/
                window.location.href = '<?php echo base_url('search')?>/'+search;
            //}
            return false;
        });
    </script>
    <script type="text/javascript">
        function verified(){
            var first = $('#first').val();
            var search;
            //alert(first);
            var n = first.search("vi:on");
            if(first!='key')
            {
                if(n > 0) {
                    first = first.replace('vi:on','vi:off');
                } else {
                   first+="+vi:on"; 
                }
            } else {
                first="vi:on";
            }
            window.location.href = '<?php echo base_url('search')?>/'+first;
            return false;
        }
    </script>
    <script type="text/javascript">
        function lookup(){
            var first = $('#first').val();
            var search;
            //alert(first);
            var n = first.search("lu:on");
            if(first!='key')
            {
                if(n > 0) {
                    first = first.replace('lu:on','lu:off');
                } else {
                   first+="+lu:on"; 
                }
            } else {
                first="lu:on";
            }
            window.location.href = '<?php echo base_url('search')?>/'+first;
            return false;
        }
    </script>
    <script type="text/javascript">
        function docs(){
            var first = $('#first').val();
            var search;
            //alert(first);
            var n = first.search("do:on");
            if(first!='key')
            {
                if(n > 0) {
                    first = first.replace('do:on','do:off');
                } else {
                   first+="+do:on"; 
                }
            } else {
                first="do:on";
            }
            window.location.href = '<?php echo base_url('search')?>/'+first;
            return false;
        }
    </script>
    <input type="hidden" value="<?php if(isset($additionalParameter)){echo $additionalParameter; };?>" id="first">
</body>

</html>
