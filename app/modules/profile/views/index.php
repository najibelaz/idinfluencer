<div class="row">
    <a href="javascript:void(0);" class="pn-toggle-open col-12"><i class="ft-settings" aria-hidden="true"></i></a>
    <div class="col-md-12 col-lg-3">
        <div class="pn-box-sidebar card">
            <div class="header">
                <h2 class="head-title"><?=lang("profile")?></h2>

            </div>
            <div class="body">
                <div class="box-search">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                        <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                            aria-describedby="basic-addon2">
                    </div>
                </div>

                <div class="item item-2 border <?=segment(3) == "" ? "active" : ""?>">
                    <a href="<?=cn("profile/index")?>" data-content="pn-ajax-content" data-result="html"
                        class="actionItem" onclick="history.pushState(null, '', '<?=cn("profile/index")?>');">
                        <div class="icon"><i class="ft-user"></i></div>
                        <div class="content content-1">
                            <div class="title"><?=lang('mon compte')?></div>
                        </div>
                    </a>
                </div>
                <div class="item item-2 border <?=segment(3) == "change_password" ? "active" : ""?>">
                    <a href="<?=cn("profile/index/change_password")?>" data-content="pn-ajax-content" data-result="html"
                        class="actionItem"
                        onclick="history.pushState(null, '', '<?=cn("profile/index/change_password")?>');">
                        <div class="icon"><i class="ft-lock"></i></div>
                        <div class="content content-1">
                            <div class="title"><?=lang('change_password')?></div>
                        </div>
                    </a>
                </div>
                <?php if(is_customer()) { ?>
                <div class="item item-2 border <?=segment(3) == "package" ? "active" : ""?>">
                    <a href="<?=cn("profile/index/package")?>" data-content="pn-ajax-content" data-result="html"
                        class="actionItem" onclick="history.pushState(null, '', '<?=cn("profile/index/package")?>');">
                        <div class="icon"><i class="ft-package"></i></div>
                        <div class="content content-1">
                            <div class="title"><?=lang('package')?></div>
                        </div>
                    </a>
                </div>
                <div class="item item-2 border <?=segment(3) == "info" ? "active" : ""?>">
                    <a href="<?=cn("profile/index/info")?>" data-content="pn-ajax-content" data-result="html"
                        class="actionItem" onclick="history.pushState(null, '', '<?=cn("profile/index/info")?>');">
                        <div class="icon"><i class="ft-plus"></i></div>
                        <div class="content content-1">
                            <div class="title"><?=lang('Informations société')?></div>
                        </div>
                    </a>
                </div>
                <div class="item item-2 border <?=segment(3) == "facture" ? "active" : ""?>">
                    <a href="<?=cn("profile/index/facture")?>" data-content="pn-ajax-content" data-result="html"
                        class="actionItem" onclick="history.pushState(null, '', '<?=cn("profile/index/facture")?>');">
                        <div class="icon"><i class="ft-credit-card"></i></div>
                        <div class="content content-1">
                            <div class="title"><?=lang('Souscriptions')?></div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>


        </div>
    </div>
    <div class="col-md-12 col-lg-9">
        <div class="pn-box-content">
            <div class="setting-from">
                <div class="pn-ajax-content">
                    <?=$view?>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .pn-mode-settings .lead {
        font-size: 18px;
        border-left: 3px solid #2196f3;
        padding-left: 10px;
    }

    .pn-mode-settings .tab-list .card-header .nav-tabs>li>a {
        margin: 15px 15px 0 0;
        color: #000 !important;
        background: transparent !important;
    }

    .pn-mode-settings .tab-list .card-header .nav-tabs>li.active>a::before {
        content: '';
        border-bottom: 2px solid #0089cf;
        position: absolute;
        width: 100%;
        top: 37px;
        left: 0;
    }
</style>