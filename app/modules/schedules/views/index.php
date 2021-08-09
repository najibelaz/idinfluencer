<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">date_range</i> <?=lang('publication_planning')?></h4>
    </div>
</div>

<div class="row">

    <a class="pn-toggle-open"><i class="fa fa-filter" aria-hidden="true"></i></a>
    <div class="col-md-12 col-lg-3 pn-box-sidebar" id="ttt">
        <form method="get" accept="<?=cn('schedules')?>">
            <div class="sc-options box-sc-option sn card ">
                <div class="box-sc-option box-sc-option-type ">
                    <div class="header pb-0">
                        <h2 class="title ntop"><?=lang("Schedule type")?></h2>
                    </div>
                    <div class="body">
                        <ul>
                            <li class="active">
                                <div class="checkbox">
                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_queue"
                                        <?php if(in_array(ST_PLANIFIED, $sc_type) || empty($sc_type)) {?> checked=""
                                        <?php } ?> value="<?=ST_PLANIFIED?>">
                                    <label for="md_checkbox_queue">
                                        <span class="name"><?=lang("Queue")?></span>
                                    </label>

                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_published"
                                        <?php if(in_array(ST_PUBLISHED, $sc_type) || empty($sc_type)) {?> checked=""
                                        <?php } ?> value="<?=ST_PUBLISHED?>">
                                    <label for="md_checkbox_published">
                                        <span class="name"><?=lang("Published")?></span>
                                    </label>

                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_unpublished"
                                        <?php if(in_array(ST_FAILED, $sc_type) || empty($sc_type)) {?> checked=""
                                        <?php } ?> value="<?=ST_FAILED?>">
                                    <label for="md_checkbox_unpublished">
                                        <span class="name"><?=lang("Unpublished")?></span>
                                    </label>

                                </div>
                            </li>
                        </ul>

                    </div>
                </div>

                <?php
                    $social_info = load_social_info(true);
                    $socials = array();
                    if (!empty($social_info)) {?>

                <div class="box-sc-option sn">
                    <div class="header pb-0">
                        <h2 class="title ntop"><?=lang("Social networks")?></h2>
                    </div>

                    <div class="body">
                        <ul>
                            <?php foreach ($social_info as $key => $row) {
                            $socials[] = strtolower($row->title);
                        ?>
                            <li>
                                <div class="checkbox">
                                    <input type="checkbox" name="social_filter[]" id="md_checkbox_<?=$row->title?>"
                                        <?php if(in_array($row->title, $social_filter) || empty($sc_type)) {?>
                                        checked="" <?php } ?> value="<?=str_replace(" ", "_", $row->title)?>">
                                    <label class="" for="md_checkbox_<?=$row->title?>">
                                        <span class="name" style="color: <?=$row->color?>"><i
                                                class="fa <?=$row->icon?>"></i>
                                            <?=$row->title?></span>
                                    </label>

                                </div>
                            </li>
                            <?php }?>
                        </ul>
                    </div>

                </div>
                <?php }?>

                <div class="box-sc-option aoption">
                    <div class="header pb-0">
                        <h2 class="title ntop"><?=lang("Date")?></h2>
                    </div>

                    <div class="body">
                        <ul>
                            <li class="box-border">
                                <div class="box-title"><?=lang("from")?> : </div>
                                <div class="box-content">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                                        <input type="text" name="date_from" class="form-control date"
                                            <?php if(get('date_from')){ ?> value="<?= get('date_from') ?>" <?php } ?>
                                            placeholder="Ex: 01/<?= date("m/Y")?>" value="01/<?= date("m/Y")?>">
                                    </div>
                                </div>
                            </li>
                            <li class="box-border">
                                <div class="box-title"><?=lang("to")?> : </div>
                                <div class="box-content">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                                        <input type="text" name="date_to" class="form-control date-end"
                                            <?php if(get('date_to')){ ?> value="<?= get('date_to') ?>" <?php } ?>
                                            placeholder="Ex: <?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>">
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="box-sc-option aoption body pb-3">
                    <button class="edit btn btn-raised btn-primary waves-effect btn-block"><i
                            class="material-icons">search</i><?=lang("search")?></button>
                </div>

            </div>
        </form>
    </div>
    <?php if(count($schedules) > 0) {?>
    <div class="col-md-12 col-lg-9">
        <?php foreach ($schedules as $key => $items_schedule) {?>
        <div class="card calander">
            <div class="header pb-0">
                <h2><?= $key ?></h2>
            </div>
            <div class="body">
                <ul class="list-unstyled">
                    <?php foreach ($items_schedule as $key => $item) {?>
                    <?php
                        $timestamp = strtotime($item->shedule_date);
                        setlocale(LC_TIME, 'fr_FR.utf8','fra');
                        $day_name = strftime('%A', $timestamp);
                        $day = date('d', $timestamp);
                        $mount = date('m', $timestamp);
                        $day_now = date('d');
                        $mount_now = date('m');
                        $post = get_post($item->id_post, $item->type);
                        $data = json_decode($post->data);
                        $data_preview = (array)$data;
                        $data_preview['idp'] = $item->id_post;
                        $text = $data->caption;
                        $disabed = ($day < $day_now && $mount <= $mount_now) ? 'disabled' : '';
                        ?>
                    <li class="calander-item <?=$disabed?>">
                        <div data-toggle="modal" data-target="#mainModal" class="popup" data-text="<?= $text; ?>">

                            <div class="popup-box">
                                <?= $this->load->view('preview/'.$item->type, $data_preview); ?>
                            </div>
                            <div class="date">

                                <span class="nday"><?= $day_name ?></span>
                                <span class="dday"><?= $day ?></span>
                            </div>
                            <div class="cinfo">
                                <div class="icon-top instagram">
                                    <i class="fa fa-<?= $item->type ?>"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="state">Auto-post</span>
                                    <h2 class="disc">
                                        <?=substr($text,0,100); ?>...

                                    </h2>
                                </div>

                            </div>
                        </div>
                        <?php if (!$disabed && (is_admin() || is_manager() || is_responsable())) {?>
                        <a href="<?=cn('post')?>?pid=<?=$item->id_post?>&social=<?= $item->type?>"
                            class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?>
                        </a>
                        <?php } ?>

                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php } ?>

    </div>
    <?php } else { ?>
    <div class="col-md-12 col-lg-9">
        <?php echo $this->load->view("empty_data"); ?>
    </div>

    <?php }?>
</div>

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