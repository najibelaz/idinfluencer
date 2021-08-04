<?php if(count($invoices)): ?>
    <div class="col-12">
        <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text">No</span>
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
                                        <span class="text"> <?=lang("Phase")?></span>
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
                                        <span class="text"> <?=lang("Status")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>

                            </tr>
                        </thead>
                         <tbody>
                            <?php foreach ($invoices as $key => $invoice) {?>
                            <?php if($invoice['phase']=="Accepté" ){?>
                            <tr>
                                <td  ><?=$invoice['No'] ?></td>
                                <td  ><?=$invoice['Client'] ?></td>
                                <td  ><?=$invoice['Montant'] ?>  €</td>
                                <td  ><?=$invoice['phase'] ?></td>
                                <td  >
                                    <span class="text-uppercase">
                                    <?= date('d-m-Y',strtotime($invoice['date'])) ?></span>
                                </td>
                                <td  ><?= ($invoice['status'] == "won") ? "Signé" : "" ?>
                                <?= ($invoice['status'] == "lost") ? "Perdu" : "" ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
    </div>
    
    <?php 
    else:
        ?>
    <div class="col-12">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active text-center" id="about">
                    Aucune souscription trouvée
                </div>
            </div>
        </div>
    </div>
<?php        
    endif;