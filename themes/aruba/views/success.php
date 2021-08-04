<div class="container-fluid">
    <div class="row participer-form">
        <div class="col-md-12 p-0">
            <img class="img-bg img-1" src="<?=$game->img?>" alt="">
            <div class="row m-0 form-content">
                <div class="offset-lg-1 offset-md-0 col-md-12 col-lg-5">
                    <div class="content mb-3">
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-column">
                                    <h4 class="text-center mt-2">
                                        <?=lang("you have successfully participated")?>
                                    </h4>
                                    <span class="my-2 text-center">
                                        <?= lang('Pour augmenter vos chances de gagner à ce jeu, partagez avec votre communauté et parrainez vos amis'); ?>.
                                    </span>
                                    <div class="social_share m-3">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= cn('game/'.$game->slug.'?refer='.$participant->ids )?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                            target="_blank" class="rounded-circle btn btn-primary"><i
                                                class="zmdi zmdi-facebook"></i></a>
                                        <a href="https://twitter.com/share?url=<?= cn('game/'.$game->slug.'?refer='.$participant->ids )?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                            target="_blank" class="rounded-circle btn btn-primary"><i
                                                class="zmdi zmdi-twitter"></i></a>
                                    </div>

                                    <span class="mb-3">
                                        <?= lang('Parrainez vos amis et augmentez vos chances'); ?>
                                    </span>

                                    <form class="actionForm parrain_form" action="/send_parrain" data-redirect="<?= cn('game_success_2').'/'.$game->ids; ?>" >
                                        <input class="form-control" type="hidden" name="parain" value="<?= $participant->id ?>">
                                        <div class="parrainageEmail">
                                            <div class="form-group">
                                                <div class="placeholder-grp mb-3">
                                                    <input type="email" required class="form-control" name="email[0]"
                                                        value="">
                                                        <label>
                                                        <?=lang("email")?> *
                                                        </label>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <div class="placeholder-grp mb-3">
                                                    <input type="email" class="form-control" name="email[1]"
                                                        value="">
                                                    <label>
                                                    <?=lang("email")?>
                                                    </label>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <div class="input-group align-items-center">
                                                    <div class="placeholder-grp">
                                                        <input type="email" class="form-control" name="email[2]"
                                                            value="">
                                                        <label>
                                                        <?=lang("email")?>
                                                        </label>
                                                    </div>
                                                    <button type="button" class="btn addemailsucess">+</button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button class="btn btn-dark btn-lg mt-3" type="submit"><?=lang("envoyer")?></button>
                                            <a class="btn btn-link text-dark btn-lg  mt-3" href="<?= cn('game_success_2').'/'.$game->ids; ?>"><?=lang("Ignorer")?></a>
                                        </div>
                                       </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
    </div>
</div>