<div class="pn-box-content" style="max-width: 1070px;">
    <?php 
    $permission = array();
    if(!empty($result)){
        $permission = (array)json_decode($result->permission);
    }
    $permission_list = permission_list();
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" name="ids" value="<?=!empty($result)?$result->ids:""?>">
                <div class="body pl15 pr15">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if(!empty($result)){?>
                            <div class="form-group">
                                <label for="name"><?=lang("name_package")?></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="<?=!empty($result)?$result->name:""?>">
                            </div>
                            <?php if($result->type==2){?>
                            <div class="form-group">
                                <label for="description"> <?=lang('description')?></label>
                                <input type="text" class="form-control" name="description" id="description"
                                    value="<?=!empty($result)?$result->description:""?>">
                            </div>
                            <?php }else{?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="trial_day"> <?=lang('trial_days')?></label>
                                        <input type="number" class="form-control" name="trial_day" id="trial_day"
                                            value="<?=!empty($result)?(int)$result->trial_day:"0"?>">
                                    </div>
                                </div>
                            </div>
                            <?php }?>

                            <?php }else{?>
                            <div class="form-group">
                                <label for="name"><?=lang("name")?></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="<?=!empty($result)?$result->name:""?>">
                            </div>
                            <div class="form-group">
                                <label for="description"><?=lang("description")?></label>
                                <input type="text" class="form-control" name="description" id="description"
                                    value="<?=!empty($result)?$result->description:""?>">
                            </div>
                            <?php }?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_storage_size"><?=lang("max_storage_size")?></label>
                                        <input type="text" class="form-control" name="max_storage_size"
                                            id="max_storage_size"
                                            value="<?=( !empty($permission) && isset($permission['max_storage_size']) )?(float)$permission['max_storage_size']:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_file_size"><?=lang("max_file_size")?></label>
                                        <input type="text" class="form-control" name="max_file_size" id="max_file_size"
                                            value="<?=(!empty($permission) && isset($permission['max_file_size']))?(float)$permission['max_file_size']:"0"?>">
                                    </div>
                                </div>
                            </div>

                            <?php if(empty($result) || $result->type==2 && get_payment()){?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_mensuel"> <?=lang('prix_mensuel_ht')?></label>
                                        <input type="text" class="form-control" name="price_mensuel" id="price_mensuel"
                                            value="<?=!empty($result)?(float)$result->price_mensuel:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_mensuel_ttc"> <?=lang('prix_mensuel_ttc')?></label>
                                        <input type="text" class="form-control" name="price_mensuel_ttc" id="price_mensuel_ttc"
                                            value="<?=!empty($result)?(float)$result->price_mensuel_ttc:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_semestriel"> <?=lang('prix_semestriel_ht')?></label>
                                        <input type="text" class="form-control" name="price_semestriel"
                                            id="price_semestriel"
                                            value="<?=!empty($result)?(float)$result->price_semestriel:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_semestriel_ttc"> <?=lang('prix_semestriel_ttc')?></label>
                                        <input type="text" class="form-control" name="price_semestriel_ttc"
                                            id="price_semestriel_ttc"
                                            value="<?=!empty($result)?(float)$result->price_semestriel_ttc:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_trimestriel"> <?=lang('prix_trimestriel_ht')?></label>
                                        <input type="text" class="form-control" name="price_trimestriel"
                                            id="price_semestriel"
                                            value="<?=!empty($result)?(float)$result->price_trimestriel:"0"?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_trimestriel_ttc"> <?=lang('prix_trimestriel_ttc')?></label>
                                        <input type="text" class="form-control" name="price_trimestriel_ttc"
                                            id="price_trimestriel_ttc"
                                            value="<?=!empty($result)?(float)$result->price_trimestriel_ttc:"0"?>">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                                <!-- <label for="sort"><?=lang('sort')?></label> -->
                                <input type="hidden" class="form-control" name="sort" id="sort"
                                    value="<?=!empty($result)?$result->sort:"0"?>">
                            <!-- </div> -->
                            <?php }?>

                            <div class="form-group">
                                <label for="number_accounts">
                                    <?=lang('number_of_social_accounts_on_each_platform')?></label>
                                <input type="number" class="form-control" name="number_accounts" id="number_accounts"
                                    value="<?=!empty($result)?$result->number_accounts:"0"?>">
                            </div>
                            <div class="form-group">
                                <label for="number_posts">
                                    <?=lang('number_of_social_posts_on_each_platform')?></label>
                                <input type="number" class="form-control" name="number_posts" id="number_posts"
                                    value="<?=!empty($result)?$result->number_posts:"0"?>">
                            </div>
                            <div class="form-group">
                                <label for="status"> <?=lang('status')?></label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1" <?=!empty($result) && $result->status == 1?"selected":""?>>
                                        <?=lang("enable")?></option>
                                    <option value="0" <?=!empty($result) && $result->status == 0?"selected":""?>>
                                        <?=lang("disable")?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort"> <?=lang('file_pickers')?></label><br />
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_google_drive" name="file_pickers[]"
                                                class="filled-in chk-col-red" value="google_drive"
                                                <?=in_array('google_drive',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_google_drive">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('google_drive')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_dropbox" name="file_pickers[]"
                                                class="filled-in chk-col-red" value="dropbox"
                                                <?=in_array('dropbox',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_dropbox">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('dropbox')?></span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="pure-checkbox grey mr15 mb15">
                                    <input type="checkbox" id="md_checkbox_onedrive" name="file_pickers[]" class="filled-in chk-col-red" value="onedrive" <?=in_array('onedrive',$permission, true)?"checked":""?>>
                                    <label class="p0 m0" for="md_checkbox_onedrive">&nbsp;</label>
                                    <span class="checkbox-text-right">  <?=lang('onedrive')?></span>
                                </div>
                            </div> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sort"> <?=lang('file_type')?></label><br />
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_photo_type" name="file_types[]"
                                                class="filled-in chk-col-red" value="photo_type"
                                                <?=in_array('photo_type',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_photo_type">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('photo')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_video_type" name="file_types[]"
                                                class="filled-in chk-col-red" value="video_type"
                                                <?=in_array('video_type',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_video_type">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('video')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sort"> <?=lang('advance_option')?></label><br />
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_watermark" name="watermark"
                                                class="filled-in chk-col-red" value="watermark"
                                                <?=in_array('watermark',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_watermark">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('watermark')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_create_a_post" name="create_a_post"
                                                class="filled-in chk-col-red" value="create_a_post"
                                                <?=in_array('create_a_post',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_create_a_post">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('create_a_post')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <input type="checkbox" id="md_checkbox_image_editor" name="image_editor"
                                                class="filled-in chk-col-red" value="image_editor"
                                                <?=in_array('image_editor',$permission, true)?"checked":""?>>
                                            <label class="p0 m0" for="md_checkbox_image_editor">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('image_editor')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sort"> <?=lang('Engagement')?></label><br />
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <?php
                                            $is_annuel = !empty($result)? $result->is_annuel : 0;
                                            $is_annel_c = ($is_annuel == "1") ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" id="is_annuel" name="is_annuel"
                                                class="filled-in chk-col-red" value="1" <?=$is_annel_c?>>
                                            <label class="p0 m0" for="is_annuel">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('Annuel')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <?php
                                            $is_semestriel = !empty($result)? $result->is_semestriel : 0;
                                            $is_semestriel_c = ($is_semestriel == "1") ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" id="is_semestriel" name="is_semestriel"
                                                class="filled-in chk-col-red" value="1" <?=$is_semestriel_c?>>
                                            <label class="p0 m0" for="is_semestriel">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('Semestriel')?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="pure-checkbox grey mr15 mb15">
                                            <?php
                                            $is_trimestriel = !empty($result)? $result->is_trimestriel : 0;
                                            $is_trimestriel_c = ($is_trimestriel == "1") ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" id="is_trimestriel" name="is_trimestriel"
                                                class="filled-in chk-col-red" value="1" <?=$is_trimestriel_c?>>
                                            <label class="p0 m0" for="is_trimestriel">&nbsp;</label>
                                            <span class="checkbox-text-right"> <?=lang('Trimestriel')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row tab-list">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="body">
                                    <ul class="nav nav-tabs padding-0">
                                        <?php
                                    if(!empty($permission_list)){
                                        $count = 0;
                                        foreach ($permission_list as $key => $row) {
                                    ?>
                                        <li class="nav-item"><a data-toggle="tab" href="#<?=$key?>"
                                                class="nav-link <?=$count==0?"active":""?>"><i
                                                    class="fa fa-<?=$key?> "></i>
                                                <?=str_replace("_", " ", ucfirst($key))?></a></li>
                                        <?php $count++; }}?>
                                    </ul>

                                    <div class="tab-content m-t-20">
                                        <?php
                                        if(!empty($permission_list)){
                                            $count = 0;
                                            foreach ($permission_list as $key => $permissions) {
                                        ?>
                                        <div id="<?=$key?>" class="row tab-pane fade in <?=$count==0?"active":""?>">
                                            <div class="col-md-12">
                                                <div class="pure-checkbox grey mr15 mb15 pb15">
                                                    <input type="checkbox" id="md_checkbox_<?=$key?>_enable"
                                                        name="permission[]" class="filled-in chk-col-red"
                                                        value="<?=$key?>_enable"
                                                        <?=in_array($key.'_enable',$permission, true)?"checked":""?>>
                                                    <label class="p0 m0"
                                                        for="md_checkbox_<?=$key?>_enable">&nbsp;</label>
                                                    <span class="checkbox-text-right"><?=lang('enable/disable')?></span>
                                                </div>
                                            </div>
                                            <?php foreach ($permissions as $row) { ?>
                                            <div class="col-md-3">
                                                <div class="pure-checkbox grey mr15 mb15">
                                                    <input type="checkbox" id="md_checkbox_<?=$row['link']?>"
                                                        name="permission[]" class="filled-in chk-col-red"
                                                        value="<?=$row['link']?>"
                                                        <?=in_array($row['link'],$permission, true)?"checked":""?>>
                                                    <label class="p0 m0"
                                                        for="md_checkbox_<?=$row['link']?>">&nbsp;</label>
                                                    <span
                                                        class="checkbox-text-right"><?=lang(strtolower(str_replace(" ", "_", $row['name'])))?></span>
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                        <?php $count++; }}?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
<div class="card">
    <div class="body">
        <button type="submit" class="btn btn-primary mr15"><?=lang('update')?></button>
        <?php if(!empty($result)){?>
        <a href="<?=cn($module."/ajax_update")?>" class="btn btn-secondary mr15 actionMultiItem"
            data-params="subscribers=1">
            <?=lang('save_and_update_subscribers')?></a>
        <?php }?>
        <a href="<?=cn($module)?>" class="btn btn-secondary"><?=lang('cancel')?></a>
    </div>

</div>