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
						<form name="position_filter">
						<div class="filter-block">
							<span>Специальности:</span><br>
							<div class="block">
								<?php foreach($positions as $key){ ?>
									<?php if($key['id_parent'] == 0){ ?>
										<span><?php echo $key['name']; ?></span><br>
										<?php foreach($positions as $key_child){ ?>
											<?php if($key_child['id_parent'] == $key['id']){ ?>
												<?php if(in_array($key_child['id'],$position_ids)){ ?>
													<input type="checkbox" checked="checked" name="position_filter" value="<?php echo $key_child['id']; ?>">
												<?php } else { ?>
													<input type="checkbox" name="position_filter" value="<?php echo $key_child['id']; ?>">
												<?php } ?>
												<span><?php echo $key_child['name']; ?></span><br>
											<?php } ?>
										<?php } ?>
									<?php } ?>
									
									<!--<span><?php echo $key['name']; ?></span><br>-->
								<?php } ?>								
							</div>
							<?php if($only_online){ ?>
									<input type="checkbox" checked="checked" name="only_online_filter" value="1">
								<?php }else{ ?>
									<input type="checkbox" name="only_online_filter" value="1">
								<?php } ?>
							<span>Только Online</span>
						</div>
						<input type="button" class="action-btn" name="filter_search" value="Фильтровать">
						</form>
					</div>
				</div>
				<div class="col-xs-9">
				<?php foreach($s_users as $key){ ?>
					<div class="col-xs-6 user-element"><div class="content"><img class="<?php if($key['online'] > $timelastenter) echo "online"; ?> photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png"><span class="login-user"><a href="/diplom/user?user_id=<?php echo $key['id']; ?>"><?php echo $key['login']; ?></a></span>
					<?php   
							
						if($key['id']!=$id_user){ ?>
							<div class="buttons-block">
								<?php if(!$key['inContacts']){ ?>
									<button class="action-btn add-to-contact" data-user="<?php echo $key['id']; ?>">Добавить в контакты</button>
								<?php }else{ ?>
									<button class="action-btn deletefromcontacts" data-user="<?php echo $key['id']; ?>">Удалить с контактов</button>
								<?php } ?>
							</div>
					<?php } ?>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("input[name='filter_search']").click(function(){
		page = "/diplom/search?search_type=users&request=<?php echo $request ?>";
		arr_selected_positions = new Array();
		$("form[name='position_filter']").find("input[name='position_filter']").each(function(){
			if($(this).is(":checked")){
				arr_selected_positions.push($(this).val());
			}
		})
		if(arr_selected_positions.length>0){
			get_position_filter = arr_selected_positions.join(",");
			page += "&position_filter="+get_position_filter;
		}
		if($("input[name='only_online_filter']").is(":checked")){
			page += "&only_online=1";
		}
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