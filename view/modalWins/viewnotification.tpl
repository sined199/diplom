<?php if(count($notifications)>0){ ?>
	<?php foreach($notifications as $key){ ?>
		<form data-n="<?php echo $key['id_notification']; ?>">
			<div class="notification-text">
				<?php if($key['type']!=3){ ?>
					<a href="/diplom/user?user_id=<?php echo $key['id_user']; ?>"><?php echo $key['login']; ?></a>
					<?php echo $text_invite[$key['type']]; ?>
					<?php if($key['type']==1){ ?>
					<a href="/diplom/projects/view?id=<?php echo $key['id_item']; ?>"><?php echo $key['title']; ?></a>
					<?php } else { ?>
					<span class="task" data-task="<?php echo $key['id_item']; ?>"><?php echo $key['title']; ?></span>
					<?php } ?>
				<?php } else { ?>
					<?php echo $text_event[3][0]; ?><a class="hrefevent task" data-task="<?php echo $key['id_item']; ?>"><?php echo $key['title'];?></a><?php echo $text_event[3][1]; ?>
				<?php } ?>
			</div>
			<div class="notification-buttons">
				<?php if($key['type']!=3){ ?>
					<input class="col-xs-6" data-n="<?php echo $key['id_notification']; ?>" type="button" name="accept_invite" value="Принять">
					<input class="col-xs-6" data-n="<?php echo $key['id_notification']; ?>" type="button" name="cancel_invite" value="Отказаться">
				<?php } else { ?>
					<!--<input type="button" class="action-btn btn-ok" data-not="<?php echo $key['id_notification']; ?>" value="Ok">-->
				<?php } ?>
			</div>
		</form>
	<?php } ?>
<?php } else { ?>
	<center>Уведомления отсутствуют</center>
<?php } ?>