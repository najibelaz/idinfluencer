
<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <?=$type?> : <small> <?=lang("lists_posts")?></small>
        </h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
    <div class="card">
        
        <div class="header d-flex justify-content-end">
            <a href="<?=cn("post")?>?role=customer" class="btn btn-raised btn-primary waves-effect"><i
                    class="fa fa-plus"></i> <?=lang("add_new")?></a>
        </div>
        <div class="body">
            <div class="table-responsive app-table">
                <table class="table center-aligned-table table-datatable">
                    <thead>
                        <tr>
                        <?php if(!is_customer()){ ?>
                            <th>
                                <a>
                                    <span class="text"> <?=lang("RS")?></span>
                                    <span class="sort-caret">
                                        <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                        <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </th>
                        <?php } ?>
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
                                    <span class="text"> <?=lang("content")?></span>
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
                                <a>
                                    <span class="text"> <?=lang("auteur")?></span>
                                    <span class="sort-caret">
                                        <i class="asc fa fa-sort-asc" aria-hidden="true"></i>
                                        <i class="desc fa fa-sort-desc" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </th>
                            <th>
                                <a>
                                    <span class="text"> <?=lang("time")?></span>
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
                        <?php foreach ($posts as $key => $post) {?>
                        <tr <?= get_status($post->status)?>>
                            <?php
                                $data = json_decode($post->data);
                                $ref = '';
                                if($post->social_label == 'instagram') {
                                    $ref = 'INSTA-';
                                }
                                if($post->social_label == 'facebook') {
                                    $ref = 'FA-';
                                }
                                if($post->social_label == 'twitter') {
                                    $ref = 'TW-';
                                }
                        		$user = $this->model->get("*", USERS, "id = '".$post->uid."'");
                                ?>
                            <?php if(!is_customer()){ ?>
                                <td><?=$user->rs; ?></td>
                            <?php } ?>
                            <td><?=$ref.$post->id; ?></td>
                            <td><?=substr($data->caption,0, 25); ?>...</td>
                            <td><?php
                                if (get_status($post->status) == "PUBLISHED"){
                                    echo lang("publier");
                                }
                                if (get_status($post->status) == "PLANIFIED"){
                                    echo lang("planifiée");
                                }
                                if (get_status($post->status) == "INVALID"){
                                    echo lang("non valide");
                                }
                                if(get_status($post->status) == "FAILED"){
                                    echo lang("echoue");
                                }
                                if(get_status($post->status) == "DRAFT"){
                                    echo lang("brouillon");
                                }
                                
                                if(get_status($post->status) == "WAITTING"){
                                    echo lang("en_attendant");
                                }
                                if(get_status($post->status)=="DELETED"){
                                    echo lang("supprimée");
                                }
                                ?>
                            </td>
                            <td>--</td>
                            <td><?=get_timezone_user($post->time_post, false) ?></td>
                            <td>

                                <div class="btn-group" role="group">
                                    <?php if($post->status != 2 && !is_customer()){ ?>
                                        <a  class="btn btn-warning" href="<?=cn('post')?>?pid=<?=$post->id; ?>&social=<?= strtolower($type)?>">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    <?php }?>
                                    <button id="btnGroupDrop1" type="button" class="btn btn-warning dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?=lang("actions")?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item postprev" href="javascript:void(0)" data-toggle="modal" data-target="#mainModal">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <?=lang("show")?>
                                        </a>
                                    <?php if(!is_customer()){ ?>
                                        <a class="dropdown-item"
                                            href="<?=cn('post')?>?pid=<?=$post->id; ?>&social=<?= strtolower($type)?>&action=duplicate">
                                            <i class="fa fa-clone" aria-hidden="true"></i>
                                            <?=lang("duplicate")?>
                                        </a>
                                        <button class="dropdown-item delete btn-delete"
                                            data-social="<?= strtolower($type)?>" data-pid="<?=$post->id?>"
                                            data-redirect="<?=cn("post/".strtolower($type))?>" 
                                            data-confirm="<?=lang("are_you_sure_want_delete_it")?>">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            <?=lang("delete")?>
                                        </button>
                                    <?php }?>
                                    </div>
                                </div>
                                <div class="d-none">
                                    <div class="post-preview card">
                                        <?php 
                                        $data = json_decode($post->data);
                                        $data_preview = (array)$data;
                                        $data_preview['idp'] = $item->id;
                                        
                                        $this->load->view('preview/'.$post->social_label, $data_preview); ?>
                                    </div>
                                </div>


                            </td>

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php if(!empty($posts) && !empty($columns) && $this->pagination->create_links() != ""){?>
			<div class="card-footer">
				<?=$this->pagination->create_links();?>
			</div>
			<?php }?>
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
<script type="text/javascript">
    $(".btn-delete").on("click", function () {
        var social = $(this).data('social');
        var pid = $(this).data('pid');
        var _that = $(this);
        var _action = PATH + "waiting/ajax_update_status_delete";
        var _data = $.param({
            token: token,
            pid: pid,
            social: social,
        });
        Main.ajax_post(_that, _action, _data, null);
    });



</script>