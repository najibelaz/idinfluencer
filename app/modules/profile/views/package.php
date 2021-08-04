   <?php
           
            $pack  = $this->model->get("*", 'general_packages', "id = '".$user->package."'");
        ?>
  <div class="col-6">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <?php if(isset($pack->name)){ ?>
                    <small class="text-muted"><?= lang('package') ?> : </small>
                    <p><?= $pack->name ?></p>
                    <hr>
                    <?php } ?>
                    <?php if(isset($user->expiration_date)){ ?>
                    <small class="text-muted"><?= lang('expiration_date') ?> : </small>
                    <?php 
                    $date = DateTime::createFromFormat('Y-m-d', $user->expiration_date);
                    //Date chart
                    $date = $date->format('d-m-Y');
                    $timestamp = strtotime($date);
                    $day = date('d', $timestamp);
                    if($day<=15){
                        $date = date("01-m-Y", strtotime($user->expiration_date));
                    }else{
                        $date = date("t-m-Y", strtotime($user->expiration_date));
                    }
                    //Last date of current month.
                    
                    // echo $lastDateOfMonth;
                    // echo $day;
                    ?>
                    <p><?=$date ?></p>
                    <hr>
                    <?php } ?>
                    <?php if(isset($user->periode)){ ?>
                    <small class="text-muted"><?= lang('periode') ?> : </small>
                    <p><?= $user->periode ?> <?= lang('mois') ?></p>
                    <hr>
                    <?php } ?>
                    <?php if(isset($user->renouvler)){ ?>
                    <small class="text-muted"><?= lang('Renouveler') ?> : </small>
                    <p><?= ($user->renouvler)? lang('Yes') : lang('No') ?></p>
                    <hr>
                    <?php } ?>
                    <small class="text-muted"><?= lang('publications prévues') ?> : </small>
                    <p><?= get_count_posts($user->id,ST_PLANIFIED) ?></p>
                    <hr>
                    <small class="text-muted"><?= lang('nombre de publications') ?> : </small>
                    <p><?= get_count_posts($user->id,ST_PUBLISHED) ?></p>
                    <hr>
                    <small class="text-muted"><?= lang('solde publications') ?> : </small>
                    <p><?= $pack->number_posts-get_count_posts($user->id,ST_PUBLISHED) ?></p>
                </div>
            </div>
        </div>
    </div>