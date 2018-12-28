<title><?php echo $pagetitle; ?></title>
<div class="main">

	<div class="main-panel">
		<div class="container">
			<div class="content">
				
				<div class="col-xs-6 profile-info-block">
				<div class="col-xs-12 page-title"><h3>Информация о пользователе</h3></div>
					<form>
						<div class="content">
							<table>
								<tr><td>Имя</td><td><span data-item="name" class="info-user-data"><?php 
										echo $info['name'];
									?></span><span class="edit-data">редактировать</span></td></tr>
								<tr><td>Фамилия</td><td><span data-item="surname" class="info-user-data"><?php 
										echo $info['surname'];
									?></span><span class="edit-data">редактировать</span></td></tr>
								<tr><td>Страна</td><td><span data-item="country" class="info-user-data"><?php 
										echo $info['country'];
									?></span><span class="edit-data">редактировать</span></td></tr>
								<tr><td>Город</td><td><span data-item="city" class="info-user-data"><?php 
										echo $info['city'];
									?></span><span class="edit-data">редактировать</span></td></tr>
								<tr><td>День рождения</td><td><span data-item="bday" class="info-user-data"><?php 
										if($info['bday']=="0000-00-00") echo ""; else echo $info['bday'];
									?></span><span class="edit-data">редактировать</span></td></tr>
							</table>
						</div>
					</form>
				</div>
				
				<div class="col-xs-6 contact-info">
				<div class="col-xs-12 page-title"><h3>Контактная информация</h3></div>
					<div class="content">
						<table>
							<tr><td>Номер телефона</td><td><span data-item="number_mobile" class="info-user-data"><?php  echo $info['number_mobile']; ?></span><span class="edit-data">редактировать</span></td></tr>
							<tr><td>Email адрес</td><td><span data-item="email_for_contact" class="info-user-data"><?php echo $info['email_for_contact']; ?></span><span class="edit-data">редактировать</span></td></tr>
						</table>
					</div>
				</div>
				<div class="col-xs-12 page-title"><h3>Специализация</h3></div>
				<div class="col-xs-4 position-block">
					<div class="content">
						<span class="title">Ваши специализации:</span>
						<div class="position-list">
							<?php for($i=0;$i<count($positions);$i++){ ?>		
								<div data-position="<?php echo $positions[$i]['id']; ?>"><?php echo $positions[$i]['name']; ?>(<?php echo $allpositions[array_search($positions[$i]['id_parent'], array_column($allpositions, 'id'))]['name']; ?>)<span class="delete-position">удалить</span></div>
							<?php } ?>
						</div>
						<input type="button" id="add-positions-btn" class="action-btn" value="Добавить новую специализацию">
						<div id="positions-area">
							<form name="positions">
								<div class="block">
									
								</div>
								<div class="button-block">
									
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>