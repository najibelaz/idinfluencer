
<div class="row profile-page">
<?php 
		$link = "admin";
		if($user->role == "customer"){
			if($user->status=="0")
			$link = "customer_inactif";
			else
			$link = "customer";

		}elseif($user->role == "manager"){
			$link = "manager";
		}elseif($user->role == "responsable"){
			$link = "res_cm";
		}
	?>
    <div class="col-lg-12 col-md-12">
        <a class="edit btn btn-raised btn-primary waves-effect" href="<?= cn($module."/".$link) ?>">
            <i class="material-icons">keyboard_backspace</i> Retour
        </a>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="card profile-header bg-dark">
            <div class="body col-white">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="profile-image float-md-right"> 
                            <?php if($user->avatar != null && is_file($user->avatar)){ ?>
                                <img src="<?= $user->avatar ?>" alt="" >
                            <?php }else{ ?>
                                <img src="/assets/img/default-avatar.png" alt="" >
                            <?php } ?>                             
                        </div>
                    </div>
                    <?php if($user->role == 'customer'){ 
                         $information  = $this->model->get("*", 'user_information', "id_user = '".$user->id."'");
                        ?>
                    <div class="col-lg-9 col-md-8 col-12">
                        <?php if(isset($user->fullname)){ ?>
                            <h4 class="m-t-0 m-b-0"><?= $user->rs ?></h4>
                        <?php } ?>
                        <?php if(isset($information->entreprise)){ ?>
                        <span class="job_post"><?= lang('type_entreprise') ?> : <?= $user->entreprise ?></span>
                        <?php } ?>
                        <?php if(isset($information->SIRET)){ ?>
                        <span class="job_post"><?= lang('SIRET') ?> : <?= $user->SIRET ?></span>
                        <?php } ?>
                        <?php if(isset($information->secteur)){ ?>
                        <span class="job_post"><?= lang('secteur') ?> : <?= $user->secteur ?></span>
                        <?php } ?>
                        <div>
                            <a href="mailto:<?= $user->email ?>" class="btn btn-primary btn-outline-primary btn-round btn-simple">Message</a>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="col-lg-9 col-md-8 col-12">
                        <span class="job_post"><?= $user->fullname ?></span>
                        <span class="job_post"><?= lang('role') ?> : <?= $user->role ?></span>
                        <div>
                            <a href="mailto:<?= $user->email ?>" class="btn btn-primary btn-outline-primary btn-round btn-simple">Message</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($user->role == 'customer'){ ?>
        <?php
           
            $pack  = $this->model->get("*", 'general_packages', "id = '".$user->package."'");
        ?>
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
                    <?php if(isset($user->ids)){ ?>
                        <small class="text-muted"><?= lang('id_teamleader') ?> :</small>
                        <p><?= $user->ids ?></p>
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
    <?php } else {?>
    <div class="col-12">
        <div class="card">
            <?php if($user->role == 'manager'){ ?>
            <div class="tab-pane body active" id="about">
                <div class="body">
                    <div class="table-responsive app-table">
                        <table class="table center-aligned-table">
                            <thead>
                                <tr>
                                    <th><?=lang("publications prévues")?></th>
                                    <th><?= lang('nombre de publications') ?></th>
                                    <th><?= lang('solde publications') ?></th>
                                </tr>
                            </thead>
                            <?php
                                $getSumPackCustomers = getSumPackCustomers($user->id);
                            ?>
                            <tbody>
                                <tr>
                                    <td><p><?= $getSumPackCustomers ?></p></td>
                                    <td><p><?= get_count_posts_manager($user->ids,ST_PLANIFIED) ?></p></td>
                                    <td>
                                    <?php
                                        $managerP = get_count_posts_manager($user->ids,ST_PUBLISHED);
                                        $managerPl = get_count_posts_manager($user->ids,ST_PLANIFIED);
                                        $total = $getSumPackCustomers-($managerP + $managerPl)
                                    ?>
                                        <?= $total; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <?php if(isset($user->email)){ ?>
                        <small class="text-muted"><?= lang('email_address') ?> :</small>
                        <p><?= $user->email ?></p>
                        <hr>
                    <?php } ?>

                    <?php if(isset($user->fullname)){ ?>
                        <small class="text-muted"><?= lang('fullname') ?> :</small>
                        <p><?= $user->fullname ?></p>
                        <hr>
                    <?php } ?>

                    <small class="text-muted"><?= lang('groupes') ?> : </small>
                    <?php 
                        if(!empty($user_groups)){
                            foreach ($user_groups as $key => $grp) {?>
                                <span><?php if($key>0){ echo '-';} ?> <?= $grp->name ?></span>
                    <?php   }
                        }else{
                            echo lang('Aucun');
                        }
                    ?>
                    <hr>
                    <?php if(isset($user->role) && $user->role == 'manager'){ ?>
                    <small class="text-muted"><?= lang('responsable') ?> : </small>
                    <p>
                        <?php 
                            $resp_groups = $this->model->get_manager_resps($user->ids);
                            if(count($resp_groups)){
                                foreach ($resp_groups as $key => $resp) { 
                        ?>
                                        <span><?php if($key>0 ){ echo '-';}?> <?= $resp->fullname ?></span>
                        <?php
                            }
                            }else{
                                    echo lang('Aucun');
                            }
                        ?>
                    </p>
                    <hr>
                    <?php } ?>

                    <?php if(isset($user->telephone)){ ?>
                        <small class="text-muted"><?= lang('phone') ?> : </small>
                        <p><?= $user->telephone ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($user->ville)){ ?>
                        <small class="text-muted"><?= lang('ville') ?> : </small>
                        <p><?= $user->ville ?></p>
                        <hr>
                    <?php } ?>
                    <?php if(isset($user->adresse)){ ?>
                        <small class="text-muted"><?= lang('address') ?> : </small>
                        <p><?= $user->adresse ?></p>
                    <?php } ?>
                    <?php if(isset($user->adresse2)){ ?>
                        <small class="text-muted"><?= lang('address2') ?> : </small>
                        <p><?= $user->adresse2 ?></p>
                    <?php } ?>
                </div>
            </div>
            
        </div>

    </div>
    <?php } ?>  

    <?php if($user->role == 'customer'){ ?>
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
                    <p><?= $user->expiration_date ?></p>
                    <hr>
                    <?php } ?>
                    <?php if(isset($user->periode)){ ?>
                    <small class="text-muted"><?= lang('periode') ?> : </small>
                    <p><?= $user->periode ?>  <?= lang('mois') ?></p>
                    <hr>
                    <?php } ?>
                    <?php if(isset($user->renouvler)){ ?>
                    <small class="text-muted"><?= lang('renouvler') ?> : </small>
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
    <?php if(count($invoices)): ?>
    <div class="col-12">
        <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("No")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Client")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Montant")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("date")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Statut")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $key => $invoice) {?>
                                <?php 
                                $time = strtotime(str_replace('/', '-', $invoice['date_formatted'] ));
                                setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
                                $diff = strtotime('now') - $invoice['due_date']; 
                                $diff = abs(round($diff / 86400)); 
                                ?>
                            <?php 
                                $style = ($invoice['paid'] == 1) ? '' : 'style="color:red"'; 
                            ?>
                            <tr>
                                <td <?=$style?> ><?=$invoice['invoice_nr_detailed'] ?></td>
                                <td <?=$style?> ><?=$invoice['name'] ?></td>
                                <td <?=$style?> ><?=$invoice['total_price_excl_vat'] ?>  €</td>
                                <td <?=$style?> >
                                    <span class="text-uppercase">
                                    <?= $invoice['date_formatted'] ?></span>
                                </td>
                                <td <?=$style?> ><?=($invoice['paid'] == 1) ? '<span class="status paye">'.lang('payé').'</span>' : lang('En retard').' ('.$diff.' jours)' ?></td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
    </div>
    
    <?php 
    endif;
} else {?>
    <?php if($user->role == 'responsable'){ ?>
    <div class="col-6">
        <div class="card">
            <div class="body">
                <?=lang("list_managers")?>
                <div class="tab-pane body active" id="about">
                    <div class="body">
                        <div class="table-responsive app-table">
                            <table class="table center-aligned-table table-datatable">
                                <thead>
                                    <tr>
                                        <th><?=lang("id")?></th>
                                        <th><?=lang("fullname")?></th>
                                        <th><?=lang("groupe")?></th>
                                        <th><?=lang("publi_prevues")?></th>
                                        <th><?= lang('nbr_publi') ?></th>
                                        <th><?= lang('solde_publi') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($managers as $key => $manager) { ?>
                                    <?php
                                        $groupes = getGroups($manager);
                                        $pub = getSumPackCustomers($manager->id);
                                        $planified = get_count_posts_manager($manager->ids,ST_PLANIFIED);
                                        $published = get_count_posts_manager($manager->ids,ST_PUBLISHED);
                                        $solde = $pub - ($planified + $published);
                                    ?>
                                    <tr>
                                        <td><?=$manager->id?></td>
                                        <td><?=$manager->fullname?></td>
                                        <td>
                                            <?php foreach ($groups as $key => $group) {?>
                                                <span><?php if($key > 0) echo "-"; ?><?=$group->name ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?= $pub ?></td>
                                        <td><?= ($planified + $published) ?></td>
                                        <td><?= $solde ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($user->role == 'responsable'){ ?>
    <div class="col-6">
    <?php } else { ?>
    <div class="col-12">
    <?php } ?>
        <div class="card">
            <div class="body">
                <?=lang("list_customers")?>
                <div class="tab-pane body active" id="about">
                    <div class="body">
                        <div class="table-responsive app-table">
                            <table class="table center-aligned-table table-datatable">
                                <thead>
                                    <tr>
                                        <th><?=lang("id")?></th>
                                        <th><?=lang("rs")?></th>
                                        <th><?=lang("fullname")?></th>
                                        <th><?=lang("cm")?></th>
                                        <th><?=lang("publi_prevues")?></th>
                                        <th><?= lang('nbr_publi') ?></th>
                                        <th><?= lang('solde_publi') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $customers =getCustomers_sidebar($user->id);
                                    foreach ($customers as $key => $customer) { ?>
                                    <?php 
                                        $managers = get_cm_by_customer($customer);
                                        $pack = $this->model->get("*", PACKAGES, "id = '".$customer->package."'");
                                        $number_posts = $pack == true ? $pack->number_posts : 0;
                                        $planified = get_count_posts($customer->id,ST_PLANIFIED);
                                        $published = get_count_posts($customer->id,ST_PUBLISHED);
                                        $solde = $number_posts - ($planified + $published);
                                    ?>    
                                    <tr>
                                        <td><?=$customer->id?></td>
                                        <td><?=$customer->raison_social?></td>
                                        <td><?=$customer->fullname?></td>
                                        <td>
                                           <?php 
                                                $user_groups = $this->model->get_customer_managers($customer->ids);
                                            if(count($user_groups)){
                                                foreach ($user_groups as $key => $grp) {
                                                    
                                            ?>
                                                            <span><?php if($key>0 ){ echo '-';}?> <?= $grp->fullname ?></span>
                                            <?php
                                                          
                                                }
                                            }else{
                                                    echo lang('Aucun');
                                            }
                                            ?>
                                        </td>
                                        <td><?= $number_posts ?></td>
                                        <td><?= ($planified + $published) ?></td>
                                        <td><?= $solde ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>