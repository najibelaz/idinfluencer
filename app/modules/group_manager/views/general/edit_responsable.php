<?php
$name = "";
$ids = "";
$data = array();

if(!empty($group)){
    $ids = $group->id;
    $data = $group_in;
}
// var_dump($data);die;
?>
<div class="pn-box-content">
    <div class="">
        <div class="card">
            <div class="header">
                <small><?=lang("Drag and drop to right to select and to left to unselect")?></small>
            </div>
        </div>
        <div class="tab-content m-t-20">

            <div id="t-responsable" class="tab-pane active">
                <div class="row" style="height: 100%">
                    <div class="col-md-6 col-6 m0" style="height: 100%">
                        <div class="pn-group-panel card">
                            <div class="header">
                                <h2 class=""><?=lang("all_groupes_manager")?></h2>
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
                                <?php if(!empty($managers)){
                                    foreach ($managers as $row) {
                                        if(!in_array_r($row->id, $data)){
                                    ?>
                                        <li class="pn-group-item">
                                            <div class="pic">
                                               <div class="icon bg-success white"><i class="ft-target"></i></div>
                                            </div>
                                            <div class="detail">
                                                <div class="title"><?=$row->fullname?></div>
                                                <div class="desc">
                                                <!-- <?= lang('Responsable affecté') ?> :
                                                 <?php 
                                                $user_groups = $this->model->fetch("*", USERS.",responsable_group", "ids=id_user and id_group='{$row->ids}'");
                                                 
                                                 if(!empty($user_groups)){
                                                    foreach ($user_groups as $key => $grp) {?>
                                                       <span><?php if($key>0){ echo '-';} ?> <?= $grp->fullname ?></span>
                                                <?php }}else{
                                                    echo lang('Aucun');
                                                    }?>       -->
                                                </div>
                                                <input type="hidden" name="id[]" value="<?=$row->id?>">
                                            </div>
                                        </li>
                                <?php }}}?>
                            </ul>
                        </div>
                    </div>
                    <form action="<?=PATH?>group_manager/ajax_save_resp" data-redirect="<?=PATH?>group_manager/responsable" method="POST"
                        class="actionForm saveFormGroups col-md-6 col-6 m0" style="height: 100%">
                        <input type="hidden" name="name" value="<?=$name?>">
                        <input type="hidden" name="ids" value="<?=$ids?>">
                        <input type="hidden" name="role" value="responsable">
                        <div class="pn-group-panel card">
                            <div class="header">
                                <h2 class=""><?=lang("selected_groupes")?></h2>
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
                                <?php if(!empty($managers)){
                                    foreach ($managers as $row) {
                                        if(in_array_r($row->id, $data)){
                                    ?>
                                        <li class="pn-group-item">
                                            <div class="pic">
                                               <div class="icon bg-success white"><i class="ft-target"></i></div>
                                            </div>
                                            <div class="detail">
                                                <div class="title"><?=$row->fullname?></div>
                                                <div class="desc">
                                                <!-- <?= lang('Responsable affecté') ?> :
                                                 <?php 
                                                $user_groups = $this->model->fetch("*", USERS.",responsable_group", "ids=id_user and id_group='{$row->ids}'");
                                                 
                                                 if(!empty($user_groups)){
                                                    foreach ($user_groups as $key => $grp) {?>
                                                        <span><?php if($key>0){ echo '-';} ?> <?= $grp->fullname ?></span>
                                                <?php }}else{
                                                    echo lang('Aucun');
                                                    }?>                                                  -->
                                                </div>
                                                <input type="hidden" name="id[]" value="<?=$row->id?>">
                                            </div>
                                        </li>
                                <?php }}}?>
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
            $(".saveFormGroups").submit();
        });
    });
</script>