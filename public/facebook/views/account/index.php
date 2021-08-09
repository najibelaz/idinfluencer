
<div class="col-md-6 col-lg-4 account-manager">
    <div class="card">
        <div class="header">
            <h2 class="card-title">
                <strong><i class="<?=$module_icon?>" aria-hidden="true"></i> </strong>
                 <?=$module_name?>
                <div class="pull-right card-option">
                    <a href="<?=cn($module."/popup_add_account")?>" class="ajaxModal" data-toggle="tooltip" data-placement="left" title="" data-original-title="<?=lang("add_account")?>"><i class="ft-plus"></i></a>
                </div>
            </h2>
        </div>
        <div class="body">
            <div class="account-manager-list scrollbar scrollbar-dynamic">
                <?php if(!empty($list_account)){
                    foreach ($list_account as $key => $row) {
                        switch ($row->type) {
                            case 'page':
                                $icon = "fa-flag";
                                break;

                            case 'group':
                                $icon = "fa-users";
                                break;
                            
                            default:
                                $icon = "fa-user";
                                break;
                        }
                ?> 
                <div class="item">
                    <div class="am-img">
                        <i class="icon fa <?=$icon?>"></i>
                        <img class="img-circle" src="<?=$row->avatar?>">
                    </div>
                    <a class="am-info" href="https://fb.com/<?=$row->pid?>" target="_blank">
                        <div class="username"><?=$row->fullname?> 
                            <?php if($row->status == 0){ ?>
                            <span class="small error"><?=lang("re_login")?></span>
                            <?php }?>
                        </div>
                    </a>
                    <div class="am-option">
                        <div class="dropdown">
                            <button class="btn btn-neutral btn-icon  btn-icon-mini btn-round dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="ft-more-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="<?=cn($module."/popup_add_account/$row->ids")?>" class="ajaxModal" data-id="<?=$row->ids?>"><?=lang("edit")?></a></li>
                                <li><a href="<?=cn($module."/ajax_delete_item")?>" data-redirect="<?=cn("account_manager")?>" class="actionItem" data-id="<?=$row->ids?>"><?=lang("delete")?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php }}else{?>
                    <div class="empty">
                        <span><?=lang("add_an_account_to_begin")?></span>
                        <a href="<?=cn($module."/popup_add_account")?>" class="btn btn-primary ajaxModal"><?=lang("add_account")?></a>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>