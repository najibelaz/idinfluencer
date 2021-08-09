<div class="row top-title">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h4><i class="material-icons">games</i> <?=lang('list of games')?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">games</div>
            <div class="body">
                <table class="table table-bordered table-datatable mb0" width="100%">

                    <thead>
                        <tr>
                            <th>
                                <a href="javascript:void(0);">
                                    <span class="text"><?=lang("name of game")?></span>
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0);">
                                    <span class="text"><?=lang("actions")?></span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                hello world
                            </td>
                            <td>
                                <div class="btn-group btn-group-option">
                                    <a href="" class="btn btn-warning">
                                        <i class="ft-edit"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning dropdown-toggle"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                            <li>
                                                <a href=""><?=lang("View")?></a>
                                            </li>
                                            <li>
                                                <a href="" class="actionItem"><?=lang("delete")?>
                                                </a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>