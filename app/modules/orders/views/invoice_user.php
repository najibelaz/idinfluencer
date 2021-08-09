<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">description</i>
            <?=lang('orders')?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("lists_orders")?></h2>
            </div>
            <div class="body">
            <a class="btn btn-primary download_all pull-right"
                href="javascript:void(0)">
                <?=lang("download_all")?> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </a>
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("No_")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Client")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Montant")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("date")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("Statut")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <?php if(is_customer()){ ?>
                                    <th>
                                        <a>
                                            <span class="text"> <?=lang("Paiement")?></span>
                                            <span class="sort-caret">
                                                <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </th>
                                <?php } ?>
                                <th>
                                    <?=lang("Factures")?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $key => $invoice) {?>
                                <?php
                                    $due_date_formatted = $invoice['due_date_formatted'];
                                    $dd = explode('/', $due_date_formatted);
                                    $due_date_formatted = $dd[2].'-'.$dd[1].'-'.$dd[0];
                                    $data['invoice'] = $invoice;
                                    $time = strtotime(str_replace('/', '-', $invoice['date_formatted'] ));
                                    $diff = strtotime('now') - $invoice['due_date']; 
                                    $diff = abs(round($diff / 86400)); 
                                    // $time = date('F Y',$time);
                                    setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
                                ?>
                            <?php 
                                $style = ($invoice['paid'] == 1) ? '' : 'style="color:red"'; 
                            ?>
                            <tr>
                                <td <?=$style?>>
                                    <div class="pure-checkbox grey mr15">
                                        <input type="checkbox" id="id_<?=$invoice['id'] ?>" name="etat_civil" class="filled-in chk-col-red" value=" <?=cn('orders/pdf_customer')?>?order_id=<?=$invoice['id'] ?>">
                                        <label class="p0 m0" for="id_<?=$invoice['id'] ?>">&nbsp;</label>
                                    </div>
                                </td>
                                <td <?=$style?> ><?=$invoice['invoice_nr_detailed'] ?></td>
                                <td <?=$style?> ><?=$invoice['name'] ?></td>
                                <td <?=$style?> ><?=$invoice['total_price_excl_vat'] ?>  €</td>
                                <td <?=$style?> >
                                    <span class="text-uppercase">
                                    <?= $invoice['due_date_formatted'] ?></span>
                                </td>
                                <td <?=$style?> >
                                    
                                    <?=($invoice['paid'] == 1) ? '<span class="status paye">'.lang('payé').'</span>' : lang('en retard').' ('.$diff.' jours)' ?>
                                    
                                </td>
                                <?php if(is_customer()){ ?>
                                    <td>
                                        <?php if($invoice['paid'] == 0) { ?>
                                        <?= $this->load->view("orders/formule_payment",$data ); ?>
                                    </td>
                                    <?php } ?>
                                <?php } ?>
                                <td>
                                    <a class="btn btn-primary" target="_blanck"
                                        href="<?=cn('orders/pdf_customer')?>?order_id=<?=$invoice['id'] ?>&client=<?=$invoice['name'] ?>">
                                        <?=lang("download")?> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                    
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>