

<?php
   $caption = "";
   $media = "";
   $time_post = "";
   $accID = "";
   $typeP = "";
   if (isset($updateP)) {
       $data = json_decode($updateP->data);
       $time = strtotime($updateP->time_post);
       $newformat = date('d/m/Y h:i', $time);
       $caption = $data->caption;
       $media = $data->media;
       $time_post = $newformat;
       $accID = $updateP->pid;
       $typeP = $updateP->type;
   }
   if (isset($draft)) {
       $data = json_decode($draft->data);
       $time = strtotime($draft->time_post);
       $newformat = date('d/m/Y h:i', $time);
       $caption = $data->caption;
       $media = $data->media;
       $time_post = $newformat;
       $accID = $draft->pid;
       $typeP = $draft->type;
   }
   ?>
<?php if (!check_number_post()) {?>
<div class="notification">
   <div class="notification-board">
      <ul>
         <li class="danger"><i class="ft-alert-circle"></i> <?=lang("solde de publication insuffisant")?></li>
      </ul>
   </div>
</div>
<?php }?>
<div class="row top-title">
   <div class="col-md-12 col-sm-12">
      <div class="info-box-2 info-box-2-1">
         <div class="d-flex align-items-center flex-wrap">
            <h4>
               <i class="fa fa-paper-plane"></i>
               <?=lang('create_a_post')?>
            </h4>
            <div class="content">
               <span class="statistic">
               <small><?=lang("solde")?></small>
               <strong><?=get_posts_solde(user_or_cm()) - (get_count_posts(user_or_cm(), ST_PUBLISHED) + get_count_posts(user_or_cm(), ST_PLANIFIED))?></strong>
               </span>
               <span class="statistic">
                  <small><?=lang("published")?></small>
                  <div style="display:none"><?= user_or_cm() ?></div>
                  <strong><?=get_count_posts(user_or_cm(), ST_PUBLISHED)?></strong>
               </span>
               <span class="statistic">
               <small><?=lang("planified")?></small>
               <strong> <?=get_count_posts(user_or_cm(), ST_PLANIFIED)?></strong>
               </span>
            </div>
            <a href="#" class="btn btn-primary"><?=lang('model')?></a>
         </div>
      </div>
   </div>
</div>
<!-- <?=get_posts_solde(user_or_cm())?> -->
<form action="<?=PATH?>post/ajax_post" data-type="html" method="POST" data-redirect="<?=PATH?>post" class="actionPostAllForm">
   <div class="row all-post">
      <div class="col-md-12 ">
         <ul class="ap-mobile-menu card">
            <li><a data-ap-open="account" class="active"><?=lang("accounts")?></a></li>
            <li><a data-ap-open="content"><?=lang("content")?></a></li>
            <li><a data-ap-open="preview"><?=lang("preview")?></a></li>
         </ul>
      </div>
      <div class="col-md-12 col-lg-3 ap-box-account ap-mbile-menu-tab active">
         <div class="ap-left active card">
            <div class="header">
               <h2> <?=lang('Sélection de compte')?> </h2>
            </div>
            <?php if (!empty($accounts)) {?>
            <div class="box-search">
               <div class="input-group">
                  <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                  <input type="text" class="form-control ap-search" placeholder="<?=lang("search")?>"
                     aria-describedby="basic-addon2">
                  <div class="input-group-btn">
                     <button type="button" class="btn btn-default btn-icon  btn-icon-mini  ap-select-all"
                        title="<?=lang("select_all")?>"><i class="ft-check"></i></button>
                     <button type="button" class="btn btn-primary btn-icon  btn-icon-mini  dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        title="<?=lang("Groups")?>"><i class="ft-target"></i></button>
                     <?php if (!empty(get_groups())) {?>
                     <ul class="dropdown-menu dropdown-menu-right ap-scroll">
                        <?php foreach (get_groups() as $group) {?>
                        <li><a href="javascript:void(0);" class="actionGroups"
                           data-item='<?=$group->data?>'><?=$group->name?></a></li>
                        <?php }?>
                     </ul>
                     <?php }?>
                  </div>
               </div>
            </div>
            <ul class="box-list ap-scroll">
               <?php foreach ($accounts as $key => $row) {?>
               <li class="item <?=$row->category?>">
                  <a href="javascript:void(0);">
                     <div class="box-img">
                        <img src="<?=$row->avatar?>">
                        <div class="checked"><i class="fa fa-check"></i></div>
                     </div>
                     <div class="pure-checkbox grey mr15">
                        <input type="checkbox" name="accounts[]" class="filled-in chk-col-red"
                           value="<?=$row->category . "-" . $row->pid?>"
                           <?php if ($accID == $row->pid) {
                              echo "checked";
                              }
                              ?>>
                        <label class="p0 m0" for="md_checkbox_<?=$row->pid?>">&nbsp;</label>
                     </div>
                     <div class="box-info">
                        <div class="title"><?=$row->username?></div>
                        <div class="desc">
                           <?=sprintf(lang("%s " . $row->type), str_replace("_", " ", ucfirst($row->category)))?>
                        </div>
                     </div>
                  </a>
               </li>
               <?php }?>
            </ul>
            <?php } else {?>
            <div class="empty">
               <span><?=lang("add_an_account_to_begin")?></span>
               <a href="<?=PATH?>account_manager" class="btn btn-primary"><?=lang("add_account")?></a>
            </div>
            <?php }?>
         </div>
      </div>
      <div class="ap-center ap-schedule col-md-12 col-lg-9">
         <div class="row">
            <div class="col-md-12 col-lg-7 ap-box-content ap-scroll ap-mbile-menu-tab">
               <div class="card new-post">
                  <?=modules::run("caption/popup")?>
                  <?=modules::run("jeux_concours/popup")?>
                  <div class="card-overplay"><i class="pe-7s-config pe-spin"></i></div>
                  <div class="header">
                     <h2>
                        <?=lang('Création du post')?> <small><?=lang('new_post')?></small>
                     </h2>
                  </div>
                  <div class="card-block pt0">
                     <div class="form-group form-caption vk-text">
                        <div class="list-icon">
                           <a href="javascript:void(0);" class="getCaption" data-toggle="tooltip"
                              data-placement="left" title="<?=lang("get_caption")?>"><i
                              class="ft-command"></i></a>
                           <a href="javascript:void(0);" class="getGame" data-toggle="tooltip"
                              data-placement="left" title="<?=lang("get_jeux")?>">
                           <i class="fa fa-trophy"></i></a>
                           <a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption"
                              data-placement="left" title="<?=lang("save_caption")?>"><i
                              class="ft-save"></i></a>
                        </div>
                        <textarea class="form-control post-message" name="caption" rows="3"
                           placeholder="<?=lang('add_a_caption')?>"
                           style="height: 114px;"><?=$content?><?=$caption?></textarea>
                        <div class="list-action">
                           <ul class="action-type">
                              <li data-toggle="tooltip" data-placement="bottom" title=""
                                 <?php if ($typeP != "" ) {if ($typeP == "text") {echo 'class="active"';}}else { echo 'class="active"';}?>
                                 data-original-title="<?=lang("text")?>">
                                 <a href="#text" class="action-select" data-toggle="tab" data-type="text"><i
                                    class="ft-file-text"></i></a>
                                 <input type="radio" class="hide" name="type" value="text"
                                    <?php if ($typeP != "" ) {if ($typeP == "text") {echo 'checked="true"';}} else {echo 'checked="true"';}?>>
                              </li>
                              <li data-toggle="tooltip" data-placement="bottom" title=""
                                 <?php if ($typeP == "link") {echo 'class="active"';}?>
                                 data-original-title="<?=lang("link")?>">
                                 <a href="#link" class="action-select " data-toggle="tab" data-type="link"><i
                                    class="ft-link"></i></a>
                                 <input type="radio" class="hide" name="type" value="link"
                                    <?php if ($typeP == "link") {echo 'checked="true"';}?>>
                              </li>
                              <li data-toggle="tooltip" data-placement="bottom" title=""
                                 <?php if ($typeP == "photo" && !empty($media) ||  $typeP == "media") {echo 'class="active"';}?>
                                 data-original-title="<?=lang("photo")?>">
                                 <a href="#photo" class="action-select <?php if(!empty($media)) echo 'active';?>" data-toggle="tab"
                                    data-type="photo"><i class="ft-image"></i></a>
                                 <input type="radio" class="hide" name="type" value="photo"
                                    <?php if ($typeP == "photo" && !empty($media) ||  $typeP == "media") {echo 'checked="true"';}?>>
                              </li>
                              <li data-toggle="tooltip" data-placement="bottom" title=""
                                 <?php if ($typeP == "video") {echo 'class="active"';}?>
                                 data-original-title="<?=lang("video")?>">
                                 <a href="#video" class="action-select" data-toggle="tab"
                                    data-type="video"><i class="ft-video"></i></a>
                                 <input type="radio" class="hide" name="type" value="video"
                                    <?php if ($typeP == "video") {echo 'checked="true"';}?>>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <ul class="ap-action-option tab-content">
                        <li class="item-option-action tab-pane fade <?php if ($typeP == "text") {echo 'active';}?>"
                           id="text"></li>
                        <li class="item-option-action tab-pane fade <?php if ($typeP == "link" && !empty($media)) {echo 'active';}?>"
                           id="link">
                           <div class="ap-box-url">
                              <div class="form-group">
                                 <?php if ($typeP == "link" && empty($media)) {?>
                                 <label for="add_link"><?=lang("LINK URL")?></label>
                                 <div class="box-bar">
                                    <div class="bar"></div>
                                    <input type="text" class="form-control add_link" id="add_link"
                                       name="link" placeholder="<?=lang("Insert the link url")?>">
                                 </div>
                                 <?php }?>
                                 <div class="box-img-url mt15">
                                    <label for="add_link"><?=lang("SELECT A THUMBNAIL")?></label>
                                    <div class="image-manage" data-type="single">
                                       <div class="image-manage-content">
                                          <div class="file-manager-list-images">
                                             <?php 
                                                if (!empty($media)) {
                                                    foreach ($media as $key => $value) {
                                                
                                                        ?>
                                             <div class="item"
                                                style="background-image: url(<?=$value?>)">
                                                <input type="hidden" name="media[]" value="<?=$value?>">
                                                <button type="button" class="close" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                                </button>
                                             </div>
                                             <?php
                                                }
                                                    } else {
                                                        ?>
                                             <div class="add-image"> <?=lang('add_images')?></div>
                                             <?php
                                                }
                                                
                                                ?>
                                          </div>
                                          <div class="clearfix"></div>
                                       </div>
                                       <div class="image-manage-footer">
                                          <a href="<?=PATH?>file_manager/popup_add_files/photo"
                                             class="item btnOpenFileManager">
                                          <i class="fa fa-laptop" aria-hidden="true"></i>
                                          <?=lang('file_manager')?>
                                          </a>
                                          <a href="javascript:void(0);" class="item fileinput-button">
                                          <i class="fa fa-upload" aria-hidden="true"></i>
                                          <input onclick="FileManager.uploadFile('#fileupload1');"
                                             id="fileupload1" type="file" name="files[]">
                                          </a>
                                          <?php if (get_option('google_drive_api_key', '') != "" && get_option('google_drive_client_id', '') != "") {?>
                                          <a href="javascript:void(0);" class="item"
                                             onclick="onApiLoad()">
                                          <i class="fa fa-google-drive" aria-hidden="true"></i>
                                          </a>
                                          <?php }?>
                                          <?php if (get_option('dropbox_api_key', '') != "") {?>
                                          <a href="javascript:void(0);" class="item" id="chooser-image"
                                             data-multi-files="false">
                                          <i class="fa fa-dropbox" aria-hidden="true"></i>
                                          </a>
                                          <?php }?>
                                          <div class="clearfix"></div>
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li class="item-option-action tab-pane fade <?php if (($typeP == "photo"  && !empty($media)) ||  $typeP == "media" ) {echo 'active';}?>"
                           id="photo">
                           <div class="image-manage" data-type="multi">
                              <div class="image-manage-content">
                                 <div class="file-manager-list-images">
                                    <?php if ($typeP == "photo" ||  $typeP == "media") {
                                        if (!empty($media) ) {
                                            foreach ($media as $key => $value) {
                                                       ?>
                                            <div class="item" style="background-image: url(<?=$value?>)">
                                               <input type="hidden" name="media[]" value="<?=$value?>">
                                               <button type="button" class="close" aria-label="Close">
                                               <span aria-hidden="true">×</span>
                                               </button>
                                            </div>
                                            <?php }
                                        } else { ?>
                                            <div class="add-image"> <?=lang('add_images')?></div>
                                  <?php }
                                    } ?>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>
                              <div class="image-manage-footer">
                                 <a href="<?=PATH?>file_manager/popup_add_files/photo"
                                    class="item btnOpenFileManager">
                                 <i class="fa fa-laptop" aria-hidden="true"></i>
                                 <?=lang('file_manager')?>
                                 </a>
                                 <a href="javascript:void(0);" class="item fileinput-button">
                                 <i class="fa fa-upload" aria-hidden="true"></i>
                                 <input onclick="FileManager.uploadFile('#fileupload2');"
                                    id="fileupload2" type="file" name="files[]">
                                 </a>
                                 <?php if (get_option('google_drive_api_key', '') != "" && get_option('google_drive_client_id', '') != "") {?>
                                 <a href="javascript:void(0);" class="item" onclick="onApiLoad()">
                                 <i class="fa fa-google-drive" aria-hidden="true"></i>
                                 </a>
                                 <?php }?>
                                 <?php if (get_option('dropbox_api_key', '') != "") {?>
                                 <a href="javascript:void(0);" class="item" id="chooser-image"
                                    data-multi-files="false">
                                 <i class="fa fa-dropbox" aria-hidden="true"></i>
                                 </a>
                                 <?php }?>
                                 <div class="clearfix"></div>
                              </div>
                              <div class="clearfix"></div>
                           </div>
                        </li>
                        <li class="item-option-action tab-pane fade <?php if ($typeP == "video") {echo 'active';}?>"
                           id="video">
                           <div class="image-manage" data-type="single">
                              <div class="image-manage-content">
                                 <div class="file-manager-list-images">
                                    <?php if ($typeP == "video") {
                                       if (!empty($media)) {
                                           foreach ($media as $key => $value) {
                                       
                                               ?>
                                    <div class="item" style="background-image: url(<?=$value?>)">
                                       <input type="hidden" name="media[]" value="<?=$value?>">
                                       <button type="button" class="close" aria-label="Close">
                                       <span aria-hidden="true">×</span>
                                       </button>
                                    </div>
                                    <?php
                                       }
                                           } else {
                                               ?>
                                    <div class="add-image"> <?=lang('add_video')?></div>
                                    <?php
                                       }
                                       }
                                       ?>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>
                              <div class="image-manage-footer">
                                 <a href="<?=PATH?>file_manager/popup_add_files/video"
                                    class="item btnOpenFileManager">
                                 <i class="fa fa-laptop" aria-hidden="true"></i>
                                 <?=lang('file_manager')?>
                                 </a>
                                 <a href="javascript:void(0);" class="item fileinput-button">
                                 <i class="fa fa-upload" aria-hidden="true"></i>
                                 <input onclick="FileManager.uploadFile('#fileupload3');"
                                    id="fileupload3" type="file" name="files[]">
                                 </a>
                                 <?php if (get_option('google_drive_api_key', '') != "" && get_option('google_drive_client_id', '') != "") {?>
                                 <a href="javascript:void(0);" class="item" onclick="onApiLoad()">
                                 <i class="fa fa-google-drive" aria-hidden="true"></i>
                                 </a>
                                 <?php }?>
                                 <?php if (get_option('dropbox_api_key', '') != "") {?>
                                 <a href="javascript:void(0);" class="item" id="chooser-image"
                                    data-multi-files="false">
                                 <i class="fa fa-dropbox" aria-hidden="true"></i>
                                 </a>
                                 <?php }?>
                                 <div class="clearfix"></div>
                              </div>
                              <div class="clearfix"></div>
                           </div>
                        </li>
                     </ul>
                     <div class="form-group">
                        <div class="checkbox grey mr15">
                           <input type="checkbox" id="md_checkbox_schedule" name="is_schedule"
                              <?php if (isset($updateP)) {echo 'checked=""';}?>
                              class="enable_post_all_schedule <?php if (isset($updateP)) {echo 'checked=""';}?> "
                              value="on">
                           <label class="" for="md_checkbox_schedule">
                           <?=lang('schedule')?>
                           </label>
                        </div>
                     </div>
                     <div class="schedule-option" id="schedule-option">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="time_post"> <?=lang('time_post')?></label>
                                 <input type="text" name="time_post" value="<?=$time_post?>"
                                    class="form-control datetime time_post" id="time_post" disabled="true">
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- <button type="button" class="btn btn-default pull-right btnPostNow"> Customize for each network</button> -->
                     <?php if (isset($updateP) && get('action') != 'duplicate') {?>
                     <input type="hidden" name="update" value="update">
                     <input type="hidden" class="idpostupdate" name="idp" value="<?=get('pid')?>">
                     <a href="#" class="btn btn-primary btnDelete" data-redirect="<?=cn('post')?>"
                        data-social="<?=get('social')?>" data-pid="<?=get('pid')?>"
                        data-confirm="<?=lang("are_you_sure_want_delete_it")?>">
                     <?=lang('delete')?>
                     </a>
                     <button type="submit" class="btn btn-primary pull-right  btnSchedulePost">
                     <?=lang('update / schedule')?>
                     </button>
                     <button type="submit" name="post" value="postdraft"
                        class="btn btn-primary pull-right  btnPostNow">
                     <?=lang('post_now')?>
                     </button>
                     <?php } elseif (isset($draft)) {?>
                     <!-- <input type="hidden" name="draft" value="draft"> -->
                     <input type="hidden" class="idpostupdate" name="idp" value="<?=get('pid')?>">
                     <button type="submit" name="draft" value="draft"
                        class="btn btn-outline-primary btnPostDraft">
                     <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?=lang('post_draft')?>
                     </button>
                     <button type="submit" name="post" value="postdraft"
                        class="btn btn-primary pull-right  btnPostNow">
                     <?=lang('post_now')?>
                     </button>
                     <button type="submit" name="post" value="postdraft"
                        class="btn btn-primary pull-right  btnSchedulePost hide">
                     <?=lang('schedule_post')?>
                     </button>
                     <?php } else {?>
                     <input type="hidden" id="typeOfSubmit" name="typeOfSubmit" value="post">
                     <button type="submit" class="btn btn-primary pull-right  btnPostNow">
                     <?=lang('post_now')?>
                     </button>
                     <button type="submit" class="btn btn-primary pull-right  btnSchedulePost hide">
                     <?=lang('schedule_post')?>
                     </button>
                     <button class="btn btn-outline-primary btn-save-model">
                     <i class="ft-save"></i> <?=lang('model')?>
                     </button>
                     <button type="submit" class="btn btn-outline-primary btnPostDraft">
                     <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?=lang('post_draft')?>
                     </button>
                     <?php }?>
                     <div class="clearfix"></div>
                  </div>
               </div>
            </div>
            <div
               class="col-md-12 col-lg-5 ap-box-preview ap-scroll box-load-previewer new_post social-box ap-mbile-menu-tab">
               <div class="loading-box" id="loading-box">
                  <div class='loader loader1'>
                     <div>
                        <div>
                           <div>
                              <div>
                                 <div>
                                    <div></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <div class="header">
                     <h2><?=lang('Visualisation')?></h2>
                  </div>
                  <div class="body p-0">
                     <?=Modules::run("post/previewer");?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="modal-custom" class="ap-modal-confirm">
      <button data-iziModal-close class="icon-close"></button>
      <div class="card mb0">
         <div class="card-header">
            <div class="card-title">
               <i class="ft-alert-circle text-warning" aria-hidden="true"></i> <?=lang("Confirm")?>
            </div>
         </div>
         <div class="card-block pt15 ap-data-errors">
         </div>
         <div class="card-footer text-right">
            <button class="btn btn-default" data-izimodal-close=""
               data-izimodal-transitionout="bounceOutDown"><?=lang("No, Cancel")?></button>
            <a class="btn btn-primary btnPostTry"
               href="<?=cn("post/ajax_post/true")?>"><?=lang("Yes, I am sure")?></a>
         </div>
      </div>
   </div>
</form>
<script type="text/javascript">
   $(".btnPostDraft").on("click", function () {
       $('#typeOfSubmit').val('draft');
   });
   
   $(".btnPostNow").on("click", function () {
       $('#btnSchedulePost').val('post');
   });
   
   $(".btnPostNow").on("click", function () {
       $('#typeOfSubmit').val('post');
   });
   $(".btnDelete").on("click", function () {
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

