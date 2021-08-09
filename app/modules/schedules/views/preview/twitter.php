<?php  
    $lastpost_tw = $this->model->get("data, username, avatar,time_post,twitter_posts.type as type", 'twitter_posts,twitter_accounts', "twitter_posts.account=twitter_accounts.id and twitter_posts.id = '".$idp."'");
    $time = strtotime($lastpost_tw->time_post);
    $newformat = date('d-m-Y h:i',$time);
?>

<div class="card">
    <div class="preview-twitter preview-twitter-photo">
        <div class="preview-header">
            <div class="twitter-logo"><i class="fa fa-twitter"></i></div>
        </div>
        <div class="preview-content">
            <div class="user-info">
                <img class="img-circle" src="<?= $lastpost_tw->avatar?>">
                <div class="text">
                    <div class="name"><?= $lastpost_tw->username?></div>
                    <span>@<?= $lastpost_tw->username?></span>
                </div>
            </div>
            <div class="caption-info">
                <?= $caption ?>
            </div>
            <?php
                if($media){
            ?>
            <div class="preview-image">

                <?php
                    if($lastpost_tw->type == "video"){
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
                <div class="info-active"><?= $newformat; ?></div>
                <div class="clearfix"></div>
            </div>

            <div class="preview-comment">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <i class="fa fa-retweet" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a  class="btn btn-warning" href="<?=cn('post')?>?pid=<?=$idp; ?>&social=twitter">
        <i class="fa fa-edit" aria-hidden="true"></i> Modifier
    </a>
</div>