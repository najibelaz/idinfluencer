<?php
$user = $this->model->get("*", USERS, "id = '".session('uid')."'");
$vads_action_mode = get_option('vads_action_mode', '');
$vads_amount = str_replace('.', '', $invoice['total_price_excl_vat']);
$vads_sub_amount = str_replace('.', '', $invoice['total_price_excl_vat']);
$vads_capture_delay = get_option('vads_capture_delay', '');
$vads_ctx_mode = get_option('vads_ctx_mode', 0)==1?"PRODUCTION":"TEST";

$vads_currency = get_option('vads_currency', '');
$vads_sub_currency = get_option('vads_currency', '');
$vads_order_id = $invoice['id'];
$vads_page_action = get_option('vads_page_action', '');

$vads_payment_config = get_option('vads_payment_config', '');
$vads_site_id = get_option('vads_site_id', '');
$vads_trans_date = date('YmdHis');
$vads_cust_email = $user->email;
$vads_sub_effect_date = date('Ymd');
$vads_sub_desc = get_option('vads_sub_desc', '');
// $vads_return_mode = 'POST';
$vads_id_client = session('uid');
// $vads_redirect_success_timeout = get_option('vads_redirect_success_timeout', '');
$vads_trans_id = substr(md5(microtime()),rand(0,26),6);;
// $vads_url_success =cn().get_option('vads_url_success', '');
$vads_version = get_option('vads_version', '');
$vads_cle_test = get_option('vads_cle_test', '');
$values_signature = $vads_action_mode.'+'. $vads_amount.'+'.$vads_capture_delay.'+'. $vads_ctx_mode.'+'.$vads_currency.'+'.$vads_cust_email .'+'.$vads_id_client.'+'.$vads_order_id.'+'. $vads_page_action.'+'. $vads_payment_config.'+'.  $vads_site_id.'+'.$vads_sub_amount.'+'.$vads_sub_currency.'+'.$vads_sub_desc.'+'.$vads_sub_effect_date.'+'. $vads_trans_date.'+'.  $vads_trans_id.'+'. $vads_version.'+'.$vads_cle_test;

$signature = base64_encode(hash_hmac('sha256',$values_signature, $vads_cle_test, true));
?>
<form method="POST" action="https://secure.payzen.eu/vads-payment/">
    <input type="hidden" name="vads_action_mode" value="<?=$vads_action_mode?>" />
    <input type="hidden" name="vads_amount" value="<?=$vads_amount?>" />
    <input type="hidden" name="vads_capture_delay" value="<?=$vads_capture_delay?>" />
    <input type="hidden" name="vads_ctx_mode" value="<?=$vads_ctx_mode?>" />
    <input type="hidden" name="vads_currency" value="<?=$vads_currency?>" />
    <input type="hidden" name="vads_cust_email" value="<?=$vads_cust_email?>" />
    <input type="hidden" name="vads_sub_amount" value="<?=$vads_sub_amount?>" />
    <input type="hidden" name="vads_sub_currency" value="<?=$vads_sub_currency?>" />
    <input type="hidden" name="vads_order_id" value="<?=$vads_order_id?>" />
    <input type="hidden" name="vads_page_action" value="<?=$vads_page_action?>" />
    <input type="hidden" name="vads_payment_config" value="<?=$vads_payment_config?>" />
    <input type="hidden" name="vads_site_id" value="<?=$vads_site_id?>" />
    <input type="hidden" name="vads_id_client" value="<?=$vads_id_client?>" />
    <input type="hidden" name="vads_trans_date" value="<?=$vads_trans_date?>" />
    <input type="hidden" name="vads_trans_id" value="<?=$vads_trans_id?>" />
    <input type="hidden" name="vads_sub_effect_date" value="<?=$vads_sub_effect_date?>" />
    <input type="hidden" name="vads_version" value="<?=$vads_version?>" />
    <input type="hidden" name="vads_sub_desc" value="<?=$vads_sub_desc?>" />
    <!-- <input type="hidden" name="vads_redirect_success_timeout" value="<?=$vads_redirect_success_timeout?>" /> -->
    <!-- <input type="hidden" name="vads_url_success" value="<?= $vads_url_success; ?>" /> -->
    <!-- <input type="hidden" name="vads_return_mode" value="POST" /> -->
    <input type="hidden" name="signature" value="<?= $signature ?>"/>
    <input type="submit" class="btn btn-primary" name="payer" value="<?=lang('Payer')?>"/>
</form>