<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="<?=$module_icon?>" aria-hidden="true"></i> <?=lang("dashboard")?></h4>
    </div>
</div>


<div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card info-box-2">
                <div class="body">
                    <!-- <div class="icon col-12">
                        <div class="person">
                            <i class="material-icons">person</i>
                        </div>
                    </div> -->
                    <div class="content col-12">
                        <div class="number"><?=$countCustomers ?></div>
                        <div class="text">Clients</div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach($packages as $package){?>
            <div class="col-lg-3 col-md-6">
                <div class="card info-box-2">
                    <div class="body">
                        <div class="content col-12">
                            <div class="number"><?php echo $package->sum;?></div>
                            <div class="text">Formule :<?php echo $package->name;?> </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- <div class="col-lg-3 col-md-6">
            <div class="card info-box-2">
                <div class="body">
                    <div class="icon col-12">
                        <div class="prime">
                            <i class="material-icons">stars</i>
                        </div>
                    </div>
                    <div class="content col-12">
                        <div class="number"><?=$count_standard ?></div>
                        <div class="text">Formules PRIME actives</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card info-box-2">
                <div class="body">
                    <div class="icon col-12">
                        <span class="business">
                            <i class="material-icons">business_center</i>
                        </span>
                    </div>
                    <div class="content col-12">
                        <div class="number"><?=$count_premium ?></div>
                        <div class="text">Formules BUSINESS actives</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card info-box-2">
                <div class="body">
                    <div class="icon col-12">
                        <span class="business">
                            <i class="material-icons">business_center</i>
                        </span>
                    </div>
                    <div class="content col-12">
                        <div class="number"><?=$count_trial ?></div>
                        <div class="text">Formules STARTER actives</div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <div class="row">
    <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><?=lang("customers_at_the_end_of_commitment_period")?></h2>
                
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table center-aligned-table">
                            <thead>
                                <tr>
                                    <th><?=lang("raison_social")?></th>
                                    <th><?=lang("fullname")?></th>
                                    <th><?=lang("email")?></th>
                                    <th><?=lang("flat_rate")?></th>
                                    <th><?=lang("expiration_date")?></th>
                                    <th><?=lang("publications prévues")?></th>
                                    <th><?=lang("nombre de publications")?></th>
                                    <th><?=lang("solde publications")?></th>
                                    <th><?=lang("actions")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $key => $user) {?>
                                    <tr>
                                        <?php
                                            list($first_name, $last_name) = explode(' ', $user->fullname);
                                            $pack = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
                                            $information = $this->model->get("*", 'user_information', "id_user = '".$user->id."'");
                                        ?>
                                        <td><?= $information->raison_social ?></td>
                                        <td><?= $user->fullname ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= $pack->name ?></td>
                                        <td><?php if ($user->expiration_date !="")  echo date('d-m-Y',strtotime($user->expiration_date)); ?></td>
                                        <td><?= $pack->number_posts ?></td>
                                        <td><?= get_count_posts($user->id,ST_PUBLISHED)+get_count_posts($user->id,ST_PLANIFIED) ?></td>
                                        <td><?= $pack->number_posts-(get_count_posts($user->id,ST_PUBLISHED)+get_count_posts($user->id,ST_PLANIFIED)) ?></td>
                                        <td><a href="users/show_user/<?=$user->ids?>" class="btn btn-raised btn-warning waves-effect btn-round"><?=lang("detail")?></a></td>
                                    </tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
