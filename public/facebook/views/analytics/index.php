<?php
$module_name = segment(1);
$account = 0;
?>
<script src="<?=BASE."assets/plugins/jszip/jszip.min.js"?>"></script>
<script src="<?=BASE."assets/plugins/kendo/kendo.all.min.js"?>"></script>

<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="ft-bar-chart-2" aria-hidden="true"></i> <?=lang("analytics")?>
        </h4>
    </div>
</div>

<div class="wrap-content instagram-analytics-manager row app-mod">
    <div class="col-md-12">
        <ul class="am-mobile-menu card">
            <li><a href="javascript:void(0);" class="active" data-am-open="account"><?=lang("Accounts")?></a></li>
            <li><a href="javascript:void(0);" data-am-open="content"><?=lang("Analytics")?></a></li>
        </ul>
    </div>
    <div class="am-sidebar active col-md-12 col-lg-3 ">
        <div class="card">
            <div class="body">
                <?php if(!empty($accounts)){?>
                <div class="box-search">
                    <div class="input-group">
                        <input type="text" class="form-control am-search" placeholder="<?=lang("search")?>"
                            aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                    </div>
                </div>
                <ul class="box-list am-scroll">
                    <?php
                    foreach ($accounts as $key => $row) {
                    ?>
                    <li class="item <?= $row->ids == segment(3)?"active":""?>">
                        <a href="<?=cn("facebook/stats/".$row->ids."/analytics")?>" 
                            onclick="openContent();"
                            >
                            <div class="box-img">
                                <img src="<?=$row->avatar?>">
                                <div class="checked"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="pure-checkbox grey mr15">
                                <input type="radio" name="account[]" class="filled-in chk-col-red"
                                    value="<?=$row->ids?>" <?=$row->ids == segment(3)?"checked":""?>>
                                <label class="p0 m0" for="md_checkbox_<?=$row->pid?>">&nbsp;</label>
                            </div>
                            <div class="box-info">
                                <div class="title"><i class="fa fa-facebook"></i><?=$row->fullname?></div>
                                <div class="desc"><?=lang("Profile")?>
                                    <?=$row->status == 0?"<span class='text-danger'>".lang("re_login")."</span>":""?>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php }?>
                    <?php
                    foreach ($accounts_insta as $key => $row) {
                    ?>
                    <li class="item <?=$row->ids == segment(4)?"active":""?>">
                        <a href="<?=cn("instagram/analytics/stats/".$row->ids)?>" 
                            onclick="openContent();">
                            <div class="box-img">
                                <img src="<?=$row->avatar?>">
                                <div class="checked"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="pure-checkbox grey mr15">
                                <input type="radio" name="account[]" class="filled-in chk-col-red"
                                    value="<?=$row->ids?>" <?=$row->ids == segment(4)?"checked":""?>>
                                <label class="p0 m0" for="md_checkbox_<?=$row->pid?>">&nbsp;</label>
                            </div>
                            <div class="box-info">
                                <div class="title"><i class="fa fa-instagram"></i><?=$row->username?></div>
                                <div class="desc"><?=lang("Profile")?>
                                    <?=$row->status == 0?"<span class='text-danger'>".lang("re_login")."</span>":""?>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php }?>
                </ul>
                <?php }else{?>

                <div class="empty">
                    <span><?=lang("add_an_account_to_begin")?></span>
                    <a href="<?=PATH?>account_manager" class="btn btn-primary"><?=lang("add_account")?></a>
                </div>

                <?php }?>
            </div>
        </div>

    </div>
    <div class="am-wrapper col-md-12 col-lg-9">

        <div class="am-content col-md-12 am-scroll card">

            <div class="header">
                <h2 class="name">
                    <strong><i class="ft-bar-chart-2" aria-hidden="true"></i></strong> <?=lang("analytics")?>
                </h2>
                <div class="btn-group pull-right" role="group">
                    <a href="javascript:void(0);" class="btn btn-secondary ExportPDF"><i class="ft-upload"></i>
                        <?=lang("Export PDF")?></a>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="box-ajax-analytics">
                <?=$view?>

            </div>

        </div>

    </div>
</div>
<div id="bypassme"></div>

<script type="text/javascript">


    $(document).on("click", ".ExportPDF", function () {
        $(".none-export").hide();
        $("#box-analytics .aoption").hide()
        $("#box-analytics .aoption").removeClass("d-flex")
        $(".instagram-analytics-manager .container").css({ "width": 745 });
        Main.overplay();
        setTimeout(function () {
            ExportPdf();
        }, 1000);

        setTimeout(function () {
            $(".instagram-analytics-manager .container").attr("style", "");
            $(".none-export").show();
            $(".loading-overplay").hide();
        }, 4000);
    });

    function openContent() {
        if ($(window).width() <= 768) {
            $(".am-mobile-menu li a[data-am-open='content']").click();
        }
    }

    function ExportPdf() {
       
        kendo.pdf.defineFont({
            "DejaVu Sans": "<?=BASE."assets/plugins/kendo/fonts/"?>DejaVuSans.ttf",
            "DejaVu Sans|Bold"        : "<?=BASE."assets/plugins/kendo/fonts/"?>DejaVuSans-Bold.ttf",
            "DejaVu Sans|Bold|Italic" : "<?=BASE."assets/plugins/kendo/fonts/"?>DejaVuSans-Oblique.ttf",
            "DejaVu Sans|Italic"      : "<?=BASE."assets/plugins/kendo/fonts/"?>DejaVuSans-Oblique.ttf",
            "WebComponentsIcons"      : "<?=BASE."assets/plugins/kendo/fonts/"?>WebComponentsIcons.ttf"
        });

    kendo.drawing
        .drawDOM("#box-analytics",
            {
                forcePageBreak: ".page-break",
                paperSize: "A4",
                margin: { top: "1cm", bottom: "1cm" },
                scale: 0.8,
                height: 500,
                template: $("#page-template").html(),
                keepTogether: ".prevent-split"
            })
        .then(function (group) {
            console.log(group);
            kendo.drawing.pdf.saveAs(group, "Report_Facebook_Account.pdf")
            
            $("#box-analytics .aoption").addClass("d-flex")
        });
    }
    

</script>

<style type="text/css">
    .k-widget {
        font-family: "Arial", sans-serif;
        font-size: .9em;
    }

    .ig-analytics {
        border-top: 1px solid #f5f5f5;
        padding-top: 103px;
    }

    .app-mod .am-wrapper .am-content {
        padding: 0;
    }

    .am-content .head-title {
        padding: 10px;
        position: fixed;
        width: calc(100% - 370px);
        background: #fff;
        margin-bottom: 0;
        padding: 0 15px;
        height: 65px;
        line-height: 64px;
        border-bottom: 1px solid #f5f5f5;
        background: #fff;
        z-index: 10;
    }

    .am-content .head-title .name {
        display: inline-block;
    }

    .am-content .head-title .btn-group {
        margin-top: 14px;
    }

    .box-ajax-analytics {
        padding: 0 15px;
    }

    @media (max-width: 768px) {
        .am-mobile-menu li {
            width: 50%;
        }

        .am-content .head-title {
            width: 100%;
        }
    }
</style>