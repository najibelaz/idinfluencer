<?php
// Récuperation des variables passées, on donne soit année; mois; année+mois
if(!isset($_GET['mois'])) {
	$num_mois = date("n"); 
} else {
	$num_mois = $_GET['mois'];
}

if(!isset($_GET['annee'])) {
	$num_an = date("Y"); 
} else {
	$num_an = $_GET['annee'];
} 	
// pour pas s'embeter a les calculer a l'affchage des fleches de navigation...
if($num_mois < 1) { 
	$num_mois = 12; 
	$num_an = $num_an - 1; 
} elseif($num_mois > 12) {	
	$num_mois = 1; 
	$num_an = $num_an + 1; 
}

// nombre de jours dans le mois et numero du premier jour du mois
$int_nbj = date("t", mktime(0,0,0,$num_mois,1,$num_an));
$int_premj = date("w",mktime(0,0,0,$num_mois,1,$num_an));

// tableau des jours, tableau des mois...
$tab_jours = array("","Lu","Ma","Me","Je","Ve","Sa","Di");
$tab_mois = array("","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre");

$int_nbjAV = date("t", mktime(0,0,0,($num_mois-1<1)?12:$num_mois-1,1,$num_an)); // nb de jours du moi d'avant
$int_nbjAP = date("t", mktime(0,0,0,($num_mois+1>12)?1:$num_mois+1,1,$num_an)); // b de jours du mois d'apres

// on affiche les jours du mois et aussi les jours du mois avant/apres, on les indique par une * a l'affichage on modifie l'apparence des chiffres *
$tab_cal = array(array(),array(),array(),array(),array(),array()); // tab_cal[Semaine][Jour de la semaine]
$int_premj = ($int_premj == 0)?7:$int_premj;
$t = 1; $p = "";
for($i=0;$i<6;$i++) {
	for($j=0;$j<7;$j++) {
		if($j+1 == $int_premj && $t == 1) { $tab_cal[$i][$j] = $t; $t++; } // on stocke le premier jour du mois
		elseif($t > 1 && $t <= $int_nbj) { $tab_cal[$i][$j] = $p.$t; $t++; } // on incremente a chaque fois...
		elseif($t > $int_nbj) { $p="*"; $tab_cal[$i][$j] = $p."1"; $t = 2; } // on a mis tout les numeros de ce mois, on commence a mettre ceux du suivant
		elseif($t == 1) { $tab_cal[$i][$j] = "*".($int_nbjAV-($int_premj-($j+1))+1); } // on a pas encore mis les num du mois, on met ceux de celui d'avant
	}
}
?>



<div class="card">
	<div class="header">
		<h2>Calander</h2>
	</div>
	<div class="body">
		<div class="ecalendar">
			<div class="ecalendar-top">
				<div class="ecalendar-header">
					<div class="title"><?=$tab_mois[$num_mois]?></div>
					<div class="btns-navigation">
						<div class="input-group">
							<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
							<input type="text" name="date_from" class="date nodays form-control" placeholder="Ex: <?php date("m/Y")?>"
								data-dtp="dtp_vzTrt">
						</div>
						<button class="btn btn-primary"><i class="material-icons">keyboard_arrow_left</i></button>
						<button class="btn btn-primary"><i class="material-icons">keyboard_arrow_right</i></button>
					</div>
				</div>
				<ul class="days days-name">
					<?php for($i = 1; $i <= 7; $i++){ ?>
					<li class="d-name"><span><?=$tab_jours[$i]?></span></li>
					<?php } ?>
				</ul>
			</div>
			<div class="ecalendar-body">
				<ul class="days days-contents">
					<?php for($i=0;$i<6;$i++) { ?>
					<?php for($j=0;$j<7;$j++) { ?>
					<li class="d-content <?=(strpos($tab_cal[$i][$j], "*") !== false) ? "disabled" : "";?>">
						<div class="d-info">

							<button class="btn btn-primary show-all" data-toggle="modal"
								data-target="#mainModal"><span>Voir plus</span>
							</button>

							<ul class="posts">
								<li class="post" data-toggle="modal" data-target="#mainModal">
									<span class="type">
										<i class="fa fa-facebook" aria-hidden="true"></i>
									</span>
									<img src="http://idinfluenceur.local/assets/img/default-avatar.png" alt=""
										class="logo">

									<span class="title">
										Lorem ipsum dolor
									</span>
									<div class="d-none">
										<div class="post-preview card">
											<div class="preview-fb preview-fb-media">
												<div class="preview-header">
													<div class="fb-logo"><i class="fa fa-facebook"></i></div>
												</div>
												<div class="preview-content">
													<div class="user-info">
														<img class="img-circle"
															src="http://idinfluenceur.local/public/facebook/assets/img/avatar.png">
														<div class="text">
															<div class="name"> Anonyme</div>
															<span> Just now . <i class="fa fa-globe"
																	aria-hidden="true"></i></span>
														</div>
													</div>
													<div class="caption-info">
														<div class="line-no-text"></div>
														<div class="line-no-text"></div>
														<div class="line-no-text w50"></div>
													</div>
													<div class="preview-image"></div>

													<div class="preview-comment">
														<div class="item">
															<i class="fb-icon like" aria-hidden="true"></i> Like </div>
														<div class="item">
															<i class="fb-icon comment" aria-hidden="true"></i> Comment
														</div>
														<div class="item">
															<i class="fb-icon share" aria-hidden="true"></i> Share
														</div>
														<div class="clearfix"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="post" data-toggle="modal" data-target="#mainModal">
									<span class="type">
										<i class="fa fa-instagram" aria-hidden="true"></i>
									</span>
									<img src="http://idinfluenceur.local/assets/img/default-avatar.png" alt=""
										class="logo">

									<span class="title">
										Lorem ipsum dolor
									</span>

									<div class="d-none">
										<div class="post-preview card">

											<div class="preview-instagram preview-instagram-photo">
												<div class="preview-header">
													<div class="pull-left"><i class="ft-camera"></i></div>
													<div class="instagram-logo"><img
															src="http://idinfluenceur.local/public/instagram/assets/img/instagram-logo.png">
													</div>
													<div class="pull-right"><i class="icon-paper-plane"></i></div>
												</div>
												<div class="preview-content">
													<div class="user-info">
														<img class="img-circle"
															src="http://idinfluenceur.local/public/instagram/assets/img/avatar.png">
														<span>Anonyme</span>
													</div>
													<div class="preview-image">
													</div>
													<div class="post-info">
														<div class="info-active pull-left"> Be the first to Like this
														</div>
														<div class="pull-right">1s</div>
														<div class="clearfix"></div>
													</div>
													<div class="caption-info pt0">
														<div class="line-no-text"></div>
														<div class="line-no-text"></div>
														<div class="line-no-text w50"></div>
													</div>
													<div class="preview-comment">
														Ajouter un commentaire…
														<div class="icon-3dot"></div>
													</div>
												</div>
											</div>

										</div>
									</div>

								</li>
							</ul>
							<span class="date">
								<?php
		                                echo "<td".(($num_mois == date("n") && $num_an == date("Y") && $tab_cal[$i][$j] == date("j"))?' style="color: #FFFFFF; background-color: #000000;"':null).">".((strpos($tab_cal[$i][$j],"*")!==false)? str_replace("*","",$tab_cal[$i][$j]) :$tab_cal[$i][$j])."</td>";
		                                ?>
							</span>
						</div>
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>


<div id="read-more-box" class="d-none">
	<div class="modal-dialog read-more-box" role="document">
		<div class="modal-content">
			<div class="modal-body" id="read-more-info">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-simple waves-effect"
					data-dismiss="modal"><?=lang("close")?></button>
			</div>
		</div>
	</div>
</div>