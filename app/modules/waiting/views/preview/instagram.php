<?php 
    $lastpost_ig = $this->model->get("data, username, avatar,time_post,instagram_posts.type as type", 'instagram_posts,instagram_accounts', "instagram_posts.account=instagram_accounts.id and instagram_posts.id = '".$idp."'");
    $time = strtotime($lastpost_ig->time_post);
    $newformat = date('d-m-Y h:i',$time);    

?>

<div class="card">

    <div class="preview-instagram preview-instagram-photo">
        <div class="preview-header">
            <div class="pull-left"><i class="ft-camera"></i></div>
            <div class="instagram-logo"><img src="/public/instagram/assets/img/instagram-logo.png"></div>
            <div class="pull-right"><i class="icon-paper-plane"></i></div>
        </div>
        <div class="preview-content">
            <div class="user-info">
                <img class="img-circle" src="<?= $lastpost_ig->avatar?>">
                <span><?= $lastpost_ig->username?></span>
            </div>
            <?php
                if($media){
            ?>
            <div class="preview-image">
                <?php
                
                        $explode = explode(".",$media[0]);
                        $ext = $explode[count($explode)-1];
                        if($ext == "mp4"){
                ?>
                <a href="<?= $media[0]; ?>" data-type="ajax"
                    data-src="/file_manager/view_video?video=<?= $media[0]; ?>" data-fancybox="">
                    <div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>
                    <video src="<?= $media[0]; ?>" playsinline="" muted="" loop=""></video>
                </a>
                <?php
                    }else{
                    ?>
                <a href="<?= $media[0]; ?>" data-fancybox="group">
                    <img class="post-img" src="<?= $media[0]; ?>" alt="">
                </a>
                <?php
                    }
                ?>
            </div>
                <?php
                }
                ?>
            <div class="post-info">
                <div class="pull-right"><?= $newformat; ?></div>
                <div class="clearfix"></div>
            </div>
            <div class="caption-info pt0">
                <?=substr($caption,0,100); ?>...
            </div>
            <div class="preview-comment">
                Ajouter un commentaire…
                <div class="icon-3dot"></div>
            </div>
        </div>
    </div>

</div>
