<div class="wrap-content">
	<?php 
		$link = "admin";
		if($_GET['role'] == "customer"){
			if($result->status=="0")
			$link = "customer_inactif";
			else
			$link = "customer";

		}elseif($_GET['role'] == "manager"){
			$link = "manager";
		}elseif($_GET['role'] == "responsable"){
			$link = "res_cm";
		}
	?>
<div>
	<a href="<?=cn($module."/".$link)?>" title="<?=lang("Back")?>" class="btn btn-secendary border-circle btn-back">
		<i class="zmdi zmdi-chevron-left"></i>
	</a>
</div>	
	<form action="<?=cn($module."/ajax_update")?>" data-redirect="<?= !empty($result) ? cn($module."/update/".$result->ID."?role=".$_GET['role']) : cn($module."/".$link) ?>" class="actionForm" method="POST">
		<input type="hidden" name="ids" value="<?=!empty($result)?$result->ids:""?>">
		<input type="hidden" name="role" value="<?=$_GET['role']?>">

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
									<!-- <div class="form-group">
										<label for="fullname"><?=lang("fullname")?></label>
										<input type="text" class="form-control" name="fullname" id="fullname"
											value="<?=!empty($result)?$result->fullname:""?>">
									</div> -->
									<div class="form-group avatar">
										<label for="fileupload"><?=lang("Avatar")?></label><br>
										
										<div class="fileinput-button" >
											<!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
											<i class="material-icons">camera_alt</i>
											<input id="fileupload" type="file" name="files[]"> 
											
										</div>
										<div class="file-manager-list-images" >
											<?php if(isset($result)){ ?>
												<div class="item" style="background-image: url('<?= $result->avatar ?>')">
													<input type="hidden" name="media[]" value="<?= $result->avatar ?>">
													<button type="button" class="close" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
											<?php } ?>
										</div>	
										<span class="recommended"><?=lang("recommended")?> : 512 x 512</span><br>								
									</div>									
									<div class="form-group">
										<label for="email"><?=lang("email")?><span>*</span></label>
										<input type="text" class="form-control" name="email" id="email"
											value="<?=!empty($result)?$result->email:""?>">
									</div>
									<div class="form-group">
										<label for="phone"><?=lang("phone")?></label>
										<input type="text" class="form-control" name="phone" id="phone"
											value="<?=!empty($result)?$result->telephone:""?>">
									</div>
									<div class="form-group">
										<label for="password"><?=lang("password")?><span>*</span></label>
										<input type="password" class="form-control" name="password" id="password">
									</div>
									<div class="form-group">
										<label for="confirm_password"><?=lang("confirm_password")?><span>*</span></label>
										<input type="password" class="form-control" name="confirm_password"
											id="confirm_password">
									</div>
									<?php if($create_customer) {?>
									<div class="form-group">
										<label for="package"><?=lang("package")?><span>*</span></label>
										<select class="form-control" name="package" id="package">
											<option value="0"><?=lang("select_package")?></option>
											<?php if(!empty($packages)){
                                		foreach ($packages as $package) {
	                                	?>
											<option value="<?=$package->ids?>"
												<?=(!empty($result) && $package->id == $result->package)?"selected":""?>>
												<?=$package->name?></option>
											<?php }}?>
										</select>
									</div>
									<div class="form-group">
										<label for="md_checkbox_create_a_post"><?=lang('option_pack')?></label><br>
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_create_a_post" name="create_a_post"
                                                class="filled-in chk-col-red" value="create_a_post"
                                                <?=(isset($result->id_user) &&permission_pack("create_a_post",$result->id_user))?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_create_a_post">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('create_a_post')?></span>
                                        </div>
                                    </div>
									<?php } ?>
									<?php if($create_customer) {
										$date = $result->first_date;
										if($result->first_date == "0000-00-00"){
											$date = date("Y-m-d");
										}
										?>
									<div class="form-group">
										<label for="first_date"><?=lang("date de début")?></label>
										<input type="text" class="form-control date" name="first_date"
											id="expiration_date"
											value="<?=!empty($result)?convert_date($date):""?>">
									</div>
									<div class="form-group">
										<label for="select_periode"> <?=lang("Durée d'engagement")?> : <span>*</span></label>
										<select name="select_periode" class="form-control ">
											<option value="12" <?php if(isset($result->periode) && $result->periode == 12) echo "selected"; ?>><?='12 '.lang('mount')?></option>
											<option value="6" <?php if(isset($result->periode) && $result->periode == 6) echo "selected"; ?>><?='6 '.lang('mount')?></option>
											<option value="3" <?php if(isset($result->periode) && $result->periode == 3) echo "selected"; ?>><?='3 '.lang('mount')?></option>
										</select>
									</div>
									<?php if(isset($result->periode)): ?>
									<div class="form-group">
										<label for="expiration_date"><?=lang("date de fin d'engagement")?></label>
										<input type="text" class="form-control date" disabled name="expiration_datee"
											id="expiration_date"
											value="<?=!empty($result)?convert_date($result->expiration_date):""?>">
										<input type="hidden" class="form-control date" name="expiration_date"
											id="expiration_datee"
											value="<?=!empty($result)?convert_date($result->expiration_date):""?>">
									</div>
									<div class="form-group">
										<label for="suspension"><?=lang("date de suspension")?></label>
										<input type="number" class="form-control" name="suspension"
											min="0"
											id="suspension"
											value="<?=!empty($result)?$result->suspension:"0"?>">
									</div>
									<?php endif; ?>
									<div class="form-group">
										<label for="pwd"> <?=lang('select_your_timezone')?>:</label>
										<select name="timezone"
											class="form-control <?=empty($result)?"auto-select-timezone":""?>">
											<?php if(!empty(tz_list())){
				                        $info_client = info_client_ip();
				                        foreach (tz_list() as $value) {
				                        ?>
				                        	<?php 
				                        		$selected = (!empty($result) && $value['zone'] == $result->timezone)?"selected":"";

				                        	?>
											<option value="<?=$value['zone']?>"
												<?=$selected?>>
												<?=$value['time']?></option>
											<?php }}?>
										</select>
									</div>
									<?php } ?>
									<div class="form-group">
										<?php 
										$role = (isset($_GET['role']) && !empty($_GET['role'])) ? $_GET['role'] : 'customer';
										?>
										<label for="role"><?=lang("role")?><span>*</span></label>
										<select name="role" class="form-control">
											<option value="admin" 
												<?= ($role == 'admin' ? 'selected' : '')?>>
												<?=lang("admin")?>
											</option>
											<option value="responsable" 
												<?= ($role == 'responsable' ? 'selected' : '')?>>
												<?=lang("responsable")?>
											</option>
											<option value="manager" 
												<?= ($role == 'manager' ? 'selected' : '')?>>
												<?=lang("manager")?>
											</option>
											<option value="customer" 
												<?= ($role == 'customer' ? 'selected' : '')?>>
												<?=lang("customer")?>
											</option>
										</select>
										
									</div>
									<?php if($create_customer) {?>
									<div class="form-group">
										<label for="renouvler"><?=lang("Tacite reconduction")?></label>
										<select name="renouvler" class="form-control">
											<option value="1" <?= ((isset($result->renouvler) && $result->renouvler == 1) ? 'selected' : '')?>><?=lang("Yes")?></option>
											<option value="0" <?= ((isset($result->renouvler) && $result->renouvler == 0 )? 'selected' : '')?>><?=lang("No")?></option>
										</select>
										
									</div>
									<?php } ?>
									<div class="form-group">
										<label for="status"><?=lang("status")?></label>
										<select name="status" class="form-control">
											<option value="1" <?= ((isset($result->status) && $result->status == 1) ? 'selected' : '')?>><?=lang("enabled")?></option>
											<option value="0" <?= ((isset($result->status) && $result->status == 0) ? 'selected' : '')?>><?=lang("disabled")?></option>
										</select>
										
									</div>
								</div>
								<div class="col-md-6">

									<?php if($create_customer) {?>
									<div class="form-group">
										<label for="id_teamlead"><?=lang("Id")?><span>*</span></label>
										<input type="text" class="form-control" name="id_teamlead" id="id_teamlead"
											value="<?=!empty($result->ids)?$result->ids:""?>">
									</div>
									<div class="form-group">
										<label for="id_teamlead_new"><?=lang("id_teamlead_facture")?><span>*</span></label>
										<input type="text" class="form-control" name="id_teamlead_new" id="id_teamlead_new"
											value="<?=!empty($result->id_teamlead_new)?$result->id_teamlead_new:""?>">
									</div>
									<div class="form-group">
										<label for="raison_social"><?=lang("entreprise")?><span>*</span></label>
										<input type="text" class="form-control" name="raison_social" id="raison_social"
											value="<?=!empty($result->raison_social)?$result->raison_social:""?>">
									</div>
									<div class="form-group">
										<label for="SIRET"><?=lang("SIRET")?><span>*</span></label>
										<input type="text" class="form-control" name="SIRET" id="SIRET"
											value="<?=!empty($result->SIRET)?$result->SIRET:""?>">
									</div>
									<div class="form-group">
										<label for="entreprise"><?=lang("type_entreprise")?><span>*</span></label>
										<input type="text" class="form-control" name="entreprise" id="entreprise"
											value="<?=!empty($result->entreprise)?$result->entreprise:""?>">
									</div>
									<div class="form-group">
										<label for="entreprise"><?=lang("n_tva")?><span>*</span></label>
										<input type="n_tva" class="form-control" name="n_tva" id="n_tva"
											value="<?=!empty($result->n_tva)?$result->n_tva:""?>">
									</div>
									<?php } ?>
									<div class="form-group">
										<label for="nom"><?=lang("fullname")?></label>
										<input type="text" class="form-control" name="fullname" id="fullname"
											value="<?=!empty($result->fullname)? $result->fullname:""?>">
									</div>
									<?php if($create_customer) {?>
									<div class="form-group">
										<label for="adresse"><?=lang("adresse1")?><span>*</span></label>
										<input type="text" class="form-control" name="adresse" id="adresse"
											value="<?=!empty($result->adresse)?$result->adresse:""?>">
									</div>
									<div class="form-group">
										<label for="adresse2"><?=lang("adresse2")?></label>
										<input type="text" class="form-control" name="adresse2" id="adresse2"
											value="<?=!empty($result->adresse2)?$result->adresse2:""?>">
									</div>
									<div class="form-group">
										<label for="code_postal"><?=lang("code_postal")?><span>*</span></label>
										<input type="text" class="form-control" name="code_postal" id="code_postal"
											value="<?=!empty($result->code_postal)?$result->code_postal:""?>">
									</div>
									<div class="form-group">
										<label for="ville"><?=lang("ville")?><span>*</span></label>
										<input type="text" class="form-control" name="ville" id="ville"
											value="<?=!empty($result->ville)?$result->ville:""?>">
									</div>
									<div class="form-group">
										<label for="pays"><?=lang("pays")?><span>*</span></label>
										<input type="text" class="form-control" name="pays" id="pays"
											value="<?=!empty($result->pays)?$result->pays:""?>">
									</div>
									<div class="form-group">
										<label for="website"><?=lang("website")?></label>
										<input type="text" class="form-control" name="website" id="website"
											value="<?=!empty($result->website)?$result->website:""?>">
									</div>
									<div class="form-group">
										<label for="secteur"><?=lang("secteur")?></label>
										<input type="text" class="form-control" name="secteur" id="secteur"
											value="<?=!empty($result->secteur)?$result->secteur:""?>">
									</div>
									<?php } ?>
									<?php
									$role = (isset($_GET['role']) && !empty($_GET['role'])) ? $_GET['role'] : 'customer';
									if($role == 'customer') { ?>
									<div class="form-group">
										<label for="sort"> <?=lang('option_réduction')?></label><br />
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="reduction">
														<?=lang('Réduction')?></label>
													<select class="form-control" name="reduction" id="reduction">
														<option value="0" <?=!empty($result) && $result->reduction == 0?"selected":""?>>
															<?=lang("sans_reduction")?></option>
														<option value="1" <?=!empty($result) && $result->status == 1?"selected":""?>>
															<?=lang("pourcentage")?></option>
														<option value="2" <?=!empty($result) && $result->status == 2?"selected":""?>>
															<?=lang("price")?></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="reduction_value">
														<?=lang('valeur de réduction')?></label>
													<input type="number" class="form-control" name="reduction_value" id="reduction_value"
														value="<?=!empty($result)?$result->reduction_value:"0"?>" min="0">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="community_manager">
														<?=lang('community_manager')?></label>
													<select class="form-control" name="community_manager" id="community_manager">
													<?php foreach ($managers as $manager) {?>
														<option value="<?=$manager->id?>" <?=!empty($manager_user) && $manager_user->id_manager == $manager->id?"selected":""?>><?=$manager->fullname?></option>
													<?php }?>
														
													</select>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="form-group">
									<span>*</span>: <?=lang("Champs obligatoires")?>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="body">
								<a href="<?=cn($module)?>" class="btn btn-secondary"><?=lang('cancel')?></a>
								<button type="submit" class="btn btn-primary pull-right">

									<?=!empty($result)?lang('update'):lang('add')?>
								</button>
								<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>