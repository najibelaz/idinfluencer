<div class="overlay"></div>
<nav class="navbar">
    <ul class="nav navbar-nav navbar-left">

        <li>
            <div class="navbar-header">
                    <a href="javascript:void(0);" class="h-bars"></a>
                    <a href="javascript:void(0);" class="bars"></a>
            </div>
        </li>
        <li>
            <span id="btn-slide"></span>
        </li>


        <li>
        <div class="navbar-header">
                <a class="navbar-brand" href="<?=cn("dashboard")?>">
                    <img src="<?=BASE?>assets/img/logo-black.png" width="60" alt="sQuare"><span class="m-l-10"></span>
                </a>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" class="menu-sm" id="menu-toggle" data-close="true">
                <i title="<?=lang("reduire_menu")?>" class="zmdi zmdi-arrow-left"></i>
                <i title="<?=lang("agrandire_menu")?>" class="zmdi zmdi-arrow-right"></i>
            </a>
        </li>

        <!-- <li class="dropdown app_menu"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-apps"></i></a>
            <ul class="dropdown-menu pullDown">
                <li><a href="{{route('app.mail-inbox')}}"><i class="zmdi zmdi-email m-r-10"></i><span>Mail</span></a></li>
                <li><a href="{{route('app.contact-list')}}"><i class="zmdi zmdi-accounts-list m-r-10"></i><span>Contacts</span></a></li>
                <li><a href="{{route('app.chat')}}"><i class="zmdi zmdi-comment-text m-r-10"></i><span>Chat</span></a></li>
                <li><a href="{{route('pages.invoices')}}"><i class="zmdi zmdi-arrows m-r-10"></i><span>Invoices</span></a></li>
                <li><a href="{{route('app.calendar')}}"><i class="zmdi zmdi-calendar-note m-r-10"></i><span>Calendar</span></a></li>
                <li><a href="javascript:void(0)"><i class="zmdi zmdi-arrows m-r-10"></i><span>Notes</span></a></li>
                <li><a href="javascript:void(0)"><i class="zmdi zmdi-view-column m-r-10"></i><span>Taskboard</span></a></li>                
            </ul>
        </li> -->
       
        <!-- <li class="dropdown task"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i>
            <span class="label-count">3</span>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="header">Project</li>
                <li class="body">
                    <ul class="menu tasks list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Clockwork Orange <span class="float-right">29%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-turquoise" role="progressbar" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100" style="width: 29%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Blazing Saddles <span class="float-right">78%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-slategray" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Project Archimedes <span class="float-right">45%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Eisenhower X <span class="float-right">68%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-coral" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Oreo Admin Templates <span class="float-right">21%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-amber" role="progressbar" aria-valuenow="21" aria-valuemin="0" aria-valuemax="100" style="width: 21%;"></div>
                                </div>
                            </a>
                        </li>                        
                    </ul>
                </li>
                <li class="footer"><a href="javascript:void(0);">View All</a></li>
            </ul>
        </li>   -->

        <?php if (!session("uid_main")) {?>
        <li class="dropdown-user float-right">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if (get_field(USERS, session("uid"), "avatar") != "") {?>
                        <!-- <img class="img-circle" src="<?=BASE?>assets/img/default-avatar.png">  -->
                        <img src="<?=get_field(USERS, session("uid"), "avatar")?>" alt="" class="img-circle">
                    <?php } else {?>
                        <img class="img-circle" src="<?=BASE?>assets/img/default-avatar.png"> 
                    <?php }?>
                    <span class="user-name"><?=get_field(USERS, session("uid"), "fullname")?></span> 
                    <i class="dropdown-down-icon ft-chevron-down"></i>
                </a>
                <ul class="dropdown-menu pullDown" aria-labelledby="dropdownMenuButton">
                    <li class="header"> <?=lang('account')?></li>
                    <li class="body">
                    
                        </span>
                        <a class="dropdown-item" href="<?=cn("profile")?>"><i class="ft-user"></i> <?=lang('profile')?></a>
                        <a class="dropdown-item" href="<?=cn('auth/logout')?>"><i class="ft-log-out"></i>
                            <?=lang('logout')?></a>

                            <?php if (is_admin()) {?>
                                <a class="dropdown-item" href="<?=cn("settings/general")?>">
                                    <i class="ft-settings"></i> <?=lang('settings')?>
                                </a>
                            <?php }?>

                            <?php if (is_admin()) {?>
                                <a class="dropdown-item" href="<?=cn("module")?>">
                                    <i class="ft-layers text-primary"></i> <?=lang('modules')?>
                                </a>
                            <?php }?>
                            
                            <?php if (is_admin()) {?>
                                <a class="dropdown-item" href="<?=cn("tools")?>">
                                    <i class="ft-sliders"></i>  <?=lang('tools')?>
                                </a>
                            <?php }?>
                            
                        <?php
                        $lang_default = get_default_language();
                        if (!empty($lang_default)) {
                            ?>
                        <div class="language-mobile">
                            <div class="name"><i class="fa fa-language"></i> Language</div>
                            <?php if (!empty($languages)) {
                            foreach ($languages as $key => $value) {
                            ?>
                            <a class="actionItem <?=$lang_default->code == $value->code ? "bg-primary" : ""?>"
                                href="<?=cn('set_language')?>" data-redirect="<?=current_url()?>"
                                data-id="<?=$value->code?>"><i class="<?=$value->icon?>"></i> <?=$value->name?></a>
                            <?php }}?>
                        </div>
                        <?php }?>
                    </li>
                </ul>
            </div>
        </li>
        <?php } else {?>
        <li class="dropdown-user team-header-item float-right">
            <div class="dropdown">
                <a class="dropdown-toggle actionItem" href="<?=cn("team/back_to_user")?>"
                    data-redirect="<?=cn("team")?>" data-toggle="tooltip" data-placement="left" title=""
                    data-original-title="<?=lang("back_to_your_profile")?>"><i
                        class="ft-arrow-right text-success"></i></a>
            </div>
        </li>
        <?php }?> 
        
        <li class="dropdown-lang float-right">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="<?=$lang_default->icon?>" style="font-size: 16px;"></span>

                    <i class="dropdown-down-icon ft-chevron-down"></i>
                </a>
                <ul class="dropdown-menu pullDown" aria-labelledby="dropdownMenuButton">
                    <li class="header"> <?=lang('language')?></li>
                    <li class="body">
                        <?php if (!empty($languages)) {
                            foreach ($languages as $key => $value) {
                            ?>
                            <a class="dropdown-item actionItem" href="<?=cn('set_language')?>"
                                data-redirect="<?=current_url()?>" data-id="<?=$value->code?>"><i class="<?=$value->icon?>"></i>
                                <?=$value->name?></a>
                            <?php }}?>
                    </li>
                   
                </ul>
            </div>
        </li>
        <?php $notification = get_notification_count() ?>        
        <li class="dropdown notifications hidden-sm-down  float-right">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                <i class="zmdi zmdi-notifications-none"></i>
            <span class="label-count"><?php if(!$notification['count'] == 0){ echo $notification['count']; }?></span>
            </a>
            <?php if($notification['notif']){ ?>
                <ul class="dropdown-menu pullDown">
                    <li class="header"><?= lang('notifications') ?></li>
                        <li class="body">
                            <ul class="menu list-unstyled">
                                <?php foreach ($notification['notif'] as $key => $value) { 
                                    $author = $this->model->get("fullname", USERS, "id = '".$value->id_user_from."'");
                                    $to_time = strtotime(NOW);
                                    $from_time = strtotime($value->time_notif);
                                    $time = "";
                                    if(round(abs($to_time - $from_time) / 60,0) > 59 ){
                                        $time = strtotime($value->time_notif);
                                        $time = date('d/m/Y',$time);
                                    }else{
                                        $time = lang('il y a ').round(abs($to_time - $from_time)/ 60,0)." min";
                                    }
                                    $information = $this->model->get("*", 'user_information', "id_user = '".$value->id_user_to."'");
                                    ?>
                                    <li>
                                        <a href="<?= cn('notification/seen/'.$value->id) ?>" data-redirect="<?= cn('notification/view/'.$value->id) ?>" class="actionItem" >
                                        <!-- <a href="<?= cn('notification/seen/'.$value->id) ?>" data-toggle="modal" data-target="#mainModal" class="actionItem read-more" data-text="<?= $value->text ?>"> -->
                                            <div class="media">
                                                <div class="name"><i class="fa fa-paper-plane"></i></div>
                                                
                                                <div class="media-body">
                                                    <span class="name"><span class="time"><?= $time ?></span></span>
                                                    <span class="message">
                                                        <span class="name"><?= lang('new_post') ?></span>
                                                        <?php
                                                            if(!is_customer()){ 
                                                                if($value->status == 2){
                                                                    echo $author->fullname.' a publié pour '.$information->raison_social; 
                                                                }elseif($value->status == 1){
                                                                    echo $author->fullname.' a planifié pour '.$information->raison_social;
                                                                }
                                                            }else{ 
                                                                if($value->status == 2){
                                                                    echo lang('post publié'); 
                                                                }elseif($value->status == 1){
                                                                    echo lang('post planifié'); 
                                                                }
                                                            } 
                                                        ?>
                                                        
                                                    </span>
                                                    <span class="name"></span>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- </a> -->
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <li class="footer"> <a href="<?= cn('notification/list') ?>"><?= lang('View_All') ?></a> </li>

                </ul>
            <?php } ?>
        </li>
        <li class="dropdown notifications hidden-sm-down float-right">
            <?php if (session('cm_uid')) {?>
                <div class="client-selected" style="padding: 19px;">
                    <span class="c-name"><?=$chosenuser->rs?></span>
                </div>
            <?php } ?>
        </li>
        <li class="float-right">  

        </li> 
         <li class="float-right package">
            <?php 
            if(is_customer()) {?>
            <span>
                <small>
                    <?= lang('my_subscription_label_1')?> <strong> <?=$package_name?></strong>
                </small> 
                
            </span>
            <?php } ?>
        </li>   
           
    </ul>
    
</nav>