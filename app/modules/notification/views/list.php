
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><?=lang("lists_notif")?></h2>
            </div>
            <div class="body">
                <a class="btn btn-primary pull-right actionItem" href="<?=cn("notification/mark_all_read")?>" data-redirect="<?=cn('notification')?>">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    <?=lang("tout marquer comme lu")?>
                </a>
                <div class="table-responsive">
                    <table class="table center-aligned-table table-datatable">
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
                                <?php if(!is_customer()): ?>
                                    <th>
                                        <a>
                                            <span class="text"> <?=lang("author")?></span>
                                            <span class="sort-caret">
                                                <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </th>
                                <?php endif; ?>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("text")?></span>
                                        <span class="sort-caret">
                                            <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                            <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </th>
                                <th>
                                    <a>
                                        <span class="text"> <?=lang("time_post")?></span>
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
                            $notif = json_decode(json_encode($notif), true);;
                            foreach ($notif as $key => $value) {
                                $post = array();
                                if(!empty($value['social_label'])){
                                    $post = $this->model->get("*",$value['social_label'].'_posts' , "ids = '".$value['idp']."'");
                                    $information = $this->model->get("*", 'user_information', "id_user = '".$value['id_user_to']."'");
                                    $author = $this->model->get("fullname", USERS, "id = '".$value['id_user_from']."'");
                                    $time = strtotime($value['time_notif']);
                                    $newformat = date('d/m/Y h:i',$time);

                                }
                                ?>
                            <tr>
                                <td><?= $information->raison_social ?></td>                                        
                                <?php if(!is_customer()): ?>
                                    <td><?= $author->fullname ?></td>                                        
                                <?php endif; ?>
                                <td><?= lang($value['text']) ?></td>
                                <td><?= $newformat ?></td>
                                <td><?= ($value['read_'.$user->role])? lang('lu') : lang('non lu') ?></td>
                                <td>
                                    <a class="btn btn-primary" href="<?= cn('notification/view/'.$value['id']) ?>">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <?=lang("show")?>
                                    </a>
                                    <?php if(!empty($value['social_label'])){
                                        $data = json_decode($value['data']);
                                        $data_preview = (array)$data;
                                        $data_preview['idp'] = $value['id'];
                                        ?>
                                        <a class="btn btn-primary postprev" href="javascript:void(0)" data-toggle="modal" data-target="#mainModal">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <?=lang("Visualisation")?>
                                        </a>
                                        <div class="d-none">
                                            <div class="post-preview card">
                                                <?= $this->load->view('preview/'.$value['social_label'], $data_preview); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php }?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".postprev").click(function (e) {
        e.preventDefault();
        let popup = $(this).parents('td').find(".post-preview");
        $("#read-more-info").html("");
        popup.clone().prependTo("#read-more-info");
        $("#mainModal").html("");
        $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");

    });
</script>
<div id="read-more-box" class="d-none">
    <div class="modal-dialog read-more-box" role="document">
        <div class="modal-content">
            <div class="modal-body" id="read-more-info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-simple waves-effect"
                    data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
    </div>
</div>