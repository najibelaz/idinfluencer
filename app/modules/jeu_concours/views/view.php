<div>
	<a href="<?=cn("jeu_concours")?>" title="<?=lang("Back")?>" class="btn btn-secendary border-circle btn-back">
		<i class="zmdi zmdi-chevron-left"></i>
	</a>
</div>	
<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?=lang('Participation_pour').' : '.$game->name?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <?php if(isset($participant->email )){ ?>
                        <small class="text-muted"><?= lang('email') ?> :</small>
                        <p><?= $participant->email  ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($participant->nom )&& isset($participant->prenom) ){ ?>
                        <small class="text-muted"><?= lang('fullname') ?> :</small>
                        <p><?= $participant->nom . " ". $participant->prenom ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($participant->etat_civil)){ ?>
                        <small class="text-muted"><?= lang('etat_civil') ?> : </small>
                        <p><?= $participant->etat_civil ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($participant->phone )){ ?>
                        <small class="text-muted"><?= lang('phone') ?> : </small>
                        <p><?= $participant->phone  ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($participant->code_postal)){ ?>
                        <small class="text-muted"><?= lang('code_postal') ?> : </small>
                        <p><?= $participant->code_postal ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($participant->address)){ ?>
                        <small class="text-muted"><?= lang('address') ?> : </small>
                        <p><?= $participant->address ?></p>
                        <hr>
                    <?php } ?>
                </div>
            </div>           
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <?php if(isset($participant->reglement)){ ?>
                        <small class="text-muted"><?= lang('reglement') ?> :</small>
                        <p><?= ($participant->reglement)? lang('Yes') : lang('No') ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($participant->newsletter)){ ?>
                        <small class="text-muted"><?= lang('newsletter') ?> :</small>
                        <p><?= ($participant->newsletter)? lang('Yes') : lang('No') ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($participant->rep_q1)){ ?>
                        <small class="text-muted"><?= lang('Question') ?> 1 : </small>
                        <p><?= ($participant->rep_q1)? lang('Yes') : lang('No') ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($participant->rep_q2)){ ?>
                        <small class="text-muted"><?= lang('Question') ?> 2 : </small>
                        <p><?= ($participant->rep_q2)? lang('Yes') : lang('No') ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($participant->rep_q3)){ ?>
                        <small class="text-muted"><?= lang('Question') ?> 3 : </small>
                        <p><?= ($participant->rep_q3)? lang('Yes') : lang('No') ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(!empty($participant->email_parrain)){ ?>
                        <small class="text-muted"><?= lang('Emails de parrainage')  ?></small>
                        <?php
                        $emails = unserialize($participant->email_parrain);
                            foreach($emails as $email){
                        ?>
                                <p>- <?= $email ?></p>
                        <?php
                            }
                        ?>
                        <hr>
                    <?php } ?>

                </div>
            </div>
            
        </div>

    </div>
</div>
