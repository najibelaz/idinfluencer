<div class="container-fluid">
    <div class="row participer-form">
        <div class="col-md-12 p-0">
            <img class="img-bg img-1" src="<?=$game->img?>" alt="">
        </div>
        <div class="col-md-12 m-0 form-content">
            <div class="offset-lg-3 offset-md-0 col-md-5">
                <div class="content mb-3">
                    <div class="row">
                        <div class="col-md-12">
                        <br>
                            <div class="card ">

                                <div class="card-header">
                                    <h2>
                                        <?=lang("game over")?> -
                                                    <?=lang("en cours de selectionner un gagnant")?>
                                    </h2>
                                </div>
                                <?php if($id_gagnant){ ?>
                                <div class="card-body">
                                    <?php 
                                                $participant = $this->model->get("*", 'participant_jeux', "id = '".$id_gagnant."'");
                                                
                                                ?>
                                    <div class="card border-primary-2">
                                        <div class="body">
                                            <div class="card-imgdisc">
                                                <div class="img-box">
                                                    <img src="https://via.placeholder.com/512x512" alt="">
                                                </div>
                                                <div class="card-content">
                                                    <div class="award">
                                                        <i class="fa fa-trophy" aria-hidden="true"></i>
                                                        <span>
                                                            <?= $participant->nom. " " . $participant->prenom  ?>
                                                        </span>
                                                    </div>
                                                    <p>
                                                        <!-- Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque
                                                        odit
                                                        a
                                                        doloribus
                                                        reiciendis cum ullam tempora ipsum exercitationem soluta?
                                                        Placeat,
                                                        harum
                                                        recusandae. -->
                                                        <?=lang("jeu_concours_termine")?>
                                                    </p>

                                                    <a href="https://<?=lang("url_jeu_concours_termine")?>" class="btn btn-primary btn-sm">
                                                        <?=lang("show_more")?></a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php } ?>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</div>