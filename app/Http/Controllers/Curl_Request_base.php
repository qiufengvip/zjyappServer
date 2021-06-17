<?php
    namespace App\Http\Controllers;
    class Curl_Request_base
    {
        protected $curl;
        protected $getcookie_url = 'https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn';
        public function __construct(){
            $this->curl = curl_init();//初始化CURL

            curl_setopt($this->curl, CURLOPT_HEADER, 0);//启用时会将头文件的信息作为数据流输出。
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);//设置延迟时间
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);//设置返回结果为字符串流而不是直接输出
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);//验证curl对等证书
            //curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($this->curl, CURLOPT_SSLVERSION,0);


        }

        public function curl_get($url,$header){
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_URL, $url);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);//设置http请求时的协议头

            $data = curl_exec($this->curl);

            if (curl_error($this->curl)) {
                return "Error: " . curl_error($this->curl);
            } else {
                return($data);
            }

        }

        public function curl_post($url,$header,$postdata){

            curl_setopt($this->curl, CURLOPT_URL, $url);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);//设置http请求时的协议头

            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            // curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($postdata));
            // http_build_query
            //print(http_build_query($postdata));
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postdata));
            
            $data = curl_exec($this->curl);
            if (curl_error($this->curl)) {
                return "Error: " . curl_error($this->curl);
            } else {
                return ($data);
            }
        }

        public function Get_cookie($user,$pwd){

            $subdata['userName'] = $user;
            $subdata['userPwd'] = $pwd;
            $subdata['verifyCode'] = '';
            $subdata['clientId'] = md5($user);
            $subdata['sourceType'] = 2;
            $subdata['appVersion'] = $this->Get_version();
            $subdata['equipmentAppVersion'] = $this->Get_version();
            $subdata['equipmentModel'] = 'Android';
            $subdata['equipmentApiVersion'] = '7.2.1';
            $emit = time().'000';
            $header = ['emit:'.$emit,'device:'.$this->GetSecret($subdata['equipmentModel'],$subdata['equipmentApiVersion'],$subdata['equipmentAppVersion'],$emit)];
            //dd($header);
            curl_setopt($this->curl, CURLOPT_HEADER, 1);//其值设置为1即可返回header所有信息包括cookie

            curl_setopt($this->curl, CURLOPT_URL, $this->getcookie_url);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);//设置http请求时的协议头

            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($subdata));

            $data_cookie=[];
            $data = curl_exec($this->curl);
            if (curl_error($this->curl)) {
                echo "Error: " . curl_error($this->curl);
            } else {
                // dd($data);
                $zzbds1 = '#acw_tc=(.*?);#';
                $zzbds2 = '#auth=(.*?);#';

                preg_match($zzbds1,$data,$m);
                preg_match($zzbds2,$data,$m1);
                //strpos 查找字符串第一次出现的位置
                $wz = strpos($data,"Content-Length: ")-1 + strlen('Content-Length: ') + 7;
                $redata = substr($data,$wz);
                //dd($data);
                //$redata = substr_replace($redata,"",'"',1);
                //$redata = substr_replace($redata,"",'"');
                $jsonall = json_decode($redata,TRUE);
                $code = $jsonall['code'];
                //dd($code);
                if($code == -1){
                    return FALSE;
                }

                $temp = 'acw_tc='.$m[1].';'.'auth='.$m1[1];
                $rearr = array($temp,$redata);
                // echo($temp);
                $data_cookie['cookie']=$rearr[0];
                $data_cookie['data']=$redata;
                //dd($data_cookie);
                return $data_cookie;
            }
        }

        public function login($user,$pwd){

            $login_post_data = array(
                'userName'=>$user,
                'userPwd'=>$pwd,
                'verifyCode'=>'',
                'clientId' => md5($user),
                'sourceType' => 2,
                'appVersion' => '2.8.25',
                'equipmentAppVersion' => '2.8.25',
                'equipmentModel' => 'Android',
                'equipmentApiVersion' => '7.2.1'
            );

            $emit = time().'000';
            $login_header = ['emit:'.$emit,'device:'.$this->GetSecret($login_post_data['equipmentModel'],$login_post_data['equipmentApiVersion'],$login_post_data['equipmentAppVersion'],$emit)];

            curl_setopt($this->curl, CURLOPT_URL, $this->getcookie_url);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $login_header);//设置http请求时的协议头

            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($login_post_data));

            $Redata = curl_exec($this->curl);
            return($Redata);
        }

        protected function GetSecret($equipmentModel,$equipmentApiVersion,$equipmentAppVersion,$emit){
            $v1 = md5($equipmentModel);
            $v2 = $v1.$equipmentApiVersion;
            $v3 = md5($v2);
            $v4 = $v3.$equipmentAppVersion;
            $v5 = md5($v4);
            $v6 = $v5.$emit;
            $result = md5($v6);
            return $result;
        }

        protected function Initarr(array $arr){
            $temp='';
            foreach ($arr as $key => $value) {
                $temp =$temp.$key."=".$value.'&';
            }
            return $temp;
        }

        //获取职教云app版本号
        protected function Get_version(){
            $version_info = $this->curl_get("https://zjyapp.icve.com.cn/newmobileapi/AppVersion/getAppVersion",[]);
            $json_version = json_decode($version_info,TRUE);
            return $json_version["data"]["appVersionInfo"]["versionCode"];
        }

        /* 析构函数 */
        function __destruct(){
            curl_close($this->curl);
        }
    }