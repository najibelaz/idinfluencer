<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="fa fa-users" aria-hidden="true"></i> <?=lang('cm')?></h4>
    </div>
</div>


<div class="row">
    <a href="javascript:void(0);" class="pn-toggle-open col-12"><i class="ft-user" aria-hidden="true"></i></a>
    <div class="col-md-12 col-lg-3">
        <div class="pn-box-sidebar">
            <div class="card">
                <div class="header">
                    <h2><?=lang("User manager")?></h2>
                </div>
                <div class="body">
                    <div class="box-search">
                        <div class="input-group">
                            <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                aria-describedby="basic-addon2">
                            <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                        </div>
                    </div>

                    <div class="item item-2 border <?=segment(3)==""?"active":""?>">
                        <a href="<?=cn("cm")?>" data-content="pn-ajax-content" data-result="html" class="actionItem"
                            onclick="history.pushState(null, '', '<?=cn("cm")?>');">
                            <div class="icon"><i class="ft-user"></i></div>
                            <div class="content content-1">
                                <div class="title"><?=lang('List users')?></div>
                            </div>
                        </a>
                    </div>
                    <div class="item item-2 border <?=segment(3)=="social"?"active":""?>">
                        <a href="<?=cn("cm/index/statistics")?>" data-content="pn-ajax-content" data-result="html"
                            class="actionItem"
                            onclick="history.pushState(null, '', '<?=cn("cm/index/statistics")?>');">
                            <div class="icon"><i class="ft-bar-chart-2"></i></div>
                            <div class="content content-1">
                                <div class="title"><?=lang('User report')?></div>
                            </div>
                        </a>
                    </div>
                    <div class="item item-2 border">
                        <a href="<?=cn("cm/export")?>">
                            <div class="icon"><i class="ft-upload"></i></div>
                            <div class="content content-1">
                                <div class="title"><?=lang('Export')?></div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="col-md-12 col-lg-9">
        <div class="pn-box-content">
            <div class="pn-ajax-content">
                <?=$view?>
            </div>
        </div>
    </div>
</div>