<?php

if(!class_exists("facebookcookie")){

    class facebookcookie{
        private $client;
        private $fb_dtsg;
        private $fbUserId;
        private $fbSess;
        private $proxy;

        public function __construct( $fbUserId = "", $fbSess = "", $proxy = null )
        {
            $this->fbUserId = $fbUserId;
            $this->fbSess   = $fbSess;
            $this->proxy    = $proxy;

            $cookies = [
                ["Name" => "c_user", "Value"  => $fbUserId,  "Domain" => ".facebook.com","Path" => "/","Max-Age" => null,"Expires" => null,"Secure" => false,"Discard" => false,"HttpOnly" => false,"Priority" => "HIGH"],
                ["Name" => "xs","Value" => $fbSess, "Domain" => ".facebook.com","Path" => "/","Max-Age" => null,"Expires" => null,"Secure" => false,"Discard" => false,"HttpOnly" => true,"Priority" => "HIGH"]
            ];

            $cookieJar      = new \GuzzleHttp\Cookie\CookieJar(false , $cookies);

            $this->client   = new \GuzzleHttp\Client([
                'cookies'           =>  $cookieJar,
                'allow_redirects'   =>  [ 'max' => 20 ],
                'proxy'             =>  empty($proxy) ? null : $proxy,
                'verify'            =>  false,
                'http_errors'       =>  false,
                'headers'           =>  ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0']
            ]);

            return $this->client;
        }

        public function getMyPages()
        {
            $result = (string)$this->client->request('GET' , 'https://touch.facebook.com/pages/launchpoint/' )->getBody();

            preg_match('/\<ul.+id\=\"owned_pages_item\".*\>(.+)\<\/ul\>/Ui', $result, $myPagesList);

            if( ! isset( $myPagesList[1] ) )
                return [];

            preg_match_all('/\<li.+\>(.+)\<\/li\>/Ui', $myPagesList[1], $myPages);

            if( ! isset( $myPages[1] ) )
                return [];

            $myPagesArr = [];

            foreach( $myPages[1] AS $myPage )
            {
                preg_match( '/page_id\"\: ?([0-9]+)/i', urldecode($myPage), $pageId );
                $pageId = isset($pageId[1]) ? $pageId[1] : 0;

                preg_match( '/\<div class\=\"_27vp\"\>(.+)\<\/div\>/Ui', $myPage, $pageName );
                $pageName = isset($pageName[1]) ? $pageName[1] : '???';

                $myPagesArr[] = [
                    'id'    =>  $pageId,
                    'name'  =>  $pageName
                ];
            }

            return $myPagesArr;
        }

        public function getLikedPages()
        {
            $result = (string)$this->client->request('GET' , 'https://touch.facebook.com/pages/launchpoint/liked_pages/' )->getBody();

            preg_match('/\<ul.*\>(.+)\<\/ul\>/Ui', $result, $likedPagesList);

            if( ! isset( $likedPagesList[1] ) )
                return [];

            preg_match_all('/\<li.+\>(.+)\<\/li\>/Ui', $likedPagesList[1], $likedPages);

            if( ! isset( $likedPages[1] ) )
                return [];

            $likedPagesArr = [];

            foreach( $likedPages[1] AS $myPage )
            {
                preg_match( '/page_id\"\: ?([0-9]+)/i', urldecode($myPage), $pageId );
                $pageId = isset($pageId[1]) ? $pageId[1] : 0;

                preg_match( '/\<div class\=\"_4nwe\"\>(.+)\<\/div\>/Ui', $myPage, $pageName );
                $pageName = isset($pageName[1]) ? $pageName[1] : '???';

                preg_match( '/\<div class\=\"_4nw9\"\>(.+)\<span/Ui', $myPage, $pageCategory );
                $pageCategory = isset($pageCategory[1]) ? $pageCategory[1] : '???';

                $likedPagesArr[] = [
                    'id'        =>  $pageId,
                    'name'  =>  $pageName,
                    'category'  =>  $pageCategory
                ];
            }

            return $likedPagesArr;
        }

        public function getGroups()
        {
            $result = (string)$this->client->request('GET' , 'https://m.facebook.com/groups/?seemore' )->getBody();

            preg_match_all('/\<a href\=\"\/groups\/([0-9]+)(?:\?refid\=[0-9]+)?\"\>(.+)\<\/a\>/Ui', $result, $groups);

            if( ! isset( $groups[1] ) )
                return [];

            $groupsArr = [];

            foreach( $groups[1] AS $key => $group )
            {
                $groupsArr[] = [
                    'id'        =>  $group,
                    'name'      =>  isset($groups[2][$key]) ? $groups[2][$key] : '???'
                ];
            }

            return $groupsArr;
        }

        public function getStats( $postId )
        {
            $result = (string)$this->client->request('GET' , 'https://touch.facebook.com/' . $postId )->getBody();

            preg_match('/\,comment_count\:([0-9]+)\,/i', $result, $comments);
            preg_match('/\,share_count\:([0-9]+)\,/i', $result, $shares);
            preg_match('/\,reactioncount\:([0-9]+)\,/i', $result, $likes);

            return [
                'like'      =>  isset($likes[1]) ? $likes[1] : 0,
                'comments'  =>  isset($comments[1]) ? $comments[1] : 0,
                'shares'    =>  isset($shares[1]) ? $shares[1] : 0,
                'details'   =>  ''
            ];
        }

        public function sendPost($type, $data, $gid = 'me', $group_type = 'profile')
        {
            $spintax = new Spintax();
            $nodeFbId = $gid;
            $nodeType = $group_type;
            $preset_id = 0;

            $data = (object)$data;
            $data->data = (object)json_decode($data->data);
            $images      = array(
                "https://znews-photo.zadn.vn/w1024/Uploaded/qhj_yvobvhfwbv/2018_07_18/Nguyen_Huy_Binh1.jpg",
                "http://thegioigiadinh.com.vn/Upload/5(195).jpg"
            );
            $message    = @$spintax->process($data->data->caption);
            $link       = @$spintax->process($data->data->link);

            $sendData = [
                'fb_dtsg'   =>  $this->fb_dtsg(),
                '__ajax__'  =>  'true'
            ];

            if( $preset_id > 0 && $type == 'status' )
            {
                $sendData['text_format_preset_id'] = $preset_id;
            }
            else if( $type == 'link' )
            {
                $sendData['linkUrl'] = $link;
            }

            $postType = 'form_params';

            if( $type == 'media' )
            {
                $sendData['photo_ids'] = [];

                if( count($images) == 1 && !is_image($images[0]) ){
                    $endPoint = 'videos';
                    $sendData['file_url']       = $medias[0];
                    $sendData['description']    = $message;

                }else{
                    
                    $images = is_array($images) ? $images : [$images];
                    foreach($images AS $imageURL)
                    {
                        $photoId = $this->uploadPhoto($imageURL, $nodeFbId, $nodeType);

                        if( $photoId > 0 )
                        {
                            $sendData['photo_ids'][ $photoId ] = $photoId;
                        }
                    }

                    if( $nodeType == 'group' )
                    {
                        $endpoint = "https://touch.facebook.com/_mupload_/composer/?target=" . $nodeFbId;

                        $sendData['message'] = $message;
                    }
                    else if( $nodeType == 'page' )
                    {
                        $endpoint = 'https://upload.facebook.com/_mupload_/composer/?target=' . $nodeFbId . '&av=' . $nodeFbId;

                        $sendData['status']             = $message;
                        $sendData['waterfall_id']       = $this->waterfallId();
                        $sendData['waterfall_source']   = 'composer_pages_feed';

                        $postType = 'multipart';
                    }
                    else if( $nodeType == 'linkedpage' )
                    {
                        $endpoint = 'https://upload.facebook.com/_mupload_/composer/?target=' . $nodeFbId . '&av=' . $nodeFbId;

                        $sendData['message']            = $message;
                        $sendData['waterfall_id']       = $this->waterfallId();
                        $sendData['waterfall_source']   = 'composer_pages_feed';

                        $postType = 'multipart';
                    }
                    else
                    {
                        $endpoint = "https://touch.facebook.com/_mupload_/composer/?target=" . $nodeFbId;

                        $sendData['status']             = $message;
                        $sendData['waterfall_id']       = $this->waterfallId();
                        $sendData['waterfall_source']   = 'composer_pages_feed';
                        $sendData['privacyx']           = $this->getPrivacyX();
                    }

                }

            }
            else
            {
                if( $nodeType == 'group' )
                {
                    $endpoint = 'https://touch.facebook.com/a/group/post/add/?gid=' . $nodeFbId;
                    $sendData['message'] = $message;
                }
                else if( $nodeType == 'ownpage' )
                {
                    $endpoint = 'https://touch.facebook.com/a/home.php?av=' . $nodeFbId;
                    $sendData['status'] = $message;
                }
                else if( $nodeType == 'page' )
                {
                    $endpoint = 'https://touch.facebook.com/a/wall.php?id=' . $nodeFbId;
                    $sendData['message'] = $message;
                }
                else
                {
                    $endpoint = 'https://touch.facebook.com/a/home.php';

                    $sendData['status']     = $message;
                    $sendData['target']     = $nodeFbId;
                    $sendData['privacyx']   = $this->getPrivacyX();
                }
            }

            if( $postType == 'multipart' )
            {
                $sendData = $this->conertToMultipartArray( $sendData );
            }

            $post = (string)$this->client->request(
                'POST' ,
                $endpoint ,
                [
                    $postType   => $sendData ,
                    'headers'   => [ 'Referer' => 'https://touch.facebook.com/' ]
                ]
            )->getBody();

            if( $nodeType == 'page' )
            {
                $getLastPostId = (string)$this->client->request('GET' , 'https://m.facebook.com/' . $nodeFbId . '/?filter=2' )->getBody();

                preg_match('/top_level_post_id\.([0-9]+)/i', $getLastPostId, $postId);
                $postId = isset($postId[1]) ? $postId[1] : 0;
            }
            else if( $nodeType == 'profile' && $type == 'media' )
            {
                $getLastPostId = (string)$this->client->request('GET' , 'https://m.facebook.com/' . $nodeFbId . '/' )->getBody();

                preg_match('/id\=\"like_([0-9]+)\"/i', $getLastPostId, $postId);
                $postId = isset($postId[1]) ? $postId[1] : 0;
            }
            else
            {
                preg_match('/story_fbid\=([0-9]+)/i', $post, $postId);
                $postId = isset($postId[1]) ? $postId[1] : 0;

                if( !$postId ) // for: group photo upload case
                {
                    preg_match('/\&(?:amp\;)?id\=([0-9]+)/i', $post, $postId);
                    $postId = isset($postId[1]) ? $postId[1] : 0;
                }
            }

            pr([
                'status'    =>  'ok',
                'id'        =>  $postId
            ],1);

            return [
                'status'    =>  'ok',
                'id'        =>  $postId
            ];
        }

        private function uploadPhoto( $photo, $target, $targetType )
        {
            $postData = [
                [
                    'name'      => 'file1',
                    'contents'  => file_get_contents($photo),
                    'filename'  => basename( $photo )
                ]
            ];

            $endpoint = 'https://upload.facebook.com/_mupload_/photo/x/saveunpublished/?thumbnail_width=80&thumbnail_height=80&waterfall_id=' . $this->waterfallId() . '&waterfall_app_name=web_m_touch&waterfall_source=composer_pages_feed&target_id=' . urlencode( $target ) . '&fb_dtsg=' . urlencode( $this->fb_dtsg() ) . '&__ajax__=true';
            if( $targetType == 'ownpage' )
            {
                $endpoint .= '&av=' . urlencode( $target );
            }

            $post = (string)$this->client->request(
                'POST' ,
                $endpoint ,
                [
                    'multipart' => $postData ,
                    'headers'   => [ 'Referer' => 'https://touch.facebook.com/' ]
                ]
            )->getBody();

            preg_match('/\"fbid\"\:\"([0-9]+)/i', $post, $photoId);

            return isset($photoId[1]) ? $photoId[1] : 0;
        }

        private function fb_dtsg()
        {
            if( is_null( $this->fb_dtsg ) )
            {
                $getFbDtsg = (string)$this->client->request('GET' , 'https://m.facebook.com/' )->getBody();

                preg_match('/name\=\"fb_dtsg\" value\=\"(.+)\"/Ui', $getFbDtsg, $fb_dtsg);

                if( !isset($fb_dtsg[1]) )
                {
                    var_dump($getFbDtsg);
                    die;

                }

                $this->fb_dtsg = $fb_dtsg[1];
            }

            return $this->fb_dtsg;
        }

        private function conertToMultipartArray( $arr )
        {
            $newArr = [];

            foreach( $arr AS $name => $value )
            {
                if( is_array( $value ) )
                {
                    foreach($value AS $name2 => $value2)
                    {
                        $newArr[] = [
                            'name'      => $name . '[' . $name2 . ']',
                            'contents'  => $value2
                        ];
                    }
                }
                else
                {
                    $newArr[] = [
                        'name'      => $name,
                        'contents'  => $value
                    ];
                }
            }

            return $newArr;
        }

        private function waterfallId()
        {
            return md5(uniqid() . rand(0,99999999) . uniqid());
        }

        private function getPrivacyX()
        {
            $url = 'https://touch.facebook.com/privacy/timeline/saved_custom_audience_selector_dialog/?fb_dtsg=' . $this->fb_dtsg();

            $getData = (string)$this->client->request('GET' , $url )->getBody();

            preg_match('/\:\"([0-9]+)\"/i', htmlspecialchars_decode( $getData ), $firstPrivacyX);

            return isset($firstPrivacyX[1]) ? $firstPrivacyX[1] : '0';
        }

        public function myInfo()
        {
            $getInfo = (string)$this->client->request('GET' , 'https://touch.facebook.com/' )->getBody();

            preg_match('/\"USER_ID\"\:\"([0-9]+)\"/i', $getInfo, $accountId);
            $accountId = isset($accountId[1]) ? $accountId[1] : '?';

            preg_match('/\"NAME\"\:\"([^\"]+)\"/i', $getInfo, $name);
            $name = json_decode( '"' . ( isset($name[1]) ? $name[1] : '?' ) . '"' );

            return [
                'id'    =>  $accountId,
                'name'  =>  $name
            ];
        }
    }
}
