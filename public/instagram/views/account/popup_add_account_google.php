<div id="load_popup_modal_contant" class="" role="dialog">
    <div class="modal-dialog modal-md">
        <form action="<?=BASE?>instagram/ajax_add_account_google" data-type-message="text" data-redirect="<?=cn("account_manager")?>" data-async role="form" class="form-horizontal actionForm" role="form" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title"><img alt="Google Business icon" src="https://img.icons8.com/ios/2x/google-business.png" style="height:50px;width:50px;"> Ajouter un compte Google</div>
            </div>
            <div class="modal-body m15">
                <div class="row">
                    <div class="col-sm-offset-2 col-sm-8">
                        <div class="row form-notify"></div>
                        <div class="form-group">
                            <label class="control-label" for="username"><?=lang("google_username")?></label>
                                <input type="text" name="username" class="form-control" id="username" value="<?=!empty($result)?$result->username:""?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password"><?=lang("google_password")?></label>
                            <input type="password" name="password" class="form-control" id="password" value="">
                        </div>
                        
                        <div class="form-group form-verification_code hide">
                            <label class="control-label" for="code"><?=lang("verification_code")?></label>
                            <input type="text" name="code" class="form-control" id="code">
                        </div>
                        <div class="form-group form-security_code hide">
                            <label class="control-label" for="security_code"> <?=lang('security_code')?></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="security_code" id="security_code">
                                <span class="input-group-btn">
                                    <a href="<?=BASE?>google/ajax_add_account/resend" class="btn btn-default actionMultiItem"><?=lang('get_new_code')?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input name="submit_popup" id="submit_popup" type="submit" value="<?=lang('add_account')?>" class="btn btn-primary" />
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
        </form>
    </div>
</div>