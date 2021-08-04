<div class="lead"><?=lang('general')?></div>

<div class="row">
    <div class="col-md-12">
        <div class="item form-group">
            <span class="text"><?=lang('Google Api Key')?></span> 
            <div class="activity-option-input">
                <input type="text" class="form-control" name="gb_api_key" value="<?=get_option("gb_api_key", "")?>">
          </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="item form-group">
            <span class="text"><?=lang('Google Client ID')?>:</span> 
            <div class="activity-option-input">
                <input type="text" class="form-control" name="gb_app_id" value="<?=get_option("gb_app_id", "")?>">
          </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="item form-group">
            <span class="text"><?=lang('Google Client Secret')?>:</span> 
            <div class="activity-option-input">
                <input type="text" class="form-control" name="gb_app_secret" value="<?=get_option("gb_app_secret", "")?>">
          </div>
        </div>
    </div>
</div>
 