<?php if(!empty($Games)){
foreach ($Games as $key => $Game) {
?>
<a href="<?=cn("game/".$Game->slug)?>" class="item item-caption" data-content="<?=specialchar_decode($Game->name)?>">
    <div class="caption-content"><div class="number text-primary">#<?=$key+($page*$limit)+1?></div> <?=specialchar_decode(nl2br($Game->name))?> 

<i class="zmdi zmdi-copy"></i>

</div>
</a>
<?php }}?>
<?php if(!empty($next_Games)){?>
<div class="wrap-load-more">
    <button type="button" class="btn btn-primary caption-load-more" data-page="<?=$page+1?>"><?=lang("load_more")?></button>
</div>
<?php }?>

<?php if($page == 0 && empty($Games)){?>
<div class="dataTables_empty"></div>
<?php }?>