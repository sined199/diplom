<?php if(!count($contacts)>0) { ?>
<p>Подходящих, для проекта, контактов нет</p>
<?php }else{ ?>
<div>
	<form name="select_users_form">
		<div class="select_users_block">
			<?php foreach($contacts as $key){ ?>
				<div class="select_user_item">
					<input type="checkbox" name="contact" value="<?php echo $key['id']; ?>"><span><?php echo $key['login']; ?></span>
				</div>
			<?php } ?>
		</div>
	<input type="button" class="action-btn" data-project="<?php echo $id_project; ?>" name="select_users" value="Выбрать пользователей">
	</form>
</div>
<?php } ?>