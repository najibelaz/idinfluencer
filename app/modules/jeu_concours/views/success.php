<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
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
                    <span class="mb-2">
                        <?=lang("share_with")?>
                    </span>
                    <div class="social_share m-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= cn('game/'.$game->slug.'?refer='.$participant->ids )?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                            target="_blank" class="rounded-circle btn btn-primary"><i
                                class="zmdi zmdi-facebook"></i></a>
                        <a href="https://twitter.com/share?url=<?= cn('game/'.$game->slug.'?refer='.$participant->ids )?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                            target="_blank" class="rounded-circle btn btn-primary"><i
                                class="zmdi zmdi-twitter"></i></a>
                        <a href="<?= cn('game/'.$game->slug.'?refer='.$participant->ids )?>" class="copyulink rounded-circle btn btn-primary"><i
                                class="zmdi zmdi-copy"></i></a>
                    </div>
                    <form class="actionForm" action="<?= cn('send_parrain') ?>" >
                        <input class="form-control" type="hidden" name="parain" value="<?= $participant->id ?>">
                        <div class="parrainageEmail">
                            <div class="form-group">
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="email" class="form-control" name="email[]"
                                        value="" placeholder="<?=lang("email")?>">
                                    <button class="btn btn-primary addemailsucess">+</button>
                                </div>

                            </div>
                        </div>
                        <button type="submit"><?=lang("send")?></button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>