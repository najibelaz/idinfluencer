<?php

if(!class_exists("newinstagramapi")){
    require "vendor/autoload.php";

    class newinstagramapi{
        private $username;
        private $password;
        private $access_token;
        private $app_id;
        private $app_secret;
        private $app_version;
        private $fb;
        private $account_id;
        private $ENDPOINT_BASE;
        public function __construct($app_id = null, $app_secret = null, $app_version = "v3.2"){
            $this->account_id = 0;
            if($app_id != "" && $app_secret != ""){
                $this->app_id = $app_id;
                $this->app_secret = $app_secret;
                $this->app_version = $app_version;
            }else{
                $this->app_id = "NONE";
                $this->app_secret = "NONE";
                $this->app_version = "v3.2";
            }
            $fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => $this->app_version,
            ]);
            $this->fb = $fb;
            $this->ENDPOINT_BASE="https://graph.facebook.com/v3.2/";
        }
        function login_url(){
            $helper = $this->fb->getRedirectLoginHelper();
            $oAuth2Client = $this->fb->getOAuth2Client();
            $permissions=["public_profile" ,"instagram_basic","pages_show_list","instagram_content_publish","pages_read_engagement","instagram_manage_insights"];
            $loginUrl =$helper->getLoginUrl("https://espaceclient.idinfluencer.com/instagram/add_account",$permissions);
            return $loginUrl;
        }
        function shortaccessToken(){
            $helper = $this->fb->getRedirectLoginHelper();
            $oAuth2Client = $this->fb->getOAuth2Client();
            $accessToken="";
            try{
                $accessToken=$helper->getAccessToken();
  
                if(!$accessToken->isLongLived()){
                    try{
                        $accessToken=$oAuth2Client->getLongLivedAccessToken($accessToken);
                    }catch(Facebook\Exceptions\FacebookSDKException $e){
                        echo "Error getting long lived access token ".$e->getMessage;
                    }
                }
              // echo "<br/>";
                // echo $accessToken;
                // die();
                // echo $accessToken;
                return (string)$accessToken;
			}catch(Facebook\Exceptions\FacebookResponseException $e){
				echo "Graph returned an error ".$e->getMessage;
			}catch(Facebook\Exceptions\FacebookSDKException $e){
				echo "Facebook SDK returned an error ".$e->getMessage;
            }
            
        }
        public function getAccountInfo($accessToken){
            /**
             * Get Faceboock Account Id
             */
            $endpointFormat = $this->ENDPOINT_BASE . 'me/accounts?access_token={access-token}';
            $pagesEndpoint = $this->ENDPOINT_BASE . 'me/accounts';

            // endpoint params
            $pagesParams = array(
                'access_token' => $accessToken
            );

            // add params to endpoint
            $pagesEndpoint .= '?' . http_build_query( $pagesParams );
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $pagesEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $response = curl_exec( $ch );
            curl_close( $ch );
            $responseArray = json_decode( $response, true );
            $id_account=$responseArray["data"][0]["id"];
            $access_token=$responseArray["data"][0]["access_token"];
           
            /**
             * Get Instgram Account Id
             */

            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}?fields=instagram_business_account&access_token={access-token}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $id_account;

            // endpoint params
            $igParams = array(
                'fields' => 'instagram_business_account',
                'access_token' => $access_token
            );
            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );

            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // make call and get response
            $response = curl_exec( $ch );

            curl_close( $ch );
            $responseArray = json_decode( $response, true );
            // echo "<pre>";var_dump($responseArray);die();
            $instagramAccountId=$responseArray["instagram_business_account"]["id"];

            /**
             * get info user
             */
            
            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}?fields=id,ig_id,name,profile_picture_url,username,website,followers_count,media_count,biography&access_token={access-token}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId;

            // endpoint params
            $igParams = array(
                'fields' => 'id,ig_id,name,profile_picture_url,username,website,followers_count,media_count,biography',
                'access_token' => $access_token
            );

            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );

            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // make call and get response
            $response = curl_exec( $ch );
            curl_close( $ch );
            $responseArray = json_decode( $response, true );
            $responseArray["access_token"]=$access_token;
            // echo "<pre>";var_dump($responseArray,$access_token);die();
            return $responseArray;
        }
        public function post($schedule_list){
            $html="";
            foreach($schedule_list as $post){
                var_dump($post->id);
                $CI = &get_instance();
                $result=$CI->db->select('*')
                ->from("instagram_accounts ")
                ->where("id=".$post->account)->get()->result();
                // echo "<pre>";var_dump($result);
                $instagramAccountId=$result[0]->pid;
                $access_token=$result[0]->password;
                // $access_token="EAAFEauXCRm8BAAyjnZCRgjJGyrOahEvJRcffMhzBStsJsRlVzkJjOJtdfIXJbMRutzddA5JNYxsU3tQbBH88BKxvgC3IKoDf7E4tXZBhmAZCnaEPRZCOkEg8iNFWHKRZBDTGHSXPz9vKfkkzipGSmw1svqd5xu7MM7Ql23nEq9yqoi6ou6LCI3JMPwFHYivkZDEAAFEauXCRm8BAAyjnZCRgjJGyrOahEvJRcffMhzBStsJsRlVzkJjOJtdfIXJbMRutzddA5JNYxsU3tQbBH88BKxvgC3IKoDf7E4tXZBhmAZCnaEPRZCOkEg8iNFWHKRZBDTGHSXPz9vKfkkzipGSmw1svqd5xu7MM7Ql23nEq9yqoi6ou6LCI3JMPwFHYivkZD";
                if($instagramAccountId!=""){
                    $data=json_decode($post->data);
                    $media=$data->media;
                    $caption=$data->caption;
                    
                    $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media?image_url={image_url}&caption={caption}';
                    $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId."/media";
                    
                    // endpoint params
                    $igParams = array(
                        'image_url' => $media[0],
                        'caption' => $caption,
                        'access_token' => $access_token
                    );
                    // add params to endpoint
                    $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
                    // setup curl
                    $ch = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
                    // make call and get response
                    $response = curl_exec( $ch );
                    curl_close( $ch );
                    // echo "<pre>";var_dump($igParams,$instagramAccountId,$instagramAccountEndpoint,json_decode($response),json_decode($response)->id);
                    // die();
                    print_r($response);
                    // echo "<br>";
                    
                    if(json_decode($response)->id!=null){

                        $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media_publishmedia?creation_id={creation_id}';
                        $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId."/media_publish";
                        $igParams = array(
                            'creation_id' =>json_decode($response)->id,
                            'access_token' => $access_token
                        );
                        // add params to endpoint
                        $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
                        // setup curl
                        $ch = curl_init();
                        curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
                        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
                        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
                        // make call and get response
                        $response = curl_exec( $ch );
                        $response=json_decode($response);
                        var_dump($response);
                        curl_close( $ch );
                        $CI = &get_instance();
                        if($response->id!=null){
                            var_dump(json_encode($response),$post->id);
                            $CI = &get_instance();

                            $CI->db->update("instagram_posts", array("result" => $response->id,"status" =>"2"), array("id" =>$post->id));
                            $html.="<tr>
                            <td>".$post->username."</td>
                            <td>".$caption."</td>
                            <td>".$media[0]."</td>
                            <td>".$schedule->time_post."</td>
                            </tr>";
                        }else{
                            $CI->db->update("instagram_posts", array("result" => $response->id), array("id" =>$post->id));
                        }
                    }else{
                        $CI->db->update("instagram_posts", array("result" => $response->id), array("id" =>$post->id));
                    }
                }
                // die();
            }
            echo "----------------";
            echo "end corne instagram";
            return $html;
        }
        public function postPublier($instagram_account_id,$access_token,$media,$caption){
            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media?image_url={image_url}&caption={caption}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagram_account_id."/media";
            // echo "<pre>";var_dump($instagram_account_id,$access_token,$media,$caption);die();
            // endpoint params
            $igParams = array(
                'image_url' => $media,
                'caption' => $caption,
                'access_token' => $access_token
            );
            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
            // make call and get response
            $response = curl_exec( $ch );
            curl_close( $ch );
            // echo "<pre>";var_dump($igParams,$instagramAccountId,$instagramAccountEndpoint,json_decode($response));die();

            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media_publishmedia?creation_id={creation_id}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagram_account_id."/media_publish";
            $igParams = array(
                'creation_id' =>json_decode($response)->id,
                'access_token' => $access_token
            );
            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
            // make call and get response
            return $response = curl_exec( $ch );
        }
        public function postCrone(){
            $CI = &get_instance();
            $accounts=$CI->db->select('*')
            ->from("instagram_accounts ")
            ->where("uid=7314")->get()->result();
            foreach($accounts as $account){
                $instagramAccountId=$account->pid;
                $access_token=$account->password;
                $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media?fields=id,caption,media_type,media_url';
                $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId."/media";
    
                // endpoint params
                $igParams = array(
                    'fields' => 'id,caption,media_type,media_url,owner,timestamp,username,permalink,children',
                    'access_token' => $access_token
                );
                // add params to endpoint
                $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
                // setup curl
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                // make call and get response
                $response = curl_exec( $ch );
                curl_close( $ch );
                $posts=json_decode($response)->data;
                $posts_profile=array();
                foreach ($posts as $post){
                    $post_profile=array(
                        "account_id"=>$account->pid,
                        "id_post"=>$post->id,
                        "caption"=>$post->caption,
                        "media_url"=>$post->media_url,
                        "media_type"=>$post->media_type,
                        "children"=>"",
                        "permalink"=>$post->permalink,
                        "timestamp"=>$post->timestamp,
                        "created_at"=>$post->timestamp,
                    );
                    if(isset($post->children)){
                            // var_dump($child->id);
                            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}/media?fields=id,caption,media_type,media_url';
                            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $post->id."/children";
                
                            // endpoint params
                            $igParams = array(
                                'fields' => 'id,media_type,media_url,permalink,thumbnail_url,timestamp,username',
                                'access_token' => $access_token
                            );
                            // add params to endpoint
                            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
                            // setup curl
                            $ch = curl_init();
                            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
                            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
                            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                            // make call and get response
                            $responsechild = curl_exec( $ch );
                            curl_close( $ch );
                            $posts=json_decode($responsechild);
                            $post_profile["children"]=json_encode($posts->data);
                    }
                    $post_exists=$CI->db->select('*')
                    ->from("instagram_post_crone")
                    ->where("id_post=".$post->id)->get()->result();
                    if(!$post_exists){
                        $CI->db->insert("instagram_post_crone",$post_profile );
                    }
                }
            }
            // echo "end post crone";
        }
        
        public function insightProfile($access_token,$instagramAccountId){
            $endpointFormat = $this->ENDPOINT_BASE . '{page-id}?fields=id,ig_id,name,profile_picture_url,username,website,followers_count,media_count,biography&access_token={access-token}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId;

            // endpoint params
            $igParams = array(
                'fields' => 'id,ig_id,name,profile_picture_url,username,website,followers_count,media_count,biography,follows_count',
                'access_token' => $access_token
            );

            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );

            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // make call and get response
            $response = curl_exec( $ch );
            curl_close( $ch );
            $responseArray = json_decode( $response, true );
            $responseArray["access_token"]=$access_token;
            // var_dump($responseArray);
            return $responseArray;
        }
        public function rapport($access_token,$instagramAccountId,$metric,$since,$until,$period){
            $endpointFormat = $this->ENDPOINT_BASE . '{ig-user-id}/insights?metric={metric}&period={period}&since={since}&until={until}&access_token={access-token}';
            $instagramAccountEndpoint = $this->ENDPOINT_BASE . $instagramAccountId."/insights";
            $igParams = array(
                'metric' =>$metric,
                'period' =>$period,
                'access_token' => $access_token
            );
            if($since!=""){
                $igParams["since"]=$since;
                if($until!=""){
                    $igParams["until"]=$until;
                }
            }
            // add params to endpoint
            $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
            // setup curl
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            // make call and get response
            $response = curl_exec( $ch );
            curl_close( $ch );
            $response=json_decode($response);
            // echo "<pre>";var_dump($response); die();
            return $response->data;
        }
    }
}
