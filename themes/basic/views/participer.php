<form action="<?= cn('jeux_concours/add_participant') ?>" data-redirect="<?= cn('jeux_concours/success') ?>" class="actionFormpart">
<input class="form-control" type="hidden" name="id_game" value="<?= $game->ids ?>">
    <div class="row justify-content-center blog-page" >
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
                                <p><?= nl2br($game->description) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    <?=lang("to win")?>
                                </h2>
                            </div>
                            <div class="body">
                                <div class="card-imgdisc">
                                    <div class="img-box">
                                        <img src="https://via.placeholder.com/512x512" alt="">
                                    </div>
                                    <div class="card-content">
                                        <p>
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque odit a doloribus
                                            reiciendis cum ullam tempora ipsum exercitationem soluta? Placeat, harum
                                            recusandae.
                                        </p>
                                        <a href="#" class="btn btn-primary btn-sm"> <?=lang("show_more")?></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header">
                                    <h2><?=lang("inscription")?></h2>
                                </div>
                                <div class="body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email"><?=lang("email")?><span>*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="prenom"><?=lang("first_name")?><span>*</span></label>
                                                <input type="text" class="form-control" name="prenom" id="prenom" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="nom"><?=lang("last_name")?><span>*</span></label>
                                                <input type="text" class="form-control" name="nom" id="nom" value="">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"><?=lang("civil state")?><span>*</span></label>
                                                <div>
                                                    <div class="pure-checkbox grey mr15">
                                                        <input type="radio" id="mal" name="etat_civil"
                                                            class="filled-in chk-col-red" value=" <?=lang("Male")?>">
                                                        <label class="p0 m0" for="mal">&nbsp;</label>
                                                        <span class="checkbox-text-right"> <?=lang("Male")?></span>
                                                    </div>
                                                    <div class="pure-checkbox grey mr15">
                                                        <input type="radio" id="female" name="etat_civil"
                                                            class="filled-in chk-col-red" value="<?=lang("Female")?>">
                                                        <label class="p0 m0" for="female">&nbsp;</label>
                                                        <span class="checkbox-text-right"> <?=lang("Female")?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label for="code_postal"><?=lang("postal code")?><span>*</span></label>
                                                <input type="text" class="form-control" name="code_postal" id="code_postal" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="ville"><?=lang("city")?><span>*</span></label>
                                                <input type="text" class="form-control" name="ville" id="ville" value="">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address"><?=lang("address")?><span>*</span></label>
                                                <textarea name="address" class="form-control" id="address"
                                                    rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="pure-checkbox grey mr15 mt15">
                                                <input type="checkbox" id="reglement"
                                                    name="reglement" class="filled-in chk-col-red" value="1">
                                                <label class="p0 m0" for="reglement">&nbsp;</label>
                                                <span class="checkbox-text-right">
                                                    <?=lang("i have read and accept the rules")?></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="pure-checkbox grey mr15 mt15">
                                                <input type="checkbox" id="newsletter"
                                                    name="newsletter" class="filled-in chk-col-red" value="1">
                                                <label class="p0 m0" for="newsletter">&nbsp;</label>
                                                <span class="checkbox-text-right">
                                                    <?=lang("i agree to receive the newsletter")?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">

                                    <div class="header">
                                        <h2><?=lang("questions")?></h2>
                                    </div>
                                    <div class="body">
                                        <div class="form-group mb-4">
                                            <label><?= $game->q1?><span>*</span></label>
                                            <div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt1-1" name="rep_q1" class="filled-in chk-col-red"
                                                        value="1">
                                                    <label class="p0 m0" for="qt1-1">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("yes")?></span>
                                                </div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt1-2" name="rep_q1" class="filled-in chk-col-red"
                                                        value="0">
                                                    <label class="p0 m0" for="qt1-2">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("no")?></span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mb-4">
                                            <label><?= $game->q2?><span>*</span></label>
                                            <div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt2-1" name="rep_q2" class="filled-in chk-col-red"
                                                        value="1">
                                                    <label class="p0 m0" for="qt2-1">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("yes")?></span>
                                                </div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt2-2" name="rep_q2" class="filled-in chk-col-red"
                                                        value="0">
                                                    <label class="p0 m0" for="qt2-2">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("no")?></span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mb-4">
                                            <label><?= $game->q3?><span>*</span></label>
                                            <div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt3-1" name="rep_q3" class="filled-in chk-col-red"
                                                        value="1">
                                                    <label class="p0 m0" for="qt3-1">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("yes")?></span>
                                                </div>
                                                <div class="pure-checkbox grey mr15">
                                                    <input type="radio" id="qt3-2" name="rep_q3" class="filled-in chk-col-red"
                                                        value="0">
                                                    <label class="p0 m0" for="qt3-2">&nbsp;</label>
                                                    <span class="checkbox-text-right"> <?=lang("no")?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="body">
                            <button type="submit" class="btn btn-primary pull-right"><?=lang("validate")?></button>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</form>
