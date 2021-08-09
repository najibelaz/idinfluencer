<?=Modules::run(get_theme()."/header", false)?>
	
	<section class="login-page">
		<div class="login-form">
			<!-- <div class="logo">
				<a href="<?=cn()?>"><img class="logo-black" src="<?=get_option("website_logo_black", BASE.'assets/img/logo-black.png')?>"></a>
			</div> -->
			<form action="<?=cn('auth/ajax_login')?>" data-redirect="<?=get("redirect")?cn(get("redirect")):cn('dashboard')?>" class="actionForm" method="POST">
				<span class="user-icon"></span>
				<div class="login-box">

					<h1>Se connecter à votre compte</h1>
					<div class="mb-3">
						<input type="email" class="form-control" name="email" placeholder="<?=lang("email")?>" aria-label="<?=lang("email")?>" aria-describedby="basic-addon1">
					</div>
					<div class="mb-3">
						<input type="password" class="form-control" name="password" placeholder="<?=lang("password")?>" aria-label="<?=lang("password")?>" aria-describedby="basic-addon1">
					</div>
					
					<div class="notify"></div>
					<div class="mb-3">
						<button type="submit" class="btn btn-primary btn-block btn-singin ladda-button" data-style="zoom-in"><?=lang("login")?></button>
					</div>
				
					<div class="mb-3">
						<input class="inp-cbx" name="remember" id="cbx" type="checkbox" style="display: none;"/>
						<label class="cbx" for="cbx"><span>
							<svg width="12px" height="10px" viewbox="0 0 12 10">
							<polyline points="1.5 6 4.5 9 10.5 1"></polyline>
							</svg></span><span><?=lang("remember")?></span>
						</label>
						<label class="forgot-password"><a href="<?=PATH."auth/forgot_password"?>"> <?=lang("forgot_password")?></a></label>
					</div>
				</div>


				<?php
				$facebook_login = (int)get_option('facebook_oauth_enable', 0);
				$google_login = (int)get_option('google_oauth_enable', 0);
				$twitter_login = (int)get_option('twitter_oauth_enable', 0);
				$count_login = $facebook_login + $google_login + $twitter_login;
				$count_login = $count_login == 0?0:12/($count_login);
				?> 
				<div class="btn-social-group text-center">
					<?php if($facebook_login){?>
					<a href="<?=PATH."auth/facebook"?>" class="btn btn-facebook"><span><i class="fa fa-facebook"></i></span> <?=lang("Login with Facebook")?></a>
					<?php }?>
					<?php if($google_login){?>
					<a href="<?=PATH."auth/google"?>" class="btn btn-google"><span><i class="fa fa-google"></i></span> <?=lang("Login with Google")?></a>
					<?php }?>
					<?php if($twitter_login){?>
					<a href="<?=PATH."auth/twitter_oauth"?>" class="btn btn-twitter"><span><i class="fa fa-twitter"></i></span> <?=lang("Login with Twitter")?></a>
					<?php }?>
				</div>	
				
				<?php if(get_option("singup_enable", 1)){?>
					<div class="text-try-now">
						<?=lang("dont_have_an_account")?> <a href="https://www.idinfluencer.com/contact/" target="_blanck"><?=lang("signup")?></a>
					</div>
				<?php }?>

			</form>

		
		</div>
	</section>

<?=Modules::run(get_theme()."/footer", false)?>