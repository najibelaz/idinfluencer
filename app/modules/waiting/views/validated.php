<div class="wrap-content pt0">
	<div class="caption-page app-table">
		<div class="row m0">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2><i class="<?=$module_icon?>" aria-hidden="true"></i> <?=lang("waiting")?></h2>
					</div>
					<div class="body">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="<?=cn('waiting/invalidated')?>" class="tabs nav-link ">
									<?=lang("invalidated_publications")?>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?=cn('waiting/validated')?>" class="tabs nav-link active">
									<?=lang("validated_publications")?>
								</a>
							</li>
							<?php if(is_admin() || is_responsable()): ?>
								<li class="nav-item">
									<a href="<?=cn('waiting')?>" class="tabs nav-link">
										<?=lang("waitting_publications")?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row m0">
			<?php if(!empty($result) && !empty($columns)){
            foreach ($result as $key => $row) {
				$data = json_decode($row->data);
				$time = strtotime($row->time_post);
        		$newformat = date('d/m/Y - h:i',$time);    
				?>
			
			<div class="col-md-12 col-lg-4">
				<div class="card card-waiting">
					<div class="header">
						<div class="d-flex flex-column">
							<h2 class="title"><?=$row->social_label?></h2>
							<span class="date" data-dtp="dtp_iXKnU"><?=$newformat?></span>
						</div>
						<div class="icon-top <?=$row->social_label?>">
							<i class="fa fa-<?=$row->social_label?>"></i>
						</div>
					</div>
					<div class="body">
						<p> <?=specialchar_decode(nl2br($data->caption))?></p>
						 <?php
                            if($data->media){
                        ?>
                            <img src="<?= $data->media[0]; ?>" alt="">
                        <?php
                            }
                        ?>
					</div>
				</div>
			</div>
            <?php }}else{?>
			<div class="ml15 mr15 bg-white dataTables_empty"></div>
            <?php }?>


            <?php if(!empty($result) && !empty($columns) && $this->pagination->create_links() != ""){?>
            <div class="clearfix"></div>
	  		<div class="card-footer">
				<?=$this->pagination->create_links();?>
	  		</div>
	  		<?php }?>
      	</div>
    </div>
</div>

<style type="text/css">
.wrap-content{
	margin-right: -15px;
	margin-left: -15px;
}

.card-caption-title{
	border-bottom: 1px solid #e5e5e5;
	border-radius: 0!important;
	-webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
    box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
}

.card-caption-item{
	border: 1px solid #e5e5e5;
    min-height: 152px;
    -webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
    box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
}
</style>
