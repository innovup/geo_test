<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
            function get_addr_string($addr_coords) {
                $url='https://maps.googleapis.com/maps/api/geocode/json?latlng='.$addr_coords.'&sensor=false&language=en&key=AIzaSyAQ73Okrh2_cP58IRSGu_yLLdztqe9NV08';
                $gstr= file_get_contents($url);
                $gjson=  json_decode($gstr,true);
                return($gjson['results'][0]['formatted_address']);
            }
            
            function get_addr_coords($addr_str) {
                $addr_str=preg_replace('/\s+/', '+', $addr_str);
                
                $url='https://maps.googleapis.com/maps/api/geocode/json?address='.$addr_str.'&language=en&key=AIzaSyAQ73Okrh2_cP58IRSGu_yLLdztqe9NV08';
                $gstr= file_get_contents($url);
                $gjson=  json_decode($gstr,true);
                $str=$gjson['results'][0]['geometry']['location']['lat'].','.$gjson['results'][0]['geometry']['location']['lng'];
                return($str);
            }
            
        
            $string = file_get_contents("hubsformap.json");
            $json_a = json_decode($string, true);
            $markers = Array();
            $i=0;
            foreach ($json_a as $key => $val) {
                
                $cords=$val[0].','.$val[1];
                
                $url='https://maps.googleapis.com/maps/api/geocode/json?latlng='.$cords.'&sensor=false&language=en&key=AIzaSyAQ73Okrh2_cP58IRSGu_yLLdztqe9NV08';
                $gstr= file_get_contents($url);
                //echo $gstr;
                $gjson=  json_decode($gstr,true);
                $adr_str=$gjson['results'][0]['formatted_address'];
                
                $lat=$gjson['results'][0]['geometry']['location']['lat'];
                $lng=$gjson['results'][0]['geometry']['location']['lng'];
                
                $cords=$lat.','.$lng;
                
                
                $cord_str=  get_addr_coords($adr_str);
       //         echo $cords==$cord_str.'<br/>';
                if($cords==$cord_str) {
                    $point= array (
                        'addr_string' => $adr_str,
                        'coords' => array (
                                'lat'=>$lat,
                                'lng' => $lng
                            )
                    );
                   
                    $markers[]=$point;
                }    
                                
                if(++$i>=500) {
                    break;
                }
            }
            
            $json=  json_encode($markers);
            echo $json;

            


            
        
        ?>
    </body>
</html>
 