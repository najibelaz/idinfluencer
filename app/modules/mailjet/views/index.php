
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
                                        <span class="text"> <?=lang("ID")?></span>
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
                                        <span class="text"> <?=lang("date")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $email) {
                                // dump( $participant);
                                ?>
                            <tr>
                                <td><?=$email['ID'] ?></td>
                                <td><?=$email['Email'] ?></td>
                                <td><?=date('d-m-Y h:i', strtotime($email['CreatedAt'])); ?></td>
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

