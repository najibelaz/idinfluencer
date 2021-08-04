<div class="ig-analytics" id="box-analytics">
	<div class="container">
		<div class="dataTables_empty"></div>

		<?php
		 if(segment(2) == "index" || segment(2) =="" ){ ?>
			<a href="<?=cn("group_manager/index/add")?>" class="btn btn-primary"><?=lang("add_new")?></a>
		<?php }else{ ?>
		<a data-toggle="modal" data-target="#mainModal"
		class="btn btn-primary responsable_popup"><?=lang("add_new")?></a>
		<!-- <a href="<?=cn("users/update?role=responsable")?>" class="btn btn-primary"><?=lang("add_new")?></a> -->
		<?php } ?>
	</div>
</div>
<div id="responsable_add_form" class="d-none">

</div>


<div id="read-more-box" class="d-none">
	<div class="modal-dialog modal-lg read-more-box" role="document">
		<div class="modal-content">
			<div class="modal-body" id="read-more-info">
				<div class="wrap-content">
					<form action="<?=cn("users/ajax_update")?>" data-redirect="/group_manager/responsable" class="actionForm"
						method="POST">

						<div class="row">
							<div class="col-md-12">
								<div class="users">
									<div class="card">
										<div class="header">
											<h2>
												<strong> <i class="material-icons" aria-hidden="true">person</i>
												</strong><?= lang('user_manager') ?>
											</h2>
										</div>
										<div class="body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group avatar">
														<label for="fileupload"><?=lang("Avatar")?></label><br>

														<div class="fileinput-button">
															<!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
															<i class="material-icons">camera_alt</i>
															<input id="fileupload" type="file" name="files[]">

														</div>
														<div class="file-manager-list-images">
														</div>
														<span class="recommended"><?=lang("recommended")?> : 512 x
															512</span><br>
													</div>
													<div class="form-group">
														<label for="email"><?=lang("email")?><span>*</span></label>
														<input type="text" class="form-control" name="email" id="email"
															value="">
													</div>
													<div class="form-group">
														<label for="phone"><?=lang("phone")?></label>
														<input type="text" class="form-control" name="phone" id="phone"
															value="">
													</div>
													<div class="form-group">
														<label
															for="password"><?=lang("password")?><span>*</span></label>
														<input type="password" class="form-control" name="password"
															id="password">
													</div>
													<div class="form-group">
														<label
															for="confirm_password"><?=lang("confirm_password")?><span>*</span></label>
														<input type="password" class="form-control"
															name="confirm_password" id="confirm_password">
													</div>
													<input type="hidden" name="role" value="responsable">
													<div class="form-group">
														<label for="status"><?=lang("status")?></label>
														<select name="status" class="form-control">
															<option value="1"
																>
																<?=lang("enabled")?></option>
															<option value="0"
																>
																<?=lang("disabled")?></option>
														</select>

													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="fullname"><?=lang("fullname")?></label>
														<input type="text" class="form-control" name="fullname" id="fullname"
															value="">
													</div>
												</div>
												<div class="form-group">
													<span>*</span>: <?=lang("required_fields")?>
												</div>
											</div>
										</div>



									</div>
									<div class="card">
										<div class="body">
											<button type="button" class="btn btn-danger btn-simple waves-effect"
												data-dismiss="modal"><?=lang("close")?></button>
											<button type="submit"
												class="btn btn-primary pull-right"><?=lang('add')?></button>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>