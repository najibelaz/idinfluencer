<?php
class instagram_analytics{
    public function __construct($parent){
        $this->_parent  = $parent;
        $this->ci       = &get_instance();
    }

    public function process(){
        try {
            //Get userinfo
            $userinfo = $this->_parent->ig->people->getSelfInfo();
            $userinfo = json_decode($userinfo);
            $userinfo = $userinfo->user;
            $follower_count = (int)$userinfo->follower_count;
            //Get user medias
            $feeds = array();
            $list_posts = array();
            
            $media_count = 0;
            $total_likes = 0;
            $total_comments = 0;
            $average_likes = 0;
            $average_comments = 0;
            $hashtags_array = array();
            $mentions_array = array();
            $medias = $this->_parent->ig->timeline->getSelfUserFeed();
            $medias = $medias->getItems();
            // echo "<pre>";var_dump($medias);die();
            if(!empty($medias)){
                foreach ($medias as $key => $row) {
                    
                    $total_likes += (int)$row->getLikeCount();
                    $total_comments += (int)$row->getCommentCount();
                    $engagement = (int)$row->getLikeCount() + (int)$row->getViewCount() + (int)$row->getCommentCount();
                    
                    // echo "<pre>";var_dump($row);die();
                    $rate = 0;
                    if($engagement != 0 && $follower_count != 0){
                        $rate = $engagement/$follower_count*100;
                    }

                    $feeds[] = [
                        'engagement' => $rate,
                        'media_id' => $row->getCode()
                    ];
                    // echo "<pre>";var_dump($row->getCaption()->getPk());die();
                    
                    // die();
                    if($row->getCaption() != ""){
                        $list_posts[]=[
                            "id"=>$row->getCaption()->getPk(),
                            "caption"=>$row->getCaption()->getPk(),
                            "created_at"=>date("Y-m-d H:i:s", $row->getCaption()->getCreated_at()),
                            "code"=>$row->getCode(),
                            "image"=>schedules_model::get_embed_html($row->getCode())
                        ];
                        // $hashtags = InstagramHelper::get_hashtags($row->getCaption()->getText());
                        // foreach ($hashtags as $hashtag) {
                            //     if (!isset($hashtags_array[$hashtag])) {
                                //         $hashtags_array[$hashtag] = 1;
                                //     } else {
                                    //         $hashtags_array[$hashtag]++;
                                    //     }
                                    // }
                                    
                        //             $mentions = InstagramHelper::get_mentions($row->getCaption()->getText());
                        //             echo "<pre>";var_dump($mentions);die();
                        // foreach ($mentions as $mention) {
                        //     if (!isset($mentions_array[$mention])) {
                        //         $mentions_array[$mention] = 1;
                        //     } else {
                        //         $mentions_array[$mention]++;
                        //     }
                        // }
                    }

                    $media_count++;

                    // if ($key >= 10) break;

                }
                usort($feeds, function($a, $b) {
                    return $b['engagement'] - $a['engagement'];
                });
            }
            $engagement = array_sum(array_column($feeds, 'engagement'));

            if ($engagement != 0 && !empty($feeds)) {
                $engagement = number_format($engagement / sizeof($feeds), 2);
            }

            if ($total_comments != 0 && $media_count != 0) {
                $average_comments = number_format($total_comments / $media_count, 2);
            }

            if ($total_likes != 0 && $media_count != 0) {
                $average_likes = number_format($total_likes / $media_count, 2);
            }

            arsort($hashtags_array);
            arsort($mentions_array);
            // $feeds = array_slice($feeds, 0, 3);
            $top_hashtags_array = array_slice($hashtags_array, 0, 15);
            $top_mentions_array = array_slice($mentions_array, 0, 15);
            // var_dump($list_posts);die();
            return (object)array(
                "feeds" => $feeds,
                "userinfo" => $userinfo,
                "engagement" => $engagement,
                "average_likes" => $average_likes,
                "average_comments" => $average_comments,
                "top_hashtags" => $top_hashtags_array,
                "top_mentions" => $top_mentions_array,
                "average_comments" => $average_comments,
                "list_posts" => $list_posts
            );
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




}