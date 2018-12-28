<title><?php echo $pagetitle; ?></title>
<div class="main">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3><?php echo $pagetitle; ?></h3>
				<?php if($statustype!=0){ ?>
					<span class="completed"><?php echo "проект завершен на ".$percent_complete_project."%"; ?></span>
				<?php } ?>
				</div>
				<?php if($statustype==0){ ?>
					<div class="col-xs-12 page-title"><h3><?php echo $private_message; ?></h3></div>
				<?php }else{ 
				?>
				<div class="col-xs-12 info-block">
					<div class="col-xs-8">
						<div class="content">
							<table class="info-project">
								<tr><td>Название</td><td><?php echo $title; ?></td></tr>
								<tr><td>Менеджер</td><td><a href="/diplom/user?user_id=<?php echo $id_manager; ?>"><?php echo $manager; ?></a></td></tr>
								<?php if(!empty($about)){ ?>
								<tr><td>О проекте</td><td><?php echo $about; ?></td></tr>
								<?php } ?>
								<tr><td>О проекте</td><td></td></tr>
								<tr><td>Проект по специальности</td><td><?php echo $position_name; ?></td></tr>
								<tr><td>Уровень доступа к проекту</td><td><?php echo $text_privacy; ?></td></tr>
								<tr><td>Состояние проекта</td><td><?php echo $text_active[$active]; ?></td></tr>
								<?php if(!empty($summa)){ ?>
								<tr><td>Стоимость</td><td><?php echo $summa; ?></td></tr>
								<?php } ?>
								<tr><td>Дата начала</td><td><?php echo $date_start; ?></td></tr>
								<tr><td>Конечный срок</td><td><?php echo $date_end; ?></td></tr>
							</table>
						</div>
					</div>
					<div class="col-xs-4 project-users">
						<div class="content">
							<div class="col-xs-12 title">Участники проекта:</div>
							<?php if($statustype==2){ ?>

								<?php if(count($participants)){ ?>
									<div class="overflow_users_block">
										<?php foreach($participants as $key){ ?>
											<div class="user-item">
												<a href="/diplom/user?user_id=<?php echo $key['id_user']; ?>"><img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png"><span><?php echo $key['login']." (".$key['position_name'].")"; ?></span></a>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
								<?php }else{
									echo $private_message;
								} ?>
						</div>
					</div>
				</div>
				<div class="col-xs-12 page-title"><h3>Задачи проекта:</h3></div>
				<div class="col-xs-12 tasks-block"> 
					
				<?php if($statustype==2){
					?>
					
					<?php
						if(count($tasks)>0){ 
							//print_r($tasks);
							for($i=0;$i<count($tasks);$i++){
								?>
								<div class="col-xs-3 task-item task" data-task="<?php echo $tasks[$i]['id']; ?>"><div class="content"><?php echo $tasks[$i]['title']." ".$tasks[$i]['percent_complete']."%"; ?><div class="progress-line" style="width:<?php echo $tasks[$i]['percent_complete']."%"; ?>"></div></div></div>
								
								
								<?php
							}
							
						} else { ?>
							<div class="col-xs-12 page-title">Задачи отсутствуют</div>
						<?php }	?>
						
					<?php
					}else{
						echo $private_message;
					}
					?>
					</div>
					<div class="col-xs-12 gantti_block">
						<?php if($statustype==2){
							echo $gantti; 
						} ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--<div class="wrap">
	<div class="modalwin">
		<div class="modal-top"><div class="modal-close"></div></div>
		<div class="modal-content"></div>
	</div>
</div>-->