<div class="pn-box-content">
    <div class="row">
        <input type="hidden" name="ids" value="<?=!empty($result)?$result->ids:""?>">

        <div class="row">
            <div class="col-md-12 ">
                <div class="users">
                    <div class="card">
                        <?php if((!empty($result) && $result->status == 1) || empty($result)){?>
                        <div class="card-body pl15 pr15">
                            <div class="form-group">
                                <label for="name"><?=lang("title")?></label>
                                <input type="text" name="name" class="form-control" id="name" value="<?=!empty($result)?$result->name:""?>">
                            </div>
                        </div>
                        <div class="card-body pl15 pr15">
                            <div class="form-group">
                                <label for="slug"><?=lang("slug")?></label>
                                <input type="text" name="slug" class="form-control" id="slug" value="<?=!empty($result)?$result->slug:""?>">
                                <div class="small pagelink" style="color: #adadad; padding-top: 3px;"></div>
                            </div>
                        </div>
                        <?php }else{?>
                        <div class="card-body pl15 pr15">
                            <div class="form-group">
                                <label for="name"><?=lang("title")?></label>
                                <input type="text" class="form-control" value="<?=!empty($result)?$result->name:""?>" disabled="">
                            </div>
                        </div>
                        <?php }?>
                        <div class="card-body pl15 pr15">
                            <div class="form-group">
                                <label for="name"><?=lang("content")?></label>
                                <textarea name="content" class="form-control texterea-editor"><?=!empty($result)?$result->content:""?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary"> <?=lang('save')?></button>
    <a href="<?=cn("custom_page")?>" class="btn btn-default"> <?=lang('cancel')?></a>
</div>

<style type="text/css">
.trumbowyg-box{
    margin-top: 0px;
}
</style>

<script type="text/javascript">
    $(function(){
        if($(".texterea-editor").length > 0){
            $('.texterea-editor').trumbowyg();
        }

        $("#name").change(function(){
            _val  = $(this).val();
            _slug = utf8ConvertJavascript(_val);
            $("#slug").val(_slug);
            $(".pagelink").text("<?=PATH?>p/"+_slug);
        });

        setTimeout(function(){
            $(".trumbowyg-editor").niceScroll();
        }, 1000);
    });
    
    function utf8ConvertJavascript(str) 
    {
        var str;
        str = str.toLowerCase();
        str = str.replace(/??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???/g, "a");
        str = str.replace(/??|??|???|???|???|??|???|???|???|???|???/g, "e");
        str = str.replace(/??|??|???|???|??/g, "i");
        str = str.replace(/??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???/g, "o");
        str = str.replace(/??|??|???|???|??|??|???|???|???|???|???/g, "u");
        str = str.replace(/???|??|???|???|???/g, "y");
        str = str.replace(/??/g, "d");
        str= str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");  
        /* t??m v?? thay th??? c??c k?? t??? ?????c bi???t trong chu???i sang k?? t??? - */
        str= str.replace(/-+-/g,"-"); //thay th??? 2- th??nh 1-  
        str = str.replace(/^\-+|\-+$/g, "");
     
        return str;
    }
</script>