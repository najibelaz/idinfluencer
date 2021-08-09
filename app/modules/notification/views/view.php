<?php 
    $information = $this->model->get("*", 'user_information', "id_user = '".$notif->id_user_to."'");
    $author = $this->model->get("fullname", USERS, "id = '".$notif->id_user_from."'");
    $time = strtotime($notif->time_notif);
    $newformat = date('d/m/Y h:i',$time);
?>
    <div class="col-12">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <?php if(isset($information->raison_social)){ ?>
                        <small class="text-muted"><?= lang('raison_social') ?> :</small>
                        <p><?= $information->raison_social ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($author->fullname)){ ?>
                        <small class="text-muted"><?= lang('fullname') ?> :</small>
                        <p><?= $author->fullname ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($notif->text)){ ?>
                        <small class="text-muted"><?= lang('text') ?> : </small>
                        <p><?= $notif->text ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($notif->time_notif)){ ?>
                        <small class="text-muted"><?= lang('time_post') ?> : </small>
                        <p><?= $newformat ?></p>
                        <hr>
                    <?php } ?>
                </div>
            </div>
            
        </div>

    </div>