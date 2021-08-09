<?php
$module_name = segment(1);

$ids = "";
$type = "";
$caption = "";
$link = "";
$repeat = 0;
$repeat_end = "";
$time_post = "";
$account = 0;
$media = array();
if(!empty($post)){
    $data = json_decode($post->data);
    $ids = $post->ids;
    $type = @$post->type;
    $account = $post->account;
    $caption = @$data->caption;
    $media = @$data->media;
    $link = @$data->link;
    $repeat = @$data->repeat/86400;
    $repeat_end = get_timezone_user(@$data->repeat_end);
    $repeat_end = @date("d/m/Y H:i", strtotime($repeat_end));
    $time_post = get_timezone_user($post->time_post);
    $time_post = date("d/m/Y H:i", strtotime($time_post));
}
?>

<form class="actionForm" action="<?=cn($module_name."/post/ajax_post")?>">
    <div class="wrap-content <?=$module_name?>-app row all-post">
        <ul class="am-mobile-menu col-12">
            <li><a href="javascript:void(0);" class="active" data-am-open="account"><?=lang("Accounts")?></a></li>
            <li><a href="javascript:void(0);" data-am-open="content"><?=lang("Content")?></a></li>
            <li><a href="javascript:void(0);" data-am-open="previews"><?=lang("Preview")?></a></li>
        </ul>
        <div class="col-md-12 col-lg-3">
            <div class="active card ap-left am-account am-mbile-menu-tab">
                <?php if(!empty($accounts)){?>
                <div class="box-search">
                    <div class="input-group">
                        <input type="text" class="form-control am-search" placeholder="<?=lang("search")?>"
                            aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                        <div class="input-group-btn">
                            <button type="button"
                                class="btn btn-primary btn-icon  btn-icon-mini btn-round  ap-select-all"
                                title="<?=lang("select_all")?>"><i class="ft-check"></i></button>
                            <button type="button"
                                class="btn btn-primary btn-icon  btn-icon-mini btn-round  dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                title="<?=lang("Groups")?>"><i class="ft-target"></i></button>
                            <?php if(!empty( get_groups() )){?>
                            <ul class="dropdown-menu dropdown-menu-right ap-scroll">
                                <?php 
                        foreach (get_groups() as $group) {
                        ?>
                                <li><a href="javascript:void(0);" class="actionGroups"
                                        data-item='<?=$group->data?>'><?=$group->name?></a></li>
                                <?php }?>
                            </ul>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <ul class="box-list am-scroll">
                    <?php
            foreach ($accounts as $key => $row) {

            if($account == 0 || $row->id == $account){
            ?>
                    <li class="item <?=$row->id == $account?"active":""?>">
                        <a href="javascript:void(0);">
                            <div class="box-img">
                                <img src="<?=$row->avatar?>">
                                <div class="checked"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="pure-checkbox grey mr15">
                                <input type="checkbox" name="account[]" class="filled-in chk-col-red"
                                    value="<?=$row->ids?>" <?=$row->id == $account?"checked":""?>>
                                <label class="p0 m0" for="md_checkbox_<?=$row->pid?>">&nbsp;</label>
                            </div>
                            <div class="box-info">
                                <div class="title"><?=$row->username?></div>
                                <div class="desc"><?=lang("Profile")?> </div>
                            </div>
                        </a>
                    </li>
                    <?php }}?>
                </ul>
                <?php }else{?>

                <div class="empty">
                    <span><?=lang("add_an_account_to_begin")?></span>
                    <a href="<?=PATH?>account_manager" class="btn btn-primary"><?=lang("add_account")?></a>
                </div>

                <?php }?>
            </div>
        </div>
        <div class="am-wrapper  col-md-12 col-lg-9">
            <div class="row">

                <div class="am-content ap-left col-md-12 col-lg-7 am-scroll am-mbile-menu-tab">

                    <?=modules::run("caption/popup")?>
                    <div class="card">
                        <div class="header">
                            <strong><i class="fa ft-edit" aria-hidden="true"></i> </strong> <?=lang("Create new")?>
                        </div>
                        <div class="body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="tab-type schedule-twitter-type file-manager-change-type">
                                        <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item active"
                                            data-type="photo" data-type-image="multi">
                                            <i class="ft-image"></i> <?=lang('photo')?>
                                            <input type="radio" name="type" class="hide" value="photo" checked="">
                                        </a>
                                        <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item"
                                            data-type="video" data-type-image="single">
                                            <i class="ft-camera"></i> <?=lang('video')?>
                                            <input type="radio" name="type" class="hide" value="video">
                                        </a>
                                        <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item"
                                            data-type="text" data-type-image="single">
                                            <i class="ft-file-text"></i> <?=lang('text')?>
                                            <input type="radio" name="type" value="text" class="hide">
                                        </a>
                                    </div>
                                </div>

                                <?=modules::run("file_manager/block_file_manager", "single", !empty($media)?array($media[0]):array() )?>
                                <div class="tab-content b0 mt15">
                                    <div id="link" class="tab-pane fade in">
                                        <div class="form-group form-help">
                                            <input type="text" class="form-control" name="link"
                                                placeholder="<?=lang("Enter your link")?>" value="<?=$link?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-caption">
                                    <div class="list-icon">
                                        <a href="javascript:void(0);" class="getCaption" data-toggle="tooltip"
                                            data-placement="left" title="<?=lang("get_caption")?>"><i
                                                class="ft-command"></i></a>
                                        <a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption"
                                            data-placement="left" title="<?=lang("save_caption")?>"><i
                                                class="ft-save"></i></a>
                                    </div>
                                    <textarea class="form-control post-message" name="caption" rows="3"
                                        placeholder="<?=lang('add_a_caption')?>"
                                        style="height: 114px;"><?=$caption?></textarea>
                                </div>

                                <div class="form-group">
                                    <?php if($ids == ""){?>
                                    <div class="pure-checkbox grey mr15">
                                        <input type="checkbox" id="md_checkbox_schedule" name="is_schedule"
                                            class="filled-in chk-col-red enable_post_all_schedule" value="on">
                                        <label class="p0 m0" for="md_checkbox_schedule">&nbsp;</label>
                                        <span class="checkbox-text-right"> <?=lang('schedule')?></span>
                                    </div>
                                    <?php }else{?>
                                    <input type="hidden" name="is_schedule" value="1">
                                    <input type="hidden" name="default_type" value="<?=$type?>">
                                    <input type="hidden" name="ids" value="<?=$ids?>">
                                    <?php }?>
                                </div>

                                <div class="schedule-option" id="schedule-option">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="time_post"> <?=lang('time_post')?></label>
                                                <input type="text" name="time_post"
                                                    class="form-control datetime time_post" id="time_post"
                                                    <?=$ids==""?"disabled='true'":""?> value="<?=$time_post?>">
                                            </div>
                                        </div>
                                        <div class="box-repeat col-md-12 <?=$ids == ""?"hide":""?>">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label> <?=lang('Repeat frequency')?></label>
                                                        <select name="repeat_every" id="repeat_every"
                                                            class="form-control">
                                                            <option value="0"> <?=lang('once')?></option>
                                                            <option value="1" <?=$repeat==1?"selected":""?>>
                                                                <?=lang('every_day')?>
                                                            </option>
                                                            <?php for ($i=2; $i <=60 ; $i++) { ?>
                                                            <option value="<?=$i?>" <?=$repeat==$i?"selected":""?>>
                                                                <?=sprintf(lang('every_x_days'),$i)?></option>
                                                            <?php  }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label> <?=lang('end_on')?></label>
                                                        <input type="text" name="repeat_end"
                                                            class="form-control datetime" id="repeat_end"
                                                            value="<?=$repeat_end?>">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <?php if($ids == ""){?>
                                <button type="submit" class="btn btn-primary pull-right border-circle btnGoNow">
                                    <?=lang("Post now")?></button>
                                <button type="submit"
                                    class="btn btn-primary pull-right border-circle btnSchedulePost hide">
                                    <?=lang("Schedule post")?></button>
                                <?php }else{?>
                                <a href="<?=cn($module_name."/post/ajax_post")?>"
                                    data-redirect="<?=cn($module_name."/post")?>"
                                    class="btn btn-primary pull-right actionMultiItem"> <?=lang('Edit post')?></a>
                                <?php }?>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="am-previews ap-left col-md-12 col-lg-5 am-scroll am-mbile-menu-tab">
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2 col-sm-12">
                            <?=Modules::run($module_name."/post/preview")?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</form>