<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4>
            <i class="material-icons">date_range</i>
            <?=lang("Report")?>
        </h4>
    </div>
</div>

<div class="row">
    <a class="pn-toggle-open"><i class="fa fa-filter" aria-hidden="true"></i></a>
    <div class="col-md-12 col-lg-3 pn-box-sidebar" id="ttt">
        <div class="sc-options box-sc-option sn card">
            
            <?php
            if(!is_customer()){
                if ($grp) { ?>
                    <div class="box-sc-option sn">
                        <div class="header pb-0">
                            <h2 class="ml-3 title ntop"><?=lang("Groups")?></h2>

                        </div>

                        <div class="body">
                            <ul class="mb-3">
                                <?php foreach ($grp as $key => $row) { ?>
                                <li class="list-grps">
                                    <div class="">
                                        
                                        <label class="" for="grp_checkbox_<?=$row->name?>">
                                            <span class="name">
                                                <?=$row->name?>
                                            </span>
                                        </label>
                                    </div>
                                    <?php
                                    $account_info = get_accounts($row->ids);
                                    if (!empty($account_info)) { ?>
                                        <button class="btn-slide <?php echo ((get('etatdrpdown_'.$row->ids) == 'open')? 'open' : ''); ?>">
                                            <i class="dropdown-down-icon ft-chevron-down"></i>
                                        </button>
                                    <?php } ?>
                                    
                                    <ul class="list-slide pl-3" style="display:block;">
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
            } else { ?>
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
                                    $accounts[] = strtolower($account_name);
                                    if($row->social_label == 'instagram') {
                                        $link = '/instagram/analytics/index/'.$row->ids;
                                    } else {
                                        $link = cn('reporting').'?rs='.$row->social_label.'&idsrs='.$row->ids;
                                    }
                                    ?>
                                    <li>
                                        <div class="checkbox">
                                            <input type="checkbox" name="account_filter[]"
                                                id="md_checkbox_<?=$row->ids?>"
                                                value="<?=str_replace(" ", "_", $row->ids)?>">
                                            <label class="" for="md_checkbox_<?=$row->ids?>">
                                                <span class="name"><i
                                                        class="fa fa-<?=$row->social_label?>"></i>
                                                    <a href="<?=$link?>"><?=$account_name?></a>
                                                </span>
                                            </label>

                                        </div>
                                    </li>
                                <?php }?>
                            <?php }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
    <div class="col-md-12 col-lg-9 pn-box-sidebar" id="ttt">
         <div class="card">
            <div class="body">
                Bonjour <?=$user->fullname;?> <br>
                <p>Page Stats</p>
                <?php if($stats) { ?>
                Stats pour : <?=$stats->name;?> <br>
                <?=lang("from")?> <?=$dateFrom?> <?=lang("to")?> <?=$dateTo?>  <br><br>
                <div class="card">
                    <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <?=lang("nombre de fans")?> : <?=$stats->fan_count ?> 
                    </div>
                    </div>
                </div>
                <?php } ?>
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
    $('.btn-slide').click(function(){
        var _this = $(this).parents('li');
        if ($(this).hasClass('open')) {
            _this.find('.etatdrpdown').val('close');
        }else{
            _this.find('.etatdrpdown').val('open');
        } 
    });
    $('.checkbox input[type="checkbox"]').change(function () {
        //$('#ttt form').submit();
    });



</script>