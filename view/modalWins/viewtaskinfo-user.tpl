<div class="main-task" data-task="<?php echo $id_task; ?>">
		<?php if($privacy==0){ ?>
			<center><h3><?php echo $text_privacy_warning[$privacy]; ?></h3></center>
		<?php } else if($privacy==1){ ?>
			<center><h3><?php echo $info['title']; ?></h3></center>
			<div class="info-task">
				<table>
					<tr><td>Исполнитель</td><td><a class="hrefevent" href="/diplom/user?user_id=<?php echo $info['id_user']; ?>"><?php echo $login." (".$info['name'].")";?></a></td></tr>
					<tr><td>Статус исполнителя</td><td><?php echo $text_status_user[$info['status_user']]; ?></td></tr>
					<tr><td>О задаче</td><td><?php echo $info['about']; ?></td></tr>
					<tr><td>Уровень доступа к задаче</td><td><?php echo $text_privacy[$info['privacy']]; ?></td></tr>
					<tr><td>Дата завершения</td><td><?php echo $info['date_end']; ?></td></tr>
					<tr><td>Проект</td><td><a class="hrefevent" href="/diplom/projects/view?id=<?php echo $id_project; ?>"><?php echo $title_project; ?></a></td></tr>
					<tr><td>Состояние задачи</td><td><?php echo $text_status_task[$info['status']]; ?></td></tr>
					<tr><td>Комментарий от менеджера</td><td><?php if(!empty($info['comment'])) echo $info['comment']; else echo $nodata; ?></td></tr>
				</table>
			</div>
			<div id="tasks">
				<center><span>Работы:</span></center>
				<?php echo $text_privacy_warning[$privacy]; ?>
			</div>
			<?php } else if($privacy==2){ ?>
			<center><h3><?php echo $info['title']; ?></h3></center>
			<div class="info-task">
				<table>
					<tr><td>Исполнитель</td><td><a class="hrefevent" href="/diplom/user?user_id=<?php echo $info['id_user']; ?>"><?php echo $login." (".$info['name'].")";?></a></td></tr>
					<tr><td>Статус исполнителя</td><td><?php echo $text_status_user[$info['status_user']]; ?></td></tr>
					<tr><td>О задаче</td><td><?php echo $info['about']; ?></td></tr>
					<tr><td>Уровень доступа к задаче</td><td><?php echo $text_privacy[$info['privacy']]; ?></td></tr>
					<tr><td>Дата завершения</td><td><?php echo $info['date_end']; ?></td></tr>
					<tr><td>Проект</td><td><a class="hrefevent" href="/diplom/projects/view?id=<?php echo $id_project; ?>"><?php echo $title_project; ?></a></td></tr>
					<tr><td>Состояние задачи</td><td><?php echo $text_status_task[$info['status']]; ?></td></tr>
					<tr><td>Комментарий от менеджера</td><td><?php if(!empty($info['comment'])) echo $info['comment']; else echo $nodata; ?></td></tr>
				</table>
			</div>
			<div id="tasks">
				<center><span class="pretitle">Работы:</span></center>
				<div id="tasks-block">
					<?php if(count($tasks)>0){ ?>
						<div class="tasks-block">
							<?php foreach($tasks as $key){ ?>
							<div class="minitask-item">
								<span class="minitask"><?php echo $key['title']; ?></span>
								<?php if($user_permission && $status_project==1){ ?>
									<?php if($key['completed']==0){ ?>
										<div class="buttons-block">
											<?php if($info['status']!=2){ ?>
											<button data-minitask="<?php echo $key['id']; ?>" class="action-btn completeminitask">Завершить</button>
											<?php } else { ?>
												Не завершено
											<?php } ?>
										</div>
									<?php } else { ?>
										<div class="buttons-block">
											Завершенно
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
</div>