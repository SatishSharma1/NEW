jQuery(function($) {
			
			
			/**Fetching Notes for individual Leads**/
			$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					//alert(temp1[1]);
					data = "leadsId="+temp1[1];
					$.get(base_url+'lead/getIndividualNotes',data,function(notes){
						$("#notes1-"+temp1[1]).html(notes);
					});
					$.get(base_url+'lead/getTotleIndividualNotes',data,function(notes){
						$("#notesCount-"+temp1[1]).html(notes);
					});
					$.get(base_url+'lead/getLastNotesTime',data,function(notes){
						$("#notesupdatedTime-"+temp1[1]).html(notes);
					});
				});
			/**End of Fetching Notes**/
			
			
			$("#sample-table-2").tablesorter({headers: { 0:{sorter: false}}}); //table shorting plgin
			
			$(".chzn-select").css('width','150px').chosen({allow_single_deselect:true , no_results_text: "No such state!"})
			.on('change', function(){
				$(this).closest('form').validate().element($(this));
			});
				/* var oTable1 = $('#sample-table-2').dataTable( {
				"aoColumns": [
			      { "bSortable": false },
			      null, null,null, null, null, null, null,
				  { "bSortable": false }
				] } ); */
				
				
				
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
			
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				
				
				//chosen plugin inside a modal will have a zero width because the select element is originally hidden
				//and its width cannot be determined.
				//so we set the width after modal is show
				$('#modal-form').on('show', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '200px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
				})
				/**
				//or you can activate the chosen plugin after modal is shown
				//this way select element becomes visible with dimensions and chosen works as expected
				$('#modal-form').on('shown', function () {
					$(this).find('.modal-chosen').chosen();
				})
				*/
				$('.date-picker').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

			})
			$('#modal_table tbody tr').click(function(){
					var the=$(this);
					var img_src=$(this).find('img').attr('src');
					var res_name=the.find("td:eq(1)").text();
					$('#r_img').attr('src',img_src);
					$('#r_txt').text(res_name);
					//$('.popup_Divbg').remove(); commented by nitika
					$('#res_edit').css('display','none');
			});	
			
			/*single checkbox click event*/
			$('table td input:checkbox').on('click' , function(){
				var that = this;
				var temp = $(this).closest('tr').attr('id');
				var temp1 = temp.split('-');
				//alert(temp1[1]);
				$('#lead-'+temp1[1]).val(that.checked);
				
				var leadCount = parseInt($('#leadsCount').val());
				leadCount = (that.checked)?++leadCount:--leadCount;
				$('#leadsCount').val(leadCount);
			});
			/*end*/
			/*all check box select event */
			$('table th input:checkbox').on('click' , function(){
			
				var that = this;
				$(this).closest('table').find('tr > td:first-child input:checkbox')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					$('#lead-'+temp1[1]).val(that.checked);
					this.checked = that.checked;
					
					var leadCount = parseInt($('#leadsCount').val());
					if(that.checked)
						++leadCount;
					else if(!that.checked && leadCount>0)
						--leadCount;
					//alert(leadCount);
					$('#leadsCount').val(leadCount);
					//$(this).closest('tr').toggleClass('selected');
				});
			});
			function showDeleteMultiModal()
			{
				//alert($('#leadsCount').val());
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#deleteMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select At-least one record to Delete.");
				}
			}
			function showTransferMultiModal()
			{
				//alert($('#leadsCount').val());
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#transferMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select At-least one record to Transfer.");
				}
			}
			function transferMultiLeads()
			{
				var leadsCsv = '';
				var counselorId = $('#counselor option:selected').val();
				if(counselorId=="")
					return;
				$('#sample-table-2').find('tr > td:first-child input:hidden')
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
				$.post(base_url+'lead/transferMultiLead',dataString,
				function(msg){
					//alert(msg);
					$("#transferMultiModal").modal('hide');
					$('#sample-table-2').find('tr > td:first-child input:checkbox')
					.each(function(){
					if(this.checked)
						$(this).closest('tr').remove();
					});
					$("#SuccessTransferModal").modal('show');
					setTimeout(function(){$("#SuccessTransferModal").modal('hide');location.reload();},1200); 
				});
			}
			function showSmsMultiModal()
			{
				var count = parseInt($('#leadsCount').val());
				if(count)
				{
					var leadCountTxt = (count>1)?count+" Leads":count+" Lead";
					$(".leadCountTxt").text(leadCountTxt);
					$("#smsMultiModal").modal('show');
				}
				else
				{
					$("#error").text("Select Atleast one record to Delete.");
				}
			}
			
			function smsMultiLeads()
			{
				var leadsCsv = '';
				$('#sample-table-2').find('tr > td:first-child input:hidden')
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
				$.post(base_url+'lead/smsMultiLead',dataString,
				function(msg){
					//alert(msg);
					$("#smsMultiModal").modal('hide');
					$("#SuccessSmsModal").modal('show');
					$("#responseSms").text(msg);
					setTimeout(function(){$("#SuccessSmsModal").modal('hide');},3200);
				});
			}
			function deleteMultiLeads()
			{
				var leadsCsv = '';
				
				$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					/* alert($('#leadCheckBox-'+temp1[1]).attr('id'));
					var checkBox = $('#leadCheckBox-'+temp1[1]);
					console.log(checkBox.attr('id')); */
					if($('#leadCheckBox-'+temp1[1]).prop('checked'))
					{
					leadsCsv += (leadsCsv!="")?","+temp1[1]:temp1[1];
					//alert(temp1[1]);
					}
				});
				dataString = "leads="+leadsCsv;
				//alert(dataString);
				$.post(base_url+'lead/deleteMultiLead',dataString,
				function(msg){
					//alert(msg);return;
					$("#deleteMultiModal").modal('hide');
					$('#sample-table-2').find('tr > td:first-child input:checkbox')
					.each(function(){
					if(this.checked)
						$(this).closest('tr').remove();
					});
					$("#SuccessDeleteModal").modal('show');
					setTimeout(function(){$("#SuccessDeleteModal").modal('hide');},1200);
				});
			}
			$("#action").change(function(){
				if($("#action option:selected").val()=='transfer')
				{
					$("#counselor").show();
					$("#smsTemplates").hide();
				}
				else if($("#action option:selected").val()=='delete')
				{
					$("#counselor").hide();
					$("#smsTemplates").hide();
				}
				else
				{
					$("#counselor").hide();
					$("#smsTemplates").show();
				}
			});
			function performAction()
			{
				if($("#action option:selected").val()=='transfer')
				{
					if($("#counselor option:selected").val()!='')
					{
						showTransferMultiModal();
					}
					else
					{
						$("#error").text("Select Counselor First.");
					}
				}
				else if($("#action option:selected").val()=='delete')
				{
					showDeleteMultiModal();
				}
				else if($("#action option:selected").val()=='sms')
				{
					if($("#smsTemplates option:selected").val()!='')
					{
						showSmsMultiModal();
					}
					else
					{
						$("#error").text("Select Template First.");
					}
				}
				else
				{
					$("#error").text("Select Action First.");
				}
			}
			// code commented by deepak sharma
			
			// jQuery(function($) {
			
			// /**Fetching Notes for individual Leads**/
			// $('#sample-table-2').find('tr > td:first-child input:hidden')
				// .each(function(){
					// var temp = $(this).closest('tr').attr('id');
					// var temp1 = temp.split('-');
					//alert(temp1[1]);
					// data = "leadsId="+temp1[1];
					// $.get(base_url+'lead/getIndividualNotes',data,function(notes){
						// $("#notes-"+temp1[1]).html(notes);
					// });
				// });
			// /**End of Fetching Notes**/
			
			
			
			// $("#sample-table-2").tablesorter({headers: { 0:{sorter: false}}}); //table shorting plgin
			
			// $(".chzn-select").css('width','150px').chosen({allow_single_deselect:true , no_results_text: "No such state!"})
			// .on('change', function(){
				// $(this).closest('form').validate().element($(this));
			// });
				// /* var oTable1 = $('#sample-table-2').dataTable( {
				// "aoColumns": [
			      // { "bSortable": false },
			      // null, null,null, null, null, null, null,
				  // { "bSortable": false }
				// ] } ); */
				
				
				//$('table th input:checkbox').on('click' , function(){
				//alert("sdfds");
				// $('table th input:checkbox').on('click' , function(){
					// var that = this;
					// $(this).closest('table').find('tr > td:first-child input:checkbox')
					// .each(function(){
						// this.checked = that.checked;
						// $(this).closest('tr').toggleClass('selected');
					// });
						
				// });
			
			
				// $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				// function tooltip_placement(context, source) {
					// var $source = $(source);
					// var $parent = $source.closest('table')
					// var off1 = $parent.offset();
					// var w1 = $parent.width();
			
					// var off2 = $source.offset();
					// var w2 = $source.width();
			
					// if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					// return 'left';
				// }
				// $('#modal-form input[type=file]').ace_file_input({
					// style:'well',
					// btn_choose:'Drop files here or click to choose',
					// btn_change:null,
					// no_icon:'icon-cloud-upload',
					// droppable:true,
					// thumbnail:'large'
				// })
				
				//chosen plugin inside a modal will have a zero width because the select element is originally hidden
				//and its width cannot be determined.
				//so we set the width after modal is show
				// $('#modal-form').on('show', function () {
					// $(this).find('.chosen-container').each(function(){
						// $(this).find('a:first-child').css('width' , '200px');
						// $(this).find('.chosen-drop').css('width' , '210px');
						// $(this).find('.chosen-search input').css('width' , '200px');
					// });
				// })
				// /**
				//or you can activate the chosen plugin after modal is shown
				//this way select element becomes visible with dimensions and chosen works as expected
				// $('#modal-form').on('shown', function () {
					// $(this).find('.modal-chosen').chosen();
				// })
				// */
				// $('.date-picker').datepicker().next().on(ace.click_event, function(){
					// $(this).prev().focus();
				// });
				// $('#modal_table tbody tr').click(function(){
					// var the=$(this);
					// var img_src=$(this).find('img').attr('src');
					// var res_name=the.find("td:eq(1)").text();
					// $('#r_img').attr('src',img_src);
					// $('#r_txt').text(res_name);
					//$('.popup_Divbg').remove(); commented by nitika
					// $('#res_edit').css('display','none');
				// });	

			// })
			
			
			
			
			// code commented by deepak sharma
			
			