<!-- <div class="card card-caption-title">
			<div class="card-header">
				<div class="card-title" style="display: inline-block;">
					<i class="<?=$module_icon?>" aria-hidden="true"></i> <?=lang("caption")?>
					<div class="clearfix"></div>
				</div>
				<div class="pull-right">
					<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
						<a href="<?=cn("caption/update")?>" class="btn btn-black"><i class="fa fa-plus"></i>
							<?=lang("add_new")?></a>
					</div>
				</div>
			</div>
		</div> -->

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12">
		<h2><i class="<?=$module_icon?>" aria-hidden="true"></i> <?=lang("caption")?></h2>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 text-right">
		<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
			<a href="<?=cn("caption/update")?>" class="btn btn-raised btn-primary waves-effect"><i
					class="fa fa-plus"></i>
				<?=lang("add_new")?></a>
		</div>
	</div>
</div>

<div class="row">

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
							<li><a href="<?=cn("caption/ajax_delete_item")?>"
									class=" waves-effect waves-block"><?=lang("delete")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat soluta assumenda.</p>
				<img src="https://loremflickr.com/150/160" alt="">
			</div>
		</div>
	</div>

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
							<li><a href="<?=cn("caption/ajax_delete_item")?>"
									class=" waves-effect waves-block"><?=lang("delete")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat soluta assumenda.</p>
				<img src="https://loremflickr.com/152/160" alt="">
			</div>
		</div>
	</div>

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
							<li><a href="<?=cn("caption/ajax_delete_item")?>"
									class=" waves-effect waves-block"><?=lang("delete")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat soluta assumenda.</p>
				<img src="https://loremflickr.com/154/160" alt="">
			</div>
		</div>
	</div>

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
							<li><a href="<?=cn("caption/ajax_delete_item")?>"
									class=" waves-effect waves-block"><?=lang("delete")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat soluta assumenda.</p>
				<img src="https://loremflickr.com/156/160" alt="">
			</div>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-12">
		<ul class="pagination pagination-primary">
			<li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
			<li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
			<li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
			<li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
			<li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
		</ul>
	</div>
</div>