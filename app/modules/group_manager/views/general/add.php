<?php
$name = "";
$ids = "";
$data = array();

if(!empty($group)){
    $name = $group->name;
    $ids = $group->ids;
    $data = json_decode($group->data);
}

?>
<div class="pn-box-content">
    <div class="">
        <div class="card">
            <div class="header">
                <h2><label><?=lang("Group name")?></label></h2>
                <small><?=lang("Drag and drop to right to select and to left to unselect")?></small>
            </div>
            <div class="body">
                <div class="form-group">
                    <input type="text" class="form-control group_name" value="<?=$name?>">
                </div>
            </div>
        </div>

        <div class="card bg-dark mb-0">
            <div class="body">
                <ul class="nav nav-tabs padding-0">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#t-manager"><?=lang('cm')?></a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#t-user"><?=lang('clients')?></a></li>
                </ul>
            </div>
        </div>
        
        <div class="tab-content m-t-20">
        
            <div id="t-manager" class="tab-pane active">
                <div class="row">
                       <div class="col-md-6 col-6 m0">
                    <div class="pn-group-panel card">
                        <div class="header">
                            <h2 class=""><?=lang('cm')?></h2>
                        </div>
                        <div class="body pt-0 pb-0">
                            <div class="box-search3">
                                <div class="input-group">
                                    <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                        aria-describedby="basic-addon2">
                                    <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <ul class="body pn-group-list connected-sortable draggable-left pn-group-scroll">
                                <?php if(!empty($users['manager'])){
                            foreach ($users['manager'] as $row) {
                                if(!in_array($row->ids, $data)){
                            ?>
                                <li class="pn-group-item">
                                    <div class="pic">
                                        <?php if(empty($row->avatar)){ ?>
                                            <img src="<?= cn('assets/img/default-avatar.png')?>">
                                        <?php }else{ ?>
                                            <img src="<?=$row->avatar?>">
                                        <?php } ?>
                                    </div>
                                    <div class="detail">
                                        <div class="title"><?=$row->fullname?></div>
                                        <div class="desc">
                                        <?= lang('Groupe affecté') ?> :
                                                 <?php 
                                                $user_groups = $this->model->fetch("*", "general_groups,manager_group", "ids=id_group and id_user='{$row->ids}'");
                                                 
                                                 if(!empty($user_groups)){
                                                    foreach ($user_groups as $key => $grp) {?>
                                                       <span><?php if($key>0){ echo '-';} ?> <?= $grp->name ?></span>
                                                <?php }}else{
                                                    echo lang('Aucun');
                                                    }?>                                                  
                                        </div>
                                        <input type="hidden" name="id[]" value="<?=$row->ids?>">
                                    </div>
                                </li>
                                <?php }}}?>
                        </ul>
                    </div>
                </div>

                <form action="<?=PATH?>group_manager/ajax_save" method="POST"
                    class="actionFormadd saveFormGroups col-md-6 col-6 m0" style="height: 100%">
                    <input type="hidden" name="name" value="<?=$name?>">
                    <input type="hidden" name="ids" value="<?=$ids?>">
                    <input type="hidden" name="role" value="manager">
                    <div class="pn-group-panel card">
                        <div class="header">
                            <h2 class=""><?=lang("Selected accounts")?></h2>
                        </div>
                        <div class="body pt-0 pb-0">
                            <div class="box-search4">
                                <div class="input-group">
                                    <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                        aria-describedby="basic-addon2">
                                    <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <ul class="body pn-group-list connected-sortable draggable-right pn-group-scroll">
                        </ul>
                    </div>
                </form>
                </div>
             
            </div>
            <div id="t-user" class="tab-pane">
                <div class="row">
                       <div class="col-md-6 col-6 m0">
                    <div class="pn-group-panel card">
                        <div class="header">
                            <h2 class=""><?=lang('clients')?></h2>
                        </div>
                        <div class="body pt-0 pb-0">
                            <div class="box-search1">
                                <div class="input-group">
                                    <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                        aria-describedby="basic-addon2">
                                    <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <ul class="body pn-group-list connected-sortable draggable-left pn-group-scroll">
                                <?php if(!empty($users['client'])){
                            foreach ($users['client'] as $row) {
                                if(!in_array($row->ids, $data)){
                            ?>
                                <li class="pn-group-item">
                                    <div class="pic">
                                        <?php if(empty($row->avatar)){ ?>
                                            <img src="<?= cn('assets/img/default-avatar.png')?>">
                                        <?php }else{ ?>
                                            <img src="<?=$row->avatar?>">
                                        <?php } ?>
                                    </div>
                                    <div class="detail">
                                        <div class="title"><?=$row->rs?></div>
                                        <div class="desc">
                                        <?= lang('Groupe affecté') ?> :
                                                 <?php 
                                                $user_groups = $this->model->fetch("*", "general_groups,user_group", "ids=id_group and id_user='{$row->ids}'");
                                                 
                                                 if(!empty($user_groups)){
                                                    foreach ($user_groups as $key => $grp) {?>
                                                       <span><?php if($key>0){ echo '-';} ?> <?= $grp->name ?></span>
                                                <?php }}else{
                                                    echo lang('Aucun');
                                                    }?>                                                  
                                        </div>
                                        <input type="hidden" name="id[]" value="<?=$row->ids?>">
                                    </div>
                                </li>
                                <?php }}}?>
                        </ul>
                    </div>
                </div>

                <form action="<?=PATH?>group_manager/ajax_save" data-redirect="<?=PATH?>group_manager" method="POST"
                    class="actionForm saveFormGroups col-md-6 col-6 m0" style="height: 100%">
                    <input type="hidden" name="name" value="<?=$name?>">
                    <input type="hidden" name="role" value="user">
                    <input type="hidden" name="ids" value="<?=$ids?>">
                    <div class="pn-group-panel card">
                        <div class="header">
                            <h2 class=""><?=lang("Selected accounts")?></h2>
                        </div>
                        <div class="body pt-0 pb-0">
                            <div class="box-search2">
                                <div class="input-group">
                                    <input type="text" class="form-control pn-search" placeholder="<?=lang("search")?>"
                                        aria-describedby="basic-addon2">
                                    <span class="input-group-addon" id="basic-addon2"><i class="ft-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <ul class="body pn-group-list connected-sortable draggable-right pn-group-scroll">

                        </ul>
                    </div>
                </form>
                </div>
             
            </div>
        </div>

    </div>
</div>

<?php if(segment(3) != ""){?>
    <div class="btns-box">
        <a href="<?=cn("group_manager")?>" class="btn btn-danger mr-2"> <?=lang('cancel')?></a>
        <button type="button" class="btn btn-primary saveGroups"> <?=lang('save')?></button>
    </div>
<?php }?>

<script type="text/javascript" src="<?=BASE."assets/plugins/jquery-ui/jquery-ui.min.js"?>"></script>
<script type="text/javascript" src="<?=BASE."assets/plugins/jquery-ui/jquery.ui.touch-punch.min.js"?>"></script>

<script type="text/javascript">
    $(function () {
        $(".draggable-left, .draggable-right").sortable({
            connectWith: ".connected-sortable",
            stack: ".connected-sortable ul"
        }).disableSelection();

        _he = $(window).height();
        $(".pn-groups").height(_he - 121);
        if ($(".pn-group-scroll").length > 0) {
            $('.pn-group-scroll').niceScroll({ cursorcolor: "#ddd", cursorwidth: "10px" });
        }

        $(".group_name").keyup(function () {
            console.log($(this).val());
            $("input[name='name']").val($(this).val());
        });

        $(window).resize(function () {
            _he = $(window).height();
            $(".pn-groups").height(_he - 121);
        });

        $(document).on("click", ".saveGroups", function () {
            $(".actionFormadd").submit();
        });
    });
</script>