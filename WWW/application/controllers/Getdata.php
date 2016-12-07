<?php
    class Getdata extends CI_Controller {

        public function index()
        {
            function curl_post($url, $apiParams=array(), $header=array()){
                $curl = curl_init();
                    
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                if( is_array( $apiParams ) && count( $apiParams ) > 0 )
                {
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($apiParams));
                }
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 100);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER , $header );
                $tmpInfo = curl_exec($curl);
                if (curl_errno($curl)) {
                    echo 'Errno'.curl_error($curl);
                }
                curl_close($curl);

                return $tmpInfo;
            }

            //抓取视频列表页返回整体数据 匹配出每个视频内容页的href   
            $domain = 'http://www.15yc.com/';
            $header = array(
                "Referer: http://zxfuli.117o.com/jx/dapi.php"
            );
            $datas = curl_post($domain, $apiParams=array(), $header);
            $reg = '/href=\"\/show\/(\d+)\.html/i';
            $matches = array();            
            if( preg_match_all($reg, $datas, $matches) ) {
                $matches = array_unique( $matches[1] );
                $matches = array_values( $matches );
                // var_dump($matches);
            }

            //抓取视频介绍页相关数据
            $urlId = $matches[0];   //19242
            $urlId = '19242';
            $url_play = $domain . 'play/' . $urlId . '.html';    //$url_play = 'http://www.15yc.com/play/19242.html';
            $url_show = $domain . 'show/' . $urlId . '.html';
            $data_play = curl_post($url_play, $apiParams=array(), $header);
            $data_show = curl_post($url_show, $apiParams=array(), $header);
            //视频iframe链接地址
            $reg_iframeUrl = '/src=\"(.+)\"\swidth=\"100%\"\>\<\/iframe\>/i';
            $match_iframe = array();
            if( preg_match($reg_iframeUrl, $data_play, $match_iframe) ) {
                $match_iframe = $match_iframe[1];   //http://zxfuli.117o.com/jx/dapi.php?id=xJSpcJaczK2hdGdrmaN3goh3sH22g6mBoG9y&key=d3b72cc414811004
            } 
            //视频名称
            $reg_movie_name = '/\<h1 class=\"movie\-title\"\>(.+)\<span class=\"movie\-year\"\>\((\d+)\)\<\/span\>/i';
            $match_movie_name = array();
            if( preg_match($reg_movie_name, $data_show, $match_movie_name) ) {
                var_dump($match_movie_name); die;
                $match_movie_name = $match_movie_name[1];  
                $match_movie_year = $match_movie_name[2];  
            } 


            //匹配视频关键id
            // $data_id = curl_post($match_iframe, $apiParams=array(), $header);
            // $reg_id = '/f\:\'\/jx\/2s\.php\?id=(\w+)\'/i';
            // $match = array();
            // $id = '';
            // if( preg_match($reg_id, $data_id, $match) ) {
            //     $id = $match[1];
            // }

            //获取视频配置xml文件内容
            // $service_path = 'http://zxfuli.117o.com/jx/2s.php?id=';
            // $url_xml = $service_path . $id;
            // $data_xml = curl_post($url_xml, $apiParams=array(), $header);

            // echo file_put_contents("D:/myweb/WWW/xml/".$urlId.".xml", $data_xml);
        }
    }
?>