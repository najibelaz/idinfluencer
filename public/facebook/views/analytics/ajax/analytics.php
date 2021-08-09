<!-- <div class="card bg-dark mb-0">
    <div class="body">
        <ul class="nav nav-tabs padding-0">
            <li class="nav-item" >
                <a class="nav-link actionItem" href="<?=cn("facebook/stats/".$ids."/mail")?>" 
					data-content="box-ajax-analytics" data-result="html" onclick="openContent();"> 
                    <?= lang("Mail_rapport");?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <?= lang("Rapports_detaille");?>
                </a>
            </li>
        </ul>
    </div>
</div> -->
<?php
	// $userinfo = $result->userinfo;
	$account_data = $result->data;
	$userinfo = $account_data->userinfo;
	// echo "<pre>";var_dump($userinfo);die();
    $follower_count = $userinfo->fan_count;
    $media_count = $userinfo->media_count;
	$total_days = $result->total_days;
	
	$count_page_impressions=$count_page_impressions=="" ? 0 : $count_page_impressions;
	$count_page_engaged_users=$count_page_engaged_users=="" ? 0 : $count_page_engaged_users;
	$count_page_fans=$count_page_fans=="" ? 0 : $count_page_fans;
	setlocale(LC_ALL, 'fr_FR','fr_FR.utf8');
	// $account_data = $result['account_data'];

?>


<div class="ig-analytics" id="box-analytics">
	<div class="container">
		<div class="userinfo">
			<div class="avatar">
				<img src="<?=$userinfo->picture->data->url?>">
			</div>
			<div class="infos">
				<div class="name"><?=$userinfo->name?></div>
				<ul class="sumary">
					<li><span><?=number_format($userinfo->fan_count)?></span> <?= lang("followers");?></li>
				</ul>

				<div class="fullname"><?=$userinfo->name?></div>
				<?php if(isset($userinfo->description)): ?>
					<div class="description"><?=$userinfo->description?></div>
				<?php endif; ?>
				<?php if(isset($userinfo->website)): ?>
					<div class="website"><a href="<?=$userinfo->website?>" target="_blank"><?= lang("website");?></a></div>
				<?php endif; ?>

			</div>
		</div>
		<div class="box-sc-option aoption col-12 d-flex flex-wrap">
			<form method="GET" class="w-100">
				<ul class="row">
					<li class="col-md-4 col-lg-3 box-border">
						<div class="box-title"><?=lang("from")?> : </div>
						<div class="box-content">
							<div class="input-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
								<input type="text" name="date_from" placeholder="Ex: 01/<?= date("m/Y")?>" value="01/<?= date("m/Y")?>" class="form-control filterdate date">
							</div>
						</div>
					</li>
					<li class="col-md-4 col-lg-3 box-border">
						<div class="box-title"><?=lang("to")?> : </div>
						<div class="box-content">
							<div class="input-group">
								<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
								<input type="text" name="date_to" placeholder="Ex: <?= date("d/m/Y")?>" value="<?= date("d/m/Y")?>" class="form-control filterdate date-end">
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
		<div class="row">
			<div class="col-lg-4 col-md-6">
				<div class="card text-center">
					<div class="body">
						<div class="content col-12">
							<div class="text">Total des Engagement :<?php echo $count_page_engaged_users; ?> </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card text-center">
					<div class="body">
						<div class="content col-12">
							<div class="text">Total des Impressions :<?php echo $count_page_impressions; ?> </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card text-center">
					<div class="body">
						<div class="content col-12">
							<div class="text">Total des abonnés :<?php echo $count_page_fans; ?> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-analytics">
			<div class="box-head">
				<h3 class="title"><?=lang("Profile_Growth_Discovery")?></h3>
				<div class="description"><?=lang("See insights on how your profile has grown and changed over time.")?></div>
			</div>
			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-fans-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("audience_evolution_chart")?></div>

			<div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-engagement-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("engagement_evolution_chart")?></div>

	        <div class="row">
	        	<div class="col-md-12">
	        		<div class="card-body box-analytic mb0">
			            <canvas id="ig-analytics-followers-line-stacked-area" height="300"></canvas>
			        </div>
	        	</div>
	        </div>
	        <div class="title-chart"><?=lang("followers_evolution_chart")?></div>

	        <div class="box-head">
				<h3 class="title"><?=lang("Account_Stats_Summary")?></h3>
				<div class="description"><?=lang("Showing_last_15_entries.")?></div>
			</div>
			<div class="table_sumary table-responsive">
				<?php
    		$total_followers_summany = 0;
    		$total_posts_summany = 0;
    		$compare_new_followers_value_string = "";
    		$compare_total_followers_value_string = "";
    			?>

				<table class="table">
					<thead>
						<tr>
							<td><?=lang("Date")?></td>
							<td colspan="2"><?=lang("Fan")?></td>
							<td colspan="2"><?=lang("Posts")?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($result->list_summary as $key => $row) {

							$followers_status = "text-default";
							$followers_sumary = "-";
							$total_followers_summany += (int) $row->followers_sumary;
							if ($row->followers_sumary > 0) {
								$followers_sumary = "+" . $row->followers_sumary;
								$followers_status = "text-success";
							} else if ($row->followers_sumary < 0) {
								$followers_sumary = $row->followers_sumary;
								$followers_status = "text-danger";
							}

							$posts_status = "text-default";
							$posts_sumary = "-";
							$total_posts_summany += (int) $row->posts_sumary;
							if ($row->posts_sumary > 0) {
								$posts_sumary = "+" . $row->posts_sumary;
								$posts_status = "text-success";
							} else if ($row->posts_sumary < 0) {
								$posts_sumary = $row->posts_sumary;
								$posts_status = "text-danger";
							}

							$compare_new_followers_value_string .= (int) $followers_sumary . ",";
							$compare_total_followers_value_string .= (int) $row->followers . ",";
							?>
						<tr>
							<td><?=strftime("%a, %d %h, %Y", strtotime($row->date))?></td>
							<td><?=$row->followers?></td>
							<td><span class="<?=$followers_status?>"><?=$followers_sumary?></span></td>
							<td><?=$row->posts?></td>
							<td><span class="<?=$posts_sumary?>"><?=$posts_sumary?></span></td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<?php

						$total_followers_status = "text-default";
						if ($total_followers_summany > 0) {
							$total_followers_summany = "+" . $total_followers_summany;
							$total_followers_status = "text-success";
						} else if ($total_followers_summany < 0) {
							$total_followers_status = "text-danger";
						}

						$total_posts_status = "text-default";
						if ($total_posts_summany > 0) {
							$total_posts_summany = "+" . $total_posts_summany;
							$total_posts_status = "text-success";
						} else if ($total_posts_summany < 0) {
							$total_posts_status = "text-danger";
						}
						?>

						<tr>
							<td><i class="ft-crosshair"></i> <?=lang("Total_Summary")?></td>
							<td colspan="2"><span class="<?=$total_followers_status?>"><?=($total_followers_summany != 0) ? $total_followers_summany : "-"?></span></td>
							<td colspan="2"><span class="<?=$total_posts_status?>"><?=($total_posts_summany != 0) ? $total_posts_summany : "-"?></span></td>
						</tr>
					</tfoot>
				</table>
		</div>	
			<div class="box-head">
				<h3 class="title"><?=lang("Future_Projections")?></h3>
				<div class="description"><?=lang("Here you can see the approximated future projections based on your previous days averages")?></div>
			</div>
			<?php
$average_followers = $total_days > 0 ? (int) ceil($total_followers_summany / $total_days) : 0;
    $average_posts = $total_days > 0 ? (int) ceil($total_posts_summany / $total_days) : 0;
    ?>
			<div class="table_sumary table-responsive">
				<table class="table">
					<thead>
						<tr>
							<td><?=lang("Time_Until")?></td>
							<td><?=lang("Date")?></td>
							<td><?=lang("Followes")?></td>
							<td><?=lang("Posts")?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?=lang("Current_Stats")?></td>
							<td><?=strftime("%d-%m-%Y", strtotime(reset($result->list_summary)->date))?></td>
							<td><?=number_format(reset($result->list_summary)->followers)?></td>
							<td><?=number_format(reset($result->list_summary)->posts)?></td>
						</tr>
						<?php if ($total_days > 0) {?>
						<tr>
		                    <td>30 <?=lang("days")?></td>
		                    <td><?=(new \DateTime())->modify('+30 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 30))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 30))?></td>
		                </tr>
		                <tr>
		                    <td>60 <?=lang("days")?></td>
		                    <td><?=(new \DateTime())->modify('+60 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 60))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 60))?></td>
		                </tr>
		                <tr>
		                    <td>3 <?=lang("months")?></td>
		                    <td><?=(new \DateTime())->modify('+90 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 90))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 90))?></td>
		                </tr>
		                <tr>
		                    <td>6 <?=lang("months")?></td>
		                    <td><?=(new \DateTime())->modify('+180 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 180))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 180))?></td>
		                </tr>
		                <tr>
		                    <td>9 <?=lang("months")?></td>
		                    <td><?=(new \DateTime())->modify('+279 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 279))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 279))?></td>
		                </tr>
		                <tr>
		                    <td>1 <?=lang("year")?></td>
		                    <td><?=(new \DateTime())->modify('+365 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 365))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 365))?></td>
		                </tr>
		                <tr>
		                    <td>1 <?=lang("year_and_half")?></td>
		                    <td><?=(new \DateTime())->modify('+547 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 547))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 547))?></td>
		                </tr>
		                <tr>
		                    <td>2 <?=lang("years")?></td>
		                    <td><?=(new \DateTime())->modify('+730 day')->format('d-m-yy')?></td>
		                    <td><?=number_format($follower_count + ($average_followers * 730))?></td>
		                    <td><?=number_format($media_count + ($average_posts * 730))?></td>
		                </tr>
		                <?php }?>
					</tbody>
					<tfoot>
						<?php if ($total_days > 0) {?>
						<tr>

							<?php
$average_followers = "-";
        if ($average_followers > 0) {
            $average_followers = "<span class='text-success'>+" . number_format($average_followers) . "<span>";
        } else if ($average_followers < 0) {
            $average_followers = "<span class='text-danger'>" . number_format($average_followers) . "<span>";
        }

        $average_posts = "-";
        if ($average_posts > 0) {
            $average_posts = "<span class='text-success'>+" . number_format($average_posts) . "<span>";
        } else if ($average_posts < 0) {
            $average_posts = "<span class='text-danger'>" . number_format($average_posts) . "<span>";
        }
        ?>

		                    <td colspan="2"><i class="ft-crosshair"></i> <?=lang("Based_on_an_average_of")?></td>
		                    <td><?=sprintf(lang("%s followers/day"), $average_followers)?></td>
		                    <td><?=sprintf(lang("%s posts/day"), $average_posts)?></td>
		                </tr>
						<?php } else {?>
						<tr>
							<td colspan="4" style="font-weight: 400"><?=lang("There is not enough data to generate future projections, please come back tomorrow.")?></td>
						</tr>
						<?php }?>
					</tfoot>
				</table>
			</div>	
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		console.log(<?=$result->followers_chart?>);
		console.log(<?=$table_page_fans_data?>);
		console.log(<?=$table_page_engagement_data?>);
		console.log(<?=$table_page_fans_data?>);
		<?php if (!empty($result)) {?>
		facebook_analytics.lineChart(
            "ig-analytics-followers-line-stacked-area",
            <?=$result->date_chart?>,
            [
                <?=$result->followers_chart?>,
            ],
            [
                "<?=lang("Fan")?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		facebook_analytics.lineChart(
            "ig-analytics-fans-line-stacked-area",
            <?=$table_page_fans_date?>,
            [
                <?=$table_page_fans_data?>,
            ],
            [
                "<?=lang("Audience")?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		facebook_analytics.lineChart(
            "ig-analytics-engagement-line-stacked-area",
            <?=$table_page_engagement_date?>,
            [
                <?=$table_page_engagement_data?>,
            ],
            [
                "<?=lang("Engagement")?>"
            ],
            "line",
            ["rgba(255,0,94,1)"]
        );
		
	<?php } ?>;
	});
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