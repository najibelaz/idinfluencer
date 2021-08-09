<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">games</i> <?=lang('jeux_concour')?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="users card">

            <div class="header">
                <h2><?=lang("new_game")?></h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group avatar no-radius mb-4">
                            <label for="fileupload"><?=lang("background")?></label><br>

                            <div class="fileinput-button">
                                <!-- <i class="ft-camera"></i> <span> <?=lang('upload')?></span>-->
                                <i class="material-icons">camera_alt</i>
                                <input id="fileupload" type="file" name="files[]">

                            </div>
                            <div class="file-manager-list-images">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name"><?=lang("name of game")?><span>*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="">
                        </div>
                        <div class="form-group">
                            <label for="description"><?=lang("description")?> 3</label>
                            <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                        </div>
                        <div class="form-group file-group">
                            <label for="regulation"><?=lang("regulation")?><span>*</span></label>
                            <input type="file" class="form-control" name="regulation" id="regulation" value="">
                            <span class="file-name form-control"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qst1"><?=lang("question")?> 1</label>
                            <textarea class="form-control" name="qst1" id="qst1" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="qst2"><?=lang("question")?> 2</label>
                            <textarea class="form-control" name="qst2" id="qst2" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="qst3"><?=lang("question")?> 3</label>
                            <textarea class="form-control" name="qst3" id="qst3" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex">
            <a href="<?=cn($module)?>" class="btn btn-secondary mr-2"><?=lang('cancel')?></a>
            <button type="submit" class="btn btn-primary">
                <?=lang('add')?>
            </button>
        </div>
    </div>
</div>
</div>