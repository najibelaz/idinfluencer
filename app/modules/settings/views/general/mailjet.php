<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong><i class="fa fa-envelope-o"></i></strong> <?=lang('mail')?></h2>
            </div>
            <div class="body">
            <div class="lead"> <?=lang('general_settings')?></div>
        <div class="form-group">
            <span class="text"><?=lang('Mailjet Api key')?></span>
            <input type="text" class="form-control" name="mailjet_api" value="<?=get_option('mailjet_api', '')?>">
        </div>
        <div class="form-group">
            <span class="text"><?=lang('Mailjet Api secret')?></span>
            <input type="text" class="form-control" name="mailjet_secret" value="<?=get_option('mailjet_secret', '')?>">
        </div>
    </div>
</div>