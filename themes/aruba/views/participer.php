<div class="container-fluid">
    <div class="row participer-form">
        <div class="col-md-12 p-0">
            <img class="img-bg img-1" src="<?=$game->img?>" alt="">
            <div class="row m-0 form-content">
                <div class="offset-lg-1 offset-md-0  col-md-12 col-lg-5">
                    <div class="content mb-3">
                        <div id="fb-root"></div>

                        <form action="<?=cn('add_participant')?>" data-redirecte="<?=cn('game_success')?>"
                            class="actionFormpart participer-form">
                            <input class="form-control" type="hidden" name="id_game" value="<?=$game->ids?>">
                            <?php if (get('refer')) {?>
                            <input class="form-control" type="hidden" name="id_referal" value="<?=get('refer')?>">
                            <?php }?>
                            <?php if (get('parrain')) {?>
                            <input class="form-control" type="hidden" name="id_parrain" value="<?=get('parrain')?>">
                            <?php }?>


                            <div class="">
                                </br>
                                <div class="col-md-12">
                                        <div class="single_post">
                                            <div class="d-none">
                                                <h3><?=nl2br($game->name)?></h3>
                                                <p><?=nl2br($game->description)?></p>
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
                                                    <a href="#" class="btn btn-dark btn-sm"> <?=lang("show_more")?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card ">

                                            <div class="card-header">
                                                <h2>
                                                    <!-- <?=lang("inscription")?> -->
                                                    <?=nl2br($game->name)?> <br>
                                                    <small>
                                                    <?=nl2br($game->description)?>
                                                    </small>
                                                </h2>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-12 ">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name"><?=lang("civil state")?><span> *</span></label>
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
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input required type="text" class="form-control" name="prenom"
                                                                            id="prenom" value="">
                                                                            <label>
                                                                                <?=lang("first_nameG")?> *
                                                                            </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input required type="text" class="form-control" name="nom" id="nom"
                                                                            value="">
                                                                            <label>
                                                                                <?=lang("last_nameG")?> *
                                                                            </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input required type="email" class="form-control" name="email" id="email"
                                                                            value="" >
                                                                            <label>
                                                                                <?=lang("email")?> *
                                                                            </label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-0">
                                                                        <input required type="text" class="form-control" name="phone" id="phone"
                                                                            value="">
                                                                            <label>
                                                                                <?=lang("phone")?> *
                                                                            </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input required name="address" class="form-control" id="address">
                                                                        <label>
                                                                            <?=lang("Adresse")?>*
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input type="text" class="form-control" name="addresscomp" id="addresscomp"
                                                                            value="">
                                                                        <label>
                                                                            <?=lang("complement_adresse")?>
                                                                        </label>
                                                                    </div>
                                                                </div> -->
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <input required type="text" class="form-control" name="code_postal"
                                                                            id="code_postal" value="">
                                                                            <label>
                                                                                <?=lang("postal code")?> *
                                                                            </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="placeholder-grp mb-3">
                                                                        <select required class="form-control" name="trancheage" id="trancheage">
                                                                            <option><?=lang("Âge")?></option>
                                                                            <option>18 - 35 ans</option>
                                                                            <option>35 - 55 ans</option>
                                                                            <option>55 ans + </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>

                                                    
                                                </div>



                                            </div>
                                            
                                            <div class="card-body pt-0">
                                                <?php if(!empty(trim($game->q1)) && !empty(trim($game->q2)) && !empty(trim($game->q3))) { ?>
                                                    <h5 class="card-title"><?=lang("questions")?></h5>
                                                <?php } ?> 
                                                <?php if(!empty(trim($game->q1))) { ?>
                                                <div class="form-group mb-4">
                                                    <label><?=$game->q1?><span></span></label>
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
                                                <?php } else { ?>
                                                    <input required type="radio" id="qt1-1" name="rep_q2"
                                                                class="custom-control-input" value="0" checked style="display:none">
                                                <?php } ?> 

                                                <?php if(!empty(trim($game->q2))) { ?>
                                                <div class="form-group mb-4">
                                                    <label><?=$game->q2?><span></span></label>
                                                    
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
                                                <?php } else { ?>
                                                    <input required type="radio" id="qt2-1" name="rep_q2"
                                                                class="custom-control-input" value="0" checked style="display:none">
                                                <?php } ?>

                                                <?php if(!empty(trim($game->q3))) { ?>
                                                <div class="form-group mb-0">
                                                    <label><?=$game->q3?><span></span></label>
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
                                                <?php } else { ?>
                                                    <input required type="radio" id="qt3-1" name="rep_q2"
                                                                class="custom-control-input" value="0" checked style="display:none">
                                                <?php } ?>
                                                <!-- <?php if ($facebook) {?>
                                                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v6.0&appId=3052269154832889&autoLogAppEvents=1"></script>
                                                    <div class="fb-page" data-href="https://www.facebook.com/<?=$facebook->fullname?>" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
                                                    <blockquote cite="https://www.facebook.com/<?=$facebook->fullname?>" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/<?=$facebook->fullname?>">Facebook</a></blockquote></div>
                                                <?php }?>
                                                <?php if ($twitter) {?>
                                                    <a href="https://twitter.com/<?=$twiter->username?>?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @<?=$twiter->username?></a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                                <?php }?> -->

                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3 pure-checkbox grey">
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
                                                                    <a href="<?=$game->reglement?>" target="_blank"
                                                                        style="text-decoration:underline"><?=lang("règlement")?> *
                                                                    </a>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="pure-checkbox grey">
                                                            <input class="inp-cbx filled-in chk-col-red" name="newsletter"
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

                                            <div class="card-body d-flex justify-content-center btn-block ">
                                                <button type="submit" class="btn btn-dark btn-lg"><?=lang("PARTICIPER")?></button>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>