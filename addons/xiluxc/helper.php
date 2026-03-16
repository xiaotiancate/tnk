<?php
//生成核销二维码与token
function xiluxc_qrcode_token($id, $platform, $url="pages/to_offset/to_offset"){

    $path = "/runtime/xiluxc/";
    if (!file_exists(ROOT_PATH . $path)) {
        @mkdir(ROOT_PATH . $path, 0777, true);
    }
    $arr = explode(' ',microtime());
    $num = $arr[0]*10000000000 + $arr[1] - $arr[0]*1000000;
    $num = str_pad($num,14,mt_rand(0,9));
    $num = str_pad($num,15,mt_rand(0,9));
    $token = MD5("id=".$id."&code=".$num);
    $filename = $token.'.png';
    $qrcode = $path.$filename;
    if($platform == 'wxmini'){
        $wechat = new \addons\xiluxc\library\Wechat($platform);
        $res = $wechat->getUnlimited($token,$url);
        if(!$res){
            exception("核销二维码生成失败");
        }
        file_put_contents(ROOT_PATH.$qrcode,$res);
    }else if($platform == 'h5'){
        $qrcode = request()->domain().'/qrcode/build?text='.request()->domain().'/web/'.$url."?scene=".$token;
        return [$token,$num,$qrcode];
    }
    // 上传成功后该文件将被删除，请务必使用临时文件，这里$tempFile为你服务器准备上传的文件
    $tempFile = ROOT_PATH . $qrcode;
    $qrcode = (new \addons\xiluxc\library\Upload())->uploadApi($tempFile);
    $qrcode = cdnurl($qrcode,true);
    return [$token,$num,$qrcode];
}

//转变时间
function xiluxc_date_change($date)
{
    if (!$date) {
        return '';
    }
    $date = (!is_numeric($date)) ? strtotime($date) : $date;
    if (date('Ymd', $date) == date('Ymd', time())) {
        return date('H:i', $date);
    } else if (date('Ymd', $date) == date('Ymd', strtotime('-1 day'))) {
        return "昨天 " . date('H:i', $date);
    } else if (date('Y', $date) == date('Y', time())) {
        return date('m月d日', $date);
    } else {
        return date('Y年m月d日', $date);
    }
}
//转变时间几  前
function xiluxc_time_tran($show_time)
{
    if (!$show_time) {
        return '';
    }
    $show_time = (!is_numeric($show_time)) ? strtotime($show_time) : $show_time;
    $now_time = time();
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $show_time;
    } else {
        if ($dur < 10) {
            return '刚刚';
        } else if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    return xiluxc_date_change($show_time);
                }
            }
        }
    }
}
function xiluxc_time_cha($s_time,$e_time)
{
         $cha = $s_time-$e_time;
         
         //年
         $year = floor($cha  / (31536000));
         if($year>=1){
            return $year."年前";
         }
 
         //月
         $month_total = floor($cha  / 2592000);
         if($month_total>=1){
            return $month_total."月前";
         }
         //日
         $day_total = floor($cha /86400);
        if($day_total>=1){
            return $day_total."天前";
         }
         //时
         $hour_total = floor($cha/3600 );//总相差N小时
        if($hour_total>=1){
            return $hour_total."小时前";
         }
         //分
         $minute_total = floor($cha/60);
        if($minute_total>=1){
            return $minute_total."分钟前";
         }
         //秒
         $second_total = floor($cha);
        if($second_total>=1){
            return $second_total."秒前";
         }
         return '刚刚';
}

/**
 * 获取经纬度
 */
if (!function_exists('xiluxcGetGeo')) {
    /**
     * 根据地址获取经纬度
     */
    function xiluxcGetGeo($address)
    {
        if (empty($address)) {
            return ['status' => 0, 'info' => '地址错误，查询无结果'];
        }
        $lng = $lat = 0;
        $config = \app\common\model\xiluxc\current\Config::getMyConfig("map");
        if(!$config){
            return ['status' => 0, 'info' => '地图配置错误'];
        }
        if ($config['maptype'] == 'baidu') {
            $return = \fast\Http::get('http://api.map.baidu.com/geocoder/v2/', ['address' => $address, 'output' => 'json', 'ak' => $config['baidukey']]);
            $return = json_decode($return);
            if (!$return->status) {
                $lng = $return->result->location->lng;
                $lat = $return->result->location->lat;
                return ['lng' => $lng, 'lat' => $lat, 'status' => 1];
            }
        } else if ($config['maptype'] == 'tencent') {
            $return = \fast\Http::get('https://apis.map.qq.com/ws/geocoder/v1/', ['address' => $address, 'key' => $config['tencentkey']]);
            $return = json_decode($return, true);
            if ($return['status'] == 0) {
                $location = $return['result']['location'];
                return ['lng' => $location['lng'], 'lat' => $location['lat'], 'status' => 1];
            }
        } else {
            $return = \fast\Http::get('https://restapi.amap.com/v3/geocode/geo', ['address' => $address, 'output' => 'json', 'key' => $config['amapkey']]);
            $return = json_decode($return, true);
            if ($return['status']) {
                $location = $return['geocodes'][0]['location'];
                $location_arr = explode(',', $location);
                return ['lng' => $location_arr[0], 'lat' => $location_arr[1], 'status' => 1];
            }
        }
        return ['status' => 0, 'info' => '查询无结果，请检查配置参数'];
    }
}

/*
* 根据经纬度返回地址信息
*/
function xiluxcGetAddr($lat, $lng){
    $config = \app\common\model\xiluxc\current\Config::getMyConfig("map");

    $address = [];
    if ($config['maptype'] == 'baidu') {
        $return = \fast\Http::get("https://api.map.baidu.com/reverse_geocoding/v3/", [
            'location' => "$lat,$lng",
            'coordtype'=> "gcj02ll",
            'ret_coordtype'=>   'gcj02ll',
            'extensions_poi'=> 1,
            'output' => 'json',
            'ak' => $config['baidukey'],
        ]);
        $return = json_decode($return, true);
        if ($return['status'] === 0) {
            $pois = $return['result']['pois'][0] ?? [];
            if($pois){
                $place = [
                    "name"=>$pois['name'],
                    "address"=>$pois['addr'],
                    "latitude"=>$pois['point']['y'],
                    "longitude"=>$pois['point']['x'],
                ];
            }
            $address = [
                'prov' => $return['result']['addressComponent']['province'],
                'city' => $return['result']['addressComponent']['city'],
                'district' => $return['result']['addressComponent']['district'],
                'pois' => $place ?? null
            ];
        }
    } else if ($config['maptype'] == 'tencent') {

        $return = \fast\Http::get("https://apis.map.qq.com/ws/geocoder/v1/", [
            'location' => "$lat,$lng",
            'get_poi'   =>  1,
            'key' => $config['tencentkey'],
        ]);
        $return = json_decode($return, true);
        // var_dump($return);
        // var_dump( $config['tencentkey']);die;
        if ($return['status'] === 0) {
            $pois = $return['result']['address_reference']['landmark_l2'] ?? [];
            if($pois){
                $place = [
                    "name"=>$pois['title'],
                    "address"=>$return['result']['formatted_addresses']['standard_address'],
                    "latitude"=>$pois['location']['lat'],
                    "longitude"=>$pois['location']['lng'],
                ];
            }
            $address = [
                'prov' => $return['result']['address_component']['province'],
                'city' => $return['result']['address_component']['city'],
                'district' => $return['result']['address_component']['district'],
                'pois' => $place ?? null
            ];
        }
    } else {
        var_dump(1);die;
        $return = \fast\Http::get("https://restapi.amap.com/v3/geocode/regeo", [
            'location' => "$lng,$lat",
            'extensions'   => 'all',
            'key' => $config['amapkey'],
        ]);
        $return = json_decode($return, true);
        if ($return['status'] == '1') {
            $pois = $return['regeocode']['pois'][0] ?? [];
            if($pois){
                $location = explode(',',$pois['location']);
                $place = [
                    "name"=>$pois['name'],
                    "address"=>$pois['address'],
                    "latitude"=>$location[1],
                    "longitude"=>$location[0],
                ];
            }
            $address = [
                'prov' => $return['regeocode']['addressComponent']['province'],
                'city' => ($return['regeocode']['addressComponent']['city'] && !empty($return['regeocode']['addressComponent']['city'])) ? $return['regeocode']['addressComponent']['city'] : $return['regeocode']['addressComponent']['province'],
                'district' => $return['regeocode']['addressComponent']['district'],
                'pois' => $place ?? null
            ];
        }
    }
    return $address;
}