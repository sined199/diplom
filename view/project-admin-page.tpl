<title><?php echo $pagetitle; ?></title>
<div class="main" data-project="<?php echo $id_project; ?>">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3><?php echo $pagetitle; ?></h3><span class="completed"><?php echo "проект завершен на ".$percent_complete_project."%"; ?></span></div>
				
				<div class="col-xs-12">
					<form name="form-projectaction">
						<?php if($active!='2'){ ?>
						<input type="button" data-project="<?php echo $id_project; ?>"  name="btn-addusers" class="action-btn" value="Добавить рабочих в проект">
						<?php } ?>
						<?php if($active=='0'){ ?> 
							<input type="button" data-project="<?php echo $id_project; ?>" name="btn-start" class="action-btn" value="Начать проект досрочно">
						<?php } ?>
						<?php if($active=='1'){ ?> 
							<input type="button" data-project="<?php echo $id_project; ?>" name="btn-stopproject" class="action-btn" value="Закончить проект досрочно">
						<?php } ?>
						<?php if($active=='2'){ ?> 
							<input type="button" data-project="<?php echo $id_project; ?>" name="btn-deleteproject" class="action-btn" value="Удалить проект">
						<?php } ?>
					</form>
					<div class="col-xs-6 info-block">
						<div class="content">
							<table class="info-project">
								<tr><td>Название</td><td><?php echo $title; ?></td></tr>
								<tr><td>Менеджер</td><td><a href="/diplom/user?user_id=<?php echo $id_manager; ?>"><?php echo $manager; ?></a></td></tr>
								<tr><td>О проекте</td><td><?php if(!empty($about)) echo $about; else echo $nodata; ?></td></tr>
								<tr><td>Проект по специальности</td><td><?php echo $position_name; ?></td></tr>
								<tr><td>Уровень доступа к проекту</td><td><?php echo $text_privacy; ?></td></tr>
								<tr><td>Состояние проекта</td><td><?php echo $text_active[$active]; ?></td></tr>
								<tr><td>Стоимость</td><td><?php if(!empty($summa)) echo $summa; else echo $nodata; ?></td></tr>
								<tr><td>Дата начала</td><td><?php echo $date_start; ?></td></tr>
								<tr><td>Конечный срок</td><td><?php echo $date_end; ?></td></tr>
								
							</table>
						</div>
					</div>
					<div class="col-xs-6 project-users">
						<div class="content">
							<div class="col-xs-12 title">Участники проекта:</div>
							<?php if(count($participants)>0){ ?>
								<div class="overflow_users_block">
								<?php foreach($participants as $key){ ?>
									<div class="user-item" data-user="<?php echo $key['id_user']; ?>">
										<a href="/diplom/user?user_id=<?php echo $key['id_user']; ?>"><img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png"><span><?php echo $key['login']." (".$key['position_name'].")"; ?></span></a>
										<?php if($active!='2'){ ?>
										<div class="user-button-block">
											<button data-user="<?php echo $key['id_user']; ?>" class="action-btn deleteuserfromproject" >Удалить</button>
										</div>
											
										<?php } ?>
									</div>
								<?php } ?>
								</div>
							<?php } ?>	
						</div>

						<?php if(count($deleteparticipants)>0){ ?>
						<div class="content">						
							<div class="col-xs-12 title">Удаленные участники:</div>
							<div class="overflow_users_block">
							<?php foreach($deleteparticipants as $key){ ?>
								<div class="user-item">
									<a href="/diplom/user?user_id=<?php echo $key['id_user']; ?>"><img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png"><span><?php echo $key['login']." (".$key['position_name'].")"; ?></span></a>
									<div class="user-button-block">
										<button data-user="<?php echo $key['id_user']; ?>" class="action-btn deleteuserfromproject" >Удалить</button>
										<button data-user="<?php echo $key['id_user']; ?>" class="action-btn resetuser">Восстановить</button>
									</div>
								</div>
							<?php } ?>
							</div>
						</div>
						<?php } ?>
						<?php if($active!='2'){ ?>
							<?php if(count($participantsNotActive)>0){ ?>
							<div class="content">
								<div class="col-xs-12 title">Ожидающие подтверждения:</div>
								<div class="overflow_users_block">
									<?php foreach($participantsNotActive as $key){ ?>
										<div class="user-item">
											<a href="/diplom/user?user_id=<?php echo $key['id_user']; ?>"><img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png"><span><?php echo $key['login']." (".$key['position_name'].")"; ?></span></a>
											<div class="user-button-block">
											<button  data-user="<?php echo $key['id_user']; ?>" class="action-btn deleteuserfromproject" >Удалить</button>
											<?php if($key['status']=='2'){ ?>
												<button  data-user="<?php echo $key['id_user']; ?>" class="action-btn inviteuser">Пригласить еще раз</button>
											<?php } ?>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
							<?php } ?>	
						<?php } ?>
					</div>
				</div>
				<div class="col-xs-12 page-title"> 
					<h3>Задачи проекта:</h3>
				</div>
				<div class="col-xs-12 tasks-block">
					<?php if($active!='2'){ ?>
						<div data-project="<?php echo $id_project;?>" class="col-xs-3 task-item add-maintask"><div class="content">+ Создать новую задачу</div></div>
					<?php } ?>
					<?php
						if(count($tasks)>0){ 
							for($i=0;$i<count($tasks);$i++){
								?>
								<div class="col-xs-3 task-item task" data-task="<?php echo $tasks[$i]['id']; ?>"><div class="content"><span class="task-title"><?php echo $tasks[$i]['title'];?></span><span class="task-percent"><?php echo $tasks[$i]['percent_complete']."%"; ?></span><div class="progress-line" style="width:<?php echo $tasks[$i]['percent_complete']."%"; ?>"></div></div></div>
								<?php
							}
							
						} ?>
				</div>
				<div class="col-xs-12 gantti_block">
					<?php echo $gantti; ?>
				</div>

			</div>
		</div>
	</div>
</div>