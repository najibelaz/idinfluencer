

<div class="wrap-content pt0">
	<div class="caption-page app-table top-title">
		<div class="d-flex justify-content-between align-items-center">
			<h4 class="">
			<i class="material-icons">view_array</i> <?=lang("caption")?>

			</h4>
			<div class="btn-group" role="group" aria-label="export">
				<a href="<?=cn("caption/update")?>" class="btn btn-raised btn-primary waves-effect"><i
						class="fa fa-plus"></i>
					<?=lang("add_new")?></a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php if (!empty($result) && !empty($columns)) {
    foreach ($result as $key => $row) {
        ?>
	<div class="col-md-6 col-lg-4">
		<div class="card card-caption">
			<div class="header">
				<h2><strong>#<?=$page + $key + 1?></strong></h2>
				<ul class="header-dropdown m-r--5">
					<li class="dropdown right"> <a href="javascript:void(0);" class="dropdown-toggle"
							data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i
								class="zmdi zmdi-more-vert"></i> </a>
						<ul class="dropdown-menu pull-right" x-placement="bottom-start"
							style="position: absolute; transform: translate3d(-5px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
							<li><a href="<?=cn("caption/update/" . $row->ids)?>"
									class=" waves-effect waves-block"><?=lang("edit")?></a></li>
							<li><a class="actionDeleteModele" data-mid="<?=$row->ids ?>" data-redirect="<?=cn("caption")?>" href="<?=cn("caption/ajax_delete_item")?>"
									class=" waves-effect waves-block"><?=lang("delete")?></a></li>
							<li><a href="<?=cn("post")?>?id_caption=<?=$row->ids ?>"
									class=" waves-effect waves-block"><?=lang("add")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<div class="col-md-10">
					<p><?=specialchar_decode(nl2br($row->content))?></p>
				</div>
				<div class="col-md-3" style="display: none;">
					<img src="https://loremflickr.com/150/160" alt="">
				</div>


			</div>
		</div>
	</div>
	<?php } ?>
	<?php } else { ?>
	<div class="ml15 mr15 bg-white dataTables_empty"></div>
	<?php } ?>

</div>
<div class="row">
	<?php if (!empty($result) && !empty($columns) && $this->pagination->create_links() != "") {?>
	<div class="clearfix"></div>
	<?=$this->pagination->create_links();?>
	<?php }?>
</div>