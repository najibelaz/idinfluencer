<div class="wrap-content container">
    <form action="<?=cn('google_business/ajax_add_account')?>" data-redirect="<?=cn("account_manager")?>" class="actionForm" method="post">
    <div class="list-add-accounts">
        <div class="account_info">
            <div class="title"><?=lang('List locations')?></div>
            <div class="desc"><?=lang('Select locations your want add')?></div>
        </div>

        <input type="hidden" name="ids" name="ids" value="<?=segment(3)?>">
        <ul class="list-group">
            <?php if(!empty($locations)){?>
            <li class="list-group-item item-header">
                <i class="ft-map-pin"></i> <?=lang('Locations')?>
            </li>
            <?php
                foreach ($locations as $key => $row) {
                    $locationstate = $row->getLocationState();
                    if($locationstate->getIsVerified() == 1){
            ?>
            <li class="list-group-item">
                <div class="pure-checkbox grey mr15">
                    <input type="checkbox" id="md_checkbox_<?=$row->getName()?>" name="accounts[]" class="filled-in chk-col-red" value="<?=$row->getName()?>">
                    <label class="p0 m0" for="md_checkbox_<?=$row->getName()?>">&nbsp;</label>
                    <span class="checkbox-text-right"><?=$row->getLocationName()?></span>
                </div>
            </li>
            <?php }}}?>

            <li class="list-group-item text-center">
                <button type="submit" class="btn btn-success"><?=lang('Add location')?></button>
            </li>
        </ul>
    </div>
    </form>
</div>