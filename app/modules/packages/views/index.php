<div class="row">
    <a href="javascript:void(0);" class="pn-toggle-open col-12"><i class="ft-package" aria-hidden="true"></i></a>

    <div class="col-md-12 col-lg-3">
        <div class="pn-box-sidebar">
            <div class="card">
                <div class="header">
                    <h2 class="head-title"><?=lang("Package manager")?> <a href="<?=cn("packages/index/add")?>"
                            class="pull-right text-primary" title="<?=lang("add_new")?>"><i
                                class="ft-plus-circle"></i></a></h3>

                </div>
                <div class="body">

                    <div class="box-search">
                        <div class="input-group">
                            <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                aria-describedby="basic-addon2">
                            <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                        </div>
                    </div>

                    <?php if(!empty($packages)){
                    foreach ($packages as $package) {
                    ?>
                    <div class="item item-3 border">
                        <a href="<?=cn("packages/index/edit/".$package->ids)?>" data-content="pn-ajax-content"
                            data-result="html" class="actionItem"
                            onclick="history.pushState(null, '', '<?=cn("packages/index/edit/".$package->ids)?>');">
                            <div class="icon bg-success white"><i class="ft-package"></i></div>
                            <div class="content content-2">
                                <div class="title"><?=$package->name?></div>
                                <div class="desc"><span class="<?=$package->status?"text-success":"text-danger"?>"
                                        title="<?=$package->status?lang("enable"):lang("disable")?>"><?=$package->status?lang("enable"):lang("disable")?></span>
                                </div>
                            </div>
                        </a>
                        <?php if($package->type != 1){?>
                        <div class="option">
                            <div class="dropdown">
                                <button class="btn btn-neutral btn-icon  btn-icon-mini btn-round dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="ft-more-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="<?=cn("packages/ajax_delete_item/")?>" data-id="<?=$package->ids?>"
                                            data-redirect="<?=cn("packages")?>"
                                            class="actionItem"><?=lang("delete")?></a></li>
                                </ul>
                            </div>
                        </div>
                        <?php }?>

                    </div>
                    <?php }}?>

                </div>
            </div>


        </div>
    </div>
    <div class="col-md-12 col-lg-9">
        <form action="<?=cn("packages/ajax_update")?>" data-redirect="<?=cn($module)?>" class="actionForm"
            method="POST">
            <div class="pn-ajax-content">
                <?=$view?>
            </div>
        </form>
    </div>
</div>

<style type="text/css">
    .item.active .desc * {
        color: #fff !important;
    }
</style>