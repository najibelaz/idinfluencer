<?php
    $load_social_list = load_social_list();
    $social_first = strtolower(reset($load_social_list));
    $social_first = str_replace(" ", "_", $social_first);
    $type = get("type")?get("type"):$social_first;
    $report = block_report($type);
    $report_lists = json_decode($report->report_lists);
    $social_info = load_social_info();
    $module_name = "dashboard";
    $successed = get_schedules_report( 2);
    $failed = get_schedules_report( 3);
    
?>

<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">dashboard</i> <?=lang("dashboard")?></h4>
    </div>
</div>


<div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="card info-box-2 facebook-widget post-count">
            <div class="icon">
                <i style="color: #616161;" class="fa fa-paper-plane"></i></div>
            <div class="content">
                <div class="number">
                    <?php
                        echo get_posts_solde(session('uid'));
                    ?>
                    <?=lang("post")?>
                </div>
                <div class="d-flex w-100 justify-content-center mt-3 mb-2">
                    <span class="statistic">
                        <small><?=lang("solde")?></small>
                        <?php
                            echo get_posts_solde(session('uid'))-($f_posts_published+$i_posts_published+$t_posts_published)-($f_posts_planified+$t_posts_planified+$i_posts_planified);
                        ?>
                    </span>
                    <span class="statistic">
                        <small><?=lang("published")?></small>
                        <?php
                            echo $f_posts_published+$i_posts_published+$t_posts_published;
                        ?>
                    </span>
                    <span class="statistic">
                        <small><?=lang("planified")?></small>
                        <?php
                            echo $f_posts_planified+$t_posts_planified+$i_posts_planified;
                        ?>
                    </span>

                </div>

            </div>
        </div>
    </div>
    <?php
    
    foreach ($report_lists as $key => $row) {
        $hide = "";
        if((( $f_posts_published+$f_posts_planified) == 0 && $row->title == 'Facebook' ) || (( $t_posts_published+$t_posts_planified) == 0 && $row->title == 'Twitter' ) || (( $i_posts_published+$i_posts_planified) == 0 && $row->title == 'Instagram' ) ) {
                            $hide = "d-none";
                        }
?>
    <!-- <div class="col-lg-3 col-md-6 col-sm-12 <?= $hide ?>">
        <div class="card info-box-2 facebook-widget post-count">
            <div class="icon">
                <i style="color: <?=$row->color?>;" class="<?=$row->icon?>"></i></div>
            <div class="content">
                <div class="number">
                    <?php
                        if($row->title == 'Facebook') {
                            echo $f_posts_published+$f_posts_planified;
                        } elseif($row->title == 'Twitter') {
                            echo $t_posts_published+$t_posts_planified;
                        } elseif($row->title == 'Instagram') {
                            echo $i_posts_published+$i_posts_planified;
                        }
                    ?>
                    <?=lang("post")?>
                </div>
                <div class="d-flex w-100 justify-content-center mt-3 mb-2">
                    <span class="statistic">
                        <small><?=lang("published")?></small>
                        <?php
                        if($row->title == 'Facebook') {
                            echo $f_posts_published;
                        } elseif($row->title == 'Twitter') {
                            echo $t_posts_published;
                        } elseif($row->title == 'Instagram') {
                            echo $i_posts_published;
                        }
                        ?>
                    </span>
                    <span class="statistic">
                        <small><?=lang("planified")?></small>
                        <?php
                        if($row->title == 'Facebook') {
                            echo $f_posts_planified;
                        } elseif($row->title == 'Twitter') {
                            echo $t_posts_planified;
                        } elseif($row->title == 'Instagram') {
                            echo $i_posts_planified;
                        }
                        ?>
                    </span>


                </div>

            </div>
        </div>
    </div> -->

    <?php
    }
?>
</div>

<div class="row clearfix dashbord-chart">
    <div class="box-sc-option aoption col-12 d-flex flex-wrap">
    <ul class="d-flex align-itmes-center flex-wrap mt-4">
            <li class="box-border mr-3">

                <div class="box-content">
                    <div class="input-group">
                        <a href="#" class="btn btn-primary pull-right Filtre_spec" data-dateto="<?= date('d/m/Y') ?>"
                            data-datefrom="<?= date('d/m/Y') ?>"><?=lang("jour")?></a>
                    </div>
                </div>
            </li>
            <li class="box-border mr-3">

                <div class="box-content">
                    <div class="input-group">
                        <a href="#" class="btn btn-primary pull-right Filtre_spec"
                            data-dateto="<?= date('d/m/Y', strtotime('last day of this month')) ?>"
                            data-datefrom="<?= date('01/m/Y') ?>"><?=lang("mois")?></a>
                    </div>
                </div>
            </li>
            <li class="box-border mr-3">

                <div class="box-content">
                    <div class="input-group">
                        <a href="#" class="btn btn-primary pull-right Filtre_spec"
                            data-dateto="<?= date('d/m/Y' , strtotime("-1 day")) ?>"
                            data-datefrom="<?= date('d/m/Y' , strtotime("-1 day")) ?>"><?=lang("j-1")?></a>
                    </div>
                </div>
            </li>
            <li class="box-border mr-3">

                <div class="box-content">
                    <div class="input-group">
                        <a href="#" class="btn btn-primary pull-right Filtre_spec"
                            data-dateto="<?= date('Y-m-d', strtotime('last day of previous month')) ?>"
                            data-datefrom="<?= date('Y-m-d', strtotime('first day of -1 month')) ?>"><?=lang("m-1")?></a>
                    </div>
                </div>
            </li>
         

        </ul>
        <ul class="d-flex">
            <li class="box-border mr-3">
                <div class="box-title"><?=lang("from")?> : </div>
                <div class="box-content">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                        <input type="text" name="date_from" class="form-control filterdate date"
                            placeholder="Ex: 01/<?= date("m/Y")?>" value="01/<?= date("m/Y")?>">
                    </div>
                </div>
            </li>
            <li class="box-border">
                <div class="box-title"><?=lang("to")?> : </div>
                <div class="box-content">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                        <input type="text" name="date_to" class="form-control filterdate date-end"
                            placeholder="Ex:<?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>">
                    </div>
                </div>
            </li>

        </ul>

    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("wrok_report")?></h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);"><?=lang("action")?></a></li>
                            <li><a href="javascript:void(0);"><?=lang("another_ction")?></a></li>
                            <li><a href="javascript:void(0);"><?=lang("something_else")?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="m_area_chart" class="m-b-20" style="height: 250px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card circle-chart">
            <div class="header">
                <h2><?=lang("network_publications")?></h2>
            </div>
            <div class="body">
                <div class="sparkline-pie text-center"><?= $f_posts?>,<?= $i_posts?>,<?= $t_posts?></div>
                <div>
                    <ul class="unlist">
                    <?php 
                        foreach ($socials as $key => $value) {
                            echo '<li class="icon"><span><i class="fa fa-'.$key.'"></i> '.$value.'</span></li>';
                        } 
                    ?>                           
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row last-post">
    <?php 
        $data_fb = json_decode($lastpost_fb->data);
        $time = strtotime($lastpost_fb->time_post);
        $newformat = date('d-m-Y h:i',$time);       
    ?>
    <?php if(!is_null($data_fb)) { ?>
    <div class="col-sm-12 col-md-6 col-lg-4">
        
        <div class="card">
            <div class="preview-fb preview-fb-media">
                <div class="preview-header">
                    <div class="fb-logo"><i class="fa fa-facebook"></i></div>
                </div>
                <div class="preview-content">
                    <div class="user-info">
                        <img class="img-circle" src="<?= $lastpost_fb->avatar?>">
                        <div class="text">
                            <div class="name"> <?= $lastpost_fb->fullname?></div>
                            <span> <?= $newformat; ?> <i class="fa fa-globe" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="caption-info">
                        <?=substr($data_fb->caption,0,100); ?>...
                    </div>
                    <?php
                        if($lastpost_fb->type == "media"){
                            ?>
                    <div class="preview-image">
                        <?php
                                if($data_fb->media){
                                    $explode = explode(".",$data_fb->media[0]);
                                    $ext = $explode[count($explode)-1];
                                    if($ext == "mp4"){
                            ?>
                        <a href="<?= $data_fb->media[0]; ?>" data-type="ajax"
                            data-src="/file_manager/view_video?video=<?= $data_fb->media[0]; ?>" data-fancybox="">
                            <div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>
                            <video src="<?= $data_fb->media[0]; ?>" playsinline="" muted="" loop=""></video>
                        </a>
                        <?php
                                }else{
                                ?>
                        <a href="<?= $data_fb->media[0]; ?>" data-fancybox="group">
                            <img class="post-img" src="<?= $data_fb->media[0]; ?>" alt="">
                        </a>
                        <?php
                                }
                            }
                            ?>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="preview-comment">
                        <div class="item">
                            <i class="fb-icon like" aria-hidden="true"></i> Like </div>
                        <div class="item">
                            <i class="fb-icon comment" aria-hidden="true"></i> Comment </div>
                        <div class="item">
                            <i class="fb-icon share" aria-hidden="true"></i> Share </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>


    <?php 
        $data_ig = json_decode($lastpost_ig->data);
        $time = strtotime($lastpost_ig->time_post);
        $newformat = date('d-m-Y h:i',$time);
    ?>
    <?php if(!is_null($data_ig)) { ?>
    <div class="col-sm-12 col-md-6 col-lg-4">
        
        <div class="card">

            <div class="preview-instagram preview-instagram-photo">
                <div class="preview-header">
                    <div class="pull-left"><i class="ft-camera"></i></div>
                    <div class="instagram-logo"><img src="/public/instagram/assets/img/instagram-logo.png"></div>
                    <div class="pull-right"><i class="icon-paper-plane"></i></div>
                </div>
                <div class="preview-content">
                    <div class="user-info">
                        <img class="img-circle" src="<?= $lastpost_ig->avatar?>">
                        <span><?= $lastpost_ig->username?></span>
                    </div>
                    <?php
                       if($data_ig->media){
                    ?>
                    <div class="preview-image">
                        <?php
                        
                                $explode = explode(".",$data_ig->media[0]);
                                $ext = $explode[count($explode)-1];
                                if($ext == "mp4"){
                        ?>
                        <a href="<?= $data_ig->media[0]; ?>" data-type="ajax"
                            data-src="/file_manager/view_video?video=<?= $data_ig->media[0]; ?>" data-fancybox="">
                            <div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>
                            <video src="<?= $data_ig->media[0]; ?>" playsinline="" muted="" loop=""></video>
                        </a>
                        <?php
                            }else{
                            ?>
                        <a href="<?= $data_ig->media[0]; ?>" data-fancybox="group">
                            <img class="post-img" src="<?= $data_ig->media[0]; ?>" alt="">
                        </a>
                        <?php
                            }
                        ?>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="post-info">
                        <div class="pull-right"><?= $newformat; ?></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="caption-info pt0">
                        <?=substr($data_ig->caption,0,100); ?>...
                    </div>
                    <div class="preview-comment">
                        Ajouter un commentaire…
                        <div class="icon-3dot"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php } ?>

    <?php 
        $data_tw = json_decode($lastpost_tw->data);
        $time = strtotime($lastpost_tw->time_post);
        $newformat = date('d-m-Y h:i',$time);
    ?>
    <?php if(!is_null($data_tw)) { ?>
    <div class="col-sm-12 col-md-6 col-lg-4">
        
        <div class="card">
            <div class="preview-twitter preview-twitter-photo">
                <div class="preview-header">
                    <div class="twitter-logo"><i class="fa fa-twitter"></i></div>
                </div>
                <div class="preview-content">
                    <div class="user-info">
                        <img class="img-circle" src="<?= $lastpost_tw->avatar?>">
                        <div class="text">
                            <div class="name"><?= $lastpost_tw->username?></div>
                            <span>@<?= $lastpost_tw->username?></span>
                        </div>
                    </div>
                    <div class="caption-info">
                        <?=substr($data_tw->caption,0,100); ?>...
                    </div>
                    <?php
                        if($data_tw->media){
                    ?>
                    <div class="preview-image">

                        <?php
                            if($lastpost_tw->type == "video"){
                        ?>

                        <a href="<?= $data_tw->media[0]; ?>" data-type="ajax"
                            data-src="/file_manager/view_video?video=<?= $data_tw->media[0]; ?>" data-fancybox="">
                            <div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>
                            <video src="<?= $data_tw->media[0]; ?>" playsinline="" muted="" loop=""></video>
                        </a>
                        <?php
                            }else{
                        ?>
                        <a href="<?= $data_tw->media[0]; ?>" data-fancybox="group">
                            <img class="post-img" src="<?= $data_tw->media[0]; ?>" alt="">
                        </a>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                        }
                    ?>

                    <div class="post-info">
                        <div class="info-active"><?= $newformat; ?></div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="preview-comment">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <i class="fa fa-comment-o" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <i class="fa fa-retweet" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>


<!-- <script src="<?=BASE?>assets/square/js/jvectormap.bundle.js<?=$version?>"></script> -->
<!-- <script src="<?=BASE?>assets/square/js/knob.bundle.js<?=$version?>"></script> -->
<!-- <script src="<?=BASE?>assets/square/js/jquery.nestable.js<?=$version?>"></script> -->

<script src="<?=BASE?>assets/square/js/sparkline.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/morrisscripts.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/knob.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/jquery.nestable.js<?=$version?>"></script>

<script>
    $(function () {
        "use strict";
        var chart = Morris.Area({
            element: 'm_area_chart',
            data: <?= $successed -> value ?>,
            xkey: 'period',
            ykeys: [
                "<?=lang('facebook')?>",
                "<?=lang('instagram')?>",
                "<?=lang('twitter')?>",
            ],
            labels: [
                "<?=lang('facebook')?>",
                "<?=lang('instagram')?>",
                "<?=lang('twitter')?>",
            ],
            pointSize: 3,
            xLabelFormat: function(d) {
                return ('0' + d.getDate()).slice(-2)+'/'+('0' + (d.getMonth()+1)).slice(-2)+'/'+d.getFullYear(); 
            },
            xLabels:'day',
            dateFormat: function (ts) {
                            var d = new Date(ts);
                            return ('0' + d.getDate()).slice(-2)+'/'+('0' + (d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();},
            fillOpacity: 0,
            pointStrokeColors: ['#007FC1', '#FF00AA', '#00CCD3'],
            behaveLikeLine: true,
            gridLineColor: '#f6f6f6',
            lineWidth: 2,
            hideHover: 'auto',
            lineColors: ['#007FC1', '#FF00AA', '#00CCD3'],
            resize: true,
            hoverCallback: function (index, options, content, row) {
                var d = new Date(row.period);
                d = ('0' + d.getDate()).slice(-2)+'/'+('0' + (d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();
                return row.period + '<br><font color="#007FC1"> <?=lang("facebook")?> </font> : ' + row.<?= lang("facebook") ?> +'<br><font color="#FF00AA"> <?=lang("instagram")?> </font> : ' + row.<?= lang("instagram") ?> +'<br><font color="#00CCD3"> <?=lang("twitter")?> </font> : ' + row.<?= lang("twitter") ?> ;
            }

        });

    let $colors = [
                    <?php
    
    foreach ($social_info as $key => $row) {
?>
                '<?= $row->color ?>',
            <?php } ?>
            ];
        $('.sparkline-pie').sparkline(<?= $pie ?>, {
            type: 'pie',
            offset: 90,
            width: '180px',
            height: '180px',
            sliceColors: $colors
        });

    drawDocSparklines();
    drawMouseSpeedDemo();

    $('.dashbord-chart .filterdate').change(function () {
        var _that = $(this);
        var date_from = $('.dashbord-chart .date').val();
        var date_to = $('.dashbord-chart .date-end').val();
        var _action = "/dashboard/get_report_by_date";
        var _data = $.param({
            token: token,
            date_from: date_from,
            date_to: date_to
        });

        Main.ajax_post(_that, _action, _data, function (_result) {
            chart.setData(JSON.parse(_result.successed.value));
            $('.sparkline-pie').sparkline(JSON.parse(_result.pie), {
                type: 'pie',
                offset: 90,
                width: '180px',
                height: '180px',
                sliceColors: $colors
            });
            $('.unlist').empty();
            var socials = _result.socials;
            $.each(socials, function(index, value) {
               $('.unlist').append('<li class="icon"><span><i class="fa fa-'+index+'"></i>'+value+'</span></li>');
            }); 
        });

    })
    $('.Filtre_spec').click(function (event) {
        event.preventDefault();
        var _that = $(this);
        var date_from = _that.data('datefrom');
        var date_to = _that.data('dateto');
        filterhere(date_from, date_to, _that);
    });

    function filterhere(date_from, date_to, _that) {
        var _action = "/dashboard/get_report_by_date";
        var _data = $.param({
            token: token,
            date_from: date_from,
            date_to: date_to
        });

        Main.ajax_post(_that, _action, _data, function (_result) {
            chart.setData(JSON.parse(_result.successed.value));
            $('.sparkline-pie').sparkline(JSON.parse(_result.pie), {
                type: 'pie',
                offset: 90,
                width: '180px',
                height: '180px',
                sliceColors: $colors
            });
            $('.unlist').empty();
            var socials = _result.socials;
            $.each(socials, function (index, value) {
                $('.unlist').append('<li class="icon"><span><i class="fa fa-' + index + '"></i>' + value + '</span></li>');
            });

        });
    }
});


</script>