<form name="form-addproject">
	<span>Название проекта<label class="required">*</label></span>
	<input type="text" name="title">
	<span>О проекте<label class="required">*</label></span>
	<textarea rows="7" resize="noresize" name="about"></textarea>
	<span>Уровень доступа к проекту<label class="required">*</label> <label data-question="privacy_project" class="question">?</label></span>
	<select name="privacy">
		<option value="0" checked>Закрытый</option>
		<option value="1">Ограниченый</option>
		<option value="2">Общедоступный</option>
	</select>
	<span>Категория проекта<label class="required">*</label></span>
	<div>
		<select name="id_position">
		<?php foreach($positions as $key){ ?>
			<option value="<?php echo $key['id']; ?>"><span><?php echo $key['name']; ?></span></option>
		<?php } ?>
		</select>
	</div>
	<span>Стоимость проекта</span>
	<input type="text" name="summa">
	<span>Дата начала проекта<label class="required">*</label></span>
	<input type="text" name="date_start">
	<span>Дата завершения проекта<label class="required">*</label></span>
	<input type="text" name="date_end">
	<span class="helper">Поля помеченные <label class="required">*</label> обязательны к заполнению</span>
	<input type="button" name="btn-addproject" class="action-btn" value="Сохранить проект">
</form>
<script>
	$("input[name='date_start']").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
	$("input[name='date_end']").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
</script>