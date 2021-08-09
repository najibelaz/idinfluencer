<div class="row top-title">
    <div class="col-md-12 col-sm-12">
        <div class="info-box-2 info-box-2-1">
            <div class="d-flex align-items-center flex-wrap">
                <h4>
                    <i class="material-icons">date_range</i>
                    <?=lang("calendrier")?>
                </h4>
                
            </div>
        </div>
    </div>
</div>

<form method="get" accept="<?=cn('schedules')?>">
<div class="row">
    <a class="pn-toggle-open"><i class="fa fa-filter" aria-hidden="true"></i></a>
    <div class="col-md-12 col-lg-3 pn-box-sidebar" id="ttt">
        
            <div class="sc-options box-sc-option sn card">




                <?php
                if(!is_customer()){
                    if ($grps) { ?>

                <div class="box-sc-option sn">
                    <div class="header pb-0">
                        <h2 class="ml-3 title ntop"><?=lang("Groups")?></h2>

                    </div>

                    <div class="body">
                        <ul class="mb-3">
                            <?php foreach ($grps as $key => $row) {
                        ?>
                            <li class="list-grps">
                                <div class="checkbox">
                                    <input type="checkbox" name="grps_filter[]" id="grp_checkbox_<?=$row->name?>"
                                        <?php if(in_array($row->ids, $grps_filter) || empty($sc_type)) {?> checked=""
                                        <?php } ?> value="<?=str_replace(" ", "_", $row->ids)?>">
                                    <label class="" for="grp_checkbox_<?=$row->name?>">
                                        <span class="name">
                                            <?=$row->name?>
                                        </span>
                                    </label>
                                </div>
                                <?php
                                $account_info = get_accounts($row->ids);
                                if (!empty($account_info)) { ?>
                                <button
                                    class="btn-slide <?php echo ((get('etatdrpdown_'.$row->ids) == 'open')? 'open' : ''); ?>">
                                    <i class="dropdown-down-icon ft-chevron-down"></i>
                                </button>
                                <?php } ?>
                                <input class="form-control etatdrpdown"
                                    value="<?php echo ((get('etatdrpdown_'.$row->ids) == 'open')?'open' : 'close'); ?>"
                                    type="hidden" name="etatdrpdown_<?=$row->ids?>">
                                <ul class="list-slide pl-3"
                                    <?php echo ((get('etatdrpdown_'.$row->ids) == 'open')?'style="display:block;"' : ''); ?>>
                                    <?php
                                        if (!empty($account_info)) { 
                                            foreach ($account_info as $key => $row) {
                                                $account_name = ""; 
                                                if (isset($row->fullname)) {
                                                    $account_name = $row->fullname; 
                                                }else{
                                                    $account_name = $row->username; 
                                                }
                                                $accounts[] = strtolower($account_name);?>

                                    <li>
                                        <div class="checkbox">
                                            <input type="checkbox" name="account_filter[]"
                                                id="md_checkbox_<?=$row->ids?>"
                                                <?php if(in_array($row->ids, $account_filter) || empty($sc_type)) {?>
                                                checked="" <?php } ?> value="<?=str_replace(" ", "_", $row->ids)?>">
                                            <label class="" for="md_checkbox_<?=$row->ids?>">
                                                <span class="name" style="color: <?=$row->color?>"><i
                                                        class="fa fa-<?=$row->social_label?>"></i>
                                                    <?=$account_name?></span>
                                            </label>

                                        </div>
                                    </li>
                                    <?php }?>
                                    <?php }
                                    ?>
                                </ul>
                            </li>
                            <?php }?>
                        </ul>
                    </div>

                </div>
                <?php }
             }else{
                 ?>
                <div class="box-sc-option sn">
                    <div class="header pb-0">
                        <h2 class="ml-3 title ntop"><?=lang("accounts_customer")?></h2>

                    </div>

                    <div class="body">
                        <ul class="list-slide">
                            <?php
                            $account_info = get_accounts();
                                if (!empty($account_info)) { 
                                    foreach ($account_info as $key => $row) {
                                        $account_name = ""; 
                                        if (isset($row->fullname)) {
                                            $account_name = $row->fullname; 
                                        }else{
                                            $account_name = $row->username; 
                                        }
                                        if(empty($account_filter)){
                                            $account_filter = array();
                                        }
                                        $accounts[] = strtolower($account_name);?>
                            <li>
                                <div class="checkbox">
                                    <input type="checkbox" name="account_filter[]" id="md_checkbox_<?=$row->ids?>"
                                        <?php if(in_array($row->ids, $account_filter) || empty($sc_type)) {?> checked=""
                                        <?php } ?> value="<?=str_replace(" ", "_", $row->ids)?>">
                                    <label class="" for="md_checkbox_<?=$row->ids?>">
                                        <span class="name"><i class="fa fa-<?=$row->social_label?>"></i>
                                            <?=$account_name?></span>
                                    </label>

                                </div>
                            </li>
                            <?php }?>
                            <?php }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php     
             }
             ?>


              
            </div>
        
    </div>
    <div class="col-md-12 col-lg-9">
      
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-lg-6  pr-2 pl-2 pr-lg-3 pl-lg-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="box-sc-option box-sc-option-type cal-dropdown">
                                    <div class="pb-0">
                                        <h2 class="title ntop">
                                            <span><?=lang("Schedule type")?></span>
                                            <i class="dropdown-down-icon ft-chevron-down"></i>
                                        </h2>
                                    </div>
                                    <div class="">
                                        <ul>
                                            <li class="active">
                                                <div class="checkbox">
                                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_queue"
                                                        <?php if(in_array(ST_PLANIFIED, $sc_type) || empty($sc_type)) {?>
                                                        checked="" <?php } ?> value="<?=ST_PLANIFIED?>">
                                                    <label for="md_checkbox_queue">
                                                        <span class="name"><?=lang("Queue")?></span>
                                                    </label>

                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_published"
                                                        <?php if(in_array(ST_PUBLISHED, $sc_type) || empty($sc_type)) {?>
                                                        checked="" <?php } ?> value="<?=ST_PUBLISHED?>">
                                                    <label for="md_checkbox_published">
                                                        <span class="name"><?=lang("Published")?></span>
                                                    </label>

                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="sc_type[]" id="md_checkbox_unpublished"
                                                        <?php if(in_array(ST_FAILED, $sc_type) || empty($sc_type)) {?>
                                                        checked="" <?php } ?> value="<?=ST_FAILED?>">
                                                    <label for="md_checkbox_unpublished">
                                                        <span class="name"><?=lang("Unpublished")?></span>
                                                    </label>

                                                </div>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $social_info = load_social_info(true);
                                $socials = array();
                                if (!empty($social_info)) { ?>

                                <div class="box-sc-option sn cal-dropdown">
                                    <div class="pb-0">
                                        <h2 class="title ntop">
                                            <span><?=lang("Social networks")?></span>
                                            <i class="dropdown-down-icon ft-chevron-down"></i>
                                        </h2>
                                    </div>

                                    <div class="">
                                        <ul>
                                            <?php foreach ($social_info as $key => $row) {
                                        $socials[] = strtolower($row->title);
                                    ?>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="social_filter[]"
                                                        id="md_checkbox_<?=str_replace(" ", "_", $row->title)?>"
                                                        <?php if(in_array(str_replace(" ", "_", $row->title), $social_filter) || empty($sc_type)) {?>
                                                        checked="" <?php } ?>
                                                        value="<?=str_replace(" ", "_", $row->title)?>">
                                                    <label class=""
                                                        for="md_checkbox_<?=str_replace(" ", "_", $row->title)?>">
                                                        <span class="name" style="color: <?=$row->color?>"><i
                                                                class="fa <?=$row->icon?>"></i>
                                                            <?=$row->title?></span>
                                                    </label>

                                                </div>
                                            </li>
                                            <?php }?>
                                        </ul>
                                    </div>

                                </div>
                                <?php }?>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-8 d-flex align-items-center">
                                <div class="input-group mb-2 mb-lg-0">
                                    <input type="text" name="date_from" class="date nodays form-control"
                                        placeholder="Ex: <?php date("m/Y")?>" data-dtp="dtp_vzTrt"
                                        value="<?php if(isset($_GET['date_from'])){ echo $_GET['date_from']; } ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button class="edit btn btn-raised btn-primary waves-effect btn-block"><i
                                        class="material-icons">search</i><?=lang("search")?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ecalendar mt-3">                      
                    <div class="ecalendar-body">                                    
                        <div class="posts pr-15 pl-15">
                            <div class="row justify-content-center justify-content-md-start">
                            <?php 
                                    foreach ($schedules as $key2 => $item) {
                                        $timestamp = strtotime($item->time_post);
                                        setlocale(LC_TIME, 'fr_FR.utf8','fra');
                                        $day_name = strftime('%A', $timestamp);
                                        $day = date('d', $timestamp);
                                        $mount = date('m', $timestamp);
                                        $day_now = date('d');
                                        $mount_now = date('m');
                                        $account = get_account($item->account, $item->social_label);
                                        $avatar = $account->avatar;
                                        $data = json_decode($item->data);
                                        $data_preview = (array)$data;
                                        $data_preview['idp'] = $item->id;
                                        // var_dump($data_preview);die;
                                        $text = $data->caption;
                                        $disabed = ($day < $day_now && $mount <= $mount_now) ? 'disabled' : '';
                                        $color ="#00aeef";
                                        if($item->status == ST_PUBLISHED){
                                            $color ="#33c15d";
                                        }
                                        if($item->status == ST_FAILED){
                                            $color ="#000000";
                                        }
                                        if($item->status == ST_WAITTING){
                                            $color ="#A63232";
                                        }
                                        
                                    ?>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="post d-flex align-items-start"
                                            >
                                            <div class="date">
                                                <span class="date-dayname">
                                                    <?= strftime('%A', $timestamp); ?>
                                                </span>
                                                <br>
                                                <span class="date-day">
                                                    <?= date('d', $timestamp); ?>
                                                </span>
                                            </div>
                                            <?= $this->load->view('preview/'.$item->social_label, $data_preview); ?>
                                            <div class="post-statut" 
                                             style="background-color: <?= $color ?>;">
                                            </div>
                                        </div>  
                                    </div>
                                        
                            <?php } ?>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<div id="read-more-box" class="d-none">
    <div class="modal-dialog read-more-box" role="document">
        <div class="modal-content">
            <div class="modal-body" id="read-more-info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-simple waves-effect"
                    data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.checkbox input[name="grps_filter[]"]').change(function () {
        var _this = $(this);
        _this.parents('li').find('ul li').each(function () {
            if (_this.is(':checked')) {
                $(this).find('input').prop('checked', true);
            } else {
                $(this).find('input').prop('checked', false);
            }
        })
    })
    $('.btn-slide').click(function () {
        var _this = $(this).parents('li');
        if ($(this).hasClass('open')) {
            _this.find('.etatdrpdown').val('close');
        } else {
            _this.find('.etatdrpdown').val('open');
        }
    });
    $('.checkbox input[type="checkbox"]').change(function () {
        //$('#ttt form').submit();
    });



</script>