<title><?php echo $search_request_text; ?></title>
<div class="main">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3><?php echo $search_request_text; ?></h3>
				<span>Найдено в результате поиска - <?php echo $all_count_result; ?> </span>
				</div>
				<div class="filters col-xs-3">
					<div class="content">
						<span class="filter-title">Фильтр поиска:</span>
						<form name="filter">
							<div class="filter-block">
								<div class="filter_block_item">
									<span>Категории:</span><br>
									<?php foreach($positions as $key){ ?>
										<?php if(in_array($key['id'],$position_ids)){ ?>
										<input type="checkbox" checked="checked" name="position_filter" value="<?php echo $key['id']; ?>">
										<?php } else { ?>
											<input type="checkbox" name="position_filter" value="<?php echo $key['id']; ?>">
										<?php } ?>
										<span><?php echo $key['name']; ?></span><br>
									<?php } ?>
								</div>
								<div class="filter_block_item">
								<span>Промежуток времени:</span><br>
									<?php if(!empty($date_start)){ ?>
									<input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="Дата начала проекта">
									<?php } else { ?>
									<input type="text" name="date_start" placeholder="Дата начала проекта">
									<?php } ?>
									
									<?php if(!empty($date_end)){ ?>
									<input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="Дата окончани проекта">
									<?php } else { ?>
									<input type="text" name="date_end" placeholder="Дата окончани проекта">
									<?php } ?>
								</div>
								<div class="filter_block_item">
									<?php if($only_active){ ?>
									<input type="checkbox" name="only_active" checked="checked" value="1">
									<?php } else{ ?>
									<input type="checkbox" name="only_active" value="1">
									<?php } ?>
									<span>Только активные <label data-question="active_project" class="question">?</label></span>
								</div>
							</div>
							<input type="button" class="action-btn" name="filter_search" value="Фильтровать">
						</form>
					</div>
				</div>
				<div class="col-xs-9">
					<?php if(count($s_projects)>0){ ?>
						<?php foreach($s_projects as $key){ ?>
							<div class="col-xs-4 project-item">
								<div class="content">
									<a href="/diplom/projects/view?id=<?php echo $key['id']; ?>"><span class="title"><?php echo $key['title'];?></span></a>
									<span class="leader"><?php echo $key['login'];?></span>
									<span class="leader"><?php echo $key['name'];?></span>
									<span class="summa"><?php echo $text_active[$key['active']];?></span>
									<span class="deadline"><?php echo $key['date_end'];?></span>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("input[name='date_start']").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
	$("input[name='date_end']").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
	$("input[name='filter_search']").click(function(){
		page = "/diplom/search?search_type=projects&request=<?php echo $request ?>";
		arr_selected_positions = new Array();
		$("form[name='filter']").find("input[name='position_filter']").each(function(){
			if($(this).is(":checked")){
				arr_selected_positions.push($(this).val());
			}
		})
		if(arr_selected_positions.length>0){
			get_position_filter = arr_selected_positions.join(",");
			page += "&position_filter="+get_position_filter;
		}
		if($("input[name='date_start']").val()!=''){
			page += "&date_start="+$("input[name='date_start']").val();
		}
		if($("input[name='date_end']").val()!=''){
			page += "&date_end="+$("input[name='date_end']").val();
		}
		if($("input[name='only_active']").is(":checked")){
			page += "&only_active=1";
		}

		/*var date1=new Date($("input[name='date_start']").val());
	    var date2=new Date($("input[name='date_end']").val());
	    var oneDay=1000*60*60*24;
	    var rez=Math.floor((date2-date1)/oneDay);

		console.log(rez);*/

		$.ajax({
			url:page,
			type:"post",
			datatype:"html",
			data:{'1':'1'},
			success:function(data){
				$("#content-page").html(data);
				setLocation(page);
			}
		})

	})
	function setLocation(curLoc){
	    try {
	      history.pushState(null, null, curLoc);
	      return;
	    } catch(e) {}
	    location.hash = '#' + curLoc;
	}
</script>