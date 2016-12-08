<?php
    class Getdata extends CI_Controller {

        public function index()
        {
            //抓取数据函数
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
            $start = '<div class="col-md-9">';
            $end = '<div class="col-md-3">';
            $data_show_reg = '/'.$start.'([\s\S]*)'.$end.'/iU';
            if ( preg_match($data_show_reg, $data_show, $data_show_data) ) {
                $data_show_data = $data_show_data[1];
                // print_r($data_show_my); die;
            }
            //电影相关信息
            function movieInfo($start, $end, $data) {
                $reg = '/'.$start.'([\s\S]*)'.$end.'/iU';
                if ( preg_match($reg, $data, $tdata) ) {
                    return $tdata[1];
                }
            }
            $movie_name = movieInfo('class=\"img-thumbnail\" alt=\"', '\" width=\"100%\"', $data_show_data);
            print_r($movie_name); die;
            $start_img = 'class=\"img-thumbnail\" alt=\"';
            $end_img = '\" width=\"100%\"';
            $reg_img = '/'.$start_img.'([\s\S]*)'.$end_img.'/iU';
            if ( preg_match($reg_img, $data_show, $movie_img) ) {
                $movie_img = $movie_img[1];
            }
            print_r($movie_img); die;

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

            // echo FCPATH.'xml\19242.xml'; die;
            // echo file_put_contents("D:/myweb/WWW/xml/".$urlId.".xml", $data_xml);
            
            //数据库连接
            // $this->load->database();
            // $query = $this->db->get('movie');   
            // foreach ($query->result() as $row)
            // {
            //     print_r($row); 
            // }  
        }
    }
?>