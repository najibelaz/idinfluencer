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
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("invoice_number")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("invoice_date")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("statut")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("raison social")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("total")?></span>
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
                            <?php
                            if($invoices == null) {
                                $invoices_json = get_option('invoices_json2');
                                $invoices_json = json_decode($invoices_json, true);
                                $invoices = $invoices_json['data'];
                            } 
                        ?>
                            <?php foreach ($invoices as $key => $invoice) {?>
                            <?php //dump($invoice); ?>
                            <tr>
                                <td><?=$invoice['invoice_nr_detailed'] ?></td>
                                <td><?=$invoice['date_formatted'] ?></td>
                                <td><?=($invoice['paid'] == 1) ? lang('payé') : lang('implayé') ?></td>
                                <td><?=$invoice['name'] ?>
                                <td><?=$invoice['total_price_excl_vat'] ?></td>
                                <td>
                                    <a class="btn btn-primary"
                                        href="<?=cn('orders/pdf')?>?order_id=<?=$invoice['id'] ?>&client=<?=$invoice['name'] ?>">
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