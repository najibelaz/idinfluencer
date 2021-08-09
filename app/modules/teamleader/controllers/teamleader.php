<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class teamleader extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){

		$clientId = "e11b43e7b7f78360d13f7d1a314a4630";
		$clientSecret = "da9c1346723e8da1630533c60369a431";
		$redirectUri = 'https://espaceclient.idinfluenceur.com/teamleader';

		/* ------------------------------------------------------------------------------------------------- */

		/**
		 * When the OAuth2 authentication flow was completed, the user is redirected back with a code.
		 * If we received the code, we can get an access token and make API calls. Otherwise we redirect
		 * the user to the OAuth2 authorization endpoints.
		 */
		if (!empty($_GET['code'])) {

		    $code = rawurldecode($_GET['code']);

		    /**
		     * Request an access token based on the received authorization code.
		     */
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, [
		        'code' => $code,
		        'client_id' => $clientId,
		        'client_secret' => $clientSecret,
		        'redirect_uri' => $redirectUri,
		        'grant_type' => 'authorization_code',
		        'date_from' => '01-01-2019',
		    ]);

		    $response = curl_exec($ch);
		    $data = json_decode($response, true);
		    $accessToken = $data['access_token'];
		    get_option('teamleader_token', '');
		    get_option('teamleader_code', '');
		} else {

		    $query = [
		        'client_id' => $clientId,
		        'response_type' => 'code',
		        'redirect_uri' => $redirectUri,
		    ];

		    header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));

		}
	}

	public function get_access(){
		set_time_limit(0);

		$clientId = get_option('teamleader_id_client');
		$clientSecret = get_option('teamleader_secret_client');
		$redirectUri = cn('').'teamleader/get_access';

		if(!empty($clientId) || !empty($clientSecret)) {
			if (!empty($_GET['code'])) {

				// $code = rawurldecode($_GET['code']);
				$code = $_GET['code'];

				// var_dump($_GET['code']);die();
			    /**
				 * Request an access token based on the received authorization code.
			     */
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://app.teamleader.eu/oauth2/access_token',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => 'client_id='.$clientId.'&client_secret='.$clientSecret.'&code='.$code.'&grant_type=authorization_code&redirect_uri='.$redirectUri,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/x-www-form-urlencoded',
					),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				
				// echo "<pre>"; var_dump($code,$clientId,$clientSecret,$redirectUri,$response);die();
			    $data = json_decode($response, true);
			    $accessToken = $data['access_token'];
			    $accessTokenRefresh = $data['refresh_token'];

			    update_option('teamleader_token', $accessToken);
			    update_option('teamleader_token_refresh', $accessTokenRefresh);
			    update_option('teamleader_code', $code);
			    redirect(cn("settings/general/teamleader"));
			} else {

			    $query = [
			        'client_id' => $clientId,
			        'response_type' => 'code',
			        'redirect_uri' => $redirectUri,
			    ];

			    header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));

			}
		} else {
			ms(
				array(
 				"status"  => "error",
 				"message" => lang("please_enter_secret_or_id_client")
 				)
			);
		}
		
	}

	public function invoices(){

		$clientId = "e11b43e7b7f78360d13f7d1a314a4630";
		$clientSecret = "da9c1346723e8da1630533c60369a431";

		$redirectUri = cn().'teamleader/invoices';

		/* ------------------------------------------------------------------------------------------------- */

		/**
		 * When the OAuth2 authentication flow was completed, the user is redirected back with a code.
		 * If we received the code, we can get an access token and make API calls. Otherwise we redirect
		 * the user to the OAuth2 authorization endpoints.
		 */
		if (!empty($_GET['code'])) {

		    $code = rawurldecode($_GET['code']);

		    /**
		     * Request an access token based on the received authorization code.
		     */
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, [
		        'code' => $code,
		        'client_id' => $clientId,
		        'client_secret' => $clientSecret,
		        'redirect_uri' => $redirectUri,
		        'grant_type' => 'authorization_code',
		        'date_from' => '01-01-2019',
		    ]);

		    $response = curl_exec($ch);
		    $data = json_decode($response, true);
		    $accessToken = $data['access_token'];
		    if($accessToken == null) {
		    	$accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjAyOTczMzdjYTg0ZjE5YWFhMmU3MDNiNDUxYTE4MzE4OWE1YmIzOGQxNDIzMDgzMTYyZDJhM2E1MGY4NTAwNTQ4OTEyYjg5MzUzNWViOWFhIn0.eyJhdWQiOiJlMTFiNDNlN2I3Zjc4MzYwZDEzZjdkMWEzMTRhNDYzMCIsImp0aSI6IjAyOTczMzdjYTg0ZjE5YWFhMmU3MDNiNDUxYTE4MzE4OWE1YmIzOGQxNDIzMDgzMTYyZDJhM2E1MGY4NTAwNTQ4OTEyYjg5MzUzNWViOWFhIiwiaWF0IjoxNTgxOTMxMTI0LCJuYmYiOjE1ODE5MzExMjQsImV4cCI6MTU4MTkzNDcyNCwic3ViIjoiMzI5NjM6NTczMjMiLCJzY29wZXMiOlsiaW52b2ljZXMiLCJxdW90YXRpb25zIiwidXNlcnMiXSwicGVybWlzc2lvbnMiOlsiYWRtaW4iLCJiaWxsaW5nIiwiY2FsZW5kYXIiLCJjb21wYW5pZXMiLCJjb250YWN0cyIsImNyZWRpdF9ub3RlcyIsImRhc2hib2FyZCIsImRlYWxzIiwiZGVsaXZlcnlfbm90ZXMiLCJpbnNpZ2h0cyIsImludm9pY2VzIiwibWFpbGluZyIsIm9yZGVycyIsInByb2R1Y3RfcHVyY2hhc2VfcHJpY2UiLCJwcm9kdWN0cyIsInNldHRpbmdzIiwic3Vic2NyaXB0aW9ucyIsInRhcmdldHMiLCJ0aW1lX3RyYWNraW5nIiwidG9kb3MiLCJ1c2VycyIsIndlYmhvb2tzIiwid29ya19vcmRlcnMiXX0.PDmCrpxHRJZEOcmP1XpdHvVbXrq0Mc8k1jR0e-UO0pXgC_0mMOI21SqZixwv_9VznljxNhdu0v6KBvWAjw47ibp2SDnml0OJUIl9OVkJWZw1c67aP5u7b0oWb2r4085bu8dIxTYOeVUu5k-Z6QhKcGdH3ys9h3buOfZOONYhtD4";
		    }
		    /**
		     * Get the user identity information using the access token.
		     */
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/invoices.list');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

		    $response = curl_exec($ch);
		    $invoices = json_decode($response, true);
		    if($invoices) {
		    	foreach ($invoices["data"] as $key => $invoice) {
		    		
		    dump($invoice);
		    	}
		    }
		} else {

		    $query = [
		        'client_id' => $clientId,
		        'response_type' => 'code',
		        'redirect_uri' => $redirectUri,
		    ];

		    header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));

		}
	}


}