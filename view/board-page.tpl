<title><?php echo $pagetitle; ?></title>
<div class="main">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<?php if(!empty($notification)){ ?>	
					<div class="col-xs-6 board-item">	
						<h2 class="block-title">Последнее уведомление</h2>
						<div class="board-notification-block">
							<form data-n="<?php echo $notification['id']; ?>">
								<div class="notification-text">
									<?php echo $text_login;?><a href="/diplom/user?user_id=<?php echo $notification['id_user']; ?>"><?php echo $notification['login']; ?></a>
									<?php echo $text_invite[$notification['type']]; ?>
									<?php if($notification['type']==1){ ?>
									<a href="/diplom/projects/view?id=<?php echo $notification['id_item']; ?>"><?php echo $notification['title']; ?></a>
									<?php } else { ?>
									<span class="task" data-task="<?php echo $notification['id_item']; ?>"><?php echo $notification['title']; ?></span>
									<?php } ?>
								</div>
								<div class="notification-buttons">
									<input class="col-xs-6" type="button" data-n="<?php echo $notification['id']; ?>" name="accept_invite" value="Принять">
									<input class="col-xs-6" type="button" data-n="<?php echo $notification['id']; ?>" name="cancel_invite" value="Отказаться">
								</div>
							</form>
						</div>
					</div>
				<?php } ?>
				<?php if(count($lastmyprojects)>0){ ?>
				<div class="col-xs-12 board-item ">
					<h2 class="block-title"><?php echo $text_myprojects; ?></h2>
					<?php for($i=0;$i<count($lastmyprojects);$i++){	?>
							<div class="col-xs-3 project-item ">
								<div class="content">
									<a href="/diplom/projects/view?id=<?php echo $lastmyprojects[$i]['project']['id']; ?>"><span class="title"><?php echo $lastmyprojects[$i]['project']['title'];?></span></a>
									<?php if($lastmyprojects[$i]['project']['privacy']==0) { ?>
									<div class="icon-padlock"></div>
									<?php } ?>
									<span class="leader"><?php echo $lastmyprojects[$i]['manager'];?></span>
									<span class="leader"><?php echo $lastmyprojects[$i]['project']['name'];?></span>
									<span class="summa"><?php echo $text_active[$lastmyprojects[$i]['project']['active']];?></span>
									<span class="deadline"><?php echo $lastmyprojects[$i]['project']['date_end'];?></span>
								</div>
							</div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php if(count($lastprojectswhereisset)>0){ ?>
					<div class="col-xs-12 board-item">
						<h2 class="block-title"><?php echo $text_projectswhereisset; ?></h2>
						<?php for($i=0;$i<count($lastprojectswhereisset);$i++){	?>
								<div class="col-xs-3 project-item">
									<div class="content">
										<a href="/diplom/projects/view?id=<?php echo $lastprojectswhereisset[$i]['project']['id']; ?>"><span class="title"><?php echo $lastprojectswhereisset[$i]['project']['title'];?></span></a>
										<?php if($lastprojectswhereisset[$i]['project']['privacy']==0) { ?>
											<div class="icon-padlock"></div>
										<?php } ?>
										<span class="leader"><?php echo $lastprojectswhereisset[$i]['manager'];?></span>
										<span class="leader"><?php echo $lastprojectswhereisset[$i]['project']['name'];?></span>
										<span class="summa"><?php echo $text_active[$lastprojectswhereisset[$i]['project']['active']];?></span>
										<span class="deadline"><?php echo $lastprojectswhereisset[$i]['project']['date_end'];?></span>
									</div>
								</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if(count($contacts)>0){ ?> 
					<div class="col-xs-12 board-item">
						<h2 class="block-title"><?php echo $text_lastcontacts; ?></h2>
						<?php foreach($contacts as $key){ ?>
							<div class="col-xs-4 user-element" data-user="<?php echo $key['id']; ?>">
								<div class="content">
									<img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png">
									<span class="login-user">
										<a href="/diplom/user?user_id=<?php echo $key['id']; ?>"><?php echo $key['login']; ?></a>
									</span>
									<div class="buttons-block">
										<button class="action-btn deletefromcontacts" data-user="<?php echo $key['id']; ?>">Удалить с контактов</button>
									</div>									
								</div>
							</div>
						<?php } ?>
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