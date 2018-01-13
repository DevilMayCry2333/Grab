<?php
/**
 * Author: jokerl
 * Website: https://jokerl.com
 * 
 */
    function curl_get_https($url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        //test Git
        return $tmpInfo;    //返回json对象
    }
    while(1){
        /**
         * queryZ 访问接口地址，有可能随时变动
         * leftTicketDTO.train_date :要查询的日期
         * leftTicketDTO.from_station: 出发站点
         * leftTicketDTO.to_station=FYS: 终到站点
         * purpose_codes 票的类型
         * 
         */
        $a=curl_get_https("https://kyfw.12306.cn/otn/leftTicket/queryZ?leftTicketDTO.train_date=2018-02-03&leftTicketDTO.from_station=LKS&leftTicketDTO.to_station=FYS&purpose_codes=ADULT");
        $b = json_decode($a,true);
        $res = $b['data']['result'];
        //  var_dump($b);
        $flag = 0;
        $test = "Successfully Discovery => ";

        for($j = 0 ; $j < count($res); $j++){
            $resE = $res[$j];
            $out = explode('|',$resE);
            if(strcmp($out[34],"O0M0O0") == 0)
                if($out[26]=='有' ||$out[30]=='有' || $out[31]=='有' ){
                    $flag =1;
                    echo "\n\n";
                    for($i = 0 ; $i < count($out); $i++){
                        // 13 is Date
                        // 3 is Start
                        //5 is End
                        //26,30,31 is Has Tickets or not
                        if($i==3 || $i==5 || $i==6 || $i==13 ||$i==26 || $i==30 || $i==31){
                            $test = $test.$i;
                            $test = $test.'* ';
                            $test = $test.$out[$i];
                            $test = $test.' ';
                        }
                    }
                }
        }
        if($flag == 1)
            system("java -classpath .:lib/javax.mail.jar:lib/activation.jar Main ".$test);
        var_dump($test);
        sleep(5);
    }
?>