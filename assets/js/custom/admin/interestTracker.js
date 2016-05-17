jQuery(function($) {
			
			/**Fetching campaign for individual Leads**/
			$('#sample-table-2').find('tr > td:first-child input:hidden')
				.each(function(){
					var temp = $(this).closest('tr').attr('id');
					var temp1 = temp.split('-');
					data = "leadsId="+temp1[1];
					
					$.get(base_url+'campaign/getCampaign',data,function(datas){
						$("#status-"+temp1[1]).html(datas);
					});
				$.get(base_url+'lead/getIndividualNotes',data,function(notes){
						$("#notes1-"+temp1[1]).html(notes);
					});
				$.get(base_url+'lead/getTotleIndividualNotes',data,function(notes){
						$("#notesCount-"+temp1[1]).html(notes);
					});
					$.get(base_url+'lead/getLastNotesTime',data,function(notes){
						$("#notesupdatedTime-"+temp1[1]).html(notes);
					});
					
				$.get(base_url+'lead/getTransferedToName',data,function(datas){
						$("#transfered-"+temp1[1]).html(datas);
					});
					$.get(base_url+'lead/getTransferedToCampaignName',data,function(datas){
						$("#transferedCampaign-"+temp1[1]).html(datas);
					});
				
				});
			/**End of Fetching campaign**/
			
			
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

			})
			
			