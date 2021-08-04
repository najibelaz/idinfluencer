<?php 
use Mailjet\Client;
use Mailjet\Resources;
?>
<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">mail</i>
            <?=lang("Mailjet")?> 
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="body">
                <?php if($result): ?>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("ID Contact")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Email")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre bloqué")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre rebondi")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre de clics")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre de différé")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre livré")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Nombre rebondi dur")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Dernière activité à")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Actions")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $apikey = get_option('mailjet_api');
		                    $apisecret = get_option('mailjet_secret');
                            $mj = new Client($apikey, $apisecret,true,['version' => 'v3']);
                            foreach ($result as $key => $stats) {
                                $params = array(
                                    "method"    => "GET",
                                    "ID"     => $stats['ContactID'],
                                );
                                $response = $mj->get(Resources::$Contact,$params);
                                $response->success();
                                $contact = $response->getData();
                                ?>
                            <tr>
                                <td><?=$stats['ContactID'] ?></td>
                                <td><?=$contact[0]['Email'] ?></td>
                                <td><?=$stats['BlockedCount'] ?></td>
                                <td><?=$stats['BouncedCount'] ?></td>
                                <td><?=$stats['ClickedCount'] ?></td>
                                <td><?=$stats['DeferredCount'] ?></td>
                                <td><?=$stats['DeliveredCount'] ?></td>
                                <td><?=$stats['HardBouncedCount'] ?></td>
                                <td><?=date('d-m-Y h:i', strtotime($stats['LastActivityAt'])); ?></td>
                                <td>
                                    <a class="btn btn-primary" href="/mailjet/contact_show/<?=$stats['ContactID'] ?>">
                                        <?=lang("details")?>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>
<script src="<?=BASE?>assets/square/js/sparkline.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/morrisscripts.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/knob.bundle.js<?=$version?>"></script>
<script src="<?=BASE?>assets/square/js/jquery.nestable.js<?=$version?>"></script>

