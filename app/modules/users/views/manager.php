<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">assignment_ind</i>
            <?=lang('cm')?>
        </h4>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("lists_cm")?></h2>
            </div>
            <div class="header d-flex justify-content-end">
                <a href="<?=cn($module."/update")?>?role=manager" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("add_new")?></a>
            </div>
            <div class="body pt-0">
            <?php if($users): ?>

                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("fullname")?></span>
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
                                        <span class="text"> <?=lang("responsable")?></span>
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
                                    list($first_name, $last_name) = explode(' ', $user->fullname);
                                    $pack = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
                                ?>
                                <td>
                                    <?=$user->fullname ?>
                                </td>
                                <td><?= $user->email ?></td>
                                <td>
                                    <?php 
                                        $resp_groups = $this->model->get_manager_resps($user->id);
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
                                </td>
                                <!-- <td>
                                    <?php 
                                        $user_groups = $this->model->fetch("*", "general_groups,manager_group", "ids=id_group and id_user='{$user->ids}'");
                                        if(!empty($user_groups)){
                                            foreach ($user_groups as $key => $grp) {?>
                                                <span><?php if($key>0){ echo '-';} ?> <?= $grp->name ?></span>
                                    <?php   }
                                        }else{
                                            echo lang('Aucun');
                                        }
                                    ?>
                                </td> -->
                                <td>
                                    <?php
                                        $getSumPackCustomers = getSumPackCustomers($user->id_user);
                                        $managerP = get_count_posts_manager($user->ids,ST_PUBLISHED);
                                        $managerPl = get_count_posts_manager($user->ids,ST_PLANIFIED);
                                        $total = $getSumPackCustomers-($managerP + $managerPl)
                                    ?>
                                   <?= $total; ?>
                                </td>
                                <td>


                                    <div class="btn-group" role="group">
                                        <a class="btn btn-primary"
                                            href="<?=cn($module."/update/".$user->id)?>?role=manager">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?=lang("actions")?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">


                                            <a class="dropdown-item actionItem" href="<?=cn($module."/ajax_delete_item")?>"
                                                data-id="<?=$user->ids?>" data-redirect="<?=cn($module)?>"
                                                data-confirm="<?=lang("are_you_sure_want_delete_it")?>"
                                                >
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

            </div>
        </div>
    </div>
</div>