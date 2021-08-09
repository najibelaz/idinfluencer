<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">games</i> <?=lang('jeu_concour')?></h4>
    </div>
</div>
<div>
	<a href="<?=cn($module)?>" title="<?=lang("Back")?>" class="btn btn-secendary border-circle btn-back">
		<i class="zmdi zmdi-chevron-left"></i>
	</a>
</div>	
<div class="row">
	<form action="<?=cn($module."/ajax_update")?>" data-redirect="<?= cn($module) ?>" class="actionForm col-12" method="POST">	
		<?php if(!get('update')){ ?>
			<input id="id_game" type="hidden" name="id_game" value="<?=!empty($result)?$result->id:""?>"> 
		<?php } ?>
		<div class="col-md-12">
			<div class="users card">

				<div class="header">
					<h2><?=lang("new_game")?></h2>
				</div>
				<div class="body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group avatar">
								<label for="fileupload"><?=lang("Visuel du jeu")?></label><br>
								
								<div class="fileinput-button" >
									<!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
									<i class="material-icons">camera_alt</i>
									<input id="fileupload" type="file" name="files[]"> 
									
								</div>
								<div class="file-manager-list-images" >
									<?php if(isset($result)){ ?>
										<div class="item" style="background-image: url('<?= $result->img ?>')">
											<input type="hidden" name="media[]" value="<?= $result->img ?>">
											<button type="button" class="close" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
									<?php } ?>
								</div>	
								<span class="recommended"><?=lang("recommended")?> : 1440 x 1440</span><br>								
							</div>
							<div class="form-group avatar">
								<label for="fileupload"><?=lang("Visuel du jeu mobile")?></label><br>
								
								<div class="fileinput-button" >
									<!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
									<i class="material-icons">camera_alt</i>
									<input id="fileupload2" type="file" name="files[]"> 
									
								</div>
								<div class="file-manager-list-images2" >
									<?php if(isset($result)){ ?>
										<div class="item" style="background-image: url('<?= $result->img2 ?>')">
											<input type="hidden" name="media[]" value="<?= $result->img2 ?>">
											<button type="button" class="close" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
									<?php } ?>
								</div>	
								<span class="recommended"><?=lang("recommended")?> : 1440 x 1440</span><br>								
							</div>
							<div class="form-group">
								<label for="name"><?=lang("Nom de jeu")?><span>*</span></label>
								<input type="text" class="form-control" name="name" id="name" value="<?=!empty($result)?$result->name:""?>">
							</div>
							<div class="form-group">
								<label for="description"><?=lang("description")?> </label>
								<textarea class="form-control" name="description" id="description" rows="5"><?=!empty($result)?$result->description:""?></textarea>
							</div>
							<div class="form-group reglement form-group file-group">
								<label for="reglement"><?=lang("reglement")?></label>
								
								<div class="fileinput-buttonreglement" >
									<!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
									<input id="reglement"  class="form-control" type="file" name="reglement[]"> 
									<span class="file-name form-control"></span>
								</div>
								
								<div class="file-manager-list-imagesreglement" >
								</div>	
							</div>
							<?php if(isset($result)){ ?>
								<a href="<?= $result->reglement ?>" >
									<input id="reglement"  class="form-control" value="<?= $result->reglement ?>" type="hidden" name="reglement1[]"> 
									<i class="material-icons">description</i> <?=lang("reglement")?> </a>
							<?php } ?>
							<div class="form-group">
								<label for="id_user"><?=lang("user")?><span>*</span></label>
								<select class="form-control" name="id_user" id="id_user">
									<option value="0"><?=lang("select_client")?></option>

									<?php 
									$users = getCustomers_sidebar();
									if(!empty($users)){
								foreach ($users as $user) {
								?>
									<option value="<?=$user->id?>"
										<?=(!empty($result) && $user->id == $result->id_user)?"selected":""?>>
										<?=$user->rs?></option>
									<?php }}?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="q1"><?=lang("question")?> 1</label>
								<textarea class="form-control" name="q1" id="q1" rows="5"><?=!empty($result)?$result->q1:""?></textarea>
							</div>
							<div class="form-group">
								<label for="q2"><?=lang("question")?> 2</label>
								<textarea class="form-control" name="q2" id="q2" rows="5"><?=!empty($result)?$result->q2:""?></textarea>
							</div>
							<div class="form-group">
								<label for="q3"><?=lang("question")?> 3</label>
								<textarea class="form-control" name="q3" id="q3" rows="5"><?=!empty($result)?$result->q3:""?></textarea>
							</div>
							<div class="form-group">
								<label for="date_debut"><?=lang("date_debut")?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
									<input type="text" value="<?=!empty($result)?$result->date_debut:""?>" name="date_debut" class="form-control form-control datetime " placeholder="Ex: 01/<?= date("m/Y")?>">
								</div>
							</div>
							<div class="form-group">
								<label for="date_fin"><?=lang("date_fin")?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
									<input type="text" value="<?=!empty($result)?$result->date_fin:""?>" name="date_fin" class="form-control form-control datetime " placeholder="Ex: 01/<?= date("m/Y")?>">
								</div>
							</div>
							<div class="form-group">
								<label for="email_jeu"><?=lang("Email jeu")?><span>*</span></label>
								<input type="text" class="form-control" name="email_jeu" id="name" value="<?=!empty($result)?$result->email_jeu:""?>">
							</div>
							<div class="form-group">
								<label for="pass"><?=lang("Mot de passe")?><span>*</span></label>
								<input type="text" class="form-control" name="pass" id="name" value="<?=!empty($result)?$result->pass:""?>">
							</div>
							<div class="form-group">
								<label for="objcet_mail"><?=lang("objet mail")?></label>
								<input type="text" class="form-control" name="object_mail" id="object_mail" value="<?=!empty($result)?$result->objet_mail:""?>">
							</div>
							<div class="form-group">
								<label for="email_participant"><?=lang("email_participant")?> </label>
								<textarea class="form-control" name="email_participant" id="email_participant" rows="5"><?=!empty($result)?$result->email_participant:""?></textarea>
							</div>
							<div class="form-group">
								<label for="object_parrain"><?=lang("objet_parrain")?></label>
								<input type="text" class="form-control" name="object_parrain" id="object_parrain" value="<?=!empty($result)?$result->object_parrain:""?>">
							</div>
							<div class="form-group">
								<label for="email_parrain"><?=lang("email_parrain")?> </label>
								<textarea class="form-control" name="email_parrain" id="email_parrain" rows="5"><?=!empty($result)?$result->email_parrain:""?></textarea>
							</div>
							<div class="form-group">
								<label for="text_jeu_termine"><?=lang("text_jeu_termine")?> </label>
								<textarea type="text" class="form-control" name="text_jeu_termine" id="text_jeu_termine"  rows="5"><?=!empty($result)?$result->text_jeu_termine:""?></textarea>
							</div>
							<div class="form-group">
								<label for="btn_jeu_termine"><?=lang("boutton_jeu_termine")?> </label>
								<input type="text" class="form-control" name="btn_jeu_termine" id="btn_jeu_termine" value="<?=!empty($result)?$result->btn_jeu_termine:""?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex">
				<a href="<?=cn($module)?>" class="btn btn-secondary mr-2"><?=lang('cancel')?></a>
				<button type="submit" class="btn btn-primary">
					<?=lang('validate')?>
				</button>
			</div>
		</div>
	</form>
</div>
</div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
				$('.file-manager-list-images').append('<div class="item" style="background-image: url('+e.target.result+')"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }   
    $("#img").change(function(){
        readURL(this);
    });

</script>