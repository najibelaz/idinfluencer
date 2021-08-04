<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">group</i>
            <?=lang('customers')?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("lists_customer")?></h2>
            </div>
            <div class="header d-flex justify-content-end">
                <a href="<?=cn($module."/update")?>?role=customer" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("add_new")?></a>
                <?php if(is_admin()) {  ?>
                <a href="<?=cn($module."/import_user")?>" class="btn btn-raised btn-primary waves-effect"><i
                        class="fa fa-plus"></i> <?=lang("import_from_teamleader")?></a>
                <?php } ?>
            </div>
            <div class="body">
                <div class="table-responsive app-table">
                    <table class="table center-aligned-table table-datatable">
                        <thead>
                            <tr>
                                <th>
                                    <a>
                                        <span class="text"> #</span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("id")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
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
                                        <span class="text"> <?=lang("email")?></span>
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
                            <?php
                            $iter = (int)$pag;
                            for ($i=0; $i <= $iter  ; $i++) { 
                                $str = "companies_list_{$i}";
                                $lists = json_decode(get_option($str)); 
                            ?>
                                <?php foreach ($lists as $key => $company) { ?>
                                <?php  //dump($company); ?>
                                <tr>
                                    <td><?= $key + 100*$i ?></td>
                                    <td><?= $company->id ?></td>
                                    <td><?= $company->name ?></td>
                                    <td><?= $company->email ?></td>
                                    <td><?= $company->status ?></td>
                                    <td>--</td>
                                </tr>
                                <?php } ?>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>