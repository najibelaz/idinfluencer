<?php
$etape = !is_null(get('etape')) ? (int) get('etape') : 1;
?>


<input class="form-control" type="hidden" name="id_game" value="<?=$game->ids?>">

<div class="container-fluid">
    <div class="row participer-form">
        <div class="col-md-12 p-0">
            <img class="img-bg" src="<?=$game->img?>" alt="">
            <div class="row m-0 form-content">
                <div class="offset-lg-1 offset-md-0 col-md-12 col-lg-5">
                    <div class="content mb-3">
                        <?php if ($etape == 1) {?>
                        <div id="fb-root"></div>

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
                                <div class="">
                                    <div class="single_post">
                                        <div class="d-none">
                                            <h3><?=nl2br($game->name)?></h3>
                                            <p><?=nl2br($game->description)?></p>
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
                                                <a href="#" class="btn btn-dark btn-sm"> <?=lang("show_more")?></a>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex justify-content-between flex-row-reverse">

                                            <?php if (in_array($etape, array(1, 2, 3))) {?>
                                            <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape + 1?>"
                                                class="btn btn-dark"><?=lang("suivant")?></a>
                                            <?php }?>
                                            <?php if (in_array($etape, array(2, 3, 4))) {?>
                                            <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape - 1?>"
                                                class="btn btn-dark"><?=lang("précédent")?></a>
                                            <?php }?>
                                
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">

                                            <div class="card-header">
                                                <h2>
                                                    <!-- <?=lang("inscription")?> -->
                                                    <?=nl2br($game->name)?> <br>
                                                    <small><?=nl2br($game->description)?></small> 
                                                </h2>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="name"><?=lang("civil state")?><span>*</span></label>
                                                            <div>
                                                                <div class="pure-checkbox grey mr15">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline">
                                                                        <input required type="radio" id="female"
                                                                            name="etat_civil"
                                                                            class="custom-control-input"
                                                                            value="<?=lang("Female")?>">
                                                                        <label class="custom-control-label"
                                                                            for="female">
                                                                            <?=lang("Female")?>
                                                                        </label>
                                                                    </div>

                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline">
                                                                        <input required type="radio" id="mal"
                                                                            name="etat_civil"
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
                                                                <input required type="text" class="form-control"
                                                                    name="prenom" id="prenom" value="">
                                                                    <label>
                                                                        <?=lang("first_nameG")?> *
                                                                    </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="placeholder-grp mb-3">
                                                                <input required type="text" class="form-control"
                                                                    name="nom" id="nom" value="">
                                                                    <label>
                                                                        <?=lang("last_nameG")?> *
                                                                    </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="placeholder-grp mb-3">
                                                                <input required type="email" class="form-control"
                                                                    name="email" id="email" value="">
                                                                    <label>
                                                                        <?=lang("email")?> *
                                                                    </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="placeholder-grp mb-3">
                                                                <input required type="text" class="form-control"
                                                                    name="phone" id="phone" value="">
                                                                <label>
                                                                    <?=lang("phone")?> *
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <div class="placeholder-grp mb-3">
                                                                <input required name="address" class="form-control"
                                                                    id="address" rows="3">
                                                                <label ><?=lang("Adresse")?> *</label>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <div class="placeholder-grp mb-3">
                                                                <input type="text" class="form-control"
                                                                    name="addresscomp" id="addresscomp" value="">
                                                                <label>
                                                                    <?=lang("complement_adresse")?>
                                                                </label>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="placeholder-grp mb-3">
                                                                <input required type="text" class="form-control"
                                                                    name="code_postal" id="code_postal" value="">
                                                                <label>
                                                                    <?=lang("postal code")?> *
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="placeholder-grp mb-0">
                                                                <select required class="form-control" name="trancheage"
                                                                    id="trancheage">
                                                                    <option><?=lang("Âge")?></option>
                                                                    <option>18 - 35</option>
                                                                    <option>35 - 55</option>
                                                                    <option>55+ </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                            
                                            <div class="card-body pt-0">
                                                <h5 class="card-title"><?=lang("questions")?></h5>
                                                <div class="form-group mb-3">
                                                    <label><?=$game->q1?><span>*</span></label>
                                                    <div>
                                                        <div class="pure-checkbox grey mr15">
                                                            <div
                                                                class="custom-control custom-radio custom-control-inline">
                                                                <input required type="radio" id="qt1-1" name="rep_q1"
                                                                    class="custom-control-input" value="1">
                                                                <label class="custom-control-label" for="qt1-1">
                                                                    <?=lang("Yes")?>
                                                                </label>
                                                            </div>
                                                            <div
                                                                class="custom-control custom-radio custom-control-inline">
                                                                <input required type="radio" id="qt1-2" name="rep_q1"
                                                                    class="custom-control-input" value="0">
                                                                <label class="custom-control-label" for="qt1-2">
                                                                    <?=lang("No")?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group mb-3">
                                                    <label><?=$game->q2?><span>*</span></label>
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

                                                <div class="form-group mb-0">
                                                    <label><?=$game->q3?><span>*</span></label>
                                                    <div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input required type="radio" id="qt3-1" name="rep_q3"
                                                                class="custom-control-input" value="0">
                                                            <label class="custom-control-label" for="qt3-1">
                                                                <?=lang("No")?>
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input required type="radio" id="qt3-2" name="rep_q3"
                                                                class="custom-control-input" value="0">
                                                            <label class="custom-control-label" for="qt3-2">
                                                                <?=lang("Yes")?>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3 pure-checkbox grey">
                                                            <input required class="inp-cbx filled-in chk-col-red"
                                                                name="reglement" id="reglement" type="checkbox" value="1" />

                                                            <label class="cbx" for="reglement">
                                                                <span>
                                                                    <svg width="12px" height="10px" viewbox="0 0 12 10">
                                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                                    </svg>
                                                                </span>
                                                                <span>
                                                                    <?=lang("i have read and accept the")?>
                                                                    <a href="<?=$game->reglement?>" target="_blank"
                                                                        style="text-decoration:underline"><?=lang("règlement")?>
                                                                    </a>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="pure-checkbox grey">
                                                            <input required class="inp-cbx filled-in chk-col-red"
                                                                name="newsletter" id="newsletter" type="checkbox" value="1" />

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
                                                <div class="btn-block d-flex justify-content-center">

                                                    <?php if (in_array($etape, array(1, 2, 3))) {?>
                                                    <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape + 1?>"
                                                        class="btn btn-dark"><?=lang("suivant")?></a>
                                                    <?php }?>
                                                    <?php if (in_array($etape, array(2, 3, 4))) {?>
                                                    <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape - 1?>"
                                                        class="btn btn-dark"><?=lang("précédent")?></a>
                                                    <?php }?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </div>
        <?php } elseif ($etape == 2) {?>
        <div class="justify-content-center">
            <div class="col-md-12">
                <div class="card mt-3">
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
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?=cn('game/?refer=')?>"
                                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                    target="_blank" class="rounded-circle btn btn-dark"><i
                                        class="zmdi zmdi-facebook"></i></a>
                                <a href="https://twitter.com/share?url=<?=cn('game/?refer=')?>"
                                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                    target="_blank" class="rounded-circle btn btn-dark"><i
                                        class="zmdi zmdi-twitter"></i></a>
                                <a href="<?=cn('game/?refer=')?>" class="rounded-circle copyulink btn btn-dark"><i
                                        class="zmdi zmdi-copy"></i></a>
                            </div>
                            <input class="form-control" type="hidden" name="parain" value="">
                            <div class="parrainageEmail">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" name="email[]" value=""
                                            placeholder="<?=lang("email")?>">
                                        <button class="btn btn-dark addemailsucess">+</button>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="card-body d-flex justify-content-between flex-row-reverse">

                            <?php if (in_array($etape, array(1, 2, 3))) {?>
                            <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape + 1?>"
                                class="btn btn-dark"><?=lang("suivant")?></a>
                            <?php }?>
                            <?php if (in_array($etape, array(2, 3, 4))) {?>
                            <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape - 1?>"
                                class="btn btn-dark"><?=lang("précédent")?></a>
                            <?php }?>
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } elseif ($etape == 3) {?>
        <div class="justify-content-center blog-page">
            <div class="col-md-12">
                <div class="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mt-3 border-danger-2">
                                <div class="card-body">
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
                                    <div class="card-body d-flex justify-content-between flex-row-reverse">

                                        <?php if (in_array($etape, array(1, 2, 3))) {?>
                                        <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape + 1?>"
                                            class="btn btn-dark"><?=lang("suivant")?></a>
                                        <?php }?>
                                        <?php if (in_array($etape, array(2, 3, 4))) {?>
                                        <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape - 1?>"
                                            class="btn btn-dark"><?=lang("précédent")?></a>
                                        <?php }?>
                            
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php } elseif ($etape == 4) {?>
        <div class="justify-content-center blog-page">
            <div class="col-md-12">
                <div class="row">
                    <div class="">
                        <div class="col-md-12">
                            <div class="card mt-3 border-primary-2">
                                <div class="card-body">
                                    <div class="card-imgdisc">
                                        <div class="img-box">
                                            <img src="https://via.placeholder.com/512x512" alt="">
                                        </div>
                                        <div class="card-content">
                                            <div class="award">
                                                <i class="fa fa-trophy" aria-hidden="true"></i>
                                                <span>
                                                    DAVID DAM
                                                </span>
                                            </div>
                                            <p><?= $game->text_jeu_termine ?></p>

                                        <a href="<?= $game->btn_jeu_termine ?>" class="btn btn-primary btn-sm"> <?=lang("show_more")?></a>

                                        </div>
                                    </div>
                                    <div class="card-body d-flex justify-content-between flex-row-reverse">

                                        <?php if (in_array($etape, array(1, 2, 3))) {?>
                                        <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape + 1?>"
                                            class="btn btn-dark"><?=lang("suivant")?></a>
                                        <?php }?>
                                        <?php if (in_array($etape, array(2, 3, 4))) {?>
                                        <a href="<?=cn('jeu_concours/show_game/')?><?=$game->ids?>?etape=<?=$etape - 1?>"
                                            class="btn btn-dark"><?=lang("précédent")?></a>
                                        <?php }?>
                            
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php }?>

     
    </div>
</div>
<div class="col-md-12 col-lg-4 p-0">

</div>
</div>
</div>