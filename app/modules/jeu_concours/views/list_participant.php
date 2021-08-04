<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?=lang('games')?>
        </h4>
    </div>
</div>
<a href="<?=cn("jeu_concours")?>" title="<?=lang("Back")?>" class="btn btn-secendary border-circle btn-back">
    <i class="zmdi zmdi-chevron-left"></i>
</a>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("listes_participant")?></h2>
            </div>
            <div class="body">
                <?php if($result): ?>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Jeu")?></span>
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
                                        <span class="text"> <?=lang("phone")?></span>
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
                                <th hidden="hidden"><?= lang('code_postal') ?></th>
                                <th hidden="hidden"><?= lang('Question') ?> 1</th>
                                <th hidden="hidden"><?= lang('Question') ?> 2</th>
                                <th hidden="hidden"><?= lang('Question') ?> 3</th>
                                <th hidden="hidden"><?= lang('newsletter') ?></th>
                                <th hidden="hidden"><?= lang('reglement') ?></th>
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
                                <td><?= lang($participant->etat_civil) ?></td>
                                <td><?=$participant->nom ." ". $participant->prenom ?></td>
                                <td><?=$participant->email ?></td>
                                <td><?= $participant->phone ?></td>
                                <td><?= (strlen($participant->address)>25) ? substr($participant->address, 0, 25)."..." : $participant->address ?></td>
                                <td hidden="hidden"><?= $participant->code_postal  ?></td>
                                <td hidden="hidden"><?= $participant->rep_q1  ?></td>
                                <td hidden="hidden"><?= $participant->rep_q2  ?></td>
                                <td hidden="hidden"><?= $participant->rep_q3  ?></td>
                                <td hidden="hidden"><?= $participant->reglement  ?></td>
                                <td hidden="hidden"><?= $participant->newsletter  ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="dropdown-item" href="<?=cn($module."/preview_participant/".$participant->ids)?>"
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