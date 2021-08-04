<?php
if (!empty($result)) {
    $account_data = $result->data;
    $top_hashtags = $account_data->top_hashtags;
    $top_mentions = $account_data->top_mentions;
	$userinfo = $insightProfile;
    $feeds = $account_data->feeds;
    $follower_count = $userinfo->follower_count;
    $media_count = $userinfo->media_count;
	$total_days = $result->total_days;
	setlocale(LC_ALL, 'fr_FR','fr_FR.utf8');
	?>

<div class="ig-analytics" id="box-analytics">
	<div class="container">
		<div class="userinfo">
			<div class="avatar">
				<img src="<?=$insightProfile["profile_picture_url"]?>" width="100px;height=100px">
			</div>
			<div class="infos">
				<div class="name"><?=$insightProfile["username"]?></div>
				<ul class="sumary">
					<li><span><?=number_format($insightProfile["media_count"])?></span> <?=lang("Posts")?></li>
					<li><span><?=number_format($insightProfile["followers_count"])?></span> <?=lang("followers")?></li>
					<li><span><?=number_format($insightProfile["follows_count"])?></span> <?=lang("following")?></li>
				</ul>

				<div class="fullname"><?=$insightProfile["full_name"]?></div>
				<div class="description"><?=$insightProfile["biography"]?></div>
				<div class="website"><a href="<?=$insightProfile["website"]?>" target="_blank"><?=$insightProfile["website"]?></a></div>

			</div>
		</div>
		<ul class="box-sumary">
			<li>
				<div>
					<span><?=$account_data->engagement?>%</span><?=lang("Engagement")?>
					<i class="activity-option-help webuiPopover fa fa-question-circle" data-content="<?=lang("The engagement rate is the number of active likes / comments on each post")?>" data-delay-show="300" data-title="<?=lang("Engagement")?>" data-target="webuiPopover1"></i>
				</div>
			</li>
			<li>
				<div>
					<span><?=$account_data->average_likes?></span><?=lang("Average_Likes")?>
					<i class="activity-option-help webuiPopover fa fa-question-circle" data-content="<?=lang("Average likes based on the last 10 posts")?>" data-delay-show="300" data-title="<?=lang("Average_Likes")?>" data-target="webuiPopover1"></i>
				</div>
			</li>
			<li>
				<div>
					<span><?=$account_data->average_comments?></span><?=lang("Average_Comments")?>
					<i class="activity-option-help webuiPopover fa fa-question-circle" data-content="<?=lang("Average comments based on the last 10 posts")?>" data-delay-show="300" data-title="<?=lang("Average_Comments")?>" data-target="webuiPopover1"></i>
				</div>
			</li>
			<span class="clearfix"></span>
		</ul>
		<div class="box-sc-option aoption col-12 d-flex flex-wrap">
			<form method="GET" class="w-100">
				<ul class="row">
					<li class="col-md-4 col-lg-3 box-border">
						<div class="box-title"><?=lang("from")?> : </div>
						<div class="box-content">
							<div class="input-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
								<input type="text" name="date_from" value="<?= (get("date_from") != "") ? get("date_from") : ''; ?>" class="form-control filterdate date"
									placeholder="Ex: 01/<?= date("m/Y")?>" value="01/<?= date("m/Y")?>">
							</div>
						</div>
					</li>
					<li class="col-md-4 col-lg-3 box-border">
						<div class="box-title"><?=lang("to")?> : </div>
						<div class="box-content">
							<div class="input-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
								<input type="text" name="date_to" value="<?= (get("date_to")!= "") ? get("date_to") : ''; ?>" class="form-control filterdate date-end"
									placeholder="Ex: <?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>">
							</div>
						</div>
					</li>
					<li class="col-md-4 col-lg-3 box-border">
						<div class="box-title"></div>
						<div class="box-content mt-3">
							<div class="input-group">
								<input class="btn btn-primary pull-right" type="submit" value="filter">
							</div>
						</div>
					</li>
				</ul>
			</form>
		</div>
		<div class="box-analytics">
			<div class="box-head">
				<h3 class="title"><?=lang("Profile_Growth_Discovery")?></h3>
				<div class="description"><?=lang("See insights on how your profile has grown and changed over time")?></div>
			</div>

			<div class="row">
	        	<div class="col-md-12">
					<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-followers-line-stacked-area" height="300"></canvas>
			        </div>
			    </div>
			</div>
	        <div class="title-chart"><?=lang("Followers_evolution_chart")?></div>

	        <div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-following-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("Following_evolution_chart")?></div>
			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-audience_gender_age-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("audience_gender_age_evolution_chart")?></div>


			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-audience_locale-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("audience_locale_evolution_chart")?></div>

			
			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-audience_city-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("audience_city_evolution_chart")?></div>

			
			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-audience_country-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("audience_country_evolution_chart")?></div>

			</div>
		</div>
	</div>
</div>

<?php } else {?>
	<div class="ig-analytics">
		<div class="dataTables_empty"></div>
		<div class="ig-analytics-empty-notice">
			<div class="title"><?=lang("No_data")?></div>
			<div class="description"><?=lang("We could not retrieve data from your Account, Try to re-login and try to again")?></div>
			<?php if ($account->status == 0) {?>
			<a href="#" class="btn btn-primary"><?=lang("Re-login")?></a>
			<?php }?>
		</div>
	</div>
<?php }?>

<script type="text/javascript">
	$(document).ready(function(){
	
		Instagram_analytics.lineChart(
            "ig-analytics-followers-line-stacked-area",
            <?= $table_follower_count_date ?>,
            [
                <?=$table_follower_count_data?>,
            ],
            [
                "<?=lang('Followers')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		Instagram_analytics.lineChart(
            "ig-analytics-following-line-stacked-area",
            <?= $table_impression_date ?>,
            [
                <?=$table_impression_data?>,
            ],
            [
                "<?=lang('Impression')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		Instagram_analytics.lineChart(
            "ig-analytics-audience_gender_age-line-stacked-area",
            <?= $table_audience_gender_age_date ?>,
            [
                <?=$table_audience_gender_age_data?>,
            ],
            [
                "<?=lang('audience_gender_age')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );

		Instagram_analytics.lineChart(
            "ig-analytics-audience_locale-line-stacked-area",
            <?= $table_audience_locale_date ?>,
            [
                <?=$table_audience_locale_data?>,
            ],
            [
                "<?=lang('audience_locale')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );

		
		Instagram_analytics.lineChart(
            "ig-analytics-audience_city-line-stacked-area",
            <?= $table_audience_city_date ?>,
            [
                <?=$table_audience_city_data?>,
            ],
            [
                "<?=lang('audience_city')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		
		Instagram_analytics.lineChart(
            "ig-analytics-audience_country-line-stacked-area",
            <?= $table_audience_country_date ?>,
            [
                <?=$table_audience_country_data?>,
            ],
            [
                "<?=lang('audience_country')?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		
		<?php if (!empty($result)) {?>

        // Instagram_analytics.lineChart(
        //     "ig-analytics-following-line-stacked-area",
        //     <?=$result->date_chart?>,
        //     [
        //         <?=$result->following_chart?>,
        //     ],
        //     [
        //         "<?=lang('Following')?>"
        //     ],
        //     "line",
        //     ["rgba(33,150,243,1)"]
        // );

        // Instagram_analytics.lineChart(
        //     "ig-analytics-get-followers-following-line-stacked-area",
        //     <?=$result->date_chart?>,
        //     [
        //         <?=$compare_new_followers_value_string != "-" ? $compare_new_followers_value_string : "[]"?>,
        //         <?=$compare_new_following_value_string != "-" ? $compare_new_following_value_string : "[]"?>,
        //     ],
        //     [
        //         "<?=lang('Followers')?>",
        //         "<?=lang('Following')?>"
        //     ],
        //     "line",
        //     ["rgba(255,0,94,1)", "rgba(33,150,243,1)"]
        // );

        // Instagram_analytics.lineChart(
        //     "ig-analytics-total-followers-following-line-stacked-area",
        //     <?=$result->date_chart?>,
        //     [
        //         <?=$compare_total_followers_value_string?>,
        //         <?=$compare_total_following_value_string?>
        //     ],
        //     [
        //         "<?=lang('Followers')?>",
        //         "<?=lang('Following')?>"
        //     ],
        //     "line",
        //     ["rgba(255,0,94,1)", "rgba(33,150,243,1)"]
        // );

        // Instagram_analytics.lineChart(
        //     "ig-analytics-engagement-line-stacked-area",
        //     <?=$result->date_chart?>,
        //     [
        //         <?=$result->engagement_chart?>,
        //     ],
        //     [
        //         "<?=lang("Average Engagement Rate")?>"
        //     ],
        //     "line",
        //     ["rgba(64,212,29,1)"]
        // );
        <?php }?>
		$owl=$(".owl-carousel").owlCarousel({
	  		nav: true,
	  		responsiveClass:true,
    		responsive:{
	        	0:{
	            	items:1
	        	},
		        768:{
		            items:2
		        },
		        1400:{
		            items:3
		        }
		    }
		  });
		  $("#menu-toggle").click(function (e) { 
			window.dispatchEvent(new Event('resize'));
        });
	});

	if ( typeof window.instgrm !== 'undefined' ) {
	    window.instgrm.Embeds.process();
	}
</script>

<style type="text/css">
	.ig-analytics{
		border-top: 1px solid #f5f5f5;
		padding-top: 103px;
	}

	.ig-analytics .userinfo{
		position: relative;
		margin-bottom: 30px;
		min-height: 151px;
	}

	.ig-analytics .userinfo .avatar{
		position: absolute;
		left: 0;
	}

	.ig-analytics .userinfo .avatar img{
		border-radius: 100px;
	}

	.ig-analytics .userinfo .infos{
		margin-left: 250px;
	}

	.ig-analytics .userinfo .infos .name{
		font-size: 28px;
	    line-height: 32px;
	    margin: -5px 0 -6px;
	    display: block;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	    color: #262626;
	    font-weight: 300;
	}

	.ig-analytics .userinfo .infos .sumary{
		margin: 20px 0;
	}

	.ig-analytics .userinfo .infos .sumary li{
		display: inline-block;
		padding-right: 40px;
		font-size: 16px;
	}


	.ig-analytics .userinfo .infos .sumary li span{
		font-weight: bold;
	}


	.ig-analytics .userinfo .infos .fullname{
		font-weight: bold;
		margin-bottom: 5px;
	}

	.ig-analytics .userinfo .infos .description{
		margin-bottom: 5px;
		color: #696969;
	}

	.ig-analytics .userinfo .infos .website a{
		color: #003569;
	    text-decoration: none;
	    font-weight: 600;
	    display: block;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	}

	.ig-analytics .title-chart{
		margin: 10px 15px 25px;
		text-align: center;
		font-weight: 600;
		color: #0087ff;
	}

	.ig-analytics .box-sumary{
		border-left: 1px solid #f5f5f5;
		border-top: 1px solid #f5f5f5;
		border-bottom: 1px solid #f5f5f5;
	}

	.ig-analytics .box-sumary li{
		float: left;
		width: 33.333333%;
		position: relative;
		margin: 0;
		font-size: 12px;
		text-transform: uppercase;
		color: #7b8994;
	}

	.ig-analytics .box-sumary li div{
		border-right: 1px solid #f5f5f5;
		padding: 15px;
	}

	.ig-analytics .box-sumary li span{
		display: block;
		font-size: 20px;
		font-weight: 600;
		text-transform: inherit;
		color: #000;
	}

	.ig-analytics .box-head{
		margin-top: 40px;
		margin-bottom: 30px;
	}

	.ig-analytics .table_sumary table{
		margin-top: 20px;
	}

	.ig-analytics .table_sumary table .text-success{
		color: #71d473;
	}

	.ig-analytics .table_sumary table .text-danger{
		color: #e64945;
	}

	.ig-analytics .table_sumary table thead{
		background: #333;
		color: #fff;
		font-weight: bold;
	}

	.ig-analytics .table_sumary table tfoot{
		background: #f7f7f7;
		font-weight: bold;
	}

	.ig-analytics .table_sumary table td{
		padding: 15px 8px!important;
	}

	.ig-analytics .owl-carousel .item{
		margin: 0px 15px;
	}

	.ig-analytics .owl-nav button{
		font-size: 130px!important;
	    color: #ff4c8e!important;
	    position: absolute;
	    top: calc(50% - 111px);
	}

	.ig-analytics .owl-nav button.owl-prev{
		left: 20px;
	}

	.ig-analytics .owl-nav button.owl-next{
		right: 20px;
	}
	.ig-analytics .summary-list-group{
	    font-size: 16px;
	    color: #a0a0a0;
	}

	.ig-analytics .summary-list-group a{
		color: #a0a0a0;
	}

	.ig-analytics .summary-list-group a:hover{
		text-decoration: underline;
		color: #ff4c8e;
	}

	.ig-analytics .summary-list-group span{
		color: #36a3f7;
	}

	.ig-analytics .summary-list-group .num{
		display: inline-block;
		width: 40px;
		height: 40px;
		color: #ff4c8e;
		border: 1px solid #ffefef;
		margin-bottom: 15px;
		text-align: center;
		font-size: 22px;
		margin-right: 10px;
	}

	.ig-analytics-empty-notice{
		text-align: center;
	}

	.ig-analytics-empty-notice .title{
		font-size: 20px;
		color: #2196f3;
		text-transform: uppercase;
	    font-weight: bold;
	}

	.ig-analytics-empty-notice .description{
		font-size: 16px;
		padding: 10px;
	}

	.ig-analytics-empty-notice .btn{
		border-radius: 100px;
	}

	@media (max-width: 768px){
		.ig-analytics .userinfo .infos {
		    margin-left: 165px;
		}
	}
</style>