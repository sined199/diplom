<div class="main-task" data-task="<?php echo $id_task; ?>">
	<center><h3><?php echo $info['title']; ?></h3></center>
	<div class="info-task">
		<table>
		<tr><td>Выполнено</td><td><?php echo $percent_complete; ?>%</td></tr>
		<tr><td>Исполнитель</td><td><a class="hrefelement" href="/diplom/user?user_id=<?php echo $info['id_user']; ?>"><?php echo $login." (".$info['name'].")";?></a>
	<?php if($status_project!=2 && $info['status']!=2){ ?>
		<button data-task="<?php echo $id_task; ?>" class="action-btn selectanotheruser">Выбрать другого исполнителя</button>
	<?php } ?></td></tr>
		<tr><td>Статус исполнителя</td><td><?php echo $text_status_user[$info['status_user']]; ?></td></tr>
		<tr><td>О задаче</td><td><?php if(!empty($info['about'])) echo $info['about']; else echo $nodata; ?></td></tr>
		<tr><td>Уровень доступа к задаче</td><td><?php echo $text_privacy[$info['privacy']]; ?></td></tr>
		<tr><td>Дата завершения</td><td><?php echo $info['date_end']; ?></td></tr>
		<tr><td>Проект</td><td><a class="hrefelement" href="/diplom/projects/view?id=<?php echo $id_project; ?>"><?php echo $title_project; ?></a></td></tr>
		<tr><td>Состояние задачи</td><td><?php echo $text_status_task[$info['status']]; ?></td></tr>
		<tr><td>Комментарий от менеджера</td><td><?php if(!empty($info['comment'])) echo $info['comment']; else echo $nodata; ?></td></tr>
		</table>
	</div>
	<div id="tasks">
		<center><span class="pretitle">Работы:</span></center>
		<!--<input type="button" name="addminitask" value="Добавить поле">-->
		<div id="tasks-block">
			<?php if(count($tasks)>0){ ?>
				<div class="tasks-block">
					<?php foreach($tasks as $key){ ?>
						<div class="minitask-item">
							<span class="minitask"><?php echo $key['title']; ?></span>
							<?php if($status_project!=2){ ?>
								<div class="buttons-block">
									<?php if($info['status']!=2){ ?>
										<button data-minitask="<?php echo $key['id']; ?>" class="action-btn deleteminitask">Удалить</button>
									<?php } ?>
									<?php if($key['completed']==0){ ?>
										<?php if($info['status']!=2){ ?>
											<button  data-minitask="<?php echo $key['id']; ?>" class="action-btn completeminitask">Завершить</button>
										<?php } else{ ?>
											Не завершено
										<?php } ?>
									<?php } else { ?>
										<?php if($info['status']!=2){ ?>
											<button  data-minitask="<?php echo $key['id']; ?>" class="action-btn resetminitask">Восстановить</button>
										<?php } else{  ?>
											Завершено
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
<?php if($status_project!=2){ ?>
	<?php if($info['status']==2){ ?>
		<button class="action-btn" id="deletetask" data-task="<?php echo $id_task; ?>">Удалить задачу</button>
	<?php } ?>
	<?php if($info['status']!=2){ ?>
		<button class="action-btn" id="completetask" data-task="<?php echo $id_task; ?>">Закончить досрочно</button>
	<?php } ?>
<?php } ?>
</div>