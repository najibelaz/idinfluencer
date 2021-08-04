<div>
	<a href="<?=cn("jeu_concours")?>" title="<?=lang("Back")?>" class="btn btn-secendary border-circle btn-back">
		<i class="zmdi zmdi-chevron-left"></i>
	</a>
</div>	
<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?php 
                $user = $this->model->get("*", USERS, "id = '".$game->id_user."'"); 
            ?>
            <?=lang("Participation_pour").' : "'.$game->name.'" de '.$user->rs ?> 
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="body">
                <?php if($result): ?>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable" data-title="<?= $game->name  ?>">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nom_du_jeu")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nom_participant")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("mail_paricipant")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("téléphone_participant")?></span>
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
                                    <a>
                                        <span class="text"> <?=lang("code_postal")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nbr_partage")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("nbr_parrainnge")?></span>
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
                                <td><?= lang($participant->etat_civil) ?></td>
                                <td><?= $participant->address  ?></td>
                                <td><?= $participant->code_postal  ?></td>
                                <td><?= $participant->nbr_partage  ?></td>
                                <td><?= $participant->nbr_parrain  ?></td>
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
<script src="<?=BASE?>assets/square/js/sparkline.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/morrisscripts.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/knob.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/jquery.nestable.js<?=$version?>"></script>
<script>
    $(document).ready(function() {
        $('.sparkline-pie').sparkline([1,5,6], {
            type: 'pie',
            offset: 90,
            width: '180px',
            height: '180px',
        });
        drawDocSparklines();
        drawMouseSpeedDemo();
    });
</script>
