<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2 class="head-title"><strong><i class="ft-credit-card"></i> </strong><?=lang('payment')?></h2>
            </div>

            <div class="body">
                <span class="text"> <?=lang('environment')?></span><br />
                <div class="pure-checkbox grey mr15 mb15">
                    <input type="radio" id="md_checkbox_payment_environment_enable" name="vads_ctx_mode"
                        class="filled-in chk-col-red" <?=get_option('vads_ctx_mode', 0)==1?"checked":""?>
                        value="1">
                    <label class="p0 m0" for="md_checkbox_payment_environment_enable">&nbsp;</label>
                    <span class="checkbox-text-right"> <?=lang('live')?></span>
                </div>
                <div class="pure-checkbox grey mr15 mb15">
                    <input type="radio" id="md_checkbox_payment_environment_disable" name="vads_ctx_mode"
                        class="filled-in chk-col-red" <?=get_option('vads_ctx_mode', 0)==0?"checked":""?>
                        value="0">
                    <label class="p0 m0" for="md_checkbox_payment_environment_disable">&nbsp;</label>
                    <span class="checkbox-text-right"> <?=lang('sandbox')?></span>
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('currency')?></span>
                    <select name="vads_currency" class="form-control">
                        <option value="840" <?=get_option('vads_currency', '978')=="840"?"selected":""?>>USD</option>
                        <option value="036" <?=get_option('vads_currency', '978')=="036"?"selected":""?>>AUD</option>
                        <option value="124" <?=get_option('vads_currency', '978')=="124"?"selected":""?>>CAD</option>
                        <option value="978" <?=get_option('vads_currency', '978')=="978"?"selected":""?>>EUR</option>
                        <option value="554" <?=get_option('vads_currency', '978')=="554"?"selected":""?>>NZD</option>
                        <option value="643" <?=get_option('vads_currency', '978')=="643"?"selected":""?>>RUB</option>
                        <option value="702" <?=get_option('vads_currency', '978')=="702"?"selected":""?>>SGD</option>
                        <option value="752" <?=get_option('vads_currency', '978')=="752"?"selected":""?>>SEK</option>
                        <option value="986" <?=get_option('vads_currency', '978')=="986"?"selected":""?>>BRL</option>
                        <option value="484" <?=get_option('vads_currency', '978')=="484"?"selected":""?>>MXN</option>
                        <option value="764" <?=get_option('vads_currency', '978')=="764"?"selected":""?>>THB</option>
                        <option value="392" <?=get_option('vads_currency', '978')=="392"?"selected":""?>>JPY</option>
                        <option value="458" <?=get_option('vads_currency', '978')=="458"?"selected":""?>>MYR</option>
                        <option value="608" <?=get_option('vads_currency', '978')=="608"?"selected":""?>>PHP</option>
                        <option value="901" <?=get_option('vads_currency', '978')=="901"?"selected":""?>>TWD</option>
                        <option value="203" <?=get_option('vads_currency', '978')=="203"?"selected":""?>>CZK</option>
                        <option value="985" <?=get_option('vads_currency', '978')=="985"?"selected":""?>>PLN</option>
                        <option value="826" <?=get_option('vads_currency', '978')=="826"?"selected":""?>>GBP</option>
                        <option value="756" <?=get_option('vads_currency', '978')=="756"?"selected":""?>>CHF</option>
                        <option value="348" <?=get_option('vads_currency', '978')=="348"?"selected":""?>>HUF</option>

                    </select>
                </div>

                <div class="form-group">
                    <span class="text"> <?=lang('publishable_key')?></span> 
                    <input type="text" class="form-control" name="vads_cle_publish" value="<?=get_option('vads_cle_publish', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_cle_test')?></span> 
                    <input type="text" class="form-control" name="vads_cle_test" value="<?=get_option('vads_cle_test', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_action_mode')?></span> 
                    <input type="text" class="form-control" name="vads_action_mode" value="<?=get_option('vads_action_mode', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_page_action')?></span> 
                    <input type="text" class="form-control" name="vads_page_action" value="<?=get_option('vads_page_action', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_payment_config')?></span> 
                    <input type="text" class="form-control" name="vads_payment_config" value="<?=get_option('vads_payment_config', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_site_id')?></span> 
                    <input type="text" class="form-control" name="vads_site_id" value="<?=get_option('vads_site_id', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_sub_desc')?></span> 
                    <input type="text" class="form-control" name="vads_sub_desc" value="<?=get_option('vads_sub_desc', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_capture_delay')?></span> 
                    <input type="text" class="form-control" name="vads_capture_delay" value="<?=get_option('vads_capture_delay', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_redirect_success_timeout')?></span> 
                    <input type="text" class="form-control" name="vads_redirect_success_timeout" value="<?=get_option('vads_redirect_success_timeout', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_url_success')?></span> 
                    <input type="text" class="form-control" name="vads_url_success" value="<?=get_option('vads_url_success', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"> <?=lang('vads_version')?></span> 
                    <input type="text" class="form-control" name="vads_version" value="<?=get_option('vads_version', '')?>">
                </div>
            </div>
        </div>
    </div>
</div>