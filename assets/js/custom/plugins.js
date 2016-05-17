    //Show multi lead transfer to users modal box
    function showTransferMultiModal()
    {
       // alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#transferMultiModal").modal('show');

            $.post(base_url+'plugins/get_users_list',
                function(msg){
                   $('#counselor').html(msg);
            });
        }
        else
        {
            alert("Select a record to transfer.");
        }
    }
    //transfer multi leads to users and send flash message
    function transferMultiLeads()
    {
        var leadsCsv = '';
        var counselorId = $('#counselor option:selected').val();
        if(counselorId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&counselorId="+counselorId;
        //alert(dataString);
        $.post(base_url+'plugins/t_multi_lead',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#transfer_success").html('<div class="alert alert-success">Leads Successfully Transfered.</div>');
            setTimeout(function(){$("#SuccessTransferModal").modal('hide');location.reload();},1500); 
        });
    }
    //show bulk sms transfer modal box
    function showSmsMultiModal()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#smsMultiModal").modal('show');

            $.post(base_url+'plugins/get_sms_list',
                function(msg){
                   $('#smsTemplates').html(msg);
            });
        }
        else
        {
            alert("Select a record to transfer.");
        }
    }


      function showMailMultiModal()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#mailMultiModal").modal('show');

            $.post(base_url+'plugins/get_email_list',
                function(msg){
                   $('#mailTemplates').html(msg);
            });
        }
        else
        {
            alert("Select a record to transfer.");
        }
    }
    //perform action bulk sms to leads and set flash message
    function smsMultiLeads()
    {
        var leadsCsv = '';
        var counselorId = $('#counselor option:selected').val();
        if(counselorId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&smsId="+$("#smsTemplates option:selected").val();
        //alert(dataString);
        $.post(base_url+'plugins/sms_multi_lead',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#sms_success").html('<div class="alert alert-success">SMS Successfully Sent.</div>');
            setTimeout(function(){$("#smsMultiModal").modal('hide');location.reload();},1500); 
        });
    }





      function mailMultiLeads()
    {
        var leadsCsv = '';
        var counselorId = $('#counselor option:selected').val();
        if(counselorId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&emailId="+$("#mailTemplates option:selected").val();
        //alert(dataString);
        $.post(base_url+'plugins/email_multi_lead',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#email_success").html('<div class="alert alert-success">Email Successfully Sent.</div>');
            setTimeout(function(){$("#mailMultiModal").modal('hide');location.reload();},1500); 
        });
    }
    //call bulk lead delete modal box
    function showDeleteMultiModal()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#deleteMultiModal").modal('show');
        }
        else
        {
            alert("Select a record to transfer.");
        }
    }
    //perform action delete multiple leads
    function deleteMultiLeads()
    {
        var leadsCsv = '';
        var counselorId = $('#counselor option:selected').val();
        if(counselorId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv;
        //alert(dataString);
        $.post(base_url+'plugins/delete_multi_lead',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#delete_success").html('<div class="alert alert-success">Successfully Deleted.</div>');
            setTimeout(function(){$("#deleteMultiModal").modal('hide');location.reload();},1500); 
        });
    }
    //show multi lead status change modal box
    function showStatusMultimodal()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#show_status_multimodal").modal('show');

            $.post(base_url+'plugins/get_status_list',
                function(msg){
                   $('#statusId').html(msg);
            });
        }
        else
        {
            alert("Select a record to transfer.");
        }
    }
    //perform action multi lead status change

    //perform action multi lead status change
    function change_bulk_status()
    {
        var leadsCsv = '';
        var statusId = $('#statusId option:selected').val();
        if(statusId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&statusId="+statusId;
        //alert(dataString);
        $.post(base_url+'plugins/change_multi_status',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#status_success").html('<div class="alert alert-success">Status Changed Successfully.</div>');
            setTimeout(function(){$("#show_status_multimodal").modal('hide');location.reload();},1500); 
        });
    }

    //show multi lead status change modal box
    function showPublishBucket()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#show_publishbucket_multimodal").modal('show');

            $.post(base_url+'plugins/get_campaigns_list',
                function(msg){
                   $('#campaignId').html(msg);
            });
        }
        else
        {
            alert("Select a lead to publish.");
        }
    }
    //perform action to publish leads
    function publish_to_bucket()
    {
        var leadsCsv = '';
        var campaignId = $('#campaignId option:selected').val();
        if(campaignId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&campaignId="+campaignId;
        //alert(dataString);
        $.post(base_url+'plugins/publish_to_bucket',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#publish_success").html('<div class="alert alert-success">Published to bucket successfully.</div>');
            window.location.href = base_url+"plugins/download_publish_lead/"+leadsCsv;
            setTimeout(function(){location.reload();},1500); 
        });
    }

    //show multi lead status change modal box
    function showAddBucket()
    {
        //alert($('#leadsCount').val());
        var count = parseInt($('#leadsCount').val());
        if(count)
        {
            var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
            //alert('showTransferMultiModal');
            $(".leadCountTxt").text(leadCountTxt);
            $("#show_addbucket_multimodal").modal('show');

            $.post(base_url+'plugins/get_campaigns_list',
                function(msg){
                   $('#addcampaignId').html(msg);
            });
        }
        else
        {
            alert("Select a lead to add to bucket.");
        }
    }
    //perform action to publish leads
    function add_to_bucket()
    {
        var leadsCsv = '';
        var campaignId = $('#addcampaignId option:selected').val();
        if(campaignId=="")
            return;
        $('#leads_data').find('tr > td:first-child input:checkbox')
        .each(function(){
            var temp = $(this).closest('tr').attr('id');
            var temp1 = temp.split('-');
            /* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
            var checkBox = $('#leadCheckBox-'+temp1[1]);
            console.log(checkBox.attr('id')); */
            if($('#leadCheckBox-'+temp1[1]).prop('checked'))
            {
            leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
            }
        });
        dataString = "leads="+leadsCsv+"&campaignId="+campaignId;
        //alert(dataString);
        $.post(base_url+'plugins/add_to_bucket',dataString,
        function(msg){
            //alert(msg);
            //$("#transferMultiModal").modal('hide');
            /*$('#sample-table-2').find('tr > td:first-child input:checkbox')
            .each(function(){
            if(this.checked)
                $(this).closest('tr').remove();
            });*/
            $("#add_success").html('<div class="alert alert-success">Added to bucket successfully.</div>');
            setTimeout(function(){location.reload();},1500); 
        });
    }
