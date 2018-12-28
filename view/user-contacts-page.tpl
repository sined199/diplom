<title><?php echo $pagetitle; ?></title>
<div class="main">
	<div class="main-panel">
		<div class="container">
			<div class="content">
			<div class="col-xs-12 page-title"><h3><?php echo $pagetitle; ?></h3><span class="count_contacts">Контактов - <?php echo $count_contacts ?></span></div>
			<?php if(count($contacts)>0){ ?> 
				<?php foreach($contacts as $key){ ?>
					<div class="col-xs-6 user-element" data-user="<?php echo $key['id']; ?>">
						<div class="content">
							<?php echo $is_online; ?>
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
			<?php } ?>
			</div>
		</div>
	</div>
</div>
