<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2 class="head-title"><strong><i class="ft-sliders"></i> </strong><?=lang('teamleader_API')?></h2>
            </div>
            <div class="body">
                <div class="form-group">
                    <span class="text"><?=lang('teamleader_id_client')?></span>
                    <input type="text" class="form-control" name="teamleader_id_client"
                        value="<?=get_option('teamleader_id_client', '')?>">
                </div>

                <div class="form-group">
                    <span class="text"><?=lang('teamleader_secret_client')?></span>
                    <input type="text" class="form-control" name="teamleader_secret_client"
                        value="<?=get_option('teamleader_secret_client', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"><?=lang('teamleader_api_group')?></span>
                    <input type="text" class="form-control" name="teamleader_api_group"
                        value="<?=get_option('teamleader_api_group', '')?>">
                </div>
                <div class="form-group">
                    <span class="text"><?=lang('teamleader_api_secret')?></span>
                    <input type="text" class="form-control" name="teamleader_api_secret"
                        value="<?=get_option('teamleader_api_secret', '')?>">
                </div>

                <div class="form-group">
                    <span class="text"><?=lang('teamleader_code')?></span>
                    <input type="text" class="form-control" name="teamleader_code"
                        value="<?=get_option('teamleader_code', '')?>">
                </div>

                <div class="form-group">
                    <span class="text"><?=lang('teamleader_token')?></span>
                    <textarea class="form-control" name="teamleader_token"
                        id="teamleader_token"><?=get_option('teamleader_token', '')?></textarea>
                </div>
                <div class="form-group">
                    <span class="text"><?=lang('teamleader_token_refresh')?></span>
                    <textarea class="form-control" name="teamleader_token"
                        id="teamleader_token"><?=get_option('teamleader_token_refresh', '')?></textarea>
                </div>
            </div>
            <div class="header">
                <a href="<?=cn('teamleader/get_access') ?>" class="btn btn-raised btn-primary waves-effect"><i class="fa fa-plus"></i> <?=lang('get_access') ?></a>
            </div>
        </div>




    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?=BASE . "assets/plugins/codemirror/lib/"?>codemirror.css">
<link rel="stylesheet" type="text/css" href="<?=BASE . "assets/plugins/codemirror/theme/"?>lucario.css">
<script src="<?=BASE . "assets/plugins/codemirror/lib/"?>codemirror.js" type="text / javascript" charset="utf - 8"></script>
<script src="<?=BASE . "assets/plugins/codemirror/mode/"?>css / css.js" type="text / javascript" charset="utf - 8"></script>
<script src="<?=BASE . "assets/plugins/codemirror/mode/"?>javascript / javascript.js" type="text / javascript"
    charset = "utf-8" ></script>
<script src="<?=BASE . "assets/plugins/codemirror/mode/"?>htmlmixed / htmlmixed.js" type="text / javascript"
    charset = "utf-8" ></script>
<script>
        setTimeout(function () {
            var editor = CodeMirror.fromTextArea(document.getElementById("embed_javascript"), {
                lineNumbers: true,
                theme: "lucario"
            });
        }, 200);
</script>