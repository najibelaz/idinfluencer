<?php 
use Mailjet\Client;
use Mailjet\Resources;
?>


<style>
    .t-label {
        font-size: 16px;
        font-weight: 400;
        color: #5e5e5e;
        margin-top: 5px;
    }
    .t-value {
        font-size: 20px;
        font-weight: 600;
        flex: 1;
    }
</style>

<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">mail</i>
            <?=lang("Mailjet")?> 
        </h4>
    </div>
</div>

<div class="row">
    <!-- <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>Title 1</h2>
              
            </div>
            <div class="body">
                <div id="m_area_chart" class="m-b-20" style="height: 250px;"></div>
            </div>
        </div>
    </div> -->
    <?php 
    
        $apikey = get_option('mailjet_api');
        $apisecret = get_option('mailjet_secret');
        $mj = new Client($apikey, $apisecret,true,['version' => 'v3']);
        $params = array(
            "method"    => "GET",
            "ID"     => $ContactID,
        );
        $response = $mj->get(Resources::$Contact,$params);
        $response->success();
        $contact = $response->getData();
    ?>
    <div class="col-md-12 col-lg-8">
        <div class="card">
            <div class="header">
                <h2>Details <?=$contact[0]['Email'] ?> </h2>
            </div>
            <div class="body">
                <div class="card">
                    <div class="d-flex">
                        <ul class="list-group list-group-flush w-100">
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("ID Contact")?>
                                </span>
                                <span class="t-value">
                                    <?= $ContactID ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre bloqué")?>
                                </span>
                                <span class="t-value">
                                    <?= $BlockedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre rebondi")?>
                                </span>
                                <span class="t-value">
                                    <?= $BouncedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                   <?=lang("Nombre de clics")?>
                                </span>
                                <span class="t-value">
                                    <?= $ClickedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre de différé")?>
                                </span>
                                <span class="t-value">
                                    <?= $DeferredCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre livré")?>
                                </span>
                                <span class="t-value">
                                    <?= $DeliveredCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre rebondi dur")?>
                                </span>
                                <span class="t-value">
                                    <?= $HardBouncedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre de files d'attente")?>
                                </span>
                                <span class="t-value">
                                    <?= $QueuedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre de pré-files d'attente")?>
                                </span>
                                <span class="t-value">
                                    <?= $PreQueuedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Nombre traités")?>
                                </span>
                                <span class="t-value">
                                    <?= $ProcessedCount ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="t-label mr-4">
                                    <?=lang("Dernière activité à")?>
                                </span>
                                <span class="t-value">
                                    <?=date('d-m-Y h:i', strtotime($LastActivityAt)); ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card circle-chart">
            <div class="header">
                <h2><?=lang("Stat")?></h2>
            </div>
            <div class="body">
                <div class="sparkline-pie text-center">
                </div>
                <div>
                    <ul class="unlist">
                       <li class="icon">
                           <span><i class="fa"> <?=lang("Nb de clics")?> : <?= $ClickedCount ?> </i> </span>
                        </li>
                       <li class="icon">
                           <span><i class="fa"> <?=lang("Nb livré")?> :  <?= $DeliveredCount ?> </i> </span>
                        </li>
                       <li class="icon">
                           <span><i class="fa"> <?=lang("Nb de files d'attente")?> : <?= $QueuedCount ?></i> </span>
                        </li>
                       <li class="icon">
                           <span><i class="fa"> <?=lang("Nb traités")?> : <?= $ProcessedCount ?></i> </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?=BASE?>assets/square/js/morrisscripts.bundle.js<?=$version?>"></script>

<script>
    $(function () {
        "use strict";
        // var chart = Morris.Area({
        //     element: 'm_area_chart',
        //     data: [
        //             {period: "2020-08-17", Facebook: "10", Instagram: "30", Twitter: "28"},
        //             {period: "2020-08-18", Facebook: "22", Instagram: "20", Twitter: "0"},
        //             {period: "2020-08-19", Facebook: "5", Instagram: "33", Twitter: "5"},
        //             {period: "2020-08-20", Facebook: "46=4", Instagram: "1", Twitter: "40"},
        //         ],
        //         xkey: 'period',
        //         ykeys: [
        //             "Facebook",
        //             "Instagram",
        //             "Twitter",
        //         ],
        //         labels: [
        //             "Facebook",
        //             "Instagram",
        //             "Twitter",
        //         ],
        //         xLabels:'day',
        //         pointStrokeColors: ['#007FC1', '#FF00AA', '#00CCD3'],
        //         lineColors: ['#007FC1', '#FF00AA', '#00CCD3'],

        // });

        $('.sparkline-pie').sparkline(
            [<?= $ClickedCount ?>, <?= $DeliveredCount ?>, <?= $QueuedCount ?>, <?= $ProcessedCount ?>], {
            type: 'pie',
            offset: 90,
            width: '180px',
            height: '180px',
            sliceColors: ['#007FC1', '#FF00AA', '#00CCD3','#005CD3']
        });

    })();
</script>