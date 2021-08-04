<div class="row profile-page">
    <div class="col-lg-12 col-md-12">
        <div class="card profile-header bg-dark">
            <div class="body col-white">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="profile-image float-md-right"> <img
                                src="http://127.0.0.1:8000/assets/images/profile_av.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <h4 class="m-t-0 m-b-0"><?= $user->fullname ?></h4>
                        <span class="job_post">Ui UX Designer</span>
                        <p><?= $user->fullname ?></p>
                        <div>
                            <button class="btn btn-primary btn-round">Follow</button>
                            <button class="btn btn-primary btn-outline-primary btn-round btn-simple">Message</button>
                        </div>
                        <p class="social-icon m-t-5 m-b-0">
                            <a title="Twitter" href="javascript:void(0);"><i class="zmdi zmdi-twitter"></i></a>
                            <a title="Facebook" href="javascript:void(0);"><i class="zmdi zmdi-facebook"></i></a>
                            <a title="Google-plus" href="javascript:void(0);"><i class="zmdi zmdi-twitter"></i></a>
                            <a title="Behance" href="javascript:void(0);"><i class="zmdi zmdi-behance"></i></a>
                            <a title="Instagram" href="javascript:void(0);"><i class="zmdi zmdi-instagram "></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <ul class="row profile_state list-unstyled">
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-camera col-amber"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="2365" data-speed="1000"
                            data-fresh-interval="700">2365</h5>
                        <small>Shots View</small>
                    </div>
                </li>
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-thumb-up col-blue"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="1203" data-speed="1000"
                            data-fresh-interval="700">1203</h5>
                        <small>Likes</small>
                    </div>
                </li>
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-comment-text col-red"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="324" data-speed="1000"
                            data-fresh-interval="700">324</h5>
                        <small>Comments</small>
                    </div>
                </li>
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-account text-success"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="1980" data-speed="1000"
                            data-fresh-interval="700">1980</h5>
                        <small>Profile Views</small>
                    </div>
                </li>
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-desktop-mac text-info"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="251" data-speed="1000"
                            data-fresh-interval="700">251</h5>
                        <small>Website View</small>
                    </div>
                </li>
                <li class="col-lg-2 col-md-4 col-6">
                    <div class="body">
                        <i class="zmdi zmdi-attachment text-warning"></i>
                        <h5 class="m-b-0 number count-to" data-from="0" data-to="52" data-speed="1000"
                            data-fresh-interval="700">52</h5>
                        <small>Attachment</small>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="body">
                <div class="tab-pane body active" id="about">
                    <small class="text-muted">Email address: </small>
                    <p>michael@gmail.com</p>
                    <hr>
                    <small class="text-muted">Phone: </small>
                    <p>+ 202-555-9191</p>
                    <hr>
                    <small class="text-muted">Mobile: </small>
                    <p>+ 202-555-2828</p>
                    <hr>
                    <small class="text-muted">Birth Date: </small>
                    <p class="m-b-0">October 22th, 1990</p>
                </div>
            </div>
        </div>
    </div>
</div>