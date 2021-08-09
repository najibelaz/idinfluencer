
<div class="row m0">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2><i class="<?=$module_icon?>" aria-hidden="true"></i> <?=lang("Waiting")?></h2>
					</div>
					<div class="body">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="<?=cn('waiting/invalidated')?>" class="tabs nav-link">
									 <?=lang("invalidated_publications")?>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?=cn('waiting/validated')?>" class="tabs nav-link">
									<?=lang("validated_publications")?>
								</a>
							</li>
							<?php if(is_admin() || is_responsable()): ?>
								<li class="nav-item">
									<a href="<?=cn('waiting')?>" class="tabs nav-link active">
										<?=lang("waitting_publications")?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							
						</div>
					</div>
				</div>
			</div>
		</div>
<div class="wrap-content pt0">
	<div class="caption-page app-table">
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
						<p> 
							<?php $content = substr($data->caption, 0, 100); 
							$content = specialchar_decode(nl2br($content));
							if(strlen($content) > 100){
								$content .="...";
							}
							echo $content;
							if(strlen($content) > 100): ?>
								<a href="#mainModal" data-toggle="modal"
								data-target="#mainModal" class="read-more postprev" data-text="<?=specialchar_decode(nl2br($data->caption))?> ">
								<?=lang("read_more")?>
								</a>
							<?php endif; ?>
						</p>
						<?php
                            if($data->media){
                        ?>
						<img src="<?= $data->media[0]; ?>" alt="">
						<?php
                            }
                        ?>
					</div>
					<div class="fotter justify-content-between">
						<div>
							<a href="#mainModal" data-toggle="modal"
                            data-target="#mainModal" class="btn btn-secondary postprev" data-text="<?=specialchar_decode(nl2br($data->caption))?> ">
                            <?=lang("visualiser")?>
                            </a>
							<div class="d-none">
								<div class="post-preview card">
									<?php 
									$data = json_decode($row->data);
									$data_preview = (array)$data;
									$data_preview['idp'] = $row->id;
									
									$this->load->view('preview/'.$row->social_label, $data_preview); ?>
								</div>
							</div>
						</div>
						
						
						<div>
							<button type="button"
							class="btn btn-raised btn-secondary waves-effect mr-2 btn-invalidate"><?=lang("invalidate")?></button>
							<button type="button" data-social="<?=$row->social_label?>" data-redirect="<?=cn("waiting")?>"
							data-pid="<?=$row->id?>"
							class="btn btn-raised btn-primary waves-effect validPost"><?=lang("validate")?></button>
						</div>
						
					</div>
					<div class="reason-box card" style="display: none;">
						<div class="header"><?=lang("please_describe_the_reasons")?> :</div>
						<div class="body">
							<textarea class="form-control" rows="5"></textarea>
						</div>
						<div class="fotter">
							<button type="button"
								class="btn btn-raised btn-warning waves-effect mr-2 btn-cancel"><?=lang("cancel")?></button>
							<button type="button" data-social="<?=$row->social_label?>"
								data-redirect="<?=cn("waiting")?>" data-pid="<?=$row->id?>"
								class="btn btn-raised btn-primary waves-effects invalidPost"><?=lang("submit")?></button>
						</div>
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

<div id="read-more-box" class="d-none">
    <div class="modal-dialog read-more-box" role="document">
        <div class="modal-content">
            <div class="modal-body" id="read-more-info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect"
                    data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".postprev").click(function (e) {
        e.preventDefault();
        let popup = $(this).parents('.card-waiting').find(".post-preview");
        $("#read-more-info").html("");
        popup.clone().prependTo("#read-more-info");
        $("#mainModal").html("");
        $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");

    });
</script>

<style type="text/css">
	.wrap-content {
		margin-right: -15px;
		margin-left: -15px;
	}

	.card-caption-title {
		border-bottom: 1px solid #e5e5e5;
		border-radius: 0 !important;
		-webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
		box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
	}

	.card-caption-item {
		border: 1px solid #e5e5e5;
		min-height: 152px;
		-webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
		box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
	}
</style>