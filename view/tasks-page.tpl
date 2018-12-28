<title><?php echo $pagetitle; ?></title>
<div class="main" data-project="<?php echo $id_project; ?>">
	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3><?php echo $pagetitle_worked; ?></h3></div>
				<div class="col-xs-12 tasks-block">
				<?php if(count($worked_tasks)>0){ 
					for($i=0;$i<count($worked_tasks);$i++){
						?>
						<div class="col-xs-3 task-item task" data-task="<?php echo $worked_tasks[$i]['id']; ?>"><div class="content"><span class="task-title"><?php echo $worked_tasks[$i]['title'];?></span><span class="task-percent"><?php echo $worked_tasks[$i]['percent_complete']."%"; ?></span><div class="progress-line" style="width:<?php echo $worked_tasks[$i]['percent_complete']."%"; ?>"></div></div></div>
						<?php
					}
					
				} else { ?>
					<h3>Работы отстутсвуют</h3>
				<?php } ?>
				</div>

				<?php if(count($completed_tasks)>0){ ?>
				<div class="col-xs-12 page-title"><h3><?php echo $pagetitle_completed; ?></h3></div>
				<div class="col-xs-12 tasks-block">
					<?php for($i=0;$i<count($completed_tasks);$i++){
						?>
						<div class="col-xs-3 task-item task" data-task="<?php echo $completed_tasks[$i]['id']; ?>"><div class="content"><span class="task-title"><?php echo $completed_tasks[$i]['title'];?></span><span class="task-percent"><?php echo $completed_tasks[$i]['percent_complete']."%"; ?></span><div class="progress-line" style="width:<?php echo $completed_tasks[$i]['percent_complete']."%"; ?>"></div></div></div>
					<?php }	?>				
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>