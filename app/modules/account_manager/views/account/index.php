<div class="wrap-content">
    <div class="row top-title">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h4><i class="fa fa-user-circle" aria-hidden="true"></i> <?=lang("account_manager")?></h4>
        </div>
    </div>
    <div class="row">
        <?php load_account('facebook')?>  
        <?php load_account('twitter')?>  
        <?php load_account('instagram')?>
        <?php load_account('google_business')?>
        
    </div>
</div>