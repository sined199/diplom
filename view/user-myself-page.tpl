<title><?php echo $pagetitle; ?></title>
<div class="main">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 profile">
					<div class="content">
						<div class="col-xs-3">
							<img class="photo-user <?php if($is_online) echo 'online'; ?>" src="http://localhost/diplom/view/images/icons/unknow-user.png">
						</div>
						<div class="profile-info col-xs-9">
							<div class="col-xs-6 info">
								<h3>Основная информация:</h3>
								<span class="login"><?php echo $login; ?></span><br>
								<span class="name"><?php echo $info['name'];?></span>
								<span class="surname"><?php echo $info['surname'];?></span>
								<br>
								<span class="country"><?php echo $info['country']; ?></span>
								<span class="city"><?php echo $info['city']; ?></span>
							</div>
							<div class="col-xs-6 contact_info">
								<h3>Контактная информация:</h3>
								<span class="mobile"><a href="tel:<?php echo $info['number_mobile']; ?>"><?php echo $info['number_mobile']; ?></a></span>
								<br>
								<span class="email"><a href="mailto:<?php echo $info['email_for_contact']; ?>"><?php echo $info['email_for_contact']; ?></a></span>
							</div>
						</div>
					</div>
				</div>
				<?php if($statistics['projects']['allcount']>0 || $statistics['tasks']['allcount']>0){ ?>
				<div class="col-xs-12 statictics">
					<div class="content">
						<?php if($statistics['projects']['allcount']>0){ ?>
						<div class="page-title">
							<h3>Проекты</h3>
							<span>Общее кол-во - <?php echo $statistics['projects']['allcount']; ?></span><br>
							<span>Завершенные - <?php echo $statistics['projects']['readycount']; ?></span><br>
							<span>Управление - <?php echo $statistics['projects']['maincount']; ?></span>
						</div>
						<?php } ?>
						<?php if($statistics['tasks']['allcount']>0){ ?>
						<div class="page-title">
							<h3>Задачи</h3>
							<span>Общее кол-во - <?php echo $statistics['tasks']['allcount']; ?></span><br>
							<span>Завершенные - <?php echo $statistics['tasks']['readycount']; ?></span>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<div class="col-xs-12 ref-link">
					<div class="content">
						<?php if(empty($key)){ ?>
						Реферальная ссылка отстуствует - <button id="createrefurl">Создать</button>
						<?php } else { ?>
							Ваша ссылка - <?php echo $url; ?>
						<?php } ?>
						<br>
						Кол-во зарегистрировавшихся по вашей ссылке - <?php echo $countInvited; ?>
						
					</div>
					<?php if(count($invited)>0){ ?>
						<div class="">
							<?php for($i=0;$i<count($invited); $i++){ ?>
								<div class="user-element" style="padding: 0 0 10px;">
									<div class="content">
										<img class="photo-user" src="http://localhost/diplom/view/images/icons/unknow-user.png">
										<span class="login-user">
											<a href="/diplom/user?user_id=<?php echo $invited[$i]['id']; ?>"><?php echo $invited[$i]['login']; ?></a>
										</span>
										<span class="pull-right"><?php echo $invited[$i]['date_activation']; ?></span>
									</div>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>