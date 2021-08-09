<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
		<h4 class="">
			<i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?=lang('draft')?>
		</h4>
    </div>
</div>


<div class="wrap-content pt0">
	<div class="caption-page app-table">
		<div class="d-flex justify-content-end align-items-center">
			
			<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
				<a href="<?=cn("post/index")?>" class="btn btn-raised btn-primary waves-effect"><i
						class="fa fa-plus"></i>
					<?=lang("add_new")?></a>
			</div>
		</div>
	</div>
</div>
<div class="wrap-content pt0">
	<div class="caption-page app-table">
		<div class="row">
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
								data-target="#mainModal" class="postprev" >
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
                            data-target="#mainModal" class="btn btn-primary postprev" data-text="<?=specialchar_decode(nl2br($data->caption))?> ">
                            <?=lang("visualiser")?>
                            </a>
							<div class="d-none">
								<div class="post-preview card">
									<?php
									// var_dump($row);
									$data = json_decode($row->data);
									$data_preview = (array)$data;
									$data_preview['idp'] = $row->id;
									
									$this->load->view('preview/'.$row->social_label, $data_preview); ?>
								</div>
							</div>
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
<div class="row">
	<?php if (!empty($result) && !empty($columns) && $this->pagination->create_links() != "") {?>
	<div class="clearfix"></div>
	<?=$this->pagination->create_links();?>
	<?php }?>
</div>
<div id="read-more-box" class="d-none">
    <div class="modal-dialog read-more-box" role="document">
        <div class="modal-content">
            <div class="modal-body" id="read-more-info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect"
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
<script>
	$('.btn-delete').click(function () {

		var social = $(this).data('social');
		var pid = $(this).data('pid');
		var _that = $(this);
		var _action = PATH + "waiting/ajax_update_status_delete";
		var _data = $.param({
			token: token,
			pid: pid,
			social: social
		});
		Main.showAjaxLoaderMessage("<?=lang('are_you_sure_want_delete_it')?>", "", function () {
			Main.ajax_post(_that, _action, _data, function () {
				swal("<?=lang('delete_successfully')?>");
			});
		});
	});
</script>