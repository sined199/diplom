<div class="block-anotheruser">
	<div class="another_block">
	<?php foreach($users as $key){ ?>
		<div class="another_item">
			<?php if($id_user == $key['id_user']){ ?>
			<input type="radio" value="<?php echo $key['id_user']; ?>" name="selectuser" checked="checked">
			<?php } else { ?>
			<input type="radio" value="<?php echo $key['id_user']; ?>" name="selectuser">
			<?php } ?>
			<span><?php echo $key['login']." (".$key['position_name'].")"; ?></span>
		</div>
	<?php } ?>
	</div>
	<button data-task="<?php echo $id_task; ?>" class="action-btn saveanotheruser">Сохранить</button>
</div>