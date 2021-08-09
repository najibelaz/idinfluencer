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
                                    <span class="text"> <?=lang("reference")?></span>
                                    <span class="sort-caret">
                                        <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                        <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </th>
                            <th>
                                <a>
                                    <span class="text"> <?=lang("first_name")?></span>
                                    <span class="sort-caret">
                                        <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                        <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </th>
                            <th>
                                <a>
                                    <span class="text"> <?=lang("last_name")?></span>
                                    <span class="sort-caret">
                                        <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                        <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </th>
                            <th>
                                <a>
                                    <span class="text"> <?=lang("salutation")?></span>
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
                                <?=lang("actions")?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $key => $order) {?>
                    	<tr>
                            <td><?=$order->id?></td>
                            <td><?=$order->first_name?></td>
                            <td><?=$order->last_name?></td>
                            <td><?=$order->salutation?></td>
                    		<td><?=$order->email?></td>
                    		<td>
                            <a href="<?=cn('orders/pdf_customer')?>?order_id=<?=$order->id?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>      
                            </td>
                    	</tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>