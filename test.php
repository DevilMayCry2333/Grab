<?php
    function curl_get_https($url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }

    while(1){
        $time = $argv[1];
        $left = $argv[2];
        $to = $argv[3];
        $addr = $argv[4];
        $a=curl_get_https("https://kyfw.12306.cn/otn/leftTicket/queryZ?leftTicketDTO.train_date=".$time."&leftTicketDTO.from_station=".$left."&leftTicketDTO.to_station=".$to."&purpose_codes=ADULT");
        // $a = curl_get_https("https://kyfw.12306.cn/otn/leftTicket/queryZ?leftTicketDTO.train_date=2018-01-20&leftTicketDTO.from_station=BJP&leftTicketDTO.to_station=SHH&purpose_codes=ADULT");
    
        // var_dump($a);
        $b = json_decode($a,true);
        $res = $b['data']['result'];
        //  var_dump($b);
        // var_dump($argv);
        $flag = 0;
        $test = "START EXECUTING...";
        for($j = 0 ; $j < count($res); $j++){
            $resE = $res[$j];
            $out = explode('|',$resE);
            // var_dump($out);
            // if(strcmp($out[34],"O040") == 0)
                if($out[23]=='有' ||$out[30]=='有' || $out[31]=='有' ){
                    $flag =1;
                    $test = $test."<==Successfully Discovery ==>";
                    for($i = 0 ; $i < count($out); $i++){
                        // 13 is Date
                        // 3 is Start
                        //5 is End
                        //26,30,31 is Has Tickets or not
                        if($i==3 || $i==4 || $i==5 || $i==8 ||$i==9 || $i==10 || $i==13){
                            if($i==3){
                                $a = "(Train No.)";
                            }
                            if($i==4){
                                $a = "(From Site:)";
                            }
                            if($i==5){
                                $a = "(To Site:)";
                            }
                            if($i==8){
                                $a = "(Left time:)";
                            }
                            if($i==9){
                                $a = "(arrive time:)";
                            }
                            if($i==10){
                                $a = "(Time used:)";
                            }
                            if($i==13){
                                $a = "(Date:)";
                            }
                            $test = $test.$a;
                            $test = $test.'***';
                            $test = $test.$out[$i];
                            $test = $test.' ';
                        }
                    }
                }
        }
        $test = $test."fin";
        var_dump($test);
        if($flag == 1)
            system("java -classpath .:lib/javax.mail.jar:lib/activation.jar Main ".$test." ".$addr);
          
        // var_dump($addr);
        sleep(5);
    }
?>