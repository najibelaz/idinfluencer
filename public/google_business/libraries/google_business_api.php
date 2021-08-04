<?php
require APPPATH."libraries/Google/autoload.php";
// require "autoload.php";
if(!class_exists("Google_business_api")){
    class Google_business_api{
        private $APIKey;
        private $ClientID;
        private $ClientSecret;
        private $client;
        private $gmbService;

        public function __construct(){
            $api_key = get_option("gb_api_key", "");
            $client_id = get_option("gb_app_id", "");
            $client_secret = get_option("gb_app_secret", "");
            $this->APIKey = $api_key;
            $this->ClientID = $api_key;
            $this->ClientSecret = $client_secret;

            $this->client = new Google_Client();
            $this->client->setAccessType("offline");
            $this->client->setApprovalPrompt("force");
            $this->client->setApplicationName("Id'Influencer");
            $this->client->setRedirectUri(cn("google_business/add_account"));
            $this->client->setClientId($client_id);
            $this->client->setClientSecret($client_secret);
            $this->client->setDeveloperKey($api_key);
            $this->client->setIncludeGrantedScopes(true);
            $this->client->setScopes(array(
                'https://www.googleapis.com/auth/business.manage',
                'https://www.googleapis.com/auth/plus.business.manage',
                'https://www.googleapis.com/auth/userinfo.email',
                )
            );

            $this->gmbService = new \Google_Service_MyBusiness($this->client);
        }

        function login_url(){
            if($this->APIKey == "" || $this->ClientID == "" || $this->ClientSecret == ""){
                redirect(PATH."settings/general/social#google_business");#
            }
            return $this->client->createAuthUrl();
        }

        function get_access_token(){
            try {
                if(get("code")){
                    $this->client->authenticate(get("code"));
                    $oauth2 = new Google_Service_Oauth2($this->client);
                    $token = $this->client->getAccessToken();
                    return $token;
                }else{
                    redirect(cn("google_business/oauth"));
                }
                
            } catch (Exception $e) {
                redirect(cn("google_business/oauth"));
            }
        }

        function set_access_token($access_token){
            $this->client->setAccessToken($access_token);
        }

        function get_locations(){
           
// 		$curl = curl_init();
// $authorization='ya29.a0AfH6SMAiZbFJTJeANnpGP1pRIke6ELvdgZYdjkKIdT6oyInMnJz1G_LK5NpK9HEjkrGjJVDYAVXNYB4gD5BZDnQ6pm-cdtGNV6t7Z5FDSzipL_ku7kKm0YwxLreYvix00BOgLyOLyBUeEkXS67-IYmIzp3-e';

// 		curl_setopt_array($curl, array(
// 		  CURLOPT_URL => 'https://mybusiness.googleapis.com/v1/accounts',
// 		  CURLOPT_RETURNTRANSFER => true,
// 		  CURLOPT_ENCODING => '',
// 		  CURLOPT_MAXREDIRS => 10,
// 		  CURLOPT_TIMEOUT => 0,
// 		  CURLOPT_FOLLOWLOCATION => true,
// 		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 		  CURLOPT_CUSTOMREQUEST => 'GET',
// 		  CURLOPT_HTTPHEADER => array(
//             // 'Authorization: Bearer '.$access_token["access_token"]
//             'Authorization:Bearer '.$authorization
// 		  ),
// 		));

// 		$response = json_decode(curl_exec($curl));

// 		curl_close($curl);
//         var_dump($response->accounts[0]->name);

// 		$curl = curl_init();

//         curl_setopt_array($curl, array(
//             CURLOPT_URL => 'https://mybusiness.googleapis.com/v/'.$response->accounts[0]->name.'/locations',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'GET',
//             CURLOPT_HTTPHEADER => array(
//               // 'Authorization: Bearer '.$access_token["access_token"]
//                 'Authorization:Bearer '.$authorization
//             ),
//           ));

//           $response = json_decode(curl_exec($curl));

// 		curl_close($curl);
//         var_dump($response->accounts[0]->name);

//         die();
        


    
            $accounts = $this->gmbService->accounts;
            $locations = $this->gmbService->accounts_locations;
            $accountsList = $accounts->listAccounts()->getAccounts();
            if ( $accountsList ) {
                $locationsList = $locations->listAccountsLocations($accountsList[0]->name)->getLocations(); 
                if ($locationsList) {
                    return $locationsList;
                }
            }

            return false;
        }

        function get_user_info($access_token){
            try {
                $oauth2 = new Google_Service_Oauth2($this->client);
                $this->client->setAccessToken($access_token);
                $userinfo = $oauth2->userinfo->get();
                return $userinfo;
            } catch (Exception $e) {
                return false;
            }
        }

        function post($data, $account = ""){
            $data       = (object)$data;
            $response   = array();
            $data->data = (object)json_decode($data->data);
            $medias     = @$data->data->media;
            $cta_action = @$data->data->cta_action;
            $caption    = @$data->data->caption;
            $description= @$data->data->description;
            $url        = @$data->data->url;
            $content    = array();

            try {

                //Add Media
                if(!empty($medias)){
                    if(is_image($medias[0]))
                    {
                        //Auto Resize
                        if(permission("watermark", $data->uid)){
                            $new_image_path = get_tmp_path(ids().".jpg");
                            $new_image_path = Watermark($medias[0], $new_image_path, $data->uid);
                            $medias[0] = $new_image_path;
                        }
                        
                        $MediaItem = new \Google_Service_MyBusiness_MediaItem();
                        $MediaItem->setMediaFormat('PHOTO');
                        $MediaItem->setSourceUrl($medias[0]);
                    }
                }

                //Call Action
                if($url != ""){
                    $CallToAction = new \Google_Service_MyBusiness_CallToAction();
                    $CallToAction->setActionType($cta_action); //BOOK,ORDER,SHOP,LEARN_MORE,SIGN_UP,CALL
                    $CallToAction->setUrl($url);
                }

                //Add Offer
                /*$Offer = new \Google_Service_MyBusiness_LocalPostOffer();
                $Offer->setCouponCode("BOGO-JET-CODE");
                $Offer->setRedeemOnlineUrl("https://stackposts.com/");
                $Offer->setTermsConditions("Offer only valid if you can prove you are a time traveler");*/

                //Add Product
                /*$LowerPrice = new \Google_Service_MyBusiness_Money();
                $LowerPrice->setCurrencyCode("USD");
                $LowerPrice->setUnits(5);
                $LowerPrice->setNanos(990000000);

                $UpperPrice = new \Google_Service_MyBusiness_Money();
                $UpperPrice->setCurrencyCode("USD");
                $UpperPrice->setUnits(7);
                $UpperPrice->setNanos(990000000);

                $Product = new \Google_Service_MyBusiness_LocalPostProduct();
                $Product->setLowerPrice($LowerPrice);
                $Product->setProductName("Product Name");
                $Product->setUpperPrice($UpperPrice);*/

                //Create the post
                $posts = $this->gmbService->accounts_locations_localPosts;
                $post = new \Google_Service_MyBusiness_LocalPost();
                $post->setlanguageCode("en-US");
                $post->setName($caption);
                $post->setSummary($caption);

                if(!empty($medias) && is_image($medias[0])){
                    $post->setMedia($MediaItem);
                }

                if($url != ""){
                    $post->setCallToAction($CallToAction);
                }

                //$post->setOffer($Offer);
                //$post->setProduct($Product);
                $response = $posts->create($account,$post);

                return $response->getSearchUrl();

            } catch (Exception $e) {
                $error = explode(": ", $e->getMessage());

                return array(
                    "status"  => "error",
                    "message" => end($error)
                );
            }
        }
    }
}