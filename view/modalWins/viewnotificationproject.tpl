<form>
	<div class="notification-text">
		<span><a href="/diplom/user?user_id=<?php echo $id_user; ?>"><?php echo $login; ?></a> хочет пригласить вас в свой проект - <a href="/diplom/projects/view?id=<?php echo $id_project; ?>"><?php echo $title; ?></a></span>
	</div>
	<div class="notification-buttons">
		<input type="button" class="action-btn" data-n="<?php echo $id_notification; ?>" data-win="modal" name="accept_invite" value="Принять">
		<input type="button" class="action-btn" data-n="<?php echo $id_notification; ?>" data-win="modal" name="cancel_invite" value="Отказаться">
	</div>
</form>