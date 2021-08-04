<div class="container-fluid">
    <div class="row participer-form">
        <div class="col-md-12 p-0">
            <img class="img-bg" src="<?=$game->img?>" alt="">
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
                                    <span class="my-2">
                                        <?=lang('Merci pour votre participation au jeu concours : ');?> <?=$game->name?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>