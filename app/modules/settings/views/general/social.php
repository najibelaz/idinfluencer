<?php
$general_settings = block_general_settings();
?>
<div class="card">
    <div class="header">
        <h2><strong><i class="fa fa-user-circle-o"></i> </strong> <?=lang('social_settings')?></h2>
    </div>


    <div class="body">
        <div class="tab-list">

            <ul class="nav nav-tabs p-0">
                <?php $setting_lists = json_decode($general_settings->setting_lists);
            	if(!empty($setting_lists)){
            		foreach ($setting_lists as $key => $name) {
            	?>
                <li class="nav-item">
                    <a class="<?=$key==0?"active":""?> nav-link" data-toggle="tab"
                        href="#<?=$name?>"><i class="fa fa-<?=$name?>"></i>
                        <?=str_replace("_", " ", ucfirst($name))?></a></li>
                <?php }}?>
            </ul>
        </div>
        <div class="card-block p0">
            <div class="tab-content pt15">
                <?=$general_settings->data?>
            </div>
        </div>
    </div>
</div>