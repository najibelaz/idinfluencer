<?php

$nm_mois;
$nm_mois1;
$nm_an;
$nm_an1;
// Récuperation des variables passées, on donne soit année; mois; année+mois
if(!isset($_GET['mois'])) {
	$num_mois = date("n"); 
} else {
	$num_mois = $_GET['mois'];
}

if(!isset($_GET['annee'])) {
	$num_an = date("Y"); 
} else {
	$num_an = $_GET['annee'];
} 	
// pour pas s'embeter a les calculer a l'affchage des fleches de navigation...
if($num_mois < 1) { 
	$num_mois = 12; 
	$num_an = $num_an - 1; 
} elseif($num_mois > 12) {	
	$num_mois = 1; 
	$num_an = $num_an + 1; 
}
if(isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $date_from = $_GET['date_from'];
    list($num_mois, $num_an) = explode('/', $date_from);
    $num_mois = (int)$num_mois;
    $num_an = (int)$num_an;
}
// nombre de jours dans le mois et numero du premier jour du mois
$int_nbj = date("t", mktime(0,0,0,$num_mois,1,$num_an));
$int_premj = date("w",mktime(0,0,0,$num_mois,1,$num_an));

// tableau des jours, tableau des mois...
$tab_jours = array("","Lu","Ma","Me","Je","Ve","Sa","Di");
$tab_mois = array("","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre");

$int_nbjAV = date("t", mktime(0,0,0,($num_mois-1<1)?12:$num_mois-1,1,$num_an)); // nb de jours du moi d'avant
$int_nbjAP = date("t", mktime(0,0,0,($num_mois+1>12)?1:$num_mois+1,1,$num_an)); // b de jours du mois d'apres

// on affiche les jours du mois et aussi les jours du mois avant/apres, on les indique par une * a l'affichage on modifie l'apparence des chiffres *
$tab_cal = array(array(),array(),array(),array(),array(),array()); // tab_cal[Semaine][Jour de la semaine]
$int_premj = ($int_premj == 0)?7:$int_premj;
$t = 1; $p = "";
for($i=0;$i<6;$i++) {
	for($j=0;$j<7;$j++) {
		if($j+1 == $int_premj && $t == 1) { $tab_cal[$i][$j] = $t; $t++; } // on stocke le premier jour du mois
		elseif($t > 1 && $t <= $int_nbj) { $tab_cal[$i][$j] = $p.$t; $t++; } // on incremente a chaque fois...
		elseif($t > $int_nbj) { $p="*"; $tab_cal[$i][$j] = $p."1"; $t = 2; } // on a mis tout les numeros de ce mois, on commence a mettre ceux du suivant
		elseif($t == 1) { $tab_cal[$i][$j] = "*".($int_nbjAV-($int_premj-($j+1))+1); } // on a pas encore mis les num du mois, on met ceux de celui d'avant
	}
}
?>


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
<div class="header d-flex justify-content-end mb-3">
    <a href="<?=cn('post')?>" class="btn btn-raised btn-primary waves-effect">
        <i class="fa fa-plus"></i> Ajouter un nouveau
    </a>
</div>
<div class="row">
    <a class="pn-toggle-open"><i class="fa fa-filter" aria-hidden="true"></i></a>
    <?php if($user_role->role!="customer"){?>
    <div class="col-md-12 col-lg-3 pn-box-sidebar" id="ttt">
            <div class="sc-options box-sc-option sn card<?php echo $user_role->role ;?>">
            <?php if(!is_customer()){
                if ($grps) { ?>
                    <div class="box-sc-option sn">
                        <div class="header pb-0">
                            <h2 class="ml-3 title ntop"><?=lang("Community manager")?></h2>
                        </div>
                        <div class="body">
                            <ul class="mb-3">
                                <?php foreach ($grps as $key => $row) { ?>
                                <li class="list-grps">
                                    <div class="checkbox">
                                        <input type="checkbox" name="grps_filter[]" id="grp_checkbox_<?=$row["fullname"]?>"
                                            <?php if(in_array($row["id_manager"], $grps_filter) || empty($sc_type)) {?> checked=""
                                            <?php } ?> value="<?=str_replace(" ", "_", $row["id_manager"])?>">
                                        <label class="" for="grp_checkbox_<?=$row["fullname"]?>">
                                            <span class="name">
                                                <?=$row["fullname"]?>
                                            </span>
                                        </label>
                                    </div>
                                    <?php $facebook = $row["facebook"];
                                        $instagram = $row["instagram"];
                                        $twitter = $row["twitter"];
                                    if (!empty($facebook) || !empty($instagram) ||!empty($twitter) ) { ?>
                                        <button
                                            class="btn-slide <?php echo ((get('etatdrpdown_'.$row["id_manager"]) == 'open')? 'open' : ''); ?>">
                                            <i class="dropdown-down-icon ft-chevron-down"></i>
                                        </button>
                                        <?php } ?>
                                    <input class="form-control etatdrpdown"
                                        value="<?php echo ((get('etatdrpdown_'.$row["id_manager"]) == 'open')?'open' : 'close'); ?>"
                                        type="hidden" name="etatdrpdown_<?=$row["id_manager"]?>">
                                    <ul class="list-slide pl-3" <?php echo ((get('etatdrpdown_'.$row["id_manager"]) == 'open')?'style="display:block;"' : ''); ?>>
                                        <?php if (!empty($twitter)) { 
                                            foreach ($twitter as $key => $row1) {
                                                if ($row1!=null) { 
                                                    $account_name = ""; 
                                                    if (isset($row1[0]->fullname)) {
                                                        $account_name = $row1[0]->fullname; 
                                                    }else{
                                                        $account_name = $row1[0]->username; 
                                                    }
                                                    $accounts[] = strtolower($account_name);?>
                                                    <li>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="account_filter[]"
                                                                id="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>"
                                                                <?php if(in_array($row["ids"].'_'.$row1[0]->ids, $account_filter) || empty($sc_type)) {?>
                                                                checked="" <?php } ?> value="<?= $row["ids"].'_'.str_replace(" ", "_", $row1[0]->ids)?>">
                                                            <label class="" for="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>">
                                                                <span class="name" style="color: <?=$row1[0]->color?>"><i
                                                                        class="fa fa-<?=$row1[0]->social_label?>"></i>
                                                                    <?=$account_name?></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (!empty($instagram)) { 
                                            foreach ($instagram as $key => $row1) {
                                                if ($row1!=null) { 
                                                    $account_name = $row1[0]->username; 
                                                    $accounts[] = strtolower($account_name);?>
                                                    <li>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="account_filter[]"
                                                                id="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>"
                                                                <?php if(in_array($row["ids"].'_'.$row1[0]->ids, $account_filter) || empty($sc_type)) {?>
                                                                checked="" <?php } ?> value="<?= $row["ids"].'_'.str_replace(" ", "_", $row1[0]->ids)?>">
                                                            <label class="" for="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>">
                                                                <span class="name" style="color: <?=$row1[0]->color?>"><i
                                                                        class="fa fa-<?=$row1[0]->social_label?>"></i>
                                                                    <?=$account_name?></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (!empty($facebook)) { 
                                            foreach ($facebook as $key => $row1) {
                                                if ($row1!=null) { 
                                                    // $account_name = ""; 
                                                    // if (isset($row1[0]->fullname)) {
                                                    //     $account_name = $row1[0]->fullname; 
                                                    // }else{
                                                        $account_name = $row1[0]->fullname; 
                                                    // }
                                                    $accounts[] = strtolower($account_name);?>
                                                    <li>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="account_filter[]"
                                                                id="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>"
                                                                <?php if(in_array($row["ids"].'_'.$row1[0]->ids, $account_filter) || empty($sc_type)) {?>
                                                                checked="" <?php } ?> value="<?= $row["ids"].'_'.str_replace(" ", "_", $row1[0]->ids)?>">
                                                            <label class="" for="md_checkbox_<?=$row["ids"].'_'.$row1[0]->ids?>">
                                                                <span class="name" style="color: <?=$row1[0]->color?>"><i
                                                                        class="fa fa-<?=$row1[0]->social_label?>"></i>
                                                                    <?=$account_name?></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php }?>
                            </ul>
                        </div>

                    </div>
                <?php }
            }else{?>
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
                                        if (isset($row["fullname"])) {
                                            $account_name = $row["fullname"]; 
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
            <?php } ?>
    <div class="col-md-12 <?php if($user_role->role!="customer"){?>col-lg-9<?php } ?>">
      
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
                <div class="ecalendar">
                    <div class="ecalendar-top">
                        <div class="ecalendar-header">
                            <div class="btns-navigation">
                                <!-- <form class="nodaysform" method="GET">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
                                        <input type="text" name="date_from" class="date nodays form-control"
                                            placeholder="Ex: <?php date("m/Y")?>" data-dtp="dtp_vzTrt"
                                            value="<?php if(isset($_GET['date_from'])){ echo $_GET['date_from']; } ?>">
                                    </div>
                                </form> -->
                                <?php 
                                    if ($num_mois ==12) {
                                        $nm_mois = 1;
                                        $nm_an = $num_an+1;
                                    }else{
                                        $nm_mois = $num_mois+1;
                                        $nm_an = $num_an;
                                    }
                                    if($num_mois ==1){
                                        $nm_mois1 = 12;
                                        $nm_an1 = $num_an-1;
                                    }else{
                                        $nm_mois1 = $num_mois-1;
                                        $nm_an1 = $num_an;
                                    }
                                ?>
                                <button data-dateswitch="<?= $nm_mois1.'/'.$nm_an1 ?>"
                                    class="datasearchswitch datesearchplus"><i
                                        class="material-icons">arrow_left</i></button>

                                <div class="title"><?=$tab_mois[$num_mois]?> - <?=$num_an?></div>

                                <button data-dateswitch="<?= $nm_mois.'/'.$nm_an ?>"
                                    class="datasearchswitch datesearchmoins"><i
                                        class="material-icons">arrow_right</i></button>
                            </div>
                        </div>
                        <ul class="days days-name">
                            <?php for($i = 1; $i <= 7; $i++){ ?>
                            <li class="d-name"><span><?=$tab_jours[$i]?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="ecalendar-body">
                        <ul class="days days-contents">
                            
                            <?php for($i=0;$i<6;$i++) { ?>
                            <?php for($j=0;$j<7;$j++) { 
                            
                            ?>
                            <li class="d-content <?=(strpos($tab_cal[$i][$j], "*") !== false) ? "disabled" : "";?>">
                            <!-- <?php foreach($posts as $post){?>
                                <li class="post" data-toggle="modal" data-target="#mainModal" style="border-left: 4px solid blue;">
                                    <span class="type">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                    </span>
                                    <img src="<?= $post->attachments_images ?>" alt="" class="logo">

                                </li> 
                            <?php } ?> -->
                                <div class="d-info">
                                    <?php 
                                    if(strpos($tab_cal[$i][$j],"*")!==false) {
                                        $nbr = (int)str_replace("*","",$tab_cal[$i][$j]);
                                        if($nbr >= 20) {
                                            $m = $num_mois - 1;
                                        } elseif ($nbr <= 10) {
                                            $m = $num_mois - 1;
                                        }
                                    } else {
                                        $key = $tab_cal[$i][$j];
                                        if($key < 10) {
                                            $key = "0".(String)$key;
                                        } else {
                                            $key = (String)$key;
                                        }
                                        // var_dump($schedules[$key]);die;
                                        $items_schedule = !is_null($schedules[$key]) ? $schedules[$key] : array();
                                        if(count($items_schedule) >= 3 ) {
                                            $items_schedule = array_slice($items_schedule, 0, 3);
                                        }
                                        
                                    ?>
                                    <?php 
                                    $html_posts="";
                                    $nb_posts=0;
                                    foreach($posts as $post){
                                        if($post["created_time"]!=null){
                                            $numero_month="";
                                            if($num_mois < 10) {
                                                $numero_month = "0".(String)$num_mois;
                                            } else {
                                                $numero_month = (String)$num_mois;
                                            }
                                            $date_post=explode("T",$post["created_time"])[0];
                                            $heure_post=explode("T",$post["created_time"])[1];
                                            list($year_post,$month_post,$day_post) = explode('-', $date_post);
                                            list($houre_post,$min_post,$sec_post) = explode(':', $heure_post);
                                            if($key==$day_post && $numero_month==$month_post && $num_an==$year_post){ 
                                                $data_post_preview=array();
                                                $data_post_preview["attachments_images"]=$post["media"];
                                                $data_post_preview["video"]=$post["video"];
                                                $data_post_preview["message"]=$post["message"];
                                                $data_post_preview["avatar"]=$post["avatar"];
                                                $data_post_preview["fullname"]=$post["fullname"];
                                                $data_post_preview["created_time"]=$year_post.'-'.$month_post.'-'.$day_post." ".$houre_post." ".$min_post;
                                                $data_post_preview["idp"]="";
                                                $html_posts.='
                                                <li class="post" data-toggle="modal" data-target="#mainModal" style="border-left: 4px solid yellow;">
                                                    <span class="type">
                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                    </span>
                                                    <img src="'. $post["avatar"] .'" alt="" class="logo">
                                                    <span class="title">'.$houre_post.':'.$min_post.' | '.$post["message"].'</span>
                                                    <div class="d-none">
                                                        <div class="post-preview">';
                                                        $html_posts.= $this->load->view("preview/facebook",$data_post_preview,TRUE);
                                                        $html_posts.='</div>
                                                    </div> 
                                                </li> ';
                                                $nb_posts++;
                                            } 
                                        }
                                    }?>
                                    <?php if( count($items_schedule)+$nb_posts > 0) { ?>
                                    <button class="btn btn-primary show-all" data-toggle="modal"
                                        data-target="#mainModal"><span><?= lang('voir_plus') ?>
                                            (<?=count($items_schedule)+$nb_posts?>)</span>
                                    </button>
                                    <?php } ?>

                                    
                                    <ul class="posts">
                                    <?php if (count($items_schedule) > 0) {  
                                            foreach ($items_schedule as $key2 => $item) {
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
                                                $text = $data->caption;
                                                $disabed = ($day < $day_now && $mount <= $mount_now) ? 'disabled' : '';
                                                $color ="#00aeef";
                                                if($item->status == ST_PUBLISHED){
                                                    $color ="green";
                                                }
                                                if($item->status == ST_FAILED){
                                                    $color ="red";
                                                }
                                                if($item->status == ST_WAITTING){
                                                    $color ="blue";
                                                }
                                                if($item->status != ST_DELETED){  ?>
                                                    <li class="post" data-toggle="modal" data-target="#mainModal"
                                                        style="border-left: 4px solid <?= $color ?>;">
                                                        <span class="type">
                                                            <i class="fa fa-<?= $item->social_label ?>" aria-hidden="true"></i>
                                                        </span>
                                                        <img src="<?= $avatar ?>" alt="" class="logo">
                                                        <p><?php var_dump($data_preview);?></p>
                                                        <span class="title">
                                                            <?=  date('H:i', $timestamp).' | '.$text ?>
                                                        </span>
                                                        <div class="d-none">
                                                            <div class="post-preview">
                                                                <?= $this->load->view('preview/'.$item->social_label, $data_preview); ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                            <?php }
                                            }
                                        } ?>
                                    <?= $html_posts ?> 

                                    </ul>
                                    <?php } ?>
                                    <span class="date">
                                        <?php if($num_mois == date("n") && $tab_cal[$i][$j] == date("d")){
                                            echo '<span style="color: #FFFFFF; background-color: #08aeef;width:25px;height:25px;border-radius:50%;text-align:center">'.date("d").'</span>';
                                        }else{
                                            echo "<td".(($num_mois == date("n") && $num_an == date("Y") && $tab_cal[$i][$j] == date("j"))?' style="color: #FFFFFF; background-color: red;"':null).">".((strpos($tab_cal[$i][$j],"*")!==false)? str_replace("*","",$tab_cal[$i][$j]) :$tab_cal[$i][$j])."</td>";
                                        } ?>
                                    </span>
                                </div>
                            </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
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
<div class="header d-flex justify-content-end mb-3">
    <p class="pr-3" style="color :green"><?=lang("publier")?> </p>
    <p class="pr-3" style="color :red"><?=lang("echoue")?> </p>
    <p class="pr-3" style="color :blue"><?=lang("en_attendant")?> </p>
    <p class="pr-3" style="color :yellow"><?=lang("Publiée à travers Facebook")?> </p>
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