<div class="wrap-content">
	<form action="<?=cn($module."/add_user_byid")?>" data-redirect="<?=cn("users/customer")?>" class="actionForm" method="POST">
		<div class="row">
			<div class="col-md-12">
				<div class="users">
					<div class="card">
						<div class="header">
							<h2>
								<strong> <i class="<?=$module_icon?>" aria-hidden="true"></i> </strong><?=$module_name?>
							</h2>
						</div>
						<div class="body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="fullname"><?=lang("ID team")?></label>
										<input type="text" class="form-control" name="id_team" id="id_team"
											value="">
									</div>
                                </div>
							</div>
						</div>

						

					</div>
					<div class="card">
						<div class="body">
								<a href="<?=cn($module)?>" class="btn btn-secondary"><?=lang('cancel')?></a>
								<button type="submit" class="btn btn-primary pull-right">

									<?=lang('add')?>
								</button>
								<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>