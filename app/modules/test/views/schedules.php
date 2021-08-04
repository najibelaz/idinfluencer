<div class="row">

    <div class="col-md-12 col-lg-3">

        <div class="sc-options">
            <div class="box-sc-option box-sc-option-type card">
                <div class="header">
                    <h2 class="title"><?=lang("Schedule type")?></h2>
                </div>
                <div class="body">
                    <ul>
                        <li class="active">
                            <input type="radio" name="sc_type" class="hide sc-action " id="sc_type_queue" checked=""
                                value="queue">
                            <label for="sc_type_queue"
                                class="btn btn-raised btn-primary waves-effect btn-block sc-btn-type " data-type="queue"
                                href="javascript:void(0);" role="button">
                                <span class="icon"></span>
                                <span class="long"><?=lang("Queue")?></span>
                            </label>
                        </li>
                        <li>
                            <input type="radio" name="sc_type" class="hide sc-action" id="sc_type_published"
                                value="published">
                            <label for="sc_type_published"
                                class="btn btn-raised btn-primary waves-effect btn-block sc-btn-type"
                                data-type="published" href="javascript:void(0);" role="button">
                                <span class="icon"></span>
                                <span class="long"><?=lang("Published")?></span>
                            </label>
                        </li>
                        <li>
                            <input type="radio" name="sc_type" class="hide sc-action" id="sc_type_unpublished"
                                value="unpublished">
                            <label for="sc_type_unpublished"
                                class="btn btn-raised btn-primary waves-effect btn-block sc-btn-type"
                                data-type="unpublished" href="javascript:void(0);" role="button">
                                <span class="icon"></span>
                                <span class="long"><?=lang("Unpublished")?></span>
                            </label>
                        </li>
                    </ul>

                </div>
            </div>

            <?php
                $social_info = load_social_info(true);
                $socials = array();
                if (!empty($social_info)) {?>

            <div class="box-sc-option sn card">
                <div class="header">
                    <h2 class="title"><?=lang("Social networks")?></h2>
                </div>

                <div class="body">
                    <ul>
                        <?php foreach ($social_info as $key => $row) {
                    $socials[] = strtolower($row->title);
                    ?>
                        <li>
                            <div class="pure-checkbox grey">
                                <input type="checkbox" name="social_filter[]" id="md_checkbox_<?=$row->title?>"
                                    class="filled-in chk-col-red sc-action" checked=""
                                    value="<?=str_replace(" ", "_", $row->title)?>">
                                <label class="p0 m0" for="md_checkbox_<?=$row->title?>">
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
            <div class="box-sc-option aoption card">
                <div class="header">
                    <h2 class="title"><?=lang("Advance options")?></h2>
                </div>

                <div class="body">
                    <ul>
                        <li class="box-border">
                            <div class="box-title"><?=lang("Delete schedules")?></div>
                            <div class="box-content">
                                <ul>
                                    <li>
                                        <div class="pure-checkbox grey">
                                            <input type="radio" name="sc_delete_type" id="md_radio_delete_queue"
                                                class="filled-in chk-col-red" checked="" value="queue">
                                            <label class="p0 m0" for="md_radio_delete_queue">
                                                <span class="name"><?=lang("Queue")?></span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pure-checkbox grey">
                                            <input type="radio" name="sc_delete_type" id="md_radio_delete_published"
                                                class="filled-in chk-col-red" value="published">
                                            <label class="p0 m0" for="md_radio_delete_published">
                                                <span class="name"><?=lang("Published")?></span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pure-checkbox grey">
                                            <input type="radio" name="sc_delete_type" id="md_radio_unpublished"
                                                class="filled-in chk-col-red" value="unpublished">
                                            <label class="p0 m0" for="md_radio_unpublished">
                                                <span class="name"><?=lang("Unpublished")?></span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-group mt-3">
                                    <div class="label mb-1"><?=lang("Social networks")?></div>
                                    <select class="form-control" name="sc_delete_social">
                                        <option value=""><?=lang("Select")?></option>
                                        <option value='<?=json_encode($socials)?>'><?=lang("delete_all")?></option>
                                        <?php foreach ($social_info as $key => $row) {?>
                                        <option value="<?=strtolower($row->title)?>"><?=$row->title?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <a class="btn btn-raised btn-danger waves-effect btn-block actionDeleteSchedules"
                                    href="<?=PATH . "schedules/delete"?>"><i class="ft-trash"></i>
                                    <?=lang("delete")?></a>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            <?php }?>

            <div class="box-sc-option aoption card">
                <div class="header">
                    <h2 class="title"><?=lang("Date")?></h2>
                </div>

                <div class="body">
                    <ul>
                        <li class="box-border">
                            <div class="box-title"><?=lang("from")?> : </div>
                            <div class="box-content">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                                    <input type="text" class="form-control date" placeholder="Ex: 01/<?= date("m/Y")?>" value="<?= "01/".date("m/Y") ?>">
                                </div>
                            </div>
                        </li>
                        <li class="box-border">
                            <div class="box-title"><?=lang("to")?> : </div>
                            <div class="box-content">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                                    <input type="text" class="form-control date" placeholder="Ex: <?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>">
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-12 col-lg-9">

        <div class="card calander">
            <div class="header">
                <h2>Décembre 2019</h2>
            </div>
            <div class="body">
                <ul class="list-unstyled">
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top facebook">
                                <i class="fa fa-facebook"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top tripadvisor">
                                <i class="fa fa-tripadvisor"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top twitter">
                                <i class="fa fa-twitter"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card calander">
            <div class="header">
                <h2>Janvier 2020</h2>
            </div>
            <div class="body">
                <ul class="list-unstyled">
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top instagram">
                                <i class="fa fa-instagram"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top facebook">
                                <i class="fa fa-facebook"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top google">
                                <i class="fa fa-google"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                    <li class="calander-item">
                        <div class="date">
                            <span class="nday">Vendredi</span>
                            <span class="dday">20</span>
                        </div>
                        <div class="cinfo">
                            <div class="icon-top google">
                                <i class="fa fa-google"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="state">Auto-post</span>
                                <h2 class="disc">Lorem ipsum dolor sit amet, consectetur ...</h2>
                            </div>

                        </div>
                        <button class="edit btn btn-raised btn-warning waves-effect"><i
                                class="material-icons">edit</i><?=lang("update")?></button>
                    </li>
                </ul>
            </div>
        </div>


    </div>
</div>