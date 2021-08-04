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
    // dump($successed);
    // var_dump($pie);die;
?>

<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">dashboard</i> <?=lang("dashboard")?></h4>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card info-box-2 info-box-2-1">
            <div class="body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-12 d-flex justify-content-center align-items-center mb-3">
                            <div class="icon">
                                <div class="person">
                                    <i class="material-icons">person</i>
                                </div>
                            </div>
                            <div class="number">
                                <small><?=lang("nbr_users_dashb")?> :</small>
                                <?php $total = $countAdmins + $countManagers + $countCustomers + $countResp; ?>
                                <?php $total += $countAdminsD + $countManagersD + $countCustomersD + $countRespD; ?>
                                <?=$total ?>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <div class="content">
                                <span class="statistic">
                                    <small><?=lang("admins")?></small>
                                    <small><?=lang("a: ")?><?=$countAdmins ?></small>
                                    <small><?=lang("i: ")?><?=$countAdminsD ?></small>
                                </span>
                                <span class="statistic">
                                    <small><?=lang("responsables")?></small>
                                    <small><?=lang("a: ")?><?=$countResp ?></small>
                                    <small><?=lang("i: ")?><?=$countRespD ?></small>
                                </span>
                                <span class="statistic">
                                    <small><?=lang("managers")?></small>
                                    <small><?=lang("a: ")?><?=$countManagers ?></small>
                                    <small><?=lang("i: ")?><?=$countManagersD ?></small>
                                </span>
                                <span class="statistic">
                                    <small><?=lang("clients")?></small>
                                    <small><?=lang("a: ")?><?=$countCustomers ?></small>
                                    <small><?=lang("i: ")?><?=$countCustomersD ?></small>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-6 col-md-6">
        <div class="card info-box-2 info-box-2-1">
            <div class="body">
                <div class="row">
                    <div class="col-12 col-12 d-flex justify-content-center align-items-center mb-3">
                        <div class="icon">
                            <div class="person">
                                <i class="material-icons">person</i>
                            </div>
                        </div>
                        <div class="number">
                            <small><?=lang("nbr_users_without_group")?> :</small>
                            <?=$countnotResp + $countnotManagers + $countnotCustomers ?>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <div class="content">

                            <span class="statistic">
                                <small><?=lang("responsables")?></small>
                                <?=$countnotResp ?>
                            </span>
                            <span class="statistic">
                                <small><?=lang("managers")?></small>
                                <?=$countnotManagers ?>
                            </span>
                            <span class="statistic">
                                <small><?=lang("clients")?></small>
                                <?=$countnotCustomers ?>
                            </span>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div> -->
    <?php foreach($packages as $package){?>
    <div class="col-lg-4 col-md-6">
        <div class="card info-box-2">
            <div class="body">
                <div class="content col-12">
                    <div class="number"><?php echo $package->sum;?></div>
                    <div class="text">Formule :<?php echo $package->name;?> </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- <div class="col-lg-4 col-md-6">
        <div class="card info-box-2">
            <div class="body">
                <div class="icon col-12">
                    <div class="prime">
                        <i class="material-icons">stars</i>
                    </div>
                </div>
                <div class="content col-12">
                    <div class="number"><?=$count_standard ?></div>
                    <div class="text"><?=lang("formules_prime_actives")?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card info-box-2">
            <div class="body">
                <div class="icon col-12">
                    <span class="business">
                        <i class="material-icons">business_center</i>
                    </span>
                </div>
                <div class="content col-12">
                    <div class="number"><?=$count_premium ?></div>
                    <div class="text"><?=lang("formules_business_actives")?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card info-box-2">
            <div class="body">
                <div class="icon col-12">
                    <span class="business">
                        <img src="/assets/img/light-on.png">
                    </span>
                </div>
                <div class="content col-12">
                    <div class="number"><?=$count_trial ?></div>
                    <div class="text"><?=lang("formules_STARTER_actives")?></div>
                </div>
            </div>
        </div>
    </div> -->
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
                            placeholder="Ex: <?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>">
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("work_report")?></h2>
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
                <div class="sparkline-pie text-center">
                </div>
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

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("lists_cm_customer")?></h2>

            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-4">
                        <form action="<?= cn("users/customer") ?>" method="GET" class="form-inline">
                            <div class="form-group flex1 mr-2 mb-0">
                                <input type="text" name="search" class="form-control w-100" id="lists_cm_customer_search" placeholder="<?=lang("search")?>">
                            </div>
                            <button type="submit" class="btn btn-primary mb-2"><?=lang("submit")?></button>
                        </form>
                    </div>
                </div>
               
                <!-- <div class="table-responsive">
                    <table class="table center-aligned-table sq-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("raison_social")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("id_team_leader")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("email")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>

                                <th>
                                    <a>
                                        <span class="text"> <?=lang("role")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("flat_rate")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("expiration")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("status")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <?=lang("actions")?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $key => $user) {?>
                            <tr>
                                <?php
                                $userid = $user->id;
                                $pack = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
                                $pack_name = ($user->role == 'customer') ? $pack->name : '--';
                                $expiration_date = ($user->role == 'customer') ? date('d-m-Y',strtotime($user->expiration_date)) : '--';
                                $rs = ($user->role == "customer") ? $user->rs : lang('Id_influencer_team');

                                ?>
                                <td><?=$rs ?></td>
                                <td><?=$user->id_team ?></td>
                                <td><?=$user->email ?></td>
                                <td><?=get_role_name($user->role) ?></td>
                                <td><?=(!empty($pack_name)) ? $pack_name : lang('without_pack')?></td>
                                <td><?=$expiration_date?></td>
                                <td><?=($user->status == 1) ? lang('enabled') : lang('disabled') ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-primary"
                                            href="<?=cn("users/update/".$user->ids)?>?role=customer">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?=lang("actions")?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                            <a class="dropdown-item actionItem" href="<?=cn("users/ajax_delete_item")?>"
                                                data-id="<?=$user->ids?>" data-redirect="<?=cn('/dashboard')?>"
                                                data-confirm="<?=lang("are_you_sure_want_delete_it")?>">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                <?=lang("delete")?>
                                            </a>

                                            <a class="dropdown-item" href="<?=cn("users/show_user/".$user->ids)?>"
                                                data-id="<?=$user->ids?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <?=lang("show")?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div> -->
            </div>
        </div>
    </div>
</div>

<script src="<?=BASE?>assets/square/js/sparkline.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/morrisscripts.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/knob.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/jquery.nestable.js<?=$version?>"></script>

<script>
    $(function () {
        "use strict";
        var chart = Morris.Area({
            element: 'm_area_chart',
            data: <?= $successed->value ?>,
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
                return d + '<br><font color="#007FC1"> <?=lang("facebook")?> </font> : ' + row.<?= lang("facebook") ?> +'<br><font color="#FF00AA"> <?=lang("instagram")?> </font> : ' + row.<?= lang("instagram") ?> +'<br><font color="#00CCD3"> <?=lang("twitter")?> </font> : ' + row.<?= lang("twitter") ?> ;
            }

        });

        let $colors = [
                    <?php
    
    foreach($social_info as $key => $row) {
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
        filterhere(date_from, date_to, _that);
    });
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