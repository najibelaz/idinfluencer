<aside id="" class="sidebar">
    <div class="menu">
        <ul class="list">
            <?php
$segement_allowed = array('waiting', 'file_manager', 'jeux_concours', 'orders', 'account_manager', 'post');
$style = '';
if (!in_array(segment(1), $segement_allowed)) {
    $style = 'display:none;';
}
if (((is_manager() || is_admin() || is_responsable())) 
// && (in_array(segment(1), ['post','dashboard', 'caption', 'waiting','account_manager','jeu_concours','schedules'])) 
) {?>
            <li style="<?=$style?>">
                <div class="clients-search">
                    <div class="clients-search-header">
                        <?php if (session('cm_uid')) {?>
                        <div class="cs-title">Compte actif :</div>
                        <div class="client-selected">
                            <?php if ($chosenuser->avatar != "") {?>
                            <img src="<?=$chosenuser->avatar?>" alt="" class="menu-toggle">
                            <?php } else {?>
                            <img src="<?=   cn('assets/img/default-avatar.png')?>" alt="" class="menu-toggle">
                            <?php }?>
                            <h2 class="c-name"><?=$chosenuser->rs?></h2>
                            <a href="<?=cn('profile/rm_user/' . $chosenuser->idu)?>" data-redirect="<?=current_url()?>"
                                class="btn-mang"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                        </div>
                        <?php } else {
                            $chosenuser["id"] = "null";
                            $chosenuser = (object) $chosenuser
                            ?>
                        <!-- <div class="cs-title"><?=lang('please_select_an_account');?></div> -->
                        <div class="client-selected">
                            <img src="<?=cn('assets/img/default-avatar.png')?>" alt="" class="menu-toggle">
                            <h2 class="c-name unselected"><?=lang('please_select_an_account');?></h2>
                        </div>
                        <?php }?>
                    </div>
                    <div class="clients-search-body">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="<?=lang('search')?>...">
                                <span class="input-group-addon">
                                    <i class="zmdi zmdi-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="clients-list">
                            <?php
                            $selected_idu = "";
                                if (isset($chosenuser->idu)) {
                                    $selected_idu = $chosenuser->idu;
                                }
                                foreach ($customers as $customer) {
                                    if ($customer->id != $selected_idu) {
                                        ?>
                            <div class="client-item">
                                <?php if ($customer->avatar != "") {?>
                                    <img src="<?=$customer->avatar?>" alt="">
                                <?php } else {?>
                                <img src="<?=BASE?>assets/img/default-avatar.png" alt="">
                                <?php }?>
                                <span class="name"><?=$customer->rs?></span>
                                <a href="<?=BASE?>profile/view_user/<?=$customer->id?>"
                                    data-redirect="<?=current_url()?>" class="btn-mang"><?=lang('manage')?></a>
                            </div>

                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php }?>

            <li class="<?=(segment(1) == 'dashboard') ? "active open" : ""?>">
                <a href="<?=cn("dashboard")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('dashboard')?>"><i
                        class="material-icons">dashboard</i><span><?=lang('dashboard')?></span></a>
            </li>
            <?php if (file_exists(APPPATH . "modules/post")) {?>
            <?php if (is_admin() || is_manager() || is_responsable() || permission_pack("create_a_post")) {?>
            <li
                class="<?=((in_array(segment(1), ['post', 'caption', 'waiting']) && segment(2) == '') || (in_array(segment(1), ['post']) && segment(2) == 'draft')) ? "active" : ""?>">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    <span><?=lang('post')?></span>
                </a>
                <ul class="ml-menu">
                    <li class="<?=(segment(1) == 'post' && segment(2) == '') ? "active open" : ""?>">
                        <a href="<?=cn("post")?>"><i class="fa fa-plus-circle"
                                aria-hidden="true"></i><span><?=lang('create_a_post')?></span></a>
                    </li>
                    <li class="<?=(segment(1) == 'caption') ? "active open" : ""?>">
                        <a href="<?=cn("caption")?>"><i
                                class="material-icons">view_array</i><span><?=lang('post_templates')?></span>
                        </a>
                    </li>
                    <?php if (is_admin() || is_manager() || is_responsable()) {?>
                    <li class="<?=(segment(2) == 'draft') ? "active open" : ""?>">
                        <a href="<?=cn("post/draft")?>"><i class="fa fa-pencil-square-o"
                                aria-hidden="true"></i><span><?=lang('draft')?></span></a>
                    </li>
                    <?php }?>
                    <?php if (!is_customer()) {?>
                    <li class="<?=(segment(1) == 'waiting') ? "active open" : ""?>">
                        <a href="<?=cn("waiting")?>"><i class="fa fa-clock-o"
                                aria-hidden="true"></i><span><?=lang('waiting')?></span></a>
                    </li>
                    <?php }?>
                </ul>
            </li>
            <?php if (is_admin() || is_manager() || is_responsable()) {?>
            <li
                class="<?=(segment(1) == 'post' && in_array(segment(2), ['facebook', 'twitter', 'instagram'])) ? "active" : ""?>">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">layers</i>
                    <span><?=lang('gestion_posts')?></span>
                </a>
                <ul class="ml-menu">
                    <li class="<?=(segment(1) == 'post' && segment(2) == 'facebook') ? "active open" : ""?>">
                        <a href="<?=cn("post/facebook")?>"><i class="fa fa-facebook"
                                aria-hidden="true"></i><span><?=lang('facebook_posts')?></span></a>
                    </li>
                    <li class="<?=(segment(1) == 'post' && segment(2) == 'twitter') ? "active open" : ""?>">
                        <a href="<?=cn("post/twitter")?>"><i
                                class="fa fa-twitter"></i><span><?=lang('twitter_posts')?></span>
                        </a>
                    </li>

                    <li class="<?=(segment(1) == 'post' && segment(2) == 'instagram') ? "active open" : ""?>">
                        <a href="<?=cn("post/instagram")?>"><i class="fa fa-instagram"
                                aria-hidden="true"></i><span><?=lang('instagram_posts')?></span></a>
                    </li>
                </ul>
            </li>
            <?php }?>
            <?php }?>

            <?php }?>
            <li class="<?=(segment(1) == 'schedules') ? "active open" : ""?>">
                <a href="<?=cn("schedules/calendar_user")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('publication_planning')?>"><i
                        class="material-icons">date_range</i><span><?=lang('publication_planning')?></span></a>
            </li>
            <?php if (!is_customer()) {?>
            <li class="<?=(segment(1) == 'orders') ? "active open" : ""?>">
                <a href="<?=cn("orders/user_invoices")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('orders')?>">
                    <i class="material-icons">description</i>
                    <span><?=lang('orders')?></span>
                </a>
            </li>
            <?php } else {?>
            <li class="<?=(segment(1) == 'users' && segment(2) == 'user_invoice_api') ? "active open" : ""?>">
                <a href="<?=cn("orders/user_invoice_api")?>?company_id=<?=$user->id_team?>" class=""
                    data-toggle="tooltip" data-placement="right" title="<?=lang('orders')?>">
                    <i class="material-icons">description</i>
                    <span><?=lang('orders')?></span>
                </a>
            </li>
            <?php }?>

            <?php if (!is_customer()) {?>
            <li class="<?=(segment(1) == 'users') ? "active" : ""?>">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span><?=lang('gestion_utilisateurs')?></span>
                </a>
                <ul class="ml-menu">
                    <?php if (is_admin()) {?>
                    <li class="<?=(segment(1) == 'users' && segment(2) == 'admin') ? "active open" : ""?>">
                        <a href="<?=cn("users/admin")?>">
                            <i class="material-icons">verified_user</i>
                            <span><?=lang('admins')?></span></a>
                    </li>
                    <li class="<?=(segment(1) == 'users' && segment(2) == 'res_cm') ? "active open" : ""?>">
                        <a href="<?=cn("users/res_cm")?>">
                            <i class="material-icons">person</i>
                            <span><?=lang('res_cm')?></span>
                        </a>
                    </li>
                    <?php }?>
                    <?php if (is_admin() || is_responsable()) {?>
                    <li class="<?=(segment(1) == 'users' && segment(2) == 'cm') ? "active open" : ""?>">
                        <a href="<?=cn("users/manager")?>">
                            <i class="material-icons">assignment_ind</i>
                            <span><?=lang('cm')?></span></a>
                    </li>
                    <?php }?>


                    <li class="<?=(segment(1) == 'users' && segment(2) == 'customer') ? "active open" : ""?>">
                        <a href="<?=cn("users/customer")?>">
                            <i class="material-icons">group</i>
                            <span><?=lang('clients_actif')?></span></a>
                    </li>
                    <?php if (is_admin() ) {?>

                    <li class="<?=(segment(1) == 'users' && segment(2) == 'customer_inactif') ? "active open" : ""?>">
                        <a href="<?=cn("users/customer_inactif")?>">
                            <i class="material-icons">group</i>
                            <span><?=lang('clients_inactif')?></span></a>
                    </li>
                    <?php } ?>


                </ul>
            </li>
            <?php }?>
            <li class="<?=(segment(1) == 'jeu_concours') ? "active open" : ""?>">
                <a href="<?=cn("jeu_concours")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="Jeu concours">
                    <i class="fa fa-trophy" aria-hidden="true"></i>
                    <span><?=lang('jeu_concours')?></span>
                </a>
            </li>


            <?php if (permission("photo_type") || permission("video_type")) {?>
            <li class="<?=(segment(1) == 'file_manager') ? "active open" : ""?>">
                <a href="<?=cn("file_manager")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('files_manager')?>"><i
                        class="material-icons">storage</i><span><?=lang('files_manager')?></span></a>
            </li>
            <?php }?>
            <li class="<?=(segment(1) == 'reporting' || segment(1) == 'instagram')?"active":""?>">
                <a href="<?=cn("facebook/stats")?>" class="" data-toggle="tooltip" data-placement="right" title="<?=lang('rapport')?>"><i
                            class="fa fa-bar-chart"></i><span><?=lang('rapport')?></span></a>
                </a>
            </li>
            <?php if(is_admin()){?>
            <li class="<?=(segment(1) == 'tools')?"active open":""?>">
                <a href="<?=cn("tools")?>" class="" data-toggle="tooltip" data-placement="right" title="<?=lang('tools')?>"><i
                        class="material-icons">Tools</i><span><?=lang('tools')?></span></a>
            </li>
            <?php }?>
            <li class="<?=(segment(1) == 'account_manager') ? "active open" : ""?>">
                <a href="<?=cn("account_manager")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('account_manager')?>"><i class="fa fa-user-circle"
                        aria-hidden="true"></i><span><?=lang('account_manager')?></span></a>
            </li>

            <!-- <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'tools') ? "active open" : ""?>">
                <a href="<?=cn("tools")?>" class="">
                    <i class="ft-sliders"></i>
                    <span><?=lang('tools')?></span>
                </a>
            </li>
            <?php }?> -->

            <!-- <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'module') ? "active open" : ""?>">
                <a href="<?=cn("module")?>" class="">
                    <i class="material-icons">view_module</i>
                    <span><?=lang('modules')?></span>
                </a>
            </li>
            <?php }?> -->

            <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'mailjet') ? "active open" : ""?>">
                <a href="<?=cn("mailjet")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('mailjet')?>">
                    <i class="ft-mail"></i>
                    <span>mailjet</span>
                </a>
            </li>
            <?php }?>

            <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'settings') ? "active open" : ""?>">
                <a href="<?=cn("settings/general")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('settings')?>">
                    <i class="ft-settings"></i>
                    <span><?=lang('settings')?></span>
                </a>
            </li>
            <?php }?>

            <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'packages') ? "active open" : ""?>">
                <a href="<?=cn("packages")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('packages')?>">
                    <i class="ft-package"></i>
                    <span><?=lang('packages')?></span>
                </a>
            </li>
            <?php }?>

            <?php if (is_admin()|| is_responsable()) {?>
            <li class="<?=(segment(1) == 'group_manager') ? "active open" : ""?>">
                <a href="<?=cn("group_manager")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('group_manager')?>">
                    <i class="ft-target"></i>
                    <span><?=lang('group_manager')?></span>
                </a>
            </li>
            <?php }?>

            <?php if (is_admin()) {?>
            <li class="<?=(segment(1) == 'language') ? "active open" : ""?>">
                <a href="<?=cn("language")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('language')?>">
                    <i class="fa fa-language" aria-hidden="true"></i>
                    <span><?=lang('language')?></span>
                </a>
            </li>
            <?php }?>
            <li class="<?=(segment(1) == 'notification') ? "active open" : ""?>">
                <a href="<?=cn("notification")?>" class="" data-toggle="tooltip" data-placement="right"
                    title="<?=lang('notification')?>">
                    <i class="zmdi zmdi-notifications-none" aria-hidden="true"></i>
                    <span><?=lang('notification')?></span>
                </a>
            </li>

            <li class="plus">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span><?=lang('Plus')?></span>
                </a>
                <ul class="ml-menu">
                   
                </ul>
            </li>

        </ul>
    </div>
</aside>