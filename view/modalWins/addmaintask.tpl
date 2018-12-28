<form name="form-addmaintask">
	<span>Название задачи<label class="required">*</label></span>
	<input type="text" name="title">
	<span>О задаче<label class="required">*</label></span>
	<textarea rows="7" name="about"></textarea>
	<span>Уровень доступа к задаче<label class="required">*</label> <label data-question="privacy_task" class="question">?</label></span>
	<select name="privacy">
		<option value="0" checked>Закрытый</option>
		<option value="1">Ограниченый</option>
		<option value="2">Общедоступный</option>
	</select>
	<span>Выбор исполнителя<label class="required">*</label></span>
	<select name="user">
		<?php if(count($users)){ ?>
			<?php foreach($users as $key){ ?>
			<option value="<?php echo $key['id_user']; ?>"><?php echo $key['login']."(".$key['position_name'].")"; ?></option>
			<?php } ?>
		<?php } ?>
	</select>
	<span>Дата завершения работ<label class="required">*</label></span>
	<input type="text" name="date_end">
	<span>Комментарий к задаче</span>
	<textarea rows="4" name="comment"></textarea>
	<span>Добавить работу<label class="required">*</label></span>
	<div id="tasks">
		<input type="button" class="action-btn" name="btn-addminitask" value="Добавить новую работу">
		<div id="tasks-block">
			<input type="text">
		</div>
	</div>
	<span class="helper">Поля помеченные <label class="required">*</label> обязательны к заполнению</span>
	<input type="button" class="action-btn" name="btn-addmaintask" data-project="<?php echo $id_project; ?>" value="Сохранить задачу">
</form>
<script>
	$("input[name='date_end']").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
	$("input[name='btn-addminitask']").click(function(){
		$("#tasks-block").prepend("<input type='text'>");
	})
</script>