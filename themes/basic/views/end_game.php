<div class="row justify-content-center blog-page">
    <div class="col-md-12 col-lg-8">
        <div class="row">
            <div class="card">
                <div class="col-md-12">
                    <div class="single_post">
                        <div class="body">
                            <h3 class="m-t-0 mb-0"><a href="">All photographs are accurate. None of them is the
                                    truth</a></h3>
                        </div>
                        <div class="body">
                            <div class="img-post m-b-15">
                                <img src="<?= $game->img ?>" alt="Awesome Image">

                            </div>
                            <p>It is a long established fact that a reader will be distracted by the readable content of
                                a page
                                when
                                looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
                                normal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php if(!$id_gagnant){ ?>
                        <div class="card border-danger-2">
                            <div class="body">
                                <div class="card-imgdisc">
                                    <div class="img-box">
                                        <img src="https://via.placeholder.com/512x512" alt="">
                                    </div>
                                    <div class="card-content">
                                        <div class="award">
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                            <span class="danger">
                                                <?=lang("game over")?>
                                            </span>
                                        </div>
                                        <p><?= $game->text_jeu_termine ?></p>

                                        <a href="<?= $game->btn_jeu_termine ?>" class="btn btn-primary btn-sm"> <?=lang("show_more")?></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php }else{ 
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
                                        <p><?= $game->text_jeu_termine ?></p>

                                        <a href="<?= $game->btn_jeu_termine ?>" class="btn btn-primary btn-sm"> <?=lang("show_more")?></a>

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