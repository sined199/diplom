<div class="selected_block">
	<div class="selected">
		<?php foreach($selected as $key){ ?> 
			<div class="selected_item" data-user="<?php echo $key['id']; ?>">
				<span><?php echo $key['login']; ?></span>
				<select name="select_position">
					<?php foreach($positions as $key_p) { ?>
						<option value="<?php echo $key_p['id']; ?>"><?php echo $key_p['name'];?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>
	</div>
	<button data-project="<?php echo $id_project; ?>" class="action-btn" id="add_users">Добавить пользователей</button>
</div>