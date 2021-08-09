<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?php echo lang('customers');?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2>
                <?php
                    if($user_active=="1")
                        echo lang('lists_customer_active');
                    else
                        echo lang('lists_customer_inactive');
                ?>
                </h2>
            </div>
            <div class="header d-flex justify-content-start justify-content-md-end flex-wrap">
                <a href="<?=cn($module."/update")?>?role=customer" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("add_new")?></a>
                <a href="<?=cn($module."/add_user_cron_date")?>" class="btn btn-raised btn-primary waves-effect" target="_blanck"><i
                        class="fa fa-plus"></i> <?=lang("Ajouter par Date")?></a>
                <a href="<?=cn($module."/add_user_cron")?>" class="btn btn-raised btn-primary waves-effect actionItem"><i
                        class="fa fa-plus"></i> <?=lang("Ajouter par API")?></a>
                <a href="<?=cn($module."/update_user_id_team")?>" class="btn btn-raised btn-primary waves-effect" target="_blanck"><i
                        class="fa fa-plus"></i> <?=lang("Mettre à jour API")?></a>
                <a href="<?=cn($module."/add_user_api")?>" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("Ajouter par ID teamlead")?></a>
                <!-- <?php if(is_admin()) {  ?>
                <a href="<?=cn($module."/import_user")?>" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("import_from_teamleader")?></a>
                <?php } ?> -->
            </div>
            <div class="body">
                <?php if($users): ?>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("id_teamleader")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("raison_social")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("email")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("flat_rate")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("expiration_date")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("cm")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <!-- <th>
                                    <a>
                                        <span class="text"> <?=lang("groupes")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th> -->
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("status")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("solde_publi")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <?=lang("actions")?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $key => $user) {?>
                            <tr>
                                <?php
                                        $userid = $user->IDUser;
                                        list($first_name, $last_name) = explode(' ', $user->fullname);
                                        $pack = $this->model->get("*", PACKAGES, "id = '".addslashes($user->package)."'");
                                        if(!is_null($pack)) {
                                            $pack_name =  $pack->name ;
                                            $number_posts = $pack->number_posts;
                                        } else {
                                            $pack_name = lang('without_pack');
                                            $number_posts = 0;
                                        }
                                        //$information = $this->model->get("*", 'user_information', "id_user = '".$user->id_user."'");
                                        
                                        $expiration_date = ($user->role == 'customer') ? $user->expiration_date : '--';
                                        
                                    ?>
                                <td <?=$user->IDUser?>><?php 
                                    if(empty($user->ids)){   
                                        echo '--';
                                    }else{
                                        echo $user->ids;  
                                    }
                                ?>
                                </td>
                                <td class="<?= $user->package?>"><?=$user->rs ?></td>
                                <td><?=$user->email ?></td>
                                <td><?php if (!is_admin()==true) { echo $pack_name;} else{ echo $user->package;}  ?></td>
                                <td><?php
                                    if($user->expiration_date!=NULL){

                                        $dt = DateTime::createFromFormat("Y-m-d", $user->expiration_date);
                                        $ts = $dt->getTimestamp();
                                        $newDate = date("d-m-Y", $ts);
                                        echo $newDate; 
                                    } 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $user_groups = $this->model->get_customer_managers($user->user_id);
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
                                <!-- <td>
                                    <?php 
                                        $user_groups = $this->model->fetch("*", "general_groups,user_group", "ids=id_group and id_user='{$user->ids}'");
                                        if(!empty($user_groups)){
                                            foreach ($user_groups as $key => $grp) {?>
                                                <span><?php if($key>0){ echo '-';} ?> <?= $grp->name ?></span>
                                    <?php   }
                                        }else{
                                            echo lang('Aucun');
                                        }
                                    ?>
                                </td> -->
                                <td><?=($user->status == 1) ? lang('enabled') : lang('disabled') ?></td>
                                <td><?= get_count_posts_user($userid,ST_PUBLISHED) ?></td>

                                <td>


                                    <div class="btn-group" role="group">
                                        <?php if (!is_manager()){?>

                                        <a class="btn btn-primary"
                                            href="<?=cn($module."/update/".$user->ID)?>?role=customer">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <?php }?>

                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?=lang("actions")?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item actionItem" href="<?=cn($module."/ajax_delete_item")?>"
                                                data-id="<?=$user->ids?>" data-redirect="<?=cn('users/customer')?>"
                                                data-confirm="<?=lang("are_you_sure_want_delete_it")?>"
                                                class="actionItem">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                <?=lang("delete")?>
                                            </a>

                                            <a class="dropdown-item" href="<?=cn($module."/show_user/".$user->ids)?>"
                                                data-id="<?=$user->ids?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <?=lang("show")?>
                                            </a>
                                        </div>
                                    </div>


                                </td>

                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                <?php if(!empty($result) && !empty($columns) && $this->pagination->create_links() != ""){?>
            <div class="clearfix"></div>
	  		<div class="card-footer">
				<?=$this->pagination->create_links();?>
	  		</div>
	  		<?php }?>
            </div>
        </div>
    </div>
</div>
<?php 
if(isset($_GET["search"])){
?>

    <script>
        $(document).ready(function(){
            setTimeout(() => {
                $('input[type="search"]').val("<?= $_GET["search"] ?>").trigger('keyup');
            }, 1000);
        })
    </script>
<?php 
 }
?>