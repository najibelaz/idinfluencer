<div class="wrap-content">
	<form action="<?=cn($module."/ajax_update")?>" data-redirect="<?=cn($module)?>" class="actionForm" method="POST">
	<input type="hidden" name="ids" value="<?=!empty($result)?$result->ids:""?>">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="users">
			  	<div class="card">
			  		<div class="header">
			  			<h2 class="title">
	                        <i class="<?=$module_icon?>" aria-hidden="true"></i> <?=$module_name?>
	                    </h2>
			  		</div>
			  		<div class="body pl15 pr15">
                        <div class="form-group">
                            <textarea class="form-control post-message" name="caption" rows="3" placeholder="<?=lang('add_a_caption')?>" style="height: 114px;"><?=(!empty($result))?specialchar_decode($result->content):"";?></textarea>
                        </div>
			  		</div>
			  		<div class="footer pr-3 pl-3">
	  					<a href="<?=cn($module)?>" class="btn btn-secondary"><?=lang('cancel')?></a>
                        <button type="submit" class="btn btn-primary pull-right"><?=lang('add or create')?></button>
	                    
			  		</div>
			  	</div>
			</div>
		</div>
	</div>
	</form>
</div>