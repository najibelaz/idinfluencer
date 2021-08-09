<?php


?>
<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?=lang('Participation_pour').' : '.$game->name?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <a class="actionItemgagnant btn btn-primary" href="<?= cn('jeu_concours/generate_winner/'.$game->ids) ?>"> <?= lang('Generer un gagnant') ?></a>
                    <div class="<?= ($user==0)? 'd-none' : '' ?>">
                        <div class="award menu-toggle tggled waves-effect waves-block winnergame text-center">
                            <i class="fa fa-trophy" aria-hidden="true"></i>
                            <a class="" href="javascript:void(0);">
                            <?= ($user==0)? '' : $user->nom.' '.$user->prenom ?>
                            </a>
                        </div>
                        <?php if($user!=0){ ?>
                            <table class="table center-aligned-table ">
                                <thead>
                                    <tr>
                                        <th>
                                            <a>
                                                <span class="text"> <?=lang("name")?></span>
                                            </a>
                                        </th>
                                        <th>
                                            <a>
                                                <span class="text"> <?=lang("email")?></span>
                                            </a>
                                        </th>
                                        <th>
                                            <a>
                                                <span class="text"> <?=lang("phone")?></span>
                                            </a>
                                        </th>

                                        <th>
                                            <a>
                                                <span class="text"> <?=lang("etat_civil")?></span>
                                            </a>
                                        </th>
                                        <th>
                                            <a>
                                                <span class="text"> <?=lang("adresse")?></span>
                                            </a>
                                        </th>
                                        <th>
                                            <?=lang("actions")?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$user->nom ." ". $user->prenom ?></td>
                                        <td><?=$user->email ?></td>
                                        <td><?= $user->phone ?></td>
                                        <td><?= $user->etat_civil ?></td>
                                        <td><?= $user->address  ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="dropdown-item" href="<?=cn("jeu_concours/preview_participant/".$user->ids)?>">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    <?=lang("show")?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                                
                    </div>
                    
                </div>
            </div>           
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="body">
                <?php if($result): ?>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("game")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("name")?></span>
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
                                        <span class="text"> <?=lang("phone")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>

                                <th>
                                    <a>
                                        <span class="text"> <?=lang("etat_civil")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("adresse")?></span>
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
                            <?php foreach ($result as $key => $participant) {
                                // dump( $participant);
                                ?>
                            <tr>
                                <?php
                                        $game = $this->model->get("*", "jeux_concours", "ids = '".$participant->id_game."'");                                        
                                    ?>
                                <td><?=$game->name ?></td>
                                <td><?=$participant->nom ." ". $participant->prenom ?></td>
                                <td><?=$participant->email ?></td>
                                <td><?= $participant->phone ?></td>
                                <td><?= $participant->etat_civil ?></td>
                                <td><?= $participant->address  ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="dropdown-item" href="<?=cn("jeu_concours/preview_participant/".$participant->ids)?>"
                                            data-id="<?=$participant->ids?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <?=lang("show")?>
                                        </a>
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