<title><?php echo $pagetitle; ?></title>
<div class="main">

	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3><?php echo $text_myproject; ?></h3></div>
				<div class="col-xs-12">
					<div class="col-xs-3 project-item"><div class="content add-project">+</div></div>
				<?php 
					if(count($projects)>0){
						for($i=0;$i<count($projects);$i++){
							?>
							<div class="col-xs-3 project-item">
								<div class="content">
									<a href="/diplom/projects/view?id=<?php echo $projects[$i]['project']['id']; ?>"><span class="title"><?php echo $projects[$i]['project']['title'];?></span></a>
									<?php if($projects[$i]['project']['privacy']==0) { ?>
									<div class="icon-padlock"></div>
									<?php } ?>
									<span class="leader"><?php echo $projects[$i]['manager'];?></span>
									<span class="leader"><?php echo $projects[$i]['project']['name'];?></span>
									<span class="summa"><?php echo $text_active[$projects[$i]['project']['active']];?></span>
									<span class="deadline"><?php echo $projects[$i]['project']['date_end'];?></span>
								</div>
							</div>
							<?php
						}
					}
				?>
				
				</div>
				
				<?php if(count($inProjects)>0){ ?>
					<div class="col-xs-12 page-title"><h3><?php echo $text_inProjects; ?></h3></div>
					<div class="col-xs-12">
					<?php 
					if(count($inProjects)>0){
						for($i=0;$i<count($inProjects);$i++){
							?>
							<div class="col-xs-3 project-item">
								<div class="content">
									<a href="/diplom/projects/view?id=<?php echo $inProjects[$i]['project']['id']; ?>"><span class="title"><?php echo $inProjects[$i]['project']['title'];?></span></a>
									<?php if($inProjects[$i]['project']['privacy']==0) { ?>
									<div class="icon-padlock"></div>
									<?php } ?>
									<span class="leader"><?php echo $inProjects[$i]['manager'];?></span>
									<span class="leader"><?php echo $inProjects[$i]['project']['name'];?></span>
									<span class="summa"><?php echo $text_active[$inProjects[$i]['project']['active']];?></span>
									<span class="deadline"><?php echo $inProjects[$i]['project']['date_end'];?></span>
								</div>
							</div>
							<?php
						}
					}
					?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--<div class="wrap">
	<div class="modalwin">
		<div class="modal-top"><div class="modal-close"></div></div>
		<div class="modal-content"></div>
	</div>
</div>-->