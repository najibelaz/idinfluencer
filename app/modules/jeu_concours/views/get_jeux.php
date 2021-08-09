<?php if(!empty($jeux)){
foreach ($jeux as $key => $j) {
?>
<a href="javascript:void(0);" class="item" data-content="<?=specialchar_decode($j->ids)?>">
    <div class="caption-content"><div class="number text-primary">#<?=$key+($page*$limit)+1?></div> <?=specialchar_decode(nl2br($j->name))?></div>
</a>
<?php }}?>
<?php if(!empty($next_jeux)){?>
<div class="wrap-load-more">
    <button type="button" class="btn btn-primary caption-load-more" data-page="<?=$page+1?>"><?=lang("load_more")?></button>
</div>
<?php }?>

<?php if($page == 0 && empty($jeux)){?>
<div class="dataTables_empty"></div>
<?php }?>