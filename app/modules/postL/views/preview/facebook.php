<?php 
    $lastpost_fb = $this->model->get("data, fullname, avatar,time_post,facebook_posts.type as type", 'facebook_posts,facebook_accounts', "facebook_posts.account=facebook_accounts.id and facebook_posts.id = '".$idp."'");
    $time = strtotime($lastpost_fb->time_post);
    $newformat = date('d-m-Y h:i',$time);
?>
<div class="card">
    <div class="preview-fb preview-fb-media">
        <div class="preview-header">
            <div class="fb-logo"><i class="fa fa-facebook"></i></div>
        </div>
        <div class="preview-content">
            <div class="user-info">
                <img class="img-circle" src="<?= $lastpost_fb->avatar?>">
                <div class="text">
                    <div class="name"> <?= $lastpost_fb->fullname?></div>
                    <span> <?= $newformat; ?> <i class="fa fa-globe" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="caption-info">
                <?=substr($caption,0,100); ?>...
            </div>
            <?php
                if($lastpost_fb->type == "media"){
                    ?>
            <div class="preview-image">
                <?php
                        if($media){
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
                    }
                    ?>
            </div>
            <?php
                }
            ?>
            <div class="preview-comment">
                <div class="item">
                    <i class="fb-icon like" aria-hidden="true"></i> <?=lang("like")?> </div>
                <div class="item">
                    <i class="fb-icon comment" aria-hidden="true"></i> <?=lang("comment")?> </div>
                <div class="item">
                    <i class="fb-icon share" aria-hidden="true"></i> <?=lang("share")?> </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>