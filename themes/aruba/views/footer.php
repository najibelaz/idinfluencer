    <?php if($show){?>
    <footer>
		<div class="container">
			<div class="row">
				<div class="col-md-6 footer-left">
					<img class="logo" src="<?=get_option("website_logo_black", BASE.'assets/img/logo-black.png')?>">
					<div class="copyright"><?=lang("copyright_2018_all_rights_reserved")?></div>
				</div>
				<div class="col-md-6 footer-right">
					<ul class="nav navbar-nav">
			      		<li><a href="<?=cn("p/privacy_policy")?>"><?=lang("privacy_policy")?></a></li>
				      	<li><a href="<?=cn("p/terms_and_policies")?>"><?=lang("terms_of_services")?></a></li>
				    </ul>
				    <br/>
				    <ul class="social-list">
                        <?php if(get_option("social_page_facebook", "") != ""){?>
                        <li class="list-inline-item">
                            <a href="<?=get_option("social_page_facebook", "")?>">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </li>
                        <?php }?>
                        <?php if(get_option("social_page_google", "") != ""){?>
                        <li class="list-inline-item">
                            <a href="<?=get_option("social_page_google", "")?>">
                                <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php }?>
                        <?php if(get_option("social_page_twitter", "") != ""){?>
                        <li class="list-inline-item">
                            <a href="<?=get_option("social_page_twitter", "")?>">
                                <i class="fa fa-twitter-square" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php }?>
                        <?php if(get_option("social_page_instagram", "") != ""){?>
                        <li class="list-inline-item">
                            <a href="<?=get_option("social_page_instagram", "")?>">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php }?>
                        <?php if(get_option("social_page_pinterest", "") != ""){?>
                        <li class="list-inline-item">
                            <a href="<?=get_option("social_page_pinterest", "")?>">
                                <i class="fa fa-pinterest-square" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php }?>
                    </ul>
				</div>
			</div>
		</div>

	</footer>
    <?php }?>


	<!--Javascript-->
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/ladda/spin.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/ladda/ladda.min.js"></script>
    <?php if(get_option('gdpr_cookie_consent', 1)==1){?>
    <script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/gdpr/jquery.ihavecookies.min.js"></script>
    <?php }?>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/jquery.aniview.js"></script>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/particles.min.js"></script>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/main.js"></script>
    <?=htmlspecialchars_decode(get_option('embed_javascript', ''), ENT_QUOTES)?>

    <?php if(get_option('gdpr_cookie_consent', 1)==1){?>
    <script type="text/javascript">
    var options = {
        title: '&#x1F36A; <?=lang("Accept Cookies & Privacy Policy?")?>',
        message: '<?=lang("There are no cookies used on this site, but if there were this message could be customised to provide more details. Click the <strong>accept</strong> button below to see the optional callback in action...")?>',
        delay: 600,
        expires: 1,
        link: '<?=cn("p/privacy_policy")?>',
        onAccept: function(){
            var myPreferences = $.fn.ihavecookies.cookie();
        },
        uncheckBoxes: true,
        acceptBtnLabel: '<?=lang("Accept Cookies")?>',
        moreInfoLabel: '<?=lang("More information")?>',
        cookieTypesTitle: '<?=lang("Select which cookies you want to accept")?>',
        fixedCookieTypeLabel: '<?=lang("Essential")?>',
        fixedCookieTypeDesc: '<?=lang("These are essential for the website to work correctly.")?>'
    }
    $(document).ready(function() {
        //$('body').ihavecookies(options);
        if ($.fn.ihavecookies.preference('marketing') === true) {
            //console.log('This should run because marketing is accepted.');
        }
    });
    </script>
    <?php }?>
</body>
</body>
</html>