<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 p-0">
            <div class="left-bar">
                <img class="img-bg" src="../assets/img/bg-login-id.jpg" alt="">
            </div>
        </div>
        <div class="col-md-6 p-0">
            <div id="fb-root"></div>
            <?php if($facebook){ ?>
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v6.0&appId=3052269154832889&autoLogAppEvents=1"></script>
                <div class="fb-page" data-href="https://www.facebook.com/<?= $facebook->fullname ?>" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/<?= $facebook->fullname ?>" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/<?= $facebook->fullname ?>">Facebook</a></blockquote></div>
            <?php } ?>
            <?php if($twitter){ ?>
                <a href="https://twitter.com/<?= $twiter->username ?>?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @<?= $twiter->username ?></a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <?php } ?>

            <form action="<?= cn('add_participant') ?>" data-redirecte="<?= cn('game_success') ?>"
                class="actionFormpart participer-form">
                <input class="form-control" type="hidden" name="id_game" value="<?= $game->ids ?>">
                <?php if(get('refer')){ ?>
                <input class="form-control" type="hidden" name="id_referal" value="<?= get('refer') ?>">
                <?php } ?>
                <?php if(get('parrain')){ ?>
                <input class="form-control" type="hidden" name="id_parrain" value="<?= get('parrain') ?>">
                <?php } ?>


                <div class="card">
                    </br>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="single_post">
                                <div class="card-body">
                                    <h3><?= nl2br($game->name) ?></h3>
                                    <p><?= nl2br($game->description) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h2>
                                    <?=lang("to win")?>
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="card-imgdisc">
                                    <div class="img-box">
                                        <img src="https://via.placeholder.com/512x512" alt="">
                                    </div>
                                    <div class="card-content">
                                        <p>
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque odit
                                            a
                                            doloribus
                                            reiciendis cum ullam tempora ipsum exercitationem soluta? Placeat,
                                            harum
                                            recusandae.
                                        </p>
                                        <a href="#" class="btn btn-primary btn-sm"> <?=lang("show_more")?></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">

                                    <div class="card-header">
                                        <h5><?=lang("inscription")?></h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="name"><?=lang("civil state")?><span>*</span></label>
                                                            <div>
                                                                <div class="pure-checkbox grey mr15">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline">
                                                                        <input required type="radio" id="female" name="etat_civil"
                                                                            class="custom-control-input"
                                                                            value="<?=lang("Female")?>">
                                                                        <label class="custom-control-label" for="female">
                                                                            <?=lang("Female")?>
                                                                        </label>
                                                                    </div>

                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline">
                                                                        <input required type="radio" id="mal" name="etat_civil"
                                                                            class="custom-control-input"
                                                                            value="<?=lang("Male")?>">
                                                                        <label class="custom-control-label" for="mal">
                                                                            <?=lang("Male")?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-user" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input required type="text" class="form-control" name="prenom"
                                                                    id="prenom" value=""
                                                                    placeholder="<?=lang("first_nameG")?> *">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-user" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input required type="text" class="form-control" name="nom" id="nom"
                                                                    value="" placeholder="<?=lang("last_nameG")?> *">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input required type="email" class="form-control" name="email" id="email"
                                                                    value="" placeholder="<?=lang("email")?> *">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                         <div class="form-group mb-4">
                                                            <label for="address"><?=lang("Adresse")?><span>*</span></label>
                                                            <textarea required name="address" class="form-control" id="address"
                                                                rows="3"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-building" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input type="text" class="form-control" name="addresscomp" id="addresscomp"
                                                                    value="" placeholder="<?=lang("complement_adresse")?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-inbox" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input required type="text" class="form-control" name="code_postal"
                                                                    id="code_postal" value=""
                                                                    placeholder="<?=lang("postal code")?> *">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-building" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <select required class="form-control" name="trancheage" id="trancheage">
                                                                    <option><?=lang("tranches d'âge")?></option>
                                                                    <option>18 - 35</option>
                                                                    <option>35 - 55</option>
                                                                    <option>55+ </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">
                                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input required type="text" class="form-control" name="phone" id="phone"
                                                                    value="" placeholder="<?=lang("phone")?> *">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-4 pure-checkbox grey">
                                                    <input required class="inp-cbx filled-in chk-col-red" name="reglement"
                                                        id="reglement" type="checkbox" 
                                                        value="1" />

                                                    <label class="cbx" for="reglement">
                                                        <span>
                                                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg>
                                                        </span>
                                                        <span>
                                                            <?=lang("i have read and accept the")?>
                                                            <a href="<?= $game->reglement ?>" target="_blank" download
                                                                style="text-decoration:underline"><?=lang("règlement")?>
                                                            </a>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-4 pure-checkbox grey">
                                                    <input required class="inp-cbx filled-in chk-col-red" name="newsletter"
                                                        id="newsletter" type="checkbox" 
                                                        value="1" />

                                                    <label class="cbx" for="newsletter">
                                                        <span>
                                                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg>
                                                        </span>
                                                        <span>
                                                            <?=lang("i agree to receive the newsletter")?>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title"><?=lang("questions")?></h5>
                                        <div class="form-group mb-4">
                                            <label><?= $game->q1?><span>*</span></label>
                                            <div>
                                                <div class="pure-checkbox grey mr15">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input required type="radio" id="qt1-1" name="rep_q1"
                                                            class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="qt1-1">
                                                            <?=lang("Yes")?>
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input  required type="radio" id="qt1-2" name="rep_q1"
                                                            class="custom-control-input" value="0">
                                                        <label class="custom-control-label" for="qt1-2">
                                                            <?=lang("No")?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mb-4">
                                            <label><?= $game->q2?><span>*</span></label>
                                            <div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input required type="radio" id="qt2-1" name="rep_q2"
                                                        class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="qt2-1">
                                                        <?=lang("Yes")?>
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input required type="radio" id="qt2-2" name="rep_q2"
                                                        class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="qt2-2">
                                                        <?=lang("No")?>
                                                    </label>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="form-group mb-4">
                                            <label><?= $game->q3?><span>*</span></label>
                                            <div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input required type="radio" id="qt3-2" name="rep_q3"
                                                        class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="qt3-2">
                                                        <?=lang("Yes")?>
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input required type="radio" id="qt3-1" name="rep_q3"
                                                        class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="qt3-1">
                                                        <?=lang("No")?>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <button type="button" class="btn btn-primary"><?=lang("validate")?></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                   

                </div>


            </form>
        </div>
    </div>
</div>