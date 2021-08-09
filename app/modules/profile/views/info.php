<div class="col-6">
    <div class="card">
        <div class="body">
            <div class="tab-pane body active" id="about">
                <?php if(isset($information->raison_social)){ ?>
                    <small class="text-muted"><?= lang('raison_social') ?> : </small>
                    <p><?= $information->raison_social ?></p>                    
                    <hr>
                <?php } ?>
                <?php if(isset($user->email)){ ?>
                    <small class="text-muted"><?= lang('email_address') ?> :</small>
                    <p><?= $user->email ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($user->id_team) && $user->role!="customer"){ ?>
                    <small class="text-muted"><?= lang('id_teamleader') ?> :</small>
                    <p><?= $user->id_team ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($user->telephone)){ ?>
                    <small class="text-muted"><?= lang('phone') ?> : </small>
                    <p><?= $user->telephone ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($information->website)){ ?>
                    <small class="text-muted"><?= lang('website') ?> : </small>
                    <p><a href="<?= $information->website ?>"><?= $information->website ?></a></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="col-6">
    <div class="card">
        <div class="body">
            <div class="tab-pane body active" id="about">
                <?php if(isset($information->code_postal)){ ?>
                    <small class="text-muted"><?= lang('code_postal') ?> : </small>
                    <p><?= $information->code_postal ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($information->ville)){ ?>
                    <small class="text-muted"><?= lang('ville') ?> : </small>
                    <p><?= $information->ville ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($information->pays)){ ?>
                    <small class="text-muted"><?= lang('pays') ?> : </small>
                    <p><?= $information->pays ?></p>
                    <hr>
                <?php } ?>
                <?php if(isset($information->adresse)){ ?>
                    <small class="text-muted"><?= lang('address') ?> : </small>
                    <p><?= $information->adresse ?></p>
                <?php } ?>
                <?php if(isset($information->adresse2)){ ?>
                    <small class="text-muted"><?= lang('address2') ?> : </small>
                    <p><?= $information->adresse2 ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>