<?php
//instagram activity get value
class InstagramHelper {
    public static function get_hashtags($string) {
        preg_match_all('/#([^\s#]+)/', $string, $array);
        return $array[1];

    }

    public static function get_mentions($string) {
        preg_match_all('/@([^\s@]+)/', $string, $array);
        return $array[1];
    }


    public static function get_embed_html($shortcode) {
        $url = 'https://graph.facebook.com/v9.0/instagram_oembed?url=https://www.instagram.com/p/' . $shortcode . '/&access_token=EAAFEauXCRm8BAJeAP2dwTPYxmjM8mQABgJBnnPVoRXrZBmHTJ7hraXrOpAq3ZAVQpxOdtzDnYBbBwZA4aP2GggCRMMrUr2sfyUvG00oFINdhQBw2tLIKIuEmqZCt3jZBYsbcaZAvkvp8IaaPgDq4doZA0v0hFiGT7iygWHEUHtfBZCPKGAEXelP9';
        /* Initiate curl */
        $ch = curl_init();
        /* Disable SSL verification */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        /* Will return the response */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* Set the Url */
        curl_setopt($ch, CURLOPT_URL, $url);
        /* Execute */
        $data = curl_exec($ch);
        /* Close */
        curl_close($ch);
        $response = json_decode($data);
        return $response ? $response->html : false;
    }
}