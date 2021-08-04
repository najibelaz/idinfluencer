<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?=lang('jeu_concours')?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("list des jeux")?></h2>
            </div>
            <div class="header justify-content-start justify-content-md-end flex-wrap">
            <?php if(!is_customer()){ ?>
                <a href="<?=cn($module."/update")?>" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("add_new")?></a>
            <?php } ?>
            </div>
            <div class="body">
                <?php if($result): ?>
                <div class="app-table table-responsive">
                    <table class="table center-aligned-table table-datatable" data-colexp="[1,2]">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nom du jeu")?></span>
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
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("description")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("date_debut")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("date_fin")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <?php if(!is_customer()){ ?>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("client")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <?php } ?>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nb_participant")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nb de partages")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nb de filleuls")?></span>
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
                            <?php foreach ($result as $key => $game) {?>
                            <tr>
                                <?php
                                        $user = $this->model->get("*", USERS, "id = '".$game->id_user."'"); 
                                        $panrticipant = $this->model->get("count(*) as count,sum(nbr_partage) as sum,sum(nbr_parrain) as sump", 'participant_jeux', "id_game = '".$game->ids."'"); 
                                        $strtime_date_fin =  strtotime($game->date_fin) ;
                                        $strtime_date_now =  strtotime(date('Y-m-d H:i:s')) ;
                                        $diff = $strtime_date_fin - $strtime_date_now;
                                                                 
                                    ?>
                                <td><?=$game->name ?></td>
                                <td>
                                    <span class="status<?= ($diff > 0) ? ' encours' : ' termine' ?>">
                                        <?=  ($diff > 0) ? lang('En cours') : lang('Terminé') ?>
                                    </span>
                                </td>
                                <td><?= (strlen($game->description)>25) ? substr($game->description, 0, 25)."..." : $game->description ?></td>
                                <td><?=$game->date_debut ?></td>
                                <td><?= $game->date_fin ?></td>
                                <?php if(!is_customer()){ ?>
                                    <td><?= $user->fullname ?></td>
                                <?php } ?>
                                <td><?= $panrticipant->count ?></td>
                                <td><?= ($panrticipant->sum != 0) ? $panrticipant->sum : '--' ?></td>
                                <td><?= ($panrticipant->sump != 0) ? $panrticipant->sump : '--' ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if(!is_customer()){ ?>
                                            <a class="btn btn-primary"
                                                href="<?=cn($module."/update/".$game->ids)?>">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                        <?php } ?>
                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?=lang("actions")?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <?php if($panrticipant->count != 0){ ?>
                                                <a class="dropdown-item" href="<?=cn($module."/detail_jeu/".$game->ids)?>"
                                                    >
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <?=lang("Participants")?>
                                                </a>
                                            <?php } ?>
                                            <?php if(!is_customer()){ ?>
                                                <a class="dropdown-item actionItem" href="<?=cn($module."/ajax_delete_item")."/".$game->ids?>"
                                                    data-redirect="<?=cn('jeu_concours')?>"
                                                    data-confirm="<?=lang("are_you_sure_want_delete_it")?>">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <?=lang("delete")?>
                                                </a>
                                                <a class="dropdown-item"  href="<?=cn($module."/update/".$game->ids."?update=1")?>">
                                                    <i class="fa fa-clone" aria-hidden="true"></i>
                                                    <?= lang("duplicate") ?>
                                                </a>
                                            <?php } ?>
                                            <a target="_blank" class="dropdown-item" href="<?=cn($module."/show_game/".$game->ids)?>"
                                                data-id="<?=$game->ids?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <?=lang("visualiser")?>
                                            </a>

                                            <?php if($diff <= 0 && !is_customer()) { ?>
                                            <a class="dropdown-item" href="<?=cn($module."/gagnant/".$game->ids)?>"
                                                data-id="<?=$game->ids?>">
                                                <i class="fa fa-trophy" aria-hidden="true"></i>
                                                <?=lang("generer le gagnant")?>
                                            </a>
                                            <?php } ?>

                                            <a target="_blank" class="dropdown-item" href="<?=cn("game/".$game->slug)?>">
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                                <?=lang("participer")?>
                                            </a>
                                            <a class="dropdown-item actionItem" href="<?=cn($module."/ajax_reset_item")?>"
                                                    data-id="<?=$game->ids?>" data-redirect="<?=cn('jeu_concours')?>"
                                                    data-confirm="<?=lang("voulez-vous vraiment réinitialiser (nb participants, nb de partages et nb de filleuls)")?>">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <?=lang("réinitialiser")?>
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