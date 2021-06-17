<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

/**
 * @desc  服务端第一版
 */
class Api_zjy_Controller extends Controller
{
    protected $Loginurls = 'https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn';


    public function login(Request $request)
    {

        if ($request->isMethod('post')) {
            # code...
            $user = input::get('user');
            $wd = input::get('wd');


            // $subdata=array(
            //     'userName'=>$user,
            //     'userPwd'=>$wd,


            // );

            // dd(get_cookie($this->Longurls,$subdata));


            // $subdata['clientId'] = '6995e464c9c44e7eb9e9fd1a6c82d7fb';//md5($user);
            // $subdata['sourceType'] = 2;
            // $subdata['appVersion'] = '2.8.32';
            // $subdata['equipmentAppVersion'] = '2.8.32';
            // $subdata['equipmentModel'] = 'Android MI 6';
            // $subdata['equipmentApiVersion'] = '5.1.1';

            // //GetSecret($subdata['equipmentModel'],$subdata['equipmentApiVersion'],$subdata['equipmentAppVersion'],$emit),

            // $emit = time().'000';
            // $longheader = ['emit:'.$emit,'device:'.GetSecret($subdata['equipmentModel'],$subdata['equipmentApiVersion'],$subdata['equipmentAppVersion'],$emit)];

            // //dd(GetSecret('Android MI 6','5.1.1','2.8.32','1595585736000'));

            // //  /newMobileAPI/MobileLogin/newSignIn
            // //  $rest = http_request_post("https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn",Initarr($subdata));
            // $rest = hpptRequest_Post("https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn",$longheader,Initarr($subdata));
            //dd($longheader,Initarr($subdata),$rest);
            $rest = Request_Login($user, $wd);
            $jsonall = json_decode($rest, TRUE);
            $code = $jsonall['code'];

            if ($code <> 1) {
                return $rest;
            } else {
                $userid = $jsonall['userId'];
                $re_json = array(
                    'code' => '200',
                    'msg' => '绑定成功',
                    'userid' => $userid
                );
                return response()->json($re_json);
            }
        } else {
            return "非法调用";
        }
    }

    public function Getsubject(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = input::get('user');
            $wd = input::get('wd');
            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);
            //dd($cookies);
            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $resubject = curl_get("https://zjy2.icve.com.cn/api/student/learning/getLearnningCourseList", $header);
            //dd($resubject);
            $jsonfull = json_decode($resubject, TRUE);
            $code = $jsonfull['code'];

            if ($code <> 1) {
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($rejson);
            } else {
                $courlist = $jsonfull['courseList'];

                $rearr = array(
                    'code' => '200',

                );
                foreach ($courlist as $key => $value) {
                    # code...

                    $id = $value['Id'];
                    $courseOpenId = $value['courseOpenId'];
                    $openClassId = $value['openClassId'];
                    $courseName = $value['courseName'];
                    $thumbnail = $value['thumbnail'];
                    $process = $value['process'];

                    $rearr['msg'][$key]['id'] = $id;
                    $rearr['msg'][$key]['courseOpenId'] = $courseOpenId;
                    $rearr['msg'][$key]['openClassId'] = $openClassId;
                    $rearr['msg'][$key]['courseName'] = $courseName;
                    $rearr['msg'][$key]['thumbnail'] = $thumbnail;
                    $rearr['msg'][$key]['process'] = $process;
                }

                return  response()->json($rearr);
            }
        } else {
            return "非法调用";
        }
        ///////////
        //  END  //
        ///////////
    }

    public function Twomonc(Request $request)
    {
        if ($request->isMethod('post')) {

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);
            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );
            $subtomondata = array(
                'currentTime' => '',
                'calendar' => 'month',
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId
            );
            $tre = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/getFaceTeachSchedule", $header, Initarr($subtomondata));
            $mjsonfull = json_decode($tre, TRUE);
            $ccodes = $mjsonfull['code'];
            if ($ccodes <> 1) {
                # code...
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($rejson);
            } else {
                $timelist = $mjsonfull['timeList']; //数组
                //dd(array_unique($mjsonfull['timeList']));

                $begin_time = date('Y-m-d', strtotime('-1 month'));
                $amonth = array(
                    'currentTime' => $begin_time,
                    'calendar' => 'month',
                    'courseOpenId' => $courseOpenId, //post 获取的东西
                    'openClassId' => $openClassId //post 获取的东西
                );
                $atre = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/getFaceTeachSchedule", $header, Initarr($amonth));
                $amjsonfull = json_decode($atre, TRUE);


                $atimelist = $amjsonfull['timeList'];
                $alllist = array_merge($atimelist, $timelist);
                $kelist = [];
                foreach ($alllist as $key => $value) {
                    # code...
                    foreach ($value['faceTeachList'] as $val) {
                        # code...
                        $strs =  json_encode($kelist);
                        $zt = strpos($strs, $val["Id"]);

                        if ($zt == FALSE) {
                            array_push($kelist, $val);
                        }
                    }
                }
                $recode = array(
                    'code' => '200',
                    'msg' => array_reverse($kelist)
                );

                return response()->json($recode);
            }
        } else {
            return "非法调用";
        }
    }


    public function Getclassinfo(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...
            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');

            $logo = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );

            $gcookie = get_cookie($this->Loginurls, $logo);
            //dd($gcookie);
            $arr = array(
                'type' => '2',
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'pageSize' => 50
            );
            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $gcookie
            );
            $retx = request_post('https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo', $header, Initarr($arr));
            $ysjsonfull = json_decode($retx, TRUE);
            $ccode = $ysjsonfull['code'];
            //dd($ysjsonfull);
            if ($ccode <> 1) {
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                response()->json($rejson);
            } else {
                $list = $ysjsonfull['list'];
                $rejsons = array(
                    'code' => '200',

                );
                $i = 0;
                foreach ($list as $key => $value) {
                    # code...
                    try {
                        //code...
                        $datetype = $value['dataType'];
                    } catch (\Throwable $th) {
                        //throw $th;
                        $datetype = NULL;
                    }

                    if ($datetype <> NULL) {
                        # code...
                        $rejsons['msg'][$i]['Id'] = $value['Id'];
                        $rejsons['msg'][$i]['title'] = $value['title'];
                        $rejsons['msg'][$i]['dataType'] = $value['dataType'];
                        $i++;
                    }
                }
            }
            return response()->json($rejsons);
        } else {
            return "非法调用";
        }
    }



    public function GetBrainstorming(Request $request)
    {
        if ($request->isMethod('post')) {


            $token = input::get('token');
            $mark = input::get('mark');
            $brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');
            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );
            $kfdata = array(
                'token' => $token,
                'fid' => 1,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];
            if ($kfcode <> 200) {
                return $kfjsonfull;
            }




            # code...
            $arr = array(
                'userName' => '替换为职教云账号',
                'userPwd' => '替换为职教云密码',
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies,

            );
            $brdata = array(
                'brainStormId' => $brainStormId
            );
            $brete = request_post('https://zjy2.icve.com.cn/api/faceTeach/brainstorm/getParticipationStuDetail', $header, Initarr($brdata));
            $brjaonfull = json_decode($brete, TRUE);
            $brcode = $brjaonfull['code'];
            if ($brcode <> 1) {
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                response()->json($rejson);
            } else {
                $brlist = $brjaonfull['stuList'];
                $jsonre = array(
                    'code' => 200,
                );
                foreach ($brlist as $key => $value) {
                    # code...
                    $docJson = $value['docJson'];
                    //dd($docJson[0]["docOssPreview"]);
                    if (sizeof($docJson) <> 0) {


                        $fjurl = "\n附件地址：" . $docJson[0]['docOssPreview'];
                    } else {
                        $fjurl = NULL;
                    }
                    $jsonre['msg'][$key]['StuName'] = $value['stuName'];
                    $jsonre['msg'][$key]['Content'] = $value['content'] . $fjurl;
                }
                return response()->json($jsonre);
            }
        } else {
            return "非法调用";
        }
    }

    public function GetWorkaw(Request $request)
    {
        if ($request->isMethod('post')) {
            $questionIds = [
                "twvsadisp5nj0ukzmnmdq",
                "hhgadmsb79emaej183itg",
                "twvsadiswqhdzvyzzbctrq",
                "szrdadis3rfo3kx1flslrw",
                "twvsadisz4fdsbbda6yopq",
                "szrdadis94vcdbab2bkmw",
                "4k5eadisz4domqrskqn8jq",
                "onzeadisjzes1cblmxhfw",
                "onzeadissj5hao2ol31xqq",
                "twvsadisc6xiu4kiziezg"
            ];
            return Getanseer("bwdpadmsbp1fs5ydtv38g", $questionIds);
        } else {
            return "非法调用";
        }
    }


    public function Getexam(Request $request)
    {

        if ($request->isMethod('post')) {

            $token = input::get('token');
            $mark = input::get('mark');
            $brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');
            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );
            $kfdata = array(
                'token' => $token,
                'fid' => 3,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );






            $header = array(
                'Content-type: application/x-www-form-urlencoded',

            );
            $brdata = array(
                'examId' => $brainStormId
            );
            $wawrete = request_post('https://zjyapp.icve.com.cn/newmobileapi/onlineexam/previewOnlineExam', $header, Initarr($brdata));
            $awajsonfull = json_decode($wawrete, TRUE);
            $awacode = $awajsonfull['code'];
            if ($awacode <> 1) {
                # code...
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($rejson);
            } else {
                try {
                    //code...
                    $questionsjson = $awajsonfull['data']['questions'];

                    if (empty($questionsjson)) {
                        $remsg = array(
                            'code' => '-2',
                            'msg' => '自己做吧。。。（没有答案）'
                        );
                        return response()->json($remsg);
                    }
                    // // $remsg = array(
                    // //     'code'=>'-2',
                    // //     'msg'=>sizeof($questionsjson)
                    // // );
                    // // return response()->json($remsg);
                    // $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen",$dfheader,Initarr($kfdata));
                    // $kfjsonfull = json_decode($kfre,TRUE);
                    // $kfcode = $kfjsonfull['code'];
                    // if ($kfcode <> 200) {
                    //     return $kfre;
                    // }
                    // $res_json = '';

                    foreach ($questionsjson as $key => $value) {
                        # code...
                        try {
                            //code...
                            $questiontype = $value['questionType'];
                            $res_json = $res_json . Getanseer($questiontype, $questionsjson[$key]);
                        } catch (\Throwable $th) {
                            //throw $th;

                            $res_json = $res_json . "自己做吧。。";
                        }

                        //echo $questiontype;

                    }
                    $retjson = array(
                        'code' => '200',
                        'msg' => $res_json
                    );
                    return response()->json($retjson);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            ///////////



            # code...




        } else {
            return "非法调用";
        }
    }




    public function Signin(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...
            $user = input::get('user');
            $wd = input::get('wd');

            $token = input::get('token');
            $mark = input::get('mark');

            $sign = input::get('sign');

            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');
            $signid = input::get('signid');
            $t = input::get('t');

            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );
            $kfdata = array(
                'token' => $token,
                'fid' => '4',
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );

            $bqdata = array(

                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'signId' => $signid
            );
            // dd($bqdata);
            $bqret = request_post('https://zjy2.icve.com.cn/api/study/faceTeachInfo/stuSign', $header, Initarr($bqdata));
            $bqjsonfull = json_decode($bqret, TRUE);
            $bqcode = $bqjsonfull['code'];
            if ($bqcode <> 1) {
                // $rejson = array(
                //     'code'=>'-2',
                //     'msg'=>'Boom'
                // );
                return response()->json($bqret);
            } else {
                $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
                $kfjsonfull = json_decode($kfre, TRUE);
                $kfcode = $kfjsonfull['code'];
                if ($kfcode <> 200) {
                    return $kfjsonfull;
                }
                $rerejson = array(
                    'code' => 200,
                    'msg' => '提交成功!'
                );
                return response()->json($rerejson);
            }
        } else {
            return "非法调用";
        }
    }


    public function Getallwork(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...

            $user = input::get('user');
            $wd = input::get('wd');

            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);


            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $getworkall = array(
                "courseOpenId" => $courseOpenId,
                "openClassId" => $openClassId,
                "pageSize" => 50,
            );

            $reallwork = request_post("https://zjy2.icve.com.cn/api/study/homework/getHomeworkList", $header, Initarr($getworkall));
            $jsonfull = json_decode($reallwork, TRUE);
            $allwordcode = $jsonfull['code'];

            if ($allwordcode <> 1) {
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($rejson);
            }

            $workalllist = $jsonfull['list'];
            $workrejson = array(
                'code' => '200',
            );
            //dd($jsonfull);
            foreach ($workalllist as $key => $value) {
                # code...
                $workrejson['msg'][$key]['Id'] = $value['Id'];
                $workrejson['msg'][$key]['Title'] = $value['Title'];
            }
            return response()->json($workrejson);
        } else {
            return "非法调用";
        }
    }


    public function Getallexam(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...

            $user = input::get('user');
            $wd = input::get('wd');

            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');

            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);


            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $getworkall = array(
                "courseOpenId" => $courseOpenId,
                "openClassId" => $openClassId,
                "pageSize" => 50,
            );

            $reallwork = request_post("https://zjy2.icve.com.cn/api/study/onlineExam/getOnlineExamList", $header, Initarr($getworkall));
            $jsonfull = json_decode($reallwork, TRUE);
            $allwordcode = $jsonfull['code'];

            if ($allwordcode <> 1) {
                $rejson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($rejson);
            }

            $workalllist = $jsonfull['list'];
            $workrejson = array(
                'code' => '200',
            );
            //dd($jsonfull);
            foreach ($workalllist as $key => $value) {
                # code...
                $workrejson['msg'][$key]['Id'] = $value['Id'];
                $workrejson['msg'][$key]['examTermTimeId'] = $value['examTermTimeId'];
                $workrejson['msg'][$key]['Title'] = $value['Title'];
            }
            return response()->json($workrejson);
        } else {
            return "非法调用";
        }
    }

    public function Nosigninall(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...

            $user = input::get('user');
            $wd = input::get('wd');



            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);


            $header = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $nosigndata = array(
                'currentTime' => '',
                'calendar' => 'month'
            );



            $retxnosignjson = request_post("https://zjy2.icve.com.cn/api/student/faceTeachInfo/getFaceTeachSchedule", $header, Initarr($nosigndata));
            $begin_time = date('Y-m-d', strtotime('-1 month'));

            $snosigndata = array(
                'currentTime' => $begin_time,
                'calendar' => 'month'
            );


            $aretxnosignjson = request_post("https://zjy2.icve.com.cn/api/student/faceTeachInfo/getFaceTeachSchedule", $header, Initarr($snosigndata));

            $dayclass = json_decode($retxnosignjson, TRUE);
            $adayclass = json_decode($aretxnosignjson, TRUE);
            $timelist = $dayclass['timeList'];
            $atimelist = $adayclass['timeList'];


            $alllist = array_merge($atimelist, $timelist);
            $allid = array();
            $courseOpenId = NULL;
            $openClassId = NULL;
            $idds = "";
            foreach ($alllist as $key => $value) {
                # code...
                foreach ($value['faceTeachList'] as $val) {
                    array_push($allid, $val['Id']);
                    $courseOpenId = $val['courseOpenId'];
                    $openClassId = $val['openClassId'];
                }
            }

            $arr = array_unique($allid);
            //var_dump($arr);
            $arr = array_reverse($arr);
            //dd($arr);
            //https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo
            $infoheader = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $resss = array(
                'code' => '200',
            );
            $i = 0;

            foreach ($arr as $key => $value) {
                $idds = $value;
                $infodata = array(
                    'type' => '2',
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'activityId' => $value,
                    'viewType' => '1'
                );
                $faceTeachActivityInfo = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo", $infoheader, Initarr($infodata));
                //dd($faceTeachActivityInfo);
                $infolist = json_decode($faceTeachActivityInfo, TRUE)['list'];
                $classid = json_decode($faceTeachActivityInfo, TRUE)['activityId'];
                if (!empty($infolist)) {
                    //$classid = json_decode($faceTeachActivityInfo,TRUE)['activityId'];
                    # code...
                    foreach ($infolist as $key => $value) {
                        $datatype = "";
                        try {
                            $datatype = $value['dataType'];

                            if ($datatype == "签到") {
                                $stucount = $value['stuCount'];
                                $answerCount = $value['answerCount'];

                                if ($stucount <> 1 or $answerCount <> 1) {
                                    # code...
                                    $IDd = $value['Id'];
                                    $title = $value['title'];

                                    $resss['msg'][$i]['title'] = $title;
                                    $resss['msg'][$i]['Id'] = $IDd;
                                    $resss['msg'][$i]['openclassid'] = $openClassId;
                                }


                                //echo $datatype;

                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                    $i++;
                }
            }


            return response()->json($resss);
        } else {
            return "非法调用";
        }
    }


    public function Getnowclass(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...
            $user = input::get('user');
            $wd = input::get('wd');
            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);


            $headersss = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $arrdddddddd = array(
                'calendar' => 'week',
                //'currentTime'=>'2020-05-07'
            );
            $rest = request_post("https://zjy2.icve.com.cn/api/student/faceTeachInfo/getFaceTeachSchedule", $headersss, Initarr($arrdddddddd));
            // dd($rest);
            $dayclass = json_decode($rest, TRUE);
            $i = 0;
            $resss = array(
                'code' => '200'
            );
            $faceTeachList = $dayclass['faceTeachList'];


            foreach ($faceTeachList as $key => $val) {
                # code...

                $id = $val['Id'];
                $courseOpenId = $val['courseOpenId'];
                $openClassId = $val['openClassId'];
                $infodata = array(
                    'type' => '2',
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'activityId' => $id,
                    'viewType' => '1'
                );
                $faceTeachActivityInfo = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo", $headersss, Initarr($infodata));

                $infolist = json_decode($faceTeachActivityInfo, TRUE)['list'];
                //dd($infolist);
                //$classid = json_decode($faceTeachActivityInfo,TRUE)['activityId'];
                if (!empty($infolist)) {
                    //$classid = json_decode($faceTeachActivityInfo,TRUE)['activityId'];
                    # code...
                    foreach ($infolist as $key => $value) {
                        $datatype = "";
                        try {
                            $datatype = $value['dataType'];
                            if ($datatype == "签到") {

                                $stucount = $value['stuCount'];
                                $answerCount = $value['answerCount'];

                                if ($stucount <> 1 or $answerCount <> 1) {

                                    # code...
                                    $IDd = $value['Id'];
                                    $title = $value['title'];

                                    $resss['msg'][$i]['title'] = $title;
                                    $resss['msg'][$i]['Id'] = $IDd;
                                    $resss['msg'][$i]['openclassid'] = $openClassId;
                                    $resss['msg'][$i]['courseOpenId'] = $courseOpenId;

                                    $resss['msg'][$i]['activityId'] = $id;
                                    // $courseOpenId = $val['courseOpenId'];
                                    // $openClassId = $val['openClassId'];
                                    $i++;
                                }


                                // $IDd = $value['Id'];
                                // $title = $value['title'];

                                // $resss['msg'][$i]['title']=$title;
                                // $resss['msg'][$i]['Id']=$IDd;
                                // $resss['msg'][$i]['activityId']=$classid;
                                //echo $datatype;
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }



                //dd($faceTeachActivityInfo);
            }


            //  $alllist = array_merge($atimelist,$timelist);
            //  $allid = array();
            //  $courseOpenId = NULL;
            //  $openClassId = NULL;
            //  foreach ($alllist as $key => $value) {
            //      # code...
            //      foreach($value['faceTeachList'] as $val){
            //          array_push($allid,$val['Id']);
            //          $courseOpenId = $val['courseOpenId'];
            //          $openClassId = $val['openClassId'];

            //      }

            //  }


            return response()->json($resss);
        } else {
            return "非法调用";
        }
    }


    public function Getdsaw(Request $request)
    {
        if ($request->isMethod('post')) {


            $token = input::get('token');
            $mark = input::get('mark');
            //$brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');

            $user = input::get('user');
            $wd = input::get('wd');

            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');
            $discussId = input::get('discussId');

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );
            $kfdata = array(
                'token' => $token,
                'fid' => 11,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];
            if ($kfcode <> 200) {
                # code...
                return $kfjsonfull;
            } else {
                $logo = array(
                    'userName' => $user,
                    'userPwd' => $wd,
                    'verifyCode' => ''
                );

                $gcookie = get_cookie($this->Loginurls, $logo);
                $getheader = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Cookie: ' . $gcookie
                );
                $senddata = array(
                    "courseOpenId" => $courseOpenId,
                    "openClassId" => $openClassId,
                    "activityId" => $activityId,
                    "discussId" => $discussId,
                );
                $redata = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/getDiscussReplyList", $getheader, Initarr($senddata));
                $alljsons = json_decode($redata, TRUE);
                $ccode = $alljsons['code'];
                if ($ccode <> 1) {
                    $rrejson = array(
                        "code" => 200,
                        "msg" => "Boom"
                    );
                    return response()->json($rrejson);
                }
                try {
                    //code...
                    $replyList = $alljsons['discussInfo']['replyList'];
                } catch (\Throwable $th) {
                    //throw $th;
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "Boom"
                    );
                    return response()->json($rrejson);
                }

                if (empty($replyList)) {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "Boom"
                    );
                    return response()->json($rrejson);
                } else {
                    $rrejsons = array(
                        "code" => 200,
                    );
                    foreach ($replyList as $key => $value) {
                        $rrejsons['msg'][$key]['creatorName'] = $value['creatorName'];
                        $rrejsons['msg'][$key]['content'] = $value['content'];
                    }
                    return response()->json($rrejsons);
                }
            }

            # code...






        } else {
            return "非法调用";
        }
    }





    public function NEWGetdsaw(Request $request)
    {

        // 获取作业考试答案，以弃用
        if ($request->isMethod('post')) {
            # code...
            //https://security.zjy2.icve.com.cn/api/study/homework/preview

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $homeWorkId = input::get('homeWorkId');

            $token = input::get('token');
            $mark = input::get('mark');
            //$brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');

            $kfdata = array(
                'token' => $token,
                'fid' => 2,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );

            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $prwdata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'homeWorkId' => $homeWorkId,
            );

            $retext_ = request_post("https://security.zjy2.icve.com.cn/api/study/homework/preview", $prwhd, Initarr($prwdata));
            $jxjson = json_decode($retext_, TRUE);
            $codes = $jxjson['code'];


            if ($codes <> 100) {
                if ($codes == 1) {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "此题目为教室出题，没有答案 自己做吧。"
                    );
                } else {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "1不在答题 指定时间段"
                    );
                }
                return response()->json($rrejson);
            }

            $redisData = $jxjson['redisData'];
            $questions = json_decode($redisData, TRUE)['questions'];

            $allsretext = '';
            // GETAWAW('ps4rabortqzm7w58rnya');
            foreach ($questions as $key => $value) {
                $questionId = $value['questionId'];
                try {
                    $allsretext = $allsretext . "\n\n" . cutstr_html(GETAWAW($questionId));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            if ($allsretext == NULL) {
                $rrejson = array(
                    "code" => "-2",
                    "msg" => "本作业没有答案（自己做吧！）"
                );
                return response()->json($rrejson);
            }

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );

            /** */
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            } else {

                $rrejson = array(
                    "code" => "200",
                    "msg" => $allsretext
                );
                return response()->json($rrejson);
            }
        } else {
            return "非法调用";
        }
    }



    public function GetnewExamaw(Request $request)
    {
        if ($request->isMethod('post')) {
            # code...
            //https://security.zjy2.icve.com.cn/api/study/homework/preview

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            //$homeWorkId = input::get('homeWorkId');
            $examId = input::get('examId');
            $examTimeId = input::get('examTermTimeId');

            $token = input::get('token');
            $mark = input::get('mark');
            $brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');

            $kfdata = array(
                'token' => $token,
                'fid' => 3,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign

            );

            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $prwdata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'examId' => $examId,
                'examTimeId' => $examTimeId

            );

            $retext_ = request_post("https://security.zjy2.icve.com.cn/api/study/onlineExam/previewNew", $prwhd, Initarr($prwdata));
            // dd($retext_);
            $jxjson = json_decode($retext_, TRUE);
            $codes = $jxjson['code'];
            //dd($prwdata);
            if ($codes == -10) {
                $rrejson = array(
                    "code" => "-2",
                    "msg" => "考试未在有效时间内 无法查看答案"
                );
                return response()->json($rrejson);
            } else if ($codes <> 1) {
                $rrejson = array(
                    "code" => "-2",
                    "msg" => "Boom"
                );
                return response()->json($rrejson);
            }


            // dd($jxjson);
            $redisData = $jxjson['questionData'];
            $questions = json_decode($redisData, TRUE)['questions'];

            $allsretext = '';
            // GETAWAW('ps4rabortqzm7w58rnya');
            foreach ($questions as $key => $value) {
                $questionId = $value['questionId'];
                try {
                    $allsretext = $allsretext . cutstr_html(GETAWAW($questionId));
                } catch (\Throwable $th) {
                    //     //throw $th;
                }
            }

            if ($allsretext == NULL) {
                $rrejson = array(
                    "code" => "-2",
                    "msg" => "本作业没有答案（自己做吧！）"
                );
                return response()->json($rrejson);
            }

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );

            /** */
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            } else {

                $rrejson = array(
                    "code" => "200",
                    "msg" => $allsretext
                );
                return response()->json($rrejson);
            }
        } else {
            return "非法调用";
        }
    }



    /*
    获取考试id
    //返回所有考试id
*/
    public function Resawdata(Request $request)
    {


        if ($request->isMethod('post')) {

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            //$homeWorkId = input::get('homeWorkId');
            $examId = input::get('examId');
            $examTimeId = input::get('examTermTimeId');
            //获取newToken
            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
            );
            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
            );
            $getnewToken = request_post("https://zjy2.icve.com.cn/newmobileapi/mobilelogin/getNewToken", $prwhd, Initarr($arr)); //获取newToken
            $getstuId = request_post("https://zjyapp.icve.com.cn/newmobileapi/mobilelogin/newlogin", $prwhd, Initarr($arr)); //获取stuId
            $json_stuId = json_decode($getstuId, TRUE);
            $stuId = $json_stuId['userId'];
            $json_newToken = json_decode($getnewToken, TRUE);
            $newToken = $json_newToken['newToken'];

            $prwdata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'examId' => $examId,
                'stuId' => $stuId,
                'newToken' => $newToken,

            );

            $retext_ = request_post("https://zjyapp.icve.com.cn/newmobileapi/onlineExam/getNewExamPreviewNew", $prwhd, Initarr($prwdata));
            // dd($retext_);
            $jxjson = json_decode($retext_, TRUE);
            $codes = $jxjson['code'];
            //dd($prwdata);
            if ($codes == -10) {
                $rrejson = array(
                    "code" => "-2",
                    "msg" => "考试未在有效时间内 无法查看答案"
                );
                return response()->json($rrejson);
            } else if ($codes == -4) {
                /*--------------------------------------------------------------------------网页端解析*/
                $arr = array(
                    'userName' => $user,
                    'userPwd' => $wd,
                    'verifyCode' => ''
                );
                $cookies = get_cookie($this->Loginurls, $arr);

                $prwhd = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Cookie: ' . $cookies
                );

                $prwdata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'examId' => $examId,
                    'examTimeId' => $examTimeId

                );


                $retext_ = request_post("https://security.zjy2.icve.com.cn/api/study/onlineExam/previewNew", $prwhd, Initarr($prwdata));
                // dd($retext_);
                $jxjson = json_decode($retext_, TRUE);
                $codes = $jxjson['code'];
                //dd($prwdata);
                if ($codes == -10) {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "考试未在有效时间内 无法查看答案"
                    );
                    return response()->json($rrejson);
                } else if ($codes <> 1) {

                    return response()->json($retext_);
                }

                /*-----------------------------------*/
                $redisData = $jxjson['questionData'];
                $questions = json_decode($redisData, TRUE)['questions'];

                $allsretext = '';
                // GETAWAW('ps4rabortqzm7w58rnya');

                $restdata = array(
                    'code' => '200',

                );
                foreach ($questions as $key => $value) {
                    $questionId = $value['questionId'];
                    $restdata['msg'][$key] = $questionId;
                }

                return response()->json($restdata);


                /*--------------------------------------------------------------------------*/
            } else if ($codes <> 1) {
                // dd($retext_);

                return response()->json($retext_);
            }

            /*-----------------------------------*/
            $redisData = $jxjson['data']['questionJson'];
            $questions = json_decode($redisData, TRUE);

            $allsretext = '';
            // GETAWAW('ps4rabortqzm7w58rnya');

            $restdata = array(
                'code' => '200',

            );
            foreach ($questions as $key => $value) {
                $questionId = $value['questionId'];
                $restdata['msg'][$key] = $questionId;
            }

            return response()->json($restdata);
            /*----------------------------------*/
        } else {
            return "非法调用";
        }
    }

    /***********************************************************/
    public function Reswkawdata(Request $request)
    {
        // 获取作业所有题目的id
        if ($request->isMethod('post')) {

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $homeWorkId = input::get('homeWorkId');


            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $prwdata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'homeWorkId' => $homeWorkId,
            );

            $retext_ = request_post("https://security.zjy2.icve.com.cn/api/study/homework/preview", $prwhd, Initarr($prwdata));
            $jxjson = json_decode($retext_, TRUE);
            $codes = $jxjson['code'];


            if ($codes <> 100) {
                if ($codes == 1) {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "此题目为教室出题，没有答案 自己做吧。"
                    );
                } else {
                    $rrejson = array(
                        "code" => "-2",
                        "msg" => "不在答题 指定时间段"
                    );
                }
                return response()->json($rrejson);
            }

            $redisData = $jxjson['redisData'];
            $questions = json_decode($redisData, TRUE)['questions'];

            $allsretext = '';

            $resawwid = array(
                'code' => 200,

            );
            // GETAWAW('ps4rabortqzm7w58rnya');
            foreach ($questions as $key => $value) {
                $questionId = $value['questionId'];
                try {
                    $resawwid['msg'][$key] = $questionId;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            return response()->json($resawwid);




            /*----------------------------------*/
        } else {
            return "非法调用";
        }
    }

    public function Restextdata(Request $request)
    {
        //返回测验id
        if ($request->isMethod('post')) {

            $user = input::get('user');
            $wd = input::get('wd');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');
            $classTestId = input::get('classTestId');

            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            // $cookies = get_cookie("https://zjyapp.icve.com.cn/newmobileapi/mobilelogin/newlogin",$arr);
            $prwdata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'classTestId' => $classTestId,
            );

            $retext_ = request_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/testPreview", $prwhd, Initarr($prwdata));
            $jxjson = json_decode($retext_, TRUE);
            $codes = $jxjson['code'];


            if ($codes <> 1) {
                return response()->json($jxjson);
            }

            $questions = $jxjson['bigQuestions'];

            $allsretext = '';

            $resawwid = array(
                'code' => 200,

            );
            // GETAWAW('ps4rabortqzm7w58rnya');
            foreach ($questions as $key => $value) {
                $questionId = $value['QuesetionId'];
                try {
                    $resawwid['msg'][$key] = $questionId;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            return response()->json($resawwid);
        } else {
            return "非法调用";
        }
    }
    /*
        获取 所有答案
    */
    public function Resallaw(Request $request)
    {

        //获取答案的
        if ($request->isMethod('post')) {
            $allsretext = NULL;

            $user = input::get('user');
            $examId = input::get('examId');
            $questionId = input::get('questionId');
            $token = input::get('token');
            $sign = input::get('sign');
            $t = input::get('t');


            $mark = $user . $examId;
            $kfdata = array(
                'token' => $token,
                'fid' => 3,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign
            );

            // $allsretext = GETAWAW($questionId);
            // dd($allsretext);
            try {
                $questionId = json_decode($questionId, TRUE);
                $allsretext = GETAWAW($questionId["msg"]);
            } catch (\Throwable $th) {
                //throw $th;
            }

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',
            );

            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code']; //返回单个题目答案


            $resadaan = array(
                'code' => 200,
                'msg' => $allsretext
            );
            if ($kfcode <> 200) {
                return $kfre;
            } else {


                return response()->json($resadaan);
            }
        } else {
            return "非法调用";
        }
    }



    //并发请求
    function EruptRequest($nodes)
    {
        $mh = curl_multi_init(); // 创建批处理cURL句柄
        $cURLs = [];  //curl句柄组
        $datas = [];  //数据组
        //  $cookie = $this->curl->Get_cookie("替换为职教云账号","替换为职教云密码");
        $arr = array(
            'userName' => '替换为职教云账号',
            'userPwd' => '替换为职教云密码',
            'verifyCode' => ''
        );
        $cookies = get_cookie('https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn', $arr);
        // dd($cookie);
        $header = ['cookie:' . $cookies];

        $url = 'https://security.zjy2.icve.com.cn/api/design/question/editQuestion';
        foreach ($nodes as $key => $value) {
            $datas[$key] = ['questionId' => $value];
            $cURLs[$key] = curl_init();  //创建一个cURL资源
            curl_setopt($cURLs[$key], CURLOPT_URL, $url);
            curl_setopt($cURLs[$key], CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
            // curl_setopt($cURLs[$key], CURLOPT_TIMEOUT, 1);//设置延迟时间
            curl_setopt($cURLs[$key], CURLOPT_RETURNTRANSFER, 1); //设置返回结果为字符串流而不是直接输出
            curl_setopt($cURLs[$key], CURLOPT_SSL_VERIFYPEER, false); //验证curl对等证书
            curl_setopt($cURLs[$key], CURLOPT_HTTPHEADER, $header); //设置http请求时的协议头
            curl_setopt($cURLs[$key], CURLOPT_POST, 1);  //设置请求方式为POST
            curl_setopt($cURLs[$key], CURLOPT_POSTFIELDS, $datas[$key]);  //传输数据
            curl_multi_add_handle($mh, $cURLs[$key]); //加入请求流
        }
        // 执行批处理句柄  等于0的时候就就结束了
        $running = null;
        curl_multi_exec($mh, $running);
        do {
            usleep(1000);
            curl_multi_exec($mh, $running);
        } while ($running > 0);


        $res = array();
        foreach ($cURLs as $i => $url) {
            //遍历获取请求结果
            $data = curl_multi_getcontent($url);
            // dd($data);

            $allsretext = GETAWAW($data);

            $res[$i] = $allsretext;
        }

        foreach ($cURLs as $i => $url) {
            curl_multi_remove_handle($mh, $url); //关闭句柄
        }
        curl_multi_close($mh);
        return $res;
    }



    /*
        路由:api/zskz/
        功能:职教云刷课 扣积分
        user:Luan Shi Liu Nian
    */

    public function zskz(Request $request)
    {
        if ($request->isMethod('post')) {

            $openClassId = input::get('openclassid');
            $user = input::get('user');
            $token = input::get('token');
            $sign = input::get('sign');
            $t = input::get('t');


            $mark = $user . $openClassId;
            $kfdata = array(
                'token' => $token,
                'fid' => 12,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign
            );

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );

            /** */
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            $kfsucessre = array(
                'code' => 200
            );

            return Response()->json($kfsucessre);
        } else {
            return "非法调用";
        }
    }

    /*
        慕课 扣分
    */
    public function moocf(Request $request)
    {
        if ($request->isMethod('post')) {

            $openClassId = input::get('openclassid');
            $user = input::get('user');
            $token = input::get('token');
            $sign = input::get('sign');
            $t = input::get('t');


            $mark = $user . $openClassId;
            $kfdata = array(
                'token' => $token,
                'fid' => 13,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign
            );

            $dfheader = array(
                'Content-type: application/x-www-form-urlencoded',

            );

            /** */
            $kfre = request_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $dfheader, Initarr($kfdata));
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            $kfsucessre = array(
                'code' => 200
            );

            return Response()->json($kfsucessre);
        } else {
            return "非法调用";
        }
    }

    /*
        慕课 获取课程
    */

    public function mooczxkc(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = input::get('user');
            $wd = input::get('wd');


            $arr = array(
                'userName' => $user,
                'userPwd' => $wd,
                'verifyCode' => ''
            );
            $cookies = get_cookie($this->Loginurls, $arr);

            $prwhd = array(
                'Content-type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies
            );

            $gett = curl_get("https://mooc.icve.com.cn/portal/Course/getMyCourse?isFinished=0&page=1&pageSize=8", $prwhd);
            $jsonst = json_decode($gett, TRUE);
            $ccoodde = $jsonst['code'];

            if ($ccoodde <> 1) {
                $reestat = array(
                    'code' => -2,
                    'msg' => '获取课程异常'
                );
                return Response()->json($reestat);
            }

            $reestat = array(
                'code' => 200,

            );

            $listsst = $jsonst['list'];
            foreach ($listsst as $key => $value) {
                $courseName = $value['courseName'];
                $thumbnail = $value['thumbnail'];
                $courseOpenId = $value['courseOpenId'];
                $process = $value['process'];

                $reestat['msg'][$key]['courseName'] = $courseName;
                $reestat['msg'][$key]['thumbnail'] = $thumbnail;
                $reestat['msg'][$key]['courseOpenId'] = $courseOpenId;
                $reestat['msg'][$key]['process'] = $process;
            }

            return Response()->json($reestat);
            // dd($gett);
        } else {
            return "非法调用";
        }
    }
    //到底了

}

//
//                       _oo0oo_
//                      o8888888o
//                      88" . "88
//                      (| -_- |)
//                      0\  =  /0
//                    ___/`---'\___
//                  .' \\|     |// '.
//                 / \\|||  :  |||// \
//                / _||||| -:- |||||- \
//               |   | \\\  -  /// |   |
//               | \_|  ''\---/''  |_/ |
//               \  .-\__  '-'  ___/-. /
//             ___'. .'  /--.--\  `. .'___
//          ."" '<  `.___\_<|>_/___.' >' "".
//         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
//         \  \ `_.   \_ __\ /__ _/   .-` /  /
//     =====`-.____`.___ \_____/___.-`___.-'=====
//                       `=---='
//
//
//     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
//               佛祖保佑         永无BUG
//
//
//


//初始化数组
function Initarr(array $arr)
{

    $temp = '';
    foreach ($arr as $key => $value) {
        # code...
        $temp = $temp . $key . "=" . $value . '&';
    }
    return $temp;
}
//post 请求
function http_request_post($url, $post_data)
{
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $post_data,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}
//curl 获取 并 取得cookie
function curl_post($url, array $header, $postdata)
{

    // $header = array(
    //     'Accept: application/json',
    //  );

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($curl, CURLOPT_HEADER, 1);
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        // 打印返回的内容



        $zzbds1 = '#acw_tc=(.*?);#';
        $zzbds2 = '#auth=(.*?);#';

        preg_match($zzbds1, $data, $m);
        preg_match($zzbds2, $data, $m1);

        $wz = strpos($data, "Content-Length: ") + strlen('Content-Length: ') + 7;
        $redata = substr($data, $wz);
        $temp = 'acw_tc=' . $m[1] . ';' . 'auth=' . $m1[1];


        $rearr = array($temp, $redata);
        // echo($temp);
        return $rearr;
        curl_close($curl);
    }
}


/** */

function hpptRequest_Post($url, array $header, $postdata)
{

    //var_dump($header);
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0); //在C++中可以禁止自动输出
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //将协议头返回
    // curl_setopt($curl, CURLOPT_HEADER,1);
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        // 打印返回的内容



        // $zzbds1 = '#acw_tc=(.*?);#';
        // $zzbds2 = '#auth=(.*?);#';

        // preg_match($zzbds1,$data,$m);
        // preg_match($zzbds2,$data,$m1);

        // $wz = strpos($data,"Content-Length: ") + strlen('Content-Length: ') + 7;
        // $redata = substr($data,$wz);
        // $temp = 'acw_tc='.$m[1].';'.'auth='.$m1[1];


        // $rearr = array($temp,$redata);
        // echo($temp);
        return $data;
        curl_close($curl);
    }
}
//curl get
function curl_get($url, array $header)
{
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 超时设置,以秒为单位
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //执行命令
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        // 打印返回的内容
        return $data;
        curl_close($curl);
    }
}

/*
clientId=6995e464c9c44e7eb9e9fd1a6c82d7fb
&sourceType=2
&userPwd=
&userName=
&appVersion=2.8.23
&equipmentAppVersion=2.8.23
&equipmentModel=Android OPPO R11
&equipmentApiVersion=5.1.1 
*/

function get_cookie($url, array $subdata)
{
    Auto_get_version();
    $arrfll = array("");

    $usermd5 = md5($subdata['userName']);

    $subdata['clientId'] = $usermd5;
    $subdata['sourceType'] = 2;
    $subdata['appVersion'] = Auto_get_version();
    $subdata['equipmentAppVersion'] = Auto_get_version();
    $subdata['equipmentModel'] = 'Android';
    $subdata['equipmentApiVersion'] = '7.2.1';
    $emit = time() . '000';
    $header = ['emit:' . $emit, 'device:' . GetSecret($subdata['equipmentModel'], $subdata['equipmentApiVersion'], $subdata['equipmentAppVersion'], $emit)];


    //dd($subdata);


    try {
        //code...
        $arrfll = curl_post($url, $header, Initarr($subdata));
        //dd($arrfll);
    } catch (\Throwable $th) {
        //throw $th;
    }

    return $arrfll[0];
}


function request_post($url, array $header, $postdata)
{

    // $header = array(
    //     'Content-type: application/x-www-form-urlencoded',
    //  );

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    //curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

    // curl_setopt($curl,CURLOPT_HEADER,1);
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {

        return $data;
        curl_close($curl);
    }
}

function Getanseer($examId, $questionIds)
{
    /*
        1.单选√
        2.多选
            
        3.判断√
        4.填空

        5.填空（主观）

        6.问答√

        7.匹配


        8.阅读理解
        9.完形填空
        10.文件做答题
        11.试听题
        
    */
    $aansw = '';

    $header = array(
        'Content-type: application/x-www-form-urlencoded',
        'cookie: Hm_lvt_a3821194625da04fcd588112be734f5b=1612607368; acw_tc=2f624a1516142373843443227e3177ee7608d06007fd50a37ffdb61055ed8a; verifycode=3568D9D73227967C4577E4D96DBF3F79@637498629849469809; auth=010218B5AF445DD9D808FE18C55B16B1D9D8080116750069003700680061006C006F00720034006B003100700061006C006F006C007A003300720078007100670000012F00FFF88DC147777B2F10C6A1F2F3906CA2BD7741781B; token=h7f7anqsmijfmdv5xj9eq; ui7halor4k1palolz3rxqgpageSize=50',
    );
    $brdata = array(
        'courseOpenId' => "uw1aadisiabhzzpzjicqna",
        "examId" => $examId,
    );
    $wawrete = request_post('https://mooc.icve.com.cn/design/onlineExam/onLineExamPreview', $header, Initarr($brdata));

    $awajsonfull = json_decode($wawrete, TRUE);
    // dd($wawrete);
    $awacode = $awajsonfull['code'];
    if ($awacode <> 2) {
        $rejson = array(
            'code' => '-2',
            'msg' => 'Boom'
        );
        return response()->json($rejson);
    } else {
        // try {
        $questionsjson = $awajsonfull['paperData']['questions'];
        $res_json = '';
        foreach ($questionsjson as $key => $value) {
            // try {
            $questiontype = $value['questionType'];
            $type = $questiontype;
            $context = $questionsjson[$key];
            switch ($type) {
                case 1: //单选
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    foreach ($context['answerList'] as $key => $value) {
                        array_push($option, chr(65 + $key) . ":" . $value['Content']);
                        if ($value['IsAnswer'] === "true") {
                            array_push($answer, chr(65 + $key));
                        }
                    }
                    $formatting  = "【单选题】" . $title . "<br>[选项]<br>";

                    foreach ($option as $key => $value) {
                        $formatting = $formatting . $value . "<br>";
                    }
                    $formatting = $formatting . "<br>[答案]<br>";
                    foreach ($answer as $key => $value) {
                        $formatting = $formatting . $value . "&emsp;";
                    }
                    $formatting = $formatting . "<hr>";

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($formatting);
                    // dd($result);
                    break;
                case 2:  //多选
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    foreach ($context['answerList'] as $key => $value) {
                        array_push($option, chr(65 + $key) . ":" . $value['Content']);
                        if ($value['IsAnswer'] === "true") {
                            array_push($answer, chr(65 + $key));
                        }
                    }
                    $formatting  = "【多选题】" . $title . "<br>[选项]<br>";

                    foreach ($option as $key => $value) {
                        $formatting = $formatting . $value . "<br>";
                    }
                    $formatting = $formatting . "<br>[答案]<br>";
                    foreach ($answer as $key => $value) {
                        $formatting = $formatting . $value . "&emsp;";
                    }
                    $formatting = $formatting . "<hr>";

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($formatting);
                    // dd($result);
                    break;

                case 3:  //判断
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = "";  //答案
                    $formatting = "";  //格式化完成结果

                    $answercode = $context['Answer'];
                    $formatting = "【判断题】:" . $title . "<br>" . "[答案]<br>";
                    if ($answercode == 1) {
                        $formatting = $formatting . "√";
                        $answer = "√";
                    } else {
                        $formatting = $formatting . "×";
                        $answer = "×";
                    }
                    $formatting = $formatting . "<hr>";

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($formatting);
                    // dd($result);
                    break;
                case 4:   //填空

                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $title = $context['Title'];
                    $formatting = "【填空题(客观)】:" . $title . "<br>" . "[答案]<br>";
                    $answercode = $context['answerList'];
                    foreach ($answercode as $key => $value) {
                        $formatting  = $formatting . $value['Content'] . "<br>";
                        array_push($answer, $value['Content']);
                    }
                    $formatting = $formatting . "<hr>";
                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    print_r($formatting);
                    dd($result);

                    break;
                case 5:  //填空
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $title = $context['Title'];
                    $formatting = "【填空题(主观)】:" . $title . "<br>" . "[答案]<br>";
                    $answercode = $context['answerList'];
                    foreach ($answercode as $key => $value) {
                        $formatting  = $formatting . $value['Content'] . "<br>";
                        array_push($answer, $value['Content']);
                    }
                    $formatting = $formatting . "<hr>";
                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($formatting);
                    // dd($result);
                    break;
                case 6:   //问答
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $answer = $context['Answer'];
                    $formatting = "[问答题]" . $context['Title'] . "<br>" . "[答案]：" . $answer;
                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($formatting);
                    // dd($result);
                    break;

                case 7:   //匹配
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $dxjson = json_decode($context['dataJson'], TRUE); //匹配json
                    $answercode = json_decode($context['Answer'], TRUE);
                    $formatting = "【匹配题】:" . $title . "<br>" . "[答案]<br>";
                    foreach ($context['answerList'] as $key => $value) {
                        array_push($option, [$value["OptionContent"] => $answercode[(int)$key]['optionAnswerContent']]);
                    }
                    foreach ($dxjson as $key => $value) {
                        $openselectcontent = $value['optionSelectContent'];
                        $answercontent = $value['optionContent']; //题目
                        $optioncontent = $answercode[(int)$openselectcontent]['optionAnswerContent'];
                        $formatting = $formatting . $answercontent . "<->" . $optioncontent . "<br>";
                        array_push($answer, [$answercontent => $optioncontent]);
                    }

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );

                    // print_r($formatting);
                    // dd($result);

                    break;
                case 8:  //阅读理解

                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果



                    $formatting  = "【阅读理解】:" . $title;

                    $questionId =  $context['questionId'];
                    $subquestion = $awajsonfull['paperData']['subQuestions'];
                    foreach ($subquestion as $key => $value) {
                        if ($value['QuestionId'] != $questionId) {
                            continue;
                        }

                        $values = $value["subQuestion"][0];
                        $subtype = $values['subQuestionType'];
                        switch ($subtype) {
                            case 1: //单选题
                                $m_title = $values['Title'];
                                $formatting = $formatting . "【单选题】" . $m_title . "<br>[选项]<br>";
                                $mm_option = array();
                                $mm_answer = "";
                                foreach ($values['subAnswerList'] as $key => $value) {
                                    array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                    $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                    if ($value['IsAnswer'] === true) {

                                        $mm_answer = $mm_answer . chr(65 + $key);
                                    }
                                }

                                array_push($answer, $mm_answer);
                                $formatting  = $formatting  . "[答案]<br>" . $mm_answer . "<br><br>";
                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);
                                break;
                            case 2:  //多选题目
                                $m_title = $values['Title'];
                                $formatting = $formatting . "【多选题】" . $m_title . "<br>[选项]<br>";
                                $mm_option = array();
                                $mmm_answer = array();
                                $mm_answer = "";
                                foreach ($values['subAnswerList'] as $key => $value) {
                                    array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                    $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                    if ($value['IsAnswer'] === true) {
                                        array_push($mmm_answer, chr(65 + $key));
                                        $mm_answer = $mm_answer . "&emsp;" . chr(65 + $key);
                                    }
                                }
                                array_push($answer, $mmm_answer);


                                $formatting  = $formatting  . "[答案]<br>" . $mm_answer . "<br><br>";
                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);

                                // print_r($formatting);
                                // dd();
                                break;
                            case 3:
                                $m_title = $values['Title'];
                                $mm_option = array();  //选项
                                $mm_answer = "";       //答案



                                if ($values['questionAnswer'] == 1) {
                                    $mm_answer = "√";
                                } else {
                                    $mm_answer = "×";
                                }
                                $formatting  =  $formatting . "<br>[判断题]:" . $m_title . "<br>" . "【答案】<br>" . $mm_answer . "<br>";
                                array_push($answer, $mm_answer);

                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($result["formatting"]);
                    // dd($result);

                    break;


                case 9:
                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $formatting  = "【完形填空】:" . $title;

                    $aansw = $aansw . $title;
                    $questionId =  $context['questionId'];
                    $subquestion = $awajsonfull['paperData']['subQuestions'];

                    foreach ($subquestion as $key => $value) {
                        if ($value['QuestionId'] != $questionId) {
                            continue;
                        }

                        $values = $value["subQuestion"][0];
                        $subtype = $values['subQuestionType'];
                        switch ($subtype) {
                            case 1: //单选题
                                $m_title = $values['Title'];
                                $formatting = $formatting . "【单选题】" . $m_title . "<br>[选项]<br>";
                                $mm_option = array();
                                $mm_answer = "";
                                foreach ($values['subAnswerList'] as $key => $value) {
                                    array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                    $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                    if ($value['IsAnswer'] === true) {

                                        $mm_answer = $mm_answer . chr(65 + $key);
                                    }
                                }

                                array_push($answer, $mm_answer);
                                $formatting  = $formatting  . "[答案]<br>" . $mm_answer . "<br><br>";
                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);
                                break;
                            case 2:  //多选题目
                                $m_title = $values['Title'];
                                $formatting = $formatting . "【多选题】" . $m_title . "<br>[选项]<br>";
                                $mm_option = array();
                                $mmm_answer = array();
                                $mm_answer = "";
                                foreach ($values['subAnswerList'] as $key => $value) {
                                    array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                    $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                    if ($value['IsAnswer'] === true) {
                                        array_push($mmm_answer, chr(65 + $key));
                                        $mm_answer = $mm_answer . "&emsp;" . chr(65 + $key);
                                    }
                                }
                                array_push($answer, $mmm_answer);


                                $formatting  = $formatting  . "[答案]<br>" . $mm_answer . "<br><br>";
                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);

                                // print_r($formatting);
                                // dd();
                                break;
                            case 3:
                                $m_title = $values['Title'];
                                $mm_option = array();  //选项
                                $mm_answer = "";       //答案



                                if ($values['questionAnswer'] == 1) {
                                    $mm_answer = "√";
                                } else {
                                    $mm_answer = "×";
                                }
                                $formatting  =  $formatting . "<br>[判断题]:" . $m_title . "<br>" . "【答案】<br>" . $mm_answer . "<br>";
                                array_push($answer, $mm_answer);

                                $option_m = array(
                                    "title" => $m_title,
                                    "option" => $mm_option
                                );
                                array_push($option, $option_m);
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    // print_r($result["formatting"]);
                    // dd($result);





                    break;

                case 11:

                    $questionId = $questionIds[$key];  //职教云题目ID
                    $moocquestionId = $context['questionId'];   //mooc 题目ID
                    $title = $context['Title'];  //标题
                    $option = array(); //选项
                    $answer = array();  //答案
                    $formatting = "";  //格式化完成结果

                    $formatting = $formatting . "<br>【试听题】<br>" . $title;


                    $questionId =  $context['questionId'];
                    $subquestion = $awajsonfull['paperData']['subQuestions'];

                    foreach ($subquestion as $key => $value) {
                        # code...

                        if ($value['QuestionId'] != $questionId) {
                            continue;
                        }



                        $values = $value["subQuestion"][0];

                        $m_title = $values['Title'];


                        // $option ;// 选项
                        // $answer;  //答案
                        $m_option = array();  //选项
                        $m_z_answer = ""; //答案
                        $mm_option = array();  //选项

                        $formatting = $formatting . "<br>【单选题】:" . $m_title . "<br>[选项]<br>";

                        foreach ($values['subAnswerList'] as $key => $value) {
                            array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                            $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                            if ($value['IsAnswer'] === true) {
                                $m_z_answer = chr(65 + $key);
                            }
                        }
                        $formatting = $formatting . $m_z_answer;
                        array_push($answer, $m_z_answer);

                        $m_option = array(
                            "title" => $m_title,
                            "option" => $mm_option
                        );


                        array_push($option, $m_option);
                    }

                    $result = array(
                        "questionId" => $questionId,
                        "moocquestionId" => $moocquestionId,
                        "title" => $title,
                        "option" => $option,
                        "answer" => $answer,
                        "formatting" => $formatting,
                    );
                    print_r($result["formatting"]);
                    dd($result);



                    break;

                default:
                    # code...
                    break;
            }




            $aansw = $aansw . "<hr>";


            // } catch (\Throwable $th) {

            // }

            //echo $questiontype;

        }
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }



    return $aansw;
}


function pushQuestion($questionId, $moocquestionId, $title, $option, $answer, $formatting)
{
    /**
     * 题目入库
     * 2021年2月25日15:40:19
     * 
     * 
     * $questionId          题目ID
     * $moocquestionId      Mooc 题目ID 
     * $title               题目标题
     * $option              选项
     * $answer              答案
     * $formatting          格式化结果
     */
}


// 解析单个作业答案
function GETAWAW($daan)
{

    // dd($daan);

    // printf($daan);
    $question = json_decode($daan, TRUE)['question'];
    $QuestionTypes = $question['QuestionType'];
    $alltitle = $question['allTitle'];
    $tx = '';
    switch ($QuestionTypes) {
        case 6:
        case 5:
        case 4:
            //填空题
            # code...
            if ($QuestionTypes == 6) {
                $tx = '问答题';
            } elseif ($QuestionTypes == 5 or $QuestionTypes == 4) {
                $tx = '填空题';
            }
            $Answer = $question['Answer'];
            return "[" . $tx . "：]" . $alltitle . "<br>答案=>" . $Answer;
            break;
        case 3:
            $Answer = $question['Answer'];
            if ($Answer == 1) {
                return "[判断题：]" . $alltitle . "<br>答案=>√";
            }
            return "[判断题：]" . $alltitle . "<br>答案=>×";
            break;

        case 7:
            $Answer = $question['Answer'];
            $jsonaw = json_decode($Answer, TRUE);

            $answerlist = json_decode($daan, TRUE)['answerList'];

            $endawtext = '';

            foreach ($answerlist as $key => $value) {
                $optioncontents = $value['OptionContent'];
                $optionselectcontent = $value['OptionSelectContent'];

                foreach ($jsonaw as $key => $val) {
                    $SortOrder = $val['SortOrder'];
                    if ($SortOrder == $optionselectcontent) {
                        $endawtext = $endawtext . "<br>" . $optioncontents . '&nbsp;=>&nbsp;' . $val['optionAnswerContent'];
                    }
                }
            }

            return "[匹配题：]" . $alltitle . "<br>[答案：]" . $endawtext;
            break;

        case 11:
            $allawtext_ = '';
            $titles = '';
            $SunQuestionList = json_decode($daan, TRUE)['subQuestionList'];
            foreach ($SunQuestionList as $key1 => $value) {
                $titles = $value['Title'];
                $datajson = json_decode($value['DataJson'], TRUE);
                $Answer = $value['Answer'];
                foreach ($datajson as $key => $vals) {
                    if ($vals['SortOrder'] == $Answer) {
                        $allawtext_ = $allawtext_ . "[听力单选]" . $titles . "答案=>" . $vals['Content'] . "<br><br>";
                    }
                }
            }

            return "<b>[听力：]</b><br>" . $allawtext_;
            break;
        case 9:
        case 8:
            $aansw = '';
            $jsondaan = json_decode($daan, TRUE);

            if ($QuestionTypes == 8) {
                $aansw = $aansw . "[阅读理解]";
            } else {
                $aansw = $aansw . "[完形填空]";
            }
            //$answercode = json_decode($context['answer'],TRUE);

            $subquestion = $jsondaan['subQuestionList'];
            foreach ($subquestion as $key => $value) {
                # code...
                $subtype = $value['SubQuestionType'];
                switch ($subtype) {
                    case 1:
                        # code...
                        $title = $value['TitleText'];
                        $datajson = json_decode($value['DataJson'], TRUE);
                        $subanswer = $value['Answer'];


                        foreach ($datajson as $key => $value) {
                            # code...

                            if ($value['SortOrder'] == $subanswer) {
                                $aansw = $aansw . "<br>[单选题]:" . $title . "<br>" . "【答案1】" . (string)$value['Content'] . "<br>";
                            }
                        }

                        break;
                    case 2:
                        # code...
                        $title = $value['TitleText'];
                        $datajson = json_decode($value['DataJson'], TRUE);
                        $subanswer = $value['Answer'];


                        //echo $datajson;

                        $answ = explode(',', $subanswer);
                        $aansw = $aansw . "[多选题]:" . $title . "<br>" . "【答案】<br>";
                        foreach ($answ as $key => $value1) {
                            # code...
                            foreach ($datajson as $key => $suba) {
                                # code...
                                if ($suba['SortOrder'] == $value1) {
                                    # code...
                                    $aansw = $aansw . $suba['Content'] . '&nbsp;&nbsp;';
                                }
                            }
                        }


                        break;

                    case 3:
                        # code...


                        $title = $value['TitleText'];

                        $subanswer = $value['Answer'];


                        $aansw = $aansw . "[判断题]:" . $title . "<br>" . "【答案】<br>";
                        if ($subanswer == 1) {
                            $aansw = $aansw . "√";
                        } else {
                            $aansw = $aansw . "×";
                        }

                        //$aansw = "【答案】<br>".$aansw;
                        break;

                    default:
                        # code...
                        break;
                }
            }






            return $aansw;
            /******************************/
            break;


        case 2:
            $Answer = $question['Answer'];
            $aawarr = explode(',', $Answer);
            $answerListt = json_decode($daan, TRUE)['answerList'];
            $rretext_ = '';
            foreach ($aawarr as $key => $value) {
                //dd(cutstr_html($answerListt[intval($value)]['Content']));
                $rretext_ = $rretext_ . "<br>" . $answerListt[intval($value)]['Content'] . ',';
            }
            return "[多选题：]" . $alltitle . "<br>[答案=>]" . $rretext_ . "<br>";
            break;
        case 1:
            $answerListsst = json_decode($daan, TRUE)['answerList'];
            $Anser_ = $question['Answer'];
            return "[单选题：]" . $alltitle . "<br>" . "[答案：=>]" . $answerListsst[intval($Anser_)]['Content'];

            //return "-----------------dx---------------------";
            break;
            /******** */
        default:

            return NULL;
            break;
    }
}

function cutstr_html($string)
{

    $string = strip_tags($string);

    $string = trim($string);
    $string = str_replace("&nbsp;", "", $string);


    return trim($string);
}

function GetSecret($equipmentModel, $equipmentApiVersion, $equipmentAppVersion, $emit)
{
    $v1 = md5($equipmentModel);
    $v2 = $v1 . $equipmentApiVersion;
    $v3 = md5($v2);
    $v4 = $v3 . $equipmentAppVersion;
    $v5 = md5($v4);
    $v6 = $v5 . $emit;
    $result = md5($v6);
    return $result;
}
/* 访问登录接口返回 cookie 和 newtoken */
function Request_Login($user, $wd)
{
    $subdata = array();
    $subdata['userName'] = $user;
    $subdata['userPwd'] = $wd;
    $subdata['clientId'] = '6995e464c9c44e7eb9e9fd1a6c82d7fb'; //不知道是啥
    $subdata['sourceType'] = 2;
    $subdata['appVersion'] = Auto_get_version();
    $subdata['equipmentAppVersion'] = Auto_get_version();
    $subdata['equipmentModel'] = 'Android MI 6';
    $subdata['equipmentApiVersion'] = '5.1.1';

    $emit = time() . '000';
    $longheader = ['emit:' . $emit, 'device:' . GetSecret($subdata['equipmentModel'], $subdata['equipmentApiVersion'], $subdata['equipmentAppVersion'], $emit)];

    $rest = hpptRequest_Post("https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn", $longheader, Initarr($subdata));
    return $rest;
}

function Auto_get_version()
{
    $header = array(
        'Content-type: application/x-www-form-urlencoded'
    );
    $appversion = curl_get("https://zjyapp.icve.com.cn/newmobileapi/AppVersion/getAppVersion", $header);
    $json_d = json_decode($appversion, TRUE);
    $data = $json_d['data'];
    $info = $data['appVersionInfo']['versionCode'];
    return $info;
}