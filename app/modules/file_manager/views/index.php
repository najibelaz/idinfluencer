<!-- <style type="text/css">
    body{
        background: #fff!important;
    }
</style> -->
<div class="row top-title">
    <div class="col-12 d-flex align-center justify-content-between">
        <h4><i class="material-icons">storage</i> <?=lang('file_manager')?> </h4>
        <div class=" primary" style="font-size: 13px;">
            <div class="small text-right"><?=lang("storage")?></div>
            <?=round($info->total_storage_size,2)." ".lang("mb")?> / <?=$info->max_storage_size." ".lang("mb")?></div>
    </div>
</div>

<div class="file_manager">
    <div class="row">
        <div class="col-md-12">
            <form action="javascript:void(0);" method="POST">
            <div class="card">
                <div class="card-file-manager-option body">
                    <span class="text"><span class="file-manager-total-item"><?=isset($total_item)?$total_item:""?></span> <?=lang('media_items')?> </span>
                    <div class="pull-right">
                        <button type="button" class="btn btn-raised btn-warning waves-effect select_multi_files">
                            <span class="check"> <?=lang('select_all')?> </span>
                            <span class="uncheck"> <?=lang('deselect_all')?> </span>
                        </button>
                        
                        <div class="btn btn-raised btn-success waves-effect fileinput-button" >
                            <i class="ft-upload"></i> <span> <?=lang('upload')?></span>
                            <input id="fileupload" type="file" name="files[]" multiple>
                        </div>
                        <?php if(get_option('dropbox_api_key', '') != "" &&  permission("dropbox")){?>
                        <button type="button" class="btn btn-raised btn-primary waves-effect" id="chooser-image">
                            <i class="fa fa-dropbox"></i>
                        </button>
                        <?php }?>
                        <?php if(get_option('google_drive_api_key', '') != "" && get_option('google_drive_client_id', '') != "" && permission("google_drive")){?>
                        <button type="button" class="btn btn-raised btn-primary waves-effect" id="show-docs-picker" onclick="onApiLoad()">
                            <i class="fa fa-google-drive"></i>
                        </button>
                        <?php }?>
                        <button type="button" class="btn btn-raised btn-danger waves-effect delete_multi_files">
                            <i class="ft-trash-2"></i> <span> <?=lang('delete')?></span>
                        </button>
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-block file-manager-content file-manager-loader file-manager-scrollbar scrollbar-dynamic">
                    <!--Ajax Load Files-->
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function reload(){
        FileManager.loadFile(0);
        $.fancybox.close();
    }

    function overplay(){
        Main.overplay();
    }

    function hide_overplay(){
        FileManager.hide_overplay();
    }
</script>

<style type="text/css">
    .db-btn-designit{
        position: absolute;
        content: '';
        max-width: 24px;
        height: 24px;
        overflow: hidden;
        bottom: 9px;
        right: 10px;
        padding: 0;
        border-radius: 6px;
        text-align: center;
        line-height: 22px;
        border: 1px solid #fff;
        background: #000;
        z-index: 0;
    }

    .item:hover .db-btn-designit,
    .item:focus .db-btn-designit{
        display: block;
    }

    .db-btn-designit:before{
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        content: "\f040";
        color: #fff;
    }

    .fancybox-slide--iframe .fancybox-content{
        background: transparent!important;
        max-width: calc(100%);
        max-height: calc(100%);
    }

    .fancybox-iframe{
        background: transparent!important;
    }

    body .fancybox-container {
        z-index: 2000!important;
    }

    .fancybox-slide--iframe .fancybox-content {
        width: 100%!important;
        height: 100%!important;
        max-width  : 100%;
        max-height : 100%;
        margin: 0;
    }

    .loading-overplay{
        z-index: 2500!important;
    }
</style>