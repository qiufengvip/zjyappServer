<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Curl_Request_base;
use Input;
use DB;


/**
 * @desc  服务端第二版
 */
class Newzjy_Controller extends Controller
{
    protected $curl;
    protected $work;
    protected $CURL_Erupt;
    protected $ffdy = '<div style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"><img src="https://s1.ax1x.com/2020/08/20/dJNmTI.jpg" alt="非法调用" title="非法调用" srcset="" width="300px" height="300px"></div>';
    protected $header_ = array(
        'Content-type: application/x-www-form-urlencoded',
    );
    /* 职教云官方API声明段 */

    //获取所有科目api
    protected $Get_all_subjects_url = "https://zjy2.icve.com.cn/api/student/learning/getLearnningCourseList";
    //获取所有课程列表api
    protected $Get_course_list_url = "https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/getFaceTeachSchedule";
    //获取详细课程信息
    protected $gci_url = "https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo";
    //获取头脑风暴答案
    protected $get_tounao_aw_url = "https://zjy2.icve.com.cn/api/faceTeach/brainstorm/getParticipationStuDetail";
    //获取讨论答案
    protected $get_taolun_aw_url = "https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/getDiscussReplyList";
    //获取今天未签到的内容
    protected $get_now_nosign_url = "https://zjy2.icve.com.cn/api/student/faceTeachInfo/getFaceTeachSchedule";
    //获取两个月为签到的课程
    protected $get_twomonth_nosign_url = "https://zjy2.icve.com.cn/api/student/faceTeachInfo/getFaceTeachSchedule";
    //签到
    protected $get_stuSign_url = "https://zjyapp.icve.com.cn/newmobileapi/faceTeach/saveStuSign";


    //获取所选课程的全部作业
    protected $get_select_work_url = "https://zjy2.icve.com.cn/api/study/homework/getHomeworkList";
    //获取所选课程的全部考试
    protected $get_select_exam_url = "https://zjy2.icve.com.cn/api/study/onlineExam/getOnlineExamList";
    //获取慕课课程
    protected $get_mooc_ke_url = "https://mooc.icve.com.cn/portal/Course/getMyCourse?isFinished=0&page=1&pageSize=8";
    //电脑端获取作业题目ID
    protected $get_PC_homeWorkId_url = "https://security.zjy2.icve.com.cn/api/study/homework/preview";
    //app获取作业题目ID
    protected $get_app_homeWorkId_url = "https://zjyapp.icve.com.cn/newmobileapi/homework/getPreview";
    //pc获取考试ID
    protected $get_PC_examId_url = "https://security.zjy2.icve.com.cn/api/study/onlineExam/previewNew";
    //app获取考试ID
    protected $get_app_examId_url = "https://zjyapp.icve.com.cn/newmobileapi/onlineExam/getNewExamPreviewNew";
    //通用获取课堂测验ID
    protected $get_textId_url  = "https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/testPreview";

    //作业-获取作业未批阅的同学 && 获取作业已经批阅的同学
    protected $get_Read_StuList_url  = "https://zjyapp.icve.com.cn/newmobileapi/homework/getReadStuList";
    //作业-获取作业未提交同学
    protected $get_UnSubmit_StuList_url  = "https://zjyapp.icve.com.cn/newmobileapi/homework/getUnSubmitStuList";
    //作业-获取作答记录
    protected $get_Homework_StuRecord_url = "https://security.zjy2.icve.com.cn/newmobileapi/homework/getHomeworkStuRecord";
    //作业-查看作答内容-自己出题的作业
    protected $get_StuFile_Homework_url = "https://security.zjy2.icve.com.cn/newmobileapi/homework/getStuFileHomeworkPreview";
    //作业-查看作答内容-题库作业
    protected $get_NewStu_Homework_url = "https://security.zjy2.icve.com.cn/newmobileapi/homework/getNewStuHomeworkPreview";
    //获取作业的详细信息包括作业类型：homework.homeworkType    4=附件作业    1=题库作业
    protected $get_homework_detail  = "https://security.zjy2.icve.com.cn/api/study/homework/detail";



    //考试-获取信息
    protected $get_getExamStuData_url = "https://zjy2.icve.com.cn/api/examMonitor/CheckExam/getExamStuData";


    //考试-查看未批阅和已批阅的学生列表
    protected $get_ReadStuList_url  = "https://security.zjy2.icve.com.cn/newmobileapi/onlineExam/getReadStuList";

    //考试-获取未提交学生名单
    protected $get_UnSubmitStuList_url  = "https://security.zjy2.icve.com.cn/newmobileapi/onlineExam/getUnSubmitStuList";
    //考试-题库考试 获取作答内容-题库考试
    protected $get_StuExam_Pre_url  = "https://security.zjy2.icve.com.cn/newmobileapi/onlineExam/getStuExamPreviewNew";


    //获取附件下载地址
    protected $get_File_ById_url = "https://zjyapp.icve.com.cn/newmobileapi/Homework/getFileHomeworkUrlById";



    //mooc作业测验api
    protected $get_Mooc_Work_url = "https://mooc.icve.com.cn/study/workExam/workExamPreview";

    //MOOC考试api
    protected $get_Mooc_exam_url = "https://mooc.icve.com.cn/study/workExam/examPreview";




    /*职教云API声明段结束 */


    /* 易如意API声明段 */
    protected $KFapi = "http://yry.对接易如意域名/api.php?app=10000&act=get_fen";


    /* 易如意API声明段结束 */

    /**
     * 构造函数 初始化功能操作
     */
    public function __construct()
    {
        $this->curl = new Curl_Request();
        $this->work = new Work_zjy();
        $this->CURL_Erupt = new CURL_Erupt();
    }

    /**
     * 扣分函数，执行扣分操作
     * $token,  职教管家token
     * $mark,   扣分记录字段  避免重复扣分
     * $t,      时间戳
     * $sign    签名
     * $fid     扣分事件id
     */
    public function FunctionName($token, $mark, $t, $sign, $fid)
    {
        $kfdata = array(
            'token' => $token,
            'fid' => $fid,
            'mark' => $mark,
            't' => $t,
            'sign' => $sign
        );
        // dd($kfdata);
        $kfre = $this->curl->curl_post("http://yry.对接易如意域名/api.php?app=10000&act=get_fen", $this->header_, $kfdata);
        $kfjsonfull = json_decode($kfre, TRUE);
        return $kfjsonfull;
    }




    /*
        路由：newapi/Login
        功能：登录接口   更新cookie  
        参数：token
    */
    public function Login(Request $request)
    {

        if ($request->isMethod('post')) {
            $token = input::get('token');
            //$data = "app=10000&token=".$token."&t=".time().
            $data = [
                "token" => $token,
                "t" => time(),
                "sign" => $this->work->GetLoginSign($token, time()),
            ];
            $Get_userinfo = $this->curl->curl_post("http://yry.对接易如意域名/api.php?act=get_info&app=10000", [], $data);

            $json_username = json_decode($Get_userinfo, TRUE);
            if ($json_username['code'] === 200) {
                $yry_user = $json_username['msg']['email'];

                $foron = DB::table('zjy_user')->where('yry_user', '=', $yry_user)->first();  //通过token查询本账号所绑定的职教云账号

                //dd($foron);
                if ($foron == null) {
                    //未绑定职教云账号
                    $Lgoin__json = array(
                        'code' => '-1',
                        'msg' => '请绑定职教云账号',
                    );
                    return response()->json($Lgoin__json);
                } else {
                    //绑定过职教云账号的
                    // dd($foron);
                    $cookie = $this->curl->Get_cookie($foron->zjy_user, $foron->zjy_pwd)['cookie'];

                    $this->work->Setcookie($foron);
                    if ($cookie == FALSE) {

                        $Lgoin__json = array(
                            'code' => '-2',
                            'msg' => '用户密码错误',
                        );
                        return response()->json($Lgoin__json);
                    } else {
                        $ret = DB::table('zjy_user')->where('yry_user', '=', $foron->yry_user)->update(['zjy_cookie' => $cookie]);
                        if ($ret == TRUE) {
                            $Lgoin__json = array(
                                'code' => '200',
                                'msg' => '验证成功',
                                'user' => $foron->zjy_user,
                                'userid' => $foron->zjy_userid,
                                'cookie' => $foron->zjy_cookie,
                                'newtoken' => $foron->zjy_newtoken,
                            );
                            return response()->json($Lgoin__json);
                        } else {
                            $Lgoin__json = array(
                                'code' => '-3',
                                'msg' => '未知错误！',
                            );
                            return response()->json($Lgoin__json);
                        }
                    }
                }
            } else {
                $Lgoin__json = array(
                    'code' => '201',
                    'msg' => '用户信息异常，请联系管理员',
                );
                return response()->json($Lgoin__json);
            }
        } else {
            return $this->ffdy;
        }
    }
    /*
        路由：newapi/huanbang
        功能：换绑职教云账号
        参数
        token
        zjyuser
        zjypwd
        kami
    */
    public function huanbang(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = $request->input('token');  //职教管家账号token
            $zjyuser = $request->input('zjyuser');  //职教云账号
            $zjypwd = $request->input('zjypwd'); //职教云密码
            $kami = $request->input('kami');  //卡密 可选参数
            //dd($token,$zjyuser,$zjypwd,$kami);
            $data = [
                "token" => $token,
                "t" => time(),
                "sign" => $this->work->GetLoginSign($token, time()),
            ];
            //dd($data);
            $Get_userinfo = $this->curl->curl_post("http://yry.对接易如意域名/api.php?act=get_info&app=10000", [], $data);

            // dd($Get_userinfo);
            $json_username = json_decode($Get_userinfo, TRUE);
            if ($json_username['code'] === 200) {
                $yry_user = $json_username['msg']['email'];  //邮箱
                //dd($yry_user);
                $foron = DB::table('zjy_user')->where('yry_user', '=', $yry_user)->first();  //通过token查询本账号所绑定的职教云账号
                $data_cookie = $this->curl->Get_cookie($zjyuser, $zjypwd);
                // dd($data_cookie);
                if ($data_cookie == false) {
                    $Lgoin__json = array(
                        'code' => '-1',
                        'msg' => '请检查职教云账号密码',
                    );
                    return response()->json($Lgoin__json);
                }



                $cookie = $data_cookie['cookie'];  //验证账号
                $data = $data_cookie['data'];
                $jsonfall = json_decode($data, TRUE);
                $zjy_userid = $jsonfall['userId'];
                $zjy_newtoken = $jsonfall['newToken'];
                //dd($cookie,$data);
                // dd($foron);
                //dd($foron->zjy_user);
                if ($foron == FALSE) {
                    //未绑定账号   
                    //dd("未绑定账号");
                    if ($cookie == null) {
                        //职教云密码错误
                        $Lgoin__json = array(
                            'code' => '-1',
                            'msg' => '用户密码错误',
                        );
                        return response()->json($Lgoin__json);
                    } else {
                        //账号验证通过的
                        //dd($cookie);

                        $retu = DB::table('zjy_user')->insertGetId([
                            'yry_user' => $yry_user,  //职教管家账号
                            'zjy_user' => $zjyuser,    //职教云账号
                            'zjy_pwd' => $zjypwd,  //职教云密码
                            'zjy_cookie' => $cookie,    //职教云cookie
                            'zjy_userid' => $zjy_userid,
                            'zjy_newtoken' => $zjy_newtoken
                        ]);
                        $Lgoin__json = array(
                            'code' => '200',
                            'msg' => '绑定职教云账号成功！',
                        );
                        return response()->json($Lgoin__json);
                    }
                } else if ($foron->zjy_user == $zjyuser) {
                    //dd("进入修改密码");
                    //密码错误，只需要修改密码的
                    if ($cookie == FALSE) {
                        //职教云密码错误
                        $Lgoin__json = array(
                            'code' => '-1',
                            'msg' => '用户密码错误',
                        );
                        return response()->json($Lgoin__json);
                    } else {
                        //账号验证通过的
                        //dd($zjy_userid);
                        $ret = DB::table('zjy_user')
                            ->where('yry_user', '=', $foron->yry_user)
                            ->update([
                                'zjy_pwd' => $zjypwd,  //职教云密码
                                'zjy_cookie' => $cookie,    //职教云cookie
                                'zjy_userid' => $zjy_userid,
                                'zjy_newtoken' => $zjy_newtoken
                            ]);
                        if ($ret == TRUE) {
                            //写入成功的
                            $Lgoin__json = array(
                                'code' => '200',
                                'msg' => '绑定职教云账号成功！',
                            );
                            return response()->json($Lgoin__json);
                        } else {
                            $Lgoin__json = array(
                                'code' => '-2',
                                'msg' => '未知错误',
                            );
                            return response()->json($Lgoin__json);
                        }
                    }
                } else {
                    //修改绑定账号，需要卡密
                    $yryurl = 'http://yry.对接易如意域名/api.php?act=card&app=10000';
                    if ($cookie == FALSE) {
                        $Lgoin__json = array(
                            'code' => '-4',
                            'msg' => '账号或密码错误！',
                        );
                        return response()->json($Lgoin__json);
                    }
                    if ($kami == null) {
                        $Lgoin__json = array(
                            'code' => '-3',
                            'msg' => '请填写卡密',
                        );
                        return response()->json($Lgoin__json);
                    }
                    $t = time();
                    $data = [
                        "token" => $token,
                        "kami" => $kami,
                        "t" => $t,
                        "sign" => md5("token=" . $token . "&kami=" . $kami . "&t=" . $t . "&13f3c400fcfa97e4e41ec4c6f154c357"),
                    ];
                    // dd($data);
                    // var_dump($data);
                    $Get_cardinfo = $this->curl->curl_post($yryurl, [], $data);
                    $json_cardinfo = json_decode($Get_cardinfo, TRUE);
                    // dd($Get_cardinfo);
                    if ($json_cardinfo['code'] == 200) {
                        $ret = DB::table('zjy_user')->where('yry_user', '=', $foron->yry_user)->update([
                            'zjy_user' => $zjyuser,
                            'zjy_pwd' => $zjypwd,  //职教云密码
                            'zjy_cookie' => $cookie,    //职教云cookie
                            'zjy_userid' => $zjy_userid,
                            'zjy_newtoken' => $zjy_newtoken
                        ]);
                        $Lgoin__json = array(
                            'code' => '200',
                            'msg' => '更换账号绑定成功！',
                        );
                        return response()->json($Lgoin__json);
                    } else {
                        $Lgoin__json = $json_cardinfo;
                        return response()->json($Lgoin__json);
                    }
                }
            } else {
                $Lgoin__json = array(
                    'code' => '-5',
                    'msg' => '未知错误,请联系管理员',
                );
                return response()->json($Lgoin__json);
            }
        }
    }
    /*
        路由：newapi/Get_all_subjects
        功能：获取所有科目信息
    */
    public function Get_all_subjects(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get("token");
            //dd($Get_all_subjects_user,$Get_all_subjects_wd);
            $Get_all_subjects_cookie = $this->work->Get_cookie($token)['cookie'];
            if ($Get_all_subjects_cookie == FALSE) {
                $Get_all_subjects__Bjson = array(
                    'code' => '-2',
                    'msg' => '请检查账号密码的正确性'
                );
                return response()->json($Get_all_subjects__Bjson);
            }
            array_push($this->header_, "cookie:" . $Get_all_subjects_cookie);
            $Get_all_subjects_recontent = $this->curl->curl_get($this->Get_all_subjects_url, $this->header_);
            $Get_all_subjects_re_json = json_decode($Get_all_subjects_recontent, TRUE);

            $Get_all_subjects_code = $Get_all_subjects_re_json['code'];
            if ($Get_all_subjects_code <> 1) {
                $Get_all_subjects__Bjson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($Get_all_subjects__Bjson);
            } else {
                $Get_all_subjects_courlist = $Get_all_subjects_re_json['courseList'];

                $Get_all_subjects__json = array(
                    'code' => '200',
                );

                foreach ($Get_all_subjects_courlist as $key => $value) {
                    $Get_all_subjects_id = $value['Id'];
                    $Get_all_subjects_courseOpenId = $value['courseOpenId'];
                    $Get_all_subjects_openClassId = $value['openClassId'];
                    $Get_all_subjects_courseName = $value['courseName'];
                    $Get_all_subjects_thumbnail = $value['thumbnail'];
                    $Get_all_subjects_process = $value['process'];

                    $Get_all_subjects__json['msg'][$key]['id'] = $Get_all_subjects_id;
                    $Get_all_subjects__json['msg'][$key]['courseOpenId'] = $Get_all_subjects_courseOpenId;
                    $Get_all_subjects__json['msg'][$key]['openClassId'] = $Get_all_subjects_openClassId;
                    $Get_all_subjects__json['msg'][$key]['courseName'] = $Get_all_subjects_courseName;
                    $Get_all_subjects__json['msg'][$key]['thumbnail'] = $Get_all_subjects_thumbnail;
                    $Get_all_subjects__json['msg'][$key]['process'] = $Get_all_subjects_process;
                }
                return  response()->json($Get_all_subjects__json);
            }
        } else {
            return $this->ffdy;
        }
    }

    /*
        路由：newapi/Get_course_list
        功能：获取所有课程列表
    */
    public function Get_course_list(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get("token");
            $Get_course_list_courseOpenId = input::get('courseOpenId');
            $Get_course_list_openClassId = input::get('openClassId');

            $Get_course_list_cookie = $this->work->Get_cookie($token)['cookie']; //$this->curl->Get_cookie($Get_course_list_user,$Get_course_list_wd);
            array_push($this->header_, "cookie:" . $Get_course_list_cookie);

            $Get_course_list_postdata = array(
                'currentTime' => '',
                'calendar' => 'month',
                'courseOpenId' => $Get_course_list_courseOpenId,
                'openClassId' => $Get_course_list_openClassId
            );
            $Get_course_list_recontent = $this->curl->curl_post($this->Get_course_list_url, $this->header_, $Get_course_list_postdata);
            $Get_course_list_rejson = json_decode($Get_course_list_recontent, TRUE);
            $Get_course_list_code = $Get_course_list_rejson['code'];

            if ($Get_course_list_code <> 1) {
                # code...
                $Get_course_list__Bjson = array(
                    'code' => '-2',
                    'msg' => 'Boom'
                );
                return response()->json($Get_course_list__Bjson);
            } else {
                $Get_course_list_timelist = $Get_course_list_rejson['timeList']; //数组
                $Get_course_list_begin_time = date('Y-m-d', strtotime('-1 month'));
                $Get_course_list_amonth = array(
                    'currentTime' => $Get_course_list_begin_time,
                    'calendar' => 'month',
                    'courseOpenId' => $Get_course_list_courseOpenId, //post 获取的东西
                    'openClassId' => $Get_course_list_openClassId //post 获取的东西
                );
                $Get_course_list_amonth_content = $this->curl->curl_post($this->Get_course_list_url, $this->header_, $Get_course_list_amonth);
                $Get_course_list_amonth_rejson = json_decode($Get_course_list_amonth_content, TRUE);
                $Get_course_list_amonth_timelist = $Get_course_list_amonth_rejson['timeList'];
                $Get_course_list_alllist = array_merge($Get_course_list_amonth_timelist, $Get_course_list_timelist);
                $Get_course_list_kelist = [];

                foreach ($Get_course_list_alllist as $key => $value) {
                    # code...
                    foreach ($value['faceTeachList'] as $val) {
                        # code...
                        $strs =  json_encode($Get_course_list_kelist);
                        $zt = strpos($strs, $val["Id"]);

                        if ($zt == FALSE) {
                            array_push($Get_course_list_kelist, $val);
                        }
                    }
                }

                $Gclist__json = array(
                    'code' => '200',
                    'msg' => array_reverse($Get_course_list_kelist)
                );
                return response()->json($Gclist__json);
            }
        } else {
            return $this->ffdy;
        }
    }
    /*
        路由：newapi/Get_Class_info
        功能：获取课程详细信息
    */
    public function Get_Class_info(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get("token");
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');



            $gcicookie = $this->work->Get_cookie($token)['cookie'];
            array_push($this->header_, "cookie:" . $gcicookie);

            $gcipostdata1 = array(
                'type' => '1',
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'pageSize' => 50
            );

            $gci_recontent = $this->curl->curl_post($this->gci_url, $this->header_, $gcipostdata1);

            $gci_dejson = json_decode($gci_recontent, TRUE);
            // dd($gci_dejson02);
            if ($gci_dejson['code'] <> 1) {
                // dd($token);
                dd($gci_dejson['code']);
                return response()->json($gci_dejson);
            }
            // dd($token);


            $gcipostdata02 = array(
                'type' => '2',
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'pageSize' => 50
            );

            $gci_recontent02 = $this->curl->curl_post($this->gci_url, $this->header_, $gcipostdata02);
            $gci_dejson02 = json_decode($gci_recontent02, TRUE);
            if ($gci_dejson02['code'] <> 1) {
                return response()->json($gci_dejson02);
            }



            $rejsons = []; //返回用数组
            $rejsons['code'] = "200";


            $gci_list = $gci_dejson['list'];
            $i = 0;
            // $datetype="--";
            // dd($gci_dejson02);
            foreach ($gci_list as $key => $value) {
                // var_dump($i);
                try {
                    // var_dump($value);
                    $activityType = $value['activityType'];
                    if ($activityType == 1) {
                        $dataType = "签到";
                    } elseif ($activityType == 3) {
                        $dataType = "讨论";
                    } elseif ($activityType == 4) {
                        $dataType = "头脑风暴";
                    } elseif ($activityType == 5) {
                        $dataType = "随堂测验";
                    } else {
                        continue;
                    }
                    $rejsons['msg'][$i]['Id'] = $value['Id'];
                    $rejsons['msg'][$i]['title'] = "[课前]" . $value['title'];
                    $rejsons['msg'][$i]['dataType'] = $dataType;
                    $i++;
                } catch (\Throwable $th) {
                }
            }
            foreach ($gci_dejson02['list'] as $key => $value) {
                // var_dump($i);
                try {
                    // var_dump($value);
                    $activityType = $value['activityType'];
                    if ($activityType == 1) {
                        $dataType = "签到";
                    } elseif ($activityType == 3) {
                        $dataType = "讨论";
                    } elseif ($activityType == 4) {
                        $dataType = "头脑风暴";
                    } elseif ($activityType == 5) {
                        $dataType = "随堂测验";
                    } else {
                        continue;
                    }
                    $rejsons['msg'][$i]['Id'] = $value['Id'];
                    $rejsons['msg'][$i]['title'] = "[课中]" . $value['title'];
                    $rejsons['msg'][$i]['dataType'] = $dataType;
                    $i++;
                } catch (\Throwable $th) {
                }
            }
            return response()->json($rejsons);
        } else {
            return $this->ffdy;
        }
    }


    /*
        路由：newapi/GetBrainstorming
        功能：获取头脑风暴答案
        参数：
            ktoken->扣分apitoken
            mark
            brainStormId
            sign
            t
    */
    public function GetBrainstorming(Request $request)
    {
        if ($request->isMethod('post')) {

            $token = input::get('token');
            $mark = input::get('mark');
            $brainStormId = input::get('brainStormId');
            $sign = input::get('sign');
            $t = input::get('t');
            //签名认证系统
            if (!strpos($mark, $brainStormId)) {
                $rrejson = array(
                    "code" => -5,
                    "msg" => "客户端信息有误！"
                );
                return response()->json($rrejson);
            }




            $kfdata = $this->FunctionName($token, $mark, $t, $sign, 1);
            // $kfdata = array(
            //     'token'=> $token,
            //     'fid'=> 1,
            //     'mark' => $mark,
            //     't' => $t,
            //     'sign' => $sign

            // );

            // $kfre = $this->curl->curl_post($this->KFapi,$this->header_,$kfdata);
            $kfjsonfull = $kfdata;
            $kfcode = $kfjsonfull['code'];
            if ($kfcode <> 200) {
                return $kfjsonfull;
                //扣分未成功返回
            }

            $arr = array(
                'userName' => '替换为职教云账号',
                'userPwd' => '替换为职教云密码',
                'verifyCode' => ''
            );




            $cookies = $this->curl->Get_cookie($arr['userName'], $arr['userPwd'])['cookie'];
            array_push($this->header_, "cookie:" . $cookies);

            $brdata = array(
                'brainStormId' => $brainStormId
            );

            $tounaore = $this->curl->curl_post($this->get_tounao_aw_url, $this->header_, $brdata);

            $brjaonfull = json_decode($tounaore, TRUE);
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
                    'code' => '200',
                );
                foreach ($brlist as $key => $value) {
                    # code...
                    $docJson = $value['docJson'];
                    //dd($docJson[0]["docOssPreview"]);
                    if (sizeof($docJson) <> 0) {


                        $fjurl = "<br>附件地址：" . $docJson[0]['docOssPreview'];
                    } else {
                        $fjurl = NULL;
                    }
                    $jsonre['msg'][$key]['StuName'] = $value['stuName'];
                    $jsonre['msg'][$key]['Content'] = $value['content'] . $fjurl;
                }
                return response()->json($jsonre);
            }
        } else {
            return $this->ffdy;
        }
    }

    /* 
        路由：newapi/Getdsaw
        功能：获取讨论答案
        参数：
            ktoken
            mark
            sign
            t
            ustoken
            courseOpenId
            openClassId
            activityId
            discussId

    */
    public function Getdsaw(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $mark = input::get('mark');
            $sign = input::get('sign');
            $t = input::get('t');
            //以上参数为扣分api
            $kfdata = array(
                'token' => $token,
                'fid' => 11,
                'mark' => $mark,
                't' => $t,
                'sign' => $sign
            );

            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');
            $discussId = input::get('discussId');

            $kfre = $this->curl->curl_post($this->KFapi, $this->header_, $kfdata);
            $kfjsonfull = json_decode($kfre, TRUE);
            $kfcode = $kfjsonfull['code'];
            if ($kfcode <> 200) {
                # code...
                return $kfjsonfull;
            } else {


                $gcookie = $this->work->Get_cookie($token)['cookie'];
                array_push($this->header_, "cookie:" . $gcookie);
                $senddata = array(
                    "courseOpenId" => $courseOpenId,
                    "openClassId" => $openClassId,
                    "activityId" => $activityId,
                    "discussId" => $discussId,
                );
                $redata = $this->curl->curl_post($this->get_taolun_aw_url, $this->header_, $senddata);
                $alljsons = json_decode($redata, TRUE);
                $ccode = $alljsons['code'];
                // dd($alljsons);
                if ($ccode <> 1) {
                    $rrejson = array(
                        "code" => '-1',
                        "msg" => "获取讨论信息失败"
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
                        "msg" => "解析讨论答案失败"
                    );
                    return response()->json($rrejson);
                }
                // dd($replyList);

                if (empty($replyList)) {
                    $rrejson = array(
                        "code" => "-3",
                        "msg" => "本讨论暂时无人作答"
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
        } else {
            return $this->ffdy;
        }
    }


    /**
     * 获取作业答案
     * 提交参数：
     * token        职教管家token
     * mark         扣分的字段
     * sign         签名
     * t            时间戳
     * courseOpenId 
     * openClassId
     * homeWorkId   作业id
     * 
     */

    public function GethomeWorkId(Request $request)
    {
        # code...
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $mark = input::get('mark');
            $sign = input::get('sign');
            $t = input::get('t');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $homeWorkId = input::get('homeWorkId');
            $Device = input::get('Device');  //1=电脑 2 =  手机

            //签名认证系统
            if (!strpos($mark, $homeWorkId)) {
                $rrejson = array(
                    "code" => -5,
                    "msg" => "客户端信息有误！"
                );
                return response()->json($rrejson);
            }


            $kfdata = $this->FunctionName($token, $mark, $t, $sign, 2);
            $kfcode = $kfdata['code'];
            // $kfcode=200;  //测试用   发布注释
            if ($kfcode <> 200) {
                return $kfdata;
            } else {
                $url = '';
                if ($Device == 1) {
                    # code...  pc端
                    $gcookie = $this->work->Get_cookie($token)['cookie'];
                    array_push($this->header_, "cookie:" . $gcookie);
                    $senddata = array(
                        "courseOpenId" => $courseOpenId,
                        "openClassId" => $openClassId,
                        "homeWorkId" => $homeWorkId,
                    );
                    $url = $this->get_PC_homeWorkId_url;
                } else if ($Device == 2) {
                    //APP端
                    $url =  $this->get_app_homeWorkId_url;
                    $userdata = $this->work->Get_cookie($token);
                    array_push($this->header_, "cookie:" . $userdata['cookie']);
                    $newToken = $userdata['newtoken'];
                    $userid  = $userdata['userid'];
                    $senddata = array(
                        "courseOpenId" => $courseOpenId,
                        "openClassId" => $openClassId,
                        "homeWorkId" => $homeWorkId,
                        "stuId"  => $userid,
                        "newToken"  => $newToken,
                    );
                    $url = $this->get_PC_homeWorkId_url;
                } else {
                    $rrejson = array(
                        "code" => -5,
                        "msg" => "客户端信息有误！"
                    );
                    return response()->json($rrejson);
                }
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
                if ($codes <> 100) {
                    if ($codes == 1) {
                        $rrejson = array(
                            "code" => "-2",
                            "msg" => "此作业非题库作业，请选择查看同学作答"
                        );
                    } else {
                        $rrejson = array(
                            "code" => "-2",
                            "msg" => "不在答题指定时间段"
                        );
                    }
                    return response()->json($rrejson);
                }
                $redisData = $jxjson['redisData'];
                $questions = json_decode($redisData, TRUE)['questions'];
                $allsretext = '';
                $resawwid = array();
                foreach ($questions as $key => $value) {
                    $questionId = $value['questionId'];
                    try {
                        $resawwid[$key] = $questionId;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                $data = $this->CURL_Erupt->EruptRequest($resawwid);
                $timu = "";
                foreach ($data as $key => $value) {
                    $timu .= "【" . ($key + 1) . "】<br>" . $value . "<br><hr><br>";
                }
                $return = array(
                    'code' => 200,
                    'msg' => $timu,
                );
                return response()->json($return);
            }
        } else {
            return $this->ffdy;
        }
    }


    /**
     * 获取考试答案
     * courseOpenId    班级
     * openClassId      班级
     * examId         考试id
     * examTermTimeId   考试时间设置id
     * Device           访问设备类型
     */
    public function GetexamId(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $mark = input::get('mark');
            $sign = input::get('sign');
            $t = input::get('t');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $examId = input::get('examId');
            $examTermTimeId = input::get('examTermTimeId');
            $Device = input::get('Device');  //1=电脑 2 =  手机

            //签名认证系统
            if (!strpos($mark, $examId)) {
                $rrejson = array(
                    "code" => -5,
                    "msg" => "客户端信息有误！"
                );
                return response()->json($rrejson);
            }


            $s_url = $this->get_getExamStuData_url;

            $s_data = array(
                "examTimeId" => $examTermTimeId,
                "type" => 2,
            );
            $s_data = $this->curl->curl_post($s_url, $this->header_, $s_data);
            $s_jsondata = json_decode($s_data, TRUE);

            if (!($s_jsondata["isVerified"] == 0)) {
                $rrejson = array(
                    "code" => -5,
                    "msg" => "本考试开启了客户端验证，暂不支持查看答案"
                );
                return response()->json($rrejson);
            }



            $kfdata = $this->FunctionName($token, $mark, $t, $sign, 3);
            $kfcode = $kfdata['code'];
            // $kfcode=200;  //测试用   发布注释










            if ($kfcode <> 200) {
                return $kfdata;
            } else {
                $url = '';
                if ($Device == 1) {
                    //PC端
                    $url = $this->get_PC_examId_url;
                    $gcookie = $this->work->Get_cookie($token)['cookie'];
                    array_push($this->header_, "cookie:" . $gcookie);
                    $senddata = array(
                        "courseOpenId" => $courseOpenId,
                        "openClassId" => $openClassId,
                        "examId" => $examId,
                        "examTimeId" => $examTermTimeId,
                    );
                    $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                    $jxjson = json_decode($redata, TRUE);
                    $codes = $jxjson['code'];
                    if ($codes == 1) {
                        $questionData = $jxjson['questionData'];
                        $questionData = json_decode($questionData, TRUE);
                        $resawwid = array();
                        // dd($questionData);
                        $allquestionid =  $questionData['questions'];

                        foreach ($allquestionid  as $key => $value) {
                            $value['questionId'];
                            $questionId = $value['questionId'];
                            try {
                                $resawwid[$key] = $questionId;
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }
                    } else {
                        return response()->json($jxjson);
                    }
                } elseif ($Device == 2) {
                    //app端
                    $url = $this->get_app_examId_url;
                    $userdata = $this->work->Get_cookie($token);
                    $gcookie = $userdata['cookie'];
                    $guserid = $userdata['userid'];
                    $gnewtoken = $userdata['newtoken'];

                    array_push($this->header_, "cookie:" . $gcookie);
                    $senddata = array(
                        "courseOpenId" => $courseOpenId,
                        "openClassId" => $openClassId,
                        "examId" => $examId,
                        "examTimeId" => $examTermTimeId,
                        "stuId" =>  $guserid,
                        "newToken" => $gnewtoken,

                    );
                    $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                    $jxjson = json_decode($redata, TRUE);
                    $codes = $jxjson['code'];

                    if ($codes == 1) {
                        // dd($jxjson);
                        $questionData = $jxjson['data']['questionJson'];

                        $questionData = json_decode($questionData, TRUE);
                        $resawwid = array();
                        foreach ($questionData  as $key => $value) {
                            $value['questionId'];
                            $questionId = $value['questionId'];
                            try {
                                $resawwid[$key] = $questionId;
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }
                    } else {
                        return response()->json($jxjson);
                    }
                } else {
                    $rrejson = array(
                        "code" => -5,
                        "msg" => "客户端信息有误！"
                    );
                    return response()->json($rrejson);
                }
                $data = $this->CURL_Erupt->EruptRequest($resawwid);

                $timu = "";
                foreach ($data as $key => $value) {
                    $timu .= "【" . ($key + 1) . "】<br>" . $value . "<br><hr><br>";
                }
                $return = array(
                    'code' => 200,
                    'msg' => $timu,
                );
                return response()->json($return);
            }
        } else {
            return $this->ffdy;
        }
    }



    /**
     * 获取课程测验答案
     * courseOpenId
     * openClassId
     * activityId    
     * classTestId   测试id
     */
    public function GettextId(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $mark = input::get('mark');
            $sign = input::get('sign');
            $t = input::get('t');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $activityId = input::get('activityId');
            $classTestId = input::get('classTestId');
            //签名认证系统
            if (!strpos($mark, $classTestId)) {
                $rrejson = array(
                    "code" => -5,
                    "msg" => "客户端信息有误！"
                );
                return response()->json($rrejson);
            }


            // dd($request);
            $kfdata = $this->FunctionName($token, $mark, $t, $sign, 5);
            $kfcode = $kfdata['code'];
            // $kfcode=200;  //测试用发布注释
            if ($kfcode <> 200) {
                return $kfdata;
            } else {
                $url = $this->get_textId_url;
                $gcookie = $this->work->Get_cookie($token)['cookie'];
                array_push($this->header_, "cookie:" . $gcookie);
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'activityId' => $activityId,
                    'classTestId' => $classTestId,
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
                if ($codes <> 1) {
                    return response()->json($jxjson);
                }
                $questions = $jxjson['bigQuestions'];
                $allsretext = '';
                $resawwid = array();
                foreach ($questions as $key => $value) {
                    $questionId = $value['QuesetionId'];
                    try {
                        $resawwid[$key] = $questionId;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                $data = $this->CURL_Erupt->EruptRequest($resawwid);
                $timu = "";
                foreach ($data as $key => $value) {
                    $timu .= "【" . ($key + 1) . "】<br>" . $value . "<br><hr><br>";
                }



                $return = array(
                    'code' => 200,
                    'msg' => $timu,
                );
                return response()->json($return);
            }
        } else {
            return $this->ffdy;
        }
    }




    /**
     * name :   获取作业提交信息
     * 路由 :    newapi/GethomeWorkInfo
     * 参数 :
     * token                // 职教管家token
     * courseOpenId         // 班级ID
     * openClassId          // 班级id
     * homeWorkId           // 作业ID
     * homeworkTermTimeId   // 作业时间id
     * state                // 类型  1=未提交   2=未批阅    3=已批阅
     */
    public function GethomeWorkInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $homeWorkId = input::get('homeWorkId');
            $homeworkTermTimeId = input::get('homeworkTermTimeId');
            $state = input::get('state');
            $url = "";
            $userinfo = $this->work->Get_cookie($token);


            if ($state == 1) {
                //未提交
                $url = $this->get_UnSubmit_StuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'homeWorkId' => $homeWorkId,
                    'homeworkTermTimeId' => $homeworkTermTimeId,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
            } elseif ($state == 2) {
                //未批阅
                $url = $this->get_Read_StuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'homeWorkId' => $homeWorkId,
                    'homeworkTermTimeId' => $homeworkTermTimeId,
                    'state' => 1,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
            } elseif ($state == 3) {
                //已批阅
                $url = $this->get_Read_StuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'homeWorkId' => $homeWorkId,
                    'homeworkTermTimeId' => $homeworkTermTimeId,
                    'state' => 2,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
            } else {
                $rrejson = array(
                    "code" => -3,
                    "msg" => "参数异常"
                );
                return response()->json($rrejson);
            }
            if ($codes == 1) {
                $homeworkStuList = $jxjson['homeworkStuList'];
                $rrejson = array(
                    "code" => 200,
                    "msg" => $homeworkStuList
                );
                return response()->json($rrejson);
            } else {
                return response()->json($jxjson);
            }
        } else {
            return $this->ffdy;
        }
    }


    /**
     * 获取作业作答记录--作业
     * token                //易如意token
     * openClassId          //班级id
     * homeworkId           //作业id
     * stuId                //学生id    
     * homeworkTermTimeId   //作业时间id
     * url
     * url = get_Homework_StuRecord_url
     */
    public function GethomeWorkRecord(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');

            $openClassId = input::get('openClassId');
            $homeWorkId = input::get('homeWorkId');
            $homeworkTermTimeId = input::get('homeworkTermTimeId');
            $stuId =  input::get('stuid');

            $url = $this->get_Homework_StuRecord_url;
            $userinfo = $this->work->Get_cookie($token);
            $senddata = array(
                'openClassId' => $openClassId,
                'homeWorkId' => $homeWorkId,
                'homeworkTermTimeId' => $homeworkTermTimeId,
                'stuId' => $stuId,
                'newToken' => $userinfo['newtoken'],
            );

            // dd($senddata);
            $redata = $this->curl->curl_post($url, $this->header_, $senddata);
            $jxjson = json_decode($redata, TRUE);
            $codes = $jxjson['code'];
            // dd($jxjson);
            if ($codes == 1) {
                if ($jxjson['isTakeHomework'] == false) {
                    $homeworkStuList = $jxjson['stuHomeworkList'];
                } else {
                    $homeworkStuList = $jxjson['homeworkStuList'];
                }

                $rrejson = array(
                    "code" => 200,
                    "msg" => $homeworkStuList
                );
                return response()->json($rrejson);
            } else {
                return response()->json($jxjson);
            }
        } else {
            return $this->ffdy;
        }
    }

    /**
     * Route：newapi/GethomeWorkAnswer
     * name：获取同学的作答内容  --作业的
     * par：
     * 
     * 
     */

    public function GethomeWorkAnswer(Request $request)
    {


        /**
         * 
         * 获取作业的详细信息包括作业类型：homework.homeworkType    4=附件作业    1=题库作业
         * protected $get_homework_detail
         * 
         * 作业-查看作答内容-自己出题的作业
         * protected $get_StuFile_Homework_url
         * 
         * 作业-查看作答内容-题库作业
         * protected $get_NewStu_Homework_url
         * 
         * 获取附件下载地址
         * protected $get_File_ById_url
         * 
         * 
         */
        if ($request->isMethod('post')) {
            $token = input::get('token');   //易如意token
            $openClassId = input::get('openClassId');
            $courseOpenId = input::get('courseOpenId');
            $homeworkId = input::get('homeworkId');
            $homeworkStuId = input::get('homeworkStuId');

            $rrejson = array(
                "code" => -5,
                "msg" => "已失效！"
            );
            return response()->json($rrejson);


            $url = $this->get_homework_detail;
            $userinfo = $this->work->Get_cookie($token);
            array_push($this->header_, "cookie:" . $userinfo['cookie']);
            $senddata = array(
                'openClassId' => $openClassId,
                'courseOpenId' => $courseOpenId,
                'homeworkId' => $homeworkId,
            );
            $redata = $this->curl->curl_post($url, $this->header_, $senddata);
            $redata = json_decode($redata, TRUE);
            dd($senddata);

            if ($redata['code'] == 1) {
                $homeworkType = $redata['homework']['homeworkType'];
                if ($homeworkType == 1) {
                    //题库作业
                    $url = $this->get_NewStu_Homework_url;
                    $senddata = array(
                        'openClassId' => $openClassId,
                        'courseOpenId' => $courseOpenId,
                        'homeworkId' => $homeworkId,
                        'stuId' => $userinfo['userid'],
                        'homeworkStuId' => $homeworkStuId,
                        'newToken' => $userinfo['newtoken'],
                    );
                    $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                    // dd($redata);
                    $redata = json_decode($redata, TRUE);
                    $iGet_daan = new Get_daan();
                    $questionsarray = $redata['data']['questions'];
                    $retuarray = "";
                    foreach ($questionsarray as $key => $value) {
                        $questionType = $value['questionType'];
                        $answer = $iGet_daan->Getanseer($questionType, $value);
                        $retuarray .= "<br><hr><br>【第" . $key . "题】" . $answer;
                    }

                    $rrejson = array(
                        "code" => 200,
                        "msg" => $retuarray
                    );
                    return response()->json($rrejson);
                } elseif ($homeworkType == 4) {
                    //附件作业
                    $url = $this->get_StuFile_Homework_url;
                    $senddata = array(
                        'openClassId' => $openClassId,
                        'courseOpenId' => $courseOpenId,
                        'homeworkId' => $homeworkId,
                        'homeworkStuId' => $homeworkStuId,
                        'stuId' =>  $userinfo['userid'],
                        'newToken' => $userinfo['newtoken'],
                    );
                    $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                    $redata = json_decode($redata, TRUE);
                    // 获取附件下载地址
                    // protected $get_File_ById_url
                    // dd($redata);
                    if ($redata['code'] == 1) {
                        $returese = "";
                        $redata = $redata['data'];
                        $title = $redata['remark'];            //题目
                        $stuRemark = $redata['stuRemark'];   //学生回答的内容 
                        $commentary = $redata['commentary'];   // 老师批语
                        $totalScore = $redata['totalScore'];   // 分数
                        $returese .= "【题目】" . $title . "<br><br><br>" . "【学生回答】" . $stuRemark . "<br>【学生得分】" . $totalScore . "<br>【老师批语】" . $commentary . "<br><br>【附件信息】";

                        foreach ($redata['stuAnswers'] as $key => $value) {    //学生的附件
                            $fileName = $value['fileName'];
                            $url = $this->get_File_ById_url;
                            $filesenddata = array(
                                'Id' => $value['Id'],
                                'newToken' => $userinfo['newtoken'],
                            );
                            $fileredata = $this->curl->curl_post($url, $this->header_, $filesenddata);
                            $fileredata = json_decode($fileredata, TRUE);
                            $fileredata = json_decode($fileredata['url'], TRUE)['urls']['download'];
                            $returese .= "<br>[附件" . (((int)$key + 1)) . "]<br>名称：" . $fileName . "<br>[下载地址]" . $fileredata;
                        }
                        $rrejson = array(
                            "code" => 200,
                            "msg" => $returese,
                        );
                        return response()->json($rrejson);
                    }
                    $rrejson = array(
                        "code" => -5,
                        "msg" => "获取提交信息异常！"
                    );
                    return response()->json($rrejson);
                }
            } else {
                $rrejson = array(
                    "code" => -4,
                    "msg" => "获取作业信息异常！"
                );
                return response()->json($rrejson);
            }
        } else {
            return $this->ffdy;
        }
    }

    /**
     * 查看学生提交信息
     * 
     * state=1 未提交
     * state=2 未批阅
     * state=3 已提交
     * 
     * 
     * 
     * 
     * //考试-查看未批阅和已批阅的学生列表
     *   protected $get_ReadStuList_url
     * //考试-获取未提交学生名单
     *   protected $get_UnSubmitStuList_url
     * 
     * 
     */


    public function GetexamInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $examId = input::get('examId');
            $examTermTimeId = input::get('examTermTimeId');
            $state = input::get('state');
            $url = "";
            $userinfo = $this->work->Get_cookie($token);


            if ($state == 1) {
                //未提交
                $url = $this->get_UnSubmitStuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'examId' => $examId,
                    'examTermTimeId' => $examTermTimeId,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
            } elseif ($state == 2) {
                //未批阅
                $url = $this->get_ReadStuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'examId' => $examId,
                    'examTermTimeId' => $examTermTimeId,
                    'state' => 1,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
            } elseif ($state == 3) {
                //已批阅
                $url = $this->get_ReadStuList_url;
                $senddata = array(
                    'courseOpenId' => $courseOpenId,
                    'openClassId' => $openClassId,
                    'examId' => $examId,
                    'examTermTimeId' => $examTermTimeId,
                    'state' => 2,
                    'newToken' => $userinfo['newtoken'],
                );
                $redata = $this->curl->curl_post($url, $this->header_, $senddata);
                $jxjson = json_decode($redata, TRUE);
                $codes = $jxjson['code'];
                // dd($senddata );
            } else {
                $rrejson = array(
                    "code" => -3,
                    "msg" => "参数异常"
                );
                return response()->json($rrejson);
            }
            // dd($jxjson);
            if ($codes == 1) {
                $examStuList = $jxjson['examStuList'];
                $rrejson = array(
                    "code" => 200,
                    "msg" => $examStuList
                );
                return response()->json($rrejson);
            } else {
                return response()->json($jxjson);
            }
        } else {
            return $this->ffdy;
        }
    }


    /**
     * 获取作答内容---考试的
     * newapi/GetexamAnswer
     * $get_StuExam_Pre_url //url->
     * 
     * 
     */

    public function GetexamAnswer(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $examId = input::get('examId');
            $examStuId = input::get('examStuId');    //作答学生姓名
            $url = $this->get_StuExam_Pre_url;
            $userinfo = $this->work->Get_cookie($token);
            $newToken = $userinfo['newtoken'];
            $rrejson = array(
                "code" => -5,
                "msg" => "已失效！"
            );
            return response()->json($rrejson);

            $senddata = array(
                'courseOpenId' => $courseOpenId,
                'openClassId' => $openClassId,
                'examId' => $examId,
                'examStuId' => $examStuId,
                'newToken' => $newToken,
            );
            $redata = $this->curl->curl_post($url, $this->header_, $senddata);
            $jxjson = json_decode($redata, TRUE);
            $codes = $jxjson['code'];

            if ($codes == 1) {
                // dd($redata);
                $iGet_daan = new Get_daan();
                $questionsarray = $jxjson['data']['questions'];
                $retuarray = "";
                foreach ($questionsarray as $key => $value) {
                    $questionType = $value['questionType'];
                    $answer = $iGet_daan->Getanseer($questionType, $value);
                    $retuarray .= "<br><hr><br>【第" . $key . "题】" . $answer;
                }
                $rrejson = array(
                    "code" => 200,
                    "msg" => $retuarray
                );
                return response()->json($rrejson);
            } else {
                $rrejson = array(
                    "code" => -3,
                    "msg" => "获取作答内容有误",
                );
                return response()->json($rrejson);
            }
        } else {
            return $this->ffdy;
        }
    }


































    /**
     * 路由：newapi/Signin
     * 功能：免密码签到
     */

    public function Signin(Request $request)
    {
        if ($request->isMethod('post')) {
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

            $userinfo = $this->work->Get_cookie($token);

            $cookies = $userinfo['cookie'];

            $newtoken = $userinfo['newtoken'];
            $userid = $userinfo['userid'];

            array_push($this->header_, "cookie:" . $cookies);

            $checkInCode = "";



            //获取签到认证码
            $infodata = array(
                'type' => '2',
                'openClassId' => $openClassId,
                'activityId' => $activityId,
                'newToken' => $newtoken,
                "stuId" => $userid,
                'classState' => 2
            );

            $faceTeachActivityInfo = $this->curl->curl_post("https://zjyapp.zjy2.icve.com.cn/newmobileapi/faceTeach/newGetStuFaceActivityList", $this->header_, $infodata);

            $infolist = json_decode($faceTeachActivityInfo, TRUE)['dataList'];

            $Gesture = "";
            foreach ($infolist as $key => $value) {
                if ($value['Id'] == $signid) {
                    $Gesture = $value['Gesture'];
                }
                # code...
            }


            //签到数据
            $bqdata = array(
                'openClassId' => $openClassId,
                'newToken' => $newtoken,
                'signId' => $signid,
                "stuId" => $userid,
                'sourceType' => 2,
                'checkInCode' => $checkInCode,
                'checkInCode' => $Gesture,
                'activityId' => $activityId,
            );



            $redata = $this->curl->curl_post($this->get_stuSign_url, $this->header_, $bqdata);
            $bqjsonfull = json_decode($redata, TRUE);
            $bqcode = $bqjsonfull['code'];
            if ($bqcode <> 1) {
                return response()->json($bqjsonfull);
            } else {
                $kfre = $this->FunctionName($token, $mark, $t, $sign, 4);
                $kfjsonfull = $kfre;

                $kfcode = $kfjsonfull['code'];
                if ($kfcode <> 200) {
                    return $kfjsonfull;
                }

                $rerejson = array(
                    'code' => 200,
                    'msg' => '签到成功!'
                );
                return response()->json($rerejson);
            }
        } else {
            return $this->ffdy;
        }
    }




    /*
        路由：newapi/getnowclass
        功能：获取今天未签到的课程
        参数
            ustoken

    */
    public function Getnowclass(Request $request)
    {
        if ($request->isMethod('post')) {
            $ustoken = input::Get('token');

            $userinfo = $this->work->Get_cookie($ustoken);
            array_push($this->header_, "cookie:" . $userinfo['cookie']);
            $arrdddddddd = array(
                'calendar' => 'week',
            );
            $rest = $this->curl->curl_post($this->get_now_nosign_url, $this->header_, $arrdddddddd);
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

                $faceTeachActivityInfo = $this->curl->curl_post("https://security.zjy2.icve.com.cn/api/study/faceTeachInfo/faceTeachActivityInfo", $this->header_, $infodata);

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

            return response()->json($resss);
        } else {
            return $this->ffdy;
        }
    }

    /*
        路由：newapij/getallwork
        功能：获取所选科目的所有作业
        参数：
            token
            courseOpenId
            openClassId
    */
    public function Getallwork(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');
            $cookie = $this->work->Get_cookie($token)['cookie'];

            array_push($this->header_, "cookie:" . $cookie);

            $getworkall = array(
                "courseOpenId" => $courseOpenId,
                "openClassId" => $openClassId,
                "pageSize" => 200,
            );

            $reallwork = $this->curl->curl_post($this->get_select_work_url, $this->header_, $getworkall);
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
                $workrejson['msg'][$key]['homeworkTermTimeId'] = $value['homeworkTermTimeId'];
            }
            return response()->json($workrejson);
        } else {
            return $this->ffdy;
        }
    }

    /*
        路由：newapi/getallexam
        功能：获取所选课程的所有考试
        参数：
            token
            courseOpenId
            openClassId
    */
    public function Getallexam(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::Get('token');
            $courseOpenId = input::get('courseOpenId');
            $openClassId = input::get('openClassId');

            $cookie = $this->work->Get_cookie($token)['cookie'];
            array_push($this->header_, "cookie:" . $cookie);

            $getworkall = array(
                "courseOpenId" => $courseOpenId,
                "openClassId" => $openClassId,
                "pageSize" => 200,
            );

            $reallwork = $this->curl->curl_post($this->get_select_exam_url, $this->header_, $getworkall);

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
            return $this->ffdy;
        }
    }

    /*
        路由：newapi/mooczxkc
        功能：获取慕课所有课程
        参数：
        token
    */
    public function mooczxkc(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = input::get('token');
            $cookie = $this->work->Get_cookie($token)['cookie'];
            array_push($this->header_, "cookie:" . $cookie);
            $gett = $this->curl->curl_get($this->get_mooc_ke_url, $this->header_, "");

            $jsonst = json_decode($gett, TRUE);
            $ccoodde = $jsonst['code'];

            if ($ccoodde <> 1) {
                $reestat = array(
                    'code' => '-2',
                    'msg' => '获取课程异常'
                );
                return Response()->json($reestat);
            }

            $reestat = array(
                'code' => '200',

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
        } else {
            return $this->ffdy;
        }
    }

    /*
        路由：newapi/zskz
        功能：职教云刷课
        参数：
        openclassid
        user
        token
        sign
        t
    */
    public function zskz(Request $request)
    {


        if ($request->isMethod('post')) {

            $openClassId = input::get('openclassid');
            $user = input::get('user');
            $token = input::get('token');
            $sign = input::get('sign');
            $t = input::get('t');

            $userinfo = $this->work->Get_cookie($token);
            $mark = $user . $openClassId;




            $kfre = $this->FunctionName($token, $mark, $t, $sign, 12);

            $kfjsonfull = $kfre;
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            $kfsucessre = array(
                'code' => '200',
                'userid' => $userinfo['userid'],
                'newtoken' => $userinfo['newtoken'],
                'cookie' => $userinfo['cookie'],
            );

            return Response()->json($kfsucessre);
        } else {
            return $this->ffdy;
        }
    }
    /*
        路由：newapi/moocf
        功能：慕课刷课
        参数：
        openclassid
        user
        token
        sign
        t
    */
    public function moocf(Request $request)
    {
        if ($request->isMethod('post')) {
            $openClassId = input::get('openclassid');
            $user = input::get('user');
            $token = input::get('token');
            $sign = input::get('sign');
            $t = input::get('t');

            $userinfo = $this->work->Get_cookie($token);
            $mark = $user . $openClassId;
            $kfre = $this->FunctionName($token, $mark, $t, $sign, 13);

            $kfjsonfull = $kfre;
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }


            $kfsucessre = array(
                'code' => '200',
                'userid' => $userinfo['userid'],
                'newtoken' => $userinfo['newtoken'],
                'cookie' => $userinfo['cookie'],
            );

            return Response()->json($kfsucessre);
        } else {
            return $this->ffdy;
        }
    }





    /*
    MOOC 获取作业测验指纹
    */
    public function moocwork(Request $request)
    {
        if ($request->isMethod('post')) {
            $courseOpenId = input::get('courseopenid');
            $workExamId = input::get('workexamid');
            $token = input::get('token');
            $userinfo = $this->work->Get_cookie($token);
            $userid = $userinfo['userid'];
            $mark = $userid . $courseOpenId . "1";
            $t = time();
            $sign = md5("token=" . $token . "&fid=14&mark=" . $mark . "&t=" . $t . "&13f3c400fcfa97e4e41ec4c6f154c357");
            $kfre = $this->FunctionName($token, $mark, $t, $sign, 14);  //作业测验扣分
            $kfjsonfull = $kfre;
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            array_push($this->header_, "cookie:" . $userinfo['cookie']);
            $getworkarray = array(
                'courseOpenId' => $courseOpenId,
                'workExamId' => $workExamId,
                'agreeHomeWork' => 'agree',
                'workExamType' => '0',
            );
            // print_r($this->header_) ;

            // dd($getworkarray);

            $rest = $this->curl->curl_post($this->get_Mooc_Work_url, $this->header_, $getworkarray);
            // return $rest;
            try {
                $rest = json_decode($rest, TRUE);
                if ($rest['code'] != 1) {
                    $returnse = array(
                        'code' => -1,
                        'msg' => "获取作业失败",
                    );
                    return Response()->json($returnse);
                }

                $uniqueId = $rest['uniqueId'];  //试卷id
            } catch (\Throwable $th) {
                $returnse = array(
                    'code' => -3,
                    'msg' => "MOOC官方服务器异常",
                );
                return Response()->json($returnse);
            }




            // return $rest;    

            $questionIdarray = [];
            $workExamData = [];

            $workExamData = $rest['paperData']['questions'];

            if ($workExamData == []) {
                $workExamData =  json_decode($rest['workExamData'], TRUE)['questions'];
            }

            foreach ($workExamData as $key => $value) {
                array_push($questionIdarray, $value['questionId']);
            }

            // dd( $questionIdarray);

            $questionIds = $this->CURL_Erupt->MoocRequest($questionIdarray);

            $returnse = array(
                'courseOpenId' => $courseOpenId,
                'workExamId' => $workExamId,
                'uniqueId' => $uniqueId,
                'question' => $questionIds,
            );




            return Response()->json($returnse);
        } else {
            return $this->ffdy;
        }
    }


    /*
    MOOC 获取测验指纹
    */
    public function mooctest(Request $request)
    {
        if ($request->isMethod('post')) {
            $courseOpenId = input::get('courseopenid');
            $workExamId = input::get('workexamid');
            $token = input::get('token');
            $userinfo = $this->work->Get_cookie($token);
            $userid = $userinfo['userid'];
            $mark = $userid . $courseOpenId . "3";
            $t = time();
            $sign = md5("token=" . $token . "&fid=16&mark=" . $mark . "&t=" . $t . "&13f3c400fcfa97e4e41ec4c6f154c357");
            $kfre = $this->FunctionName($token, $mark, $t, $sign, 16);  //作业测验扣分

            $kfjsonfull = $kfre;
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            array_push($this->header_, "cookie:" . $userinfo['cookie']);
            $getworkarray = array(
                'courseOpenId' => $courseOpenId,
                'workExamId' => $workExamId,
                'agreeHomeWork' => 'agree',
                'workExamType' => '1',
            );
            // print_r($this->header_) ;
            // dd($getworkarray);

            $rest = $this->curl->curl_post($this->get_Mooc_Work_url, $this->header_, $getworkarray);

            try {
                //code...
                $rest = json_decode($rest, TRUE);
                if ($rest['code'] != 1) {
                    $returnse = array(
                        'code' => -1,
                        'msg' => "获取测验失败",
                    );
                    return Response()->json($returnse);
                }

                // dd($rest);
                $uniqueId = $rest['uniqueId'];  //试卷id
            } catch (\Throwable $th) {
                $returnse = array(
                    'code' => -3,
                    'msg' => "MOOC官方服务器异常",
                );
                return Response()->json($returnse);
            }




            // return $rest;    

            $questionIdarray = [];
            $workExamData = $rest['paperData']['questions'];

            if ($workExamData == []) {
                $workExamData =  json_decode($rest['workExamData'], TRUE)['questions'];
            }

            foreach ($workExamData as $key => $value) {
                array_push($questionIdarray, $value['questionId']);
            }

            // dd( $questionIdarray);

            $questionIds = $this->CURL_Erupt->MoocRequest($questionIdarray);

            $returnse = array(
                'courseOpenId' => $courseOpenId,
                'workExamId' => $workExamId,
                'uniqueId' => $uniqueId,
                'question' => $questionIds,
            );




            return Response()->json($returnse);
        } else {
            return $this->ffdy;
        }
    }











    /**
     * MOOC获取考试指纹
     */
    public function moocexam(Request $request)
    {
        if ($request->isMethod('post')) {
            $courseOpenId = input::get('courseopenid');
            $examId = input::get('examId');
            $token = input::get('token');
            $userinfo = $this->work->Get_cookie($token);
            $userid = $userinfo['userid'];
            $mark = $userid . $courseOpenId . "2";
            $t = time();
            $sign = md5("token=" . $token . "&fid=15&mark=" . $mark . "&t=" . $t . "&13f3c400fcfa97e4e41ec4c6f154c357");
            $kfre = $this->FunctionName($token, $mark, $t, $sign, 15);  //考试

            $kfjsonfull = $kfre;
            $kfcode = $kfjsonfull['code'];

            if ($kfcode <> 200) {
                return $kfre;
            }

            array_push($this->header_, "cookie:" . $userinfo['cookie']);
            $getworkarray = array(
                'courseOpenId' => $courseOpenId,
                'examId' => $examId,
                'jumpType' => '',
                'workExamType' => '2',
            );
            // print_r($this->header_) ;
            // dd($getworkarray);

            $rest = $this->curl->curl_post($this->get_Mooc_exam_url, $this->header_, $getworkarray);
            // return $rest;
            try {
                $rest = json_decode($rest, TRUE);
                $uniqueId = $rest['uniqueId'];  //试卷id
            } catch (\Throwable $th) {
                //throw $th;
                $returnse = array(
                    'code' => -1,
                    'msg' => "获取考试失败",
                );
                return Response()->json($returnse);
            }




            // return $rest['workExamData'];

            $questionIdarray = [];


            $workExamData = $rest['paperData']['questions'];
            if ($workExamData == []) {
                $workExamData =  json_decode($rest['workExamData'], TRUE)['questions'];
            }
            foreach ($workExamData as $key => $value) {
                array_push($questionIdarray, $value['questionId']);
            }

            // dd( $questionIdarray);

            $questionIds = $this->CURL_Erupt->MoocRequest($questionIdarray);

            $returnse = array(
                'courseOpenId' => $courseOpenId,
                'examId' => $examId,
                'uniqueId' => $uniqueId,
                'question' => $questionIds,
            );




            return Response()->json($returnse);
        } else {
            return $this->ffdy;
        }
    }
}







/**
 * 答案查询
 */
class CURL_Erupt
{




    //并发请求-MOOC  根据id获取答案
    function MoocRequest($nodes)
    {
        $mh = curl_multi_init(); // 创建批处理cURL句柄
        $cURLs = [];  //curl句柄组
        $datas = [];  //数据组
        $curl = new Curl_Request();
        $cookie = $curl->Get_cookie("替换为职教云账号", "替换为职教云密码")['cookie'];


        // dd($cookie);
        $header = ['cookie:' . $cookie];

        $url = 'https://mooc.icve.com.cn/design/question/previewQuestion';
        // dd($nodes);

        foreach ($nodes as $key => $value) {
            $datas[$key] = ['questionId' => $value];
            $datas[$key]['courseOpenId'] = 'fo8qaqas8khcc3qqpfrlw';
            $cURLs[$key] = curl_init();  //创建一个cURL资源
            curl_setopt($cURLs[$key], CURLOPT_URL, $url);
            curl_setopt($cURLs[$key], CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
            // curl_setopt($cURLs[$key], CURLOPT_TIMEOUT, 1);//设置延迟时间
            curl_setopt($cURLs[$key], CURLOPT_RETURNTRANSFER, 1); //设置返回结果为字符串流而不是直接输出
            curl_setopt($cURLs[$key], CURLOPT_SSL_VERIFYPEER, false); //验证curl对等证书
            curl_setopt($cURLs[$key], CURLOPT_HTTPHEADER, $header); //设置http请求时的协议头

            // dd($datas[$key]);
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
            $data = json_decode($data, TRUE);
            // dd($data);
            $answer = $data['question']['answer'];
            $questionType = $data['question']['questionType'];

            $res[$nodes[$i]] = array(
                'answer' => $answer,
                'questionType' => $questionType,
            );
        }

        foreach ($cURLs as $i => $url) {
            curl_multi_remove_handle($mh, $url); //关闭句柄
        }
        curl_multi_close($mh);
        return $res;
    }


    //并发请求  根据id获取答案
    function EruptRequest($nodes)
    {
        // 从mooc 入手
        // $mh = curl_multi_init();// 创建批处理cURL句柄
        // $cURLs=[];  //curl句柄组
        // $datas=[];  //数据组
        // $curl = new Curl_Request();
        // $cookie = $curl->Get_cookie("替换为职教云账号","替换为职教云密码")['cookie'];
        // $aw = new Api_get_aw_Controller(); 
        // return $aw->Getaw($nodes,$cookie);




        //错题分析入手
        $mh = curl_multi_init(); // 创建批处理cURL句柄
        $cURLs = [];  //curl句柄组
        $datas = [];  //数据组
        $curl = new Curl_Request();
        $cookie = $curl->Get_cookie("替换为职教云账号", "替换为职教云密码")['cookie'];

        // dd($cookie);
        $header = ['cookie:' . $cookie];
        $url = 'https://zjy2.icve.com.cn/api/report/homeworkAnalysis/wrongPreviewByHwExam';
        foreach ($nodes as $key => $value) {
            $datas[$key]['courseOpenId'] = "7dtyalorolped7cz3coqya";
            $datas[$key]['openClassId'] = "enlzaloreonnjrzmobmwug";
            $datas[$key]['questionId'] = $value;
            $datas[$key]['homeworkId'] = "xyn1alorrphhstrfzmx8lg";
            $datas[$key]['type'] = "1";

            // dd($datas[$key]);
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

            $allsretext = $this->getawawone($data)['formatting'];

            $res[$i] = $allsretext;
        }

        foreach ($cURLs as $i => $url) {
            curl_multi_remove_handle($mh, $url); //关闭句柄
        }
        curl_multi_close($mh);
        return $res;
    }

    //解析单个作业答案  适用于 错题分析api
    function getawawone($data)
    {
        $questiondata = json_decode($data, TRUE);
        // dd( $questiondata);
        //答案信息
        $context = $questiondata["questionInfo"]["optionList"];
        //返回用数组
        $aansw = array();

        switch ($questiondata["questionType"]) {

            case 1: //单选
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                foreach ($context as $key => $value) {
                    array_push($option, chr(65 + $key) . ":" . $value['Content']);
                    if ($value['IsAnswer'] === "True") {
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
                // dd($result);
                $aansw = $result;
                break;
            case 2:  //多选
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID 
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                foreach ($context as $key => $value) {
                    array_push($option, chr(65 + $key) . ":" . $value['Content']);
                    if ($value['IsAnswer'] === "True") {
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
                $aansw = $result;
                break;

            case 3:  //判断
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = "";  //答案
                $formatting = "";  //格式化完成结果

                $answercode = $questiondata["questionInfo"]['questionAnswer'];
                $formatting = "【判断题】:" . $title . "<br>" . "[答案]<br>";
                if ($answercode === "1") {
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
                $aansw = $result;
                break;
            case 4:   //填空
                // print_r($questionIds);
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果
                $formatting = "【填空题(客观)】:" . $title . "<br>" . "[答案]<br>";
                $answercode = $context;
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
                $aansw = $result;

                break;
            case 5:  //填空
                // print_r($questionIds);
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                $formatting = "【填空题(主观)】:" . $title . "<br>" . "[答案]<br>";
                $answercode = $context;
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
                $aansw = $result;
                break;
            case 6:   //问答
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                $answer = $questiondata["questionInfo"]['questionAnswer'];
                $formatting = "[问答题]" . $title . "<br>" . "[答案]：" . $answer;
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
                $aansw = $result;
                break;

            case 7:   //匹配
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                $dxjson = $questiondata['answerList']; //匹配json
                $answercode = $questiondata['answerContentList'];
                $formatting = "【匹配题】:" . $title . "<br>" . "[答案]<br>";
                foreach ($questiondata['answerList'] as $key => $value) {
                    array_push($option, [$value["OptionContent"] => $answercode[(int)$key]['OptionAnswerContent']]);
                }
                foreach ($dxjson as $key => $value) {
                    $openselectcontent = $value['OptionSelectContent'];
                    $answercontent = $value['OptionContent']; //题目
                    $optioncontent = $answercode[(int)$openselectcontent]['OptionAnswerContent'];
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
                $aansw = $result;

                break;
            case 8:  //阅读理解
                // var_dump($questionIds[$key]);
                // var_dump($context);
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果



                $formatting  = "【阅读理解】:" . $title;

                // $questionId =  $context['questionId'];
                $subquestion = $questiondata["questionInfo"]['subQuestionList'];
                foreach ($subquestion as $key => $value) {

                    $values = $value;
                    $subtype = $value['subQuestionType'];
                    switch ($subtype) {
                        case 1: //单选题
                            $m_title = $values['subTitle'];
                            $formatting = $formatting . "【单选题】" . $m_title . "<br>[选项]<br>";
                            $mm_option = array();
                            $mm_answer = "";
                            foreach ($values['subOptionList'] as $key => $value) {
                                array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                if ($value['IsAnswer'] === "True") {

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
                            $m_title = $values['subTitle'];
                            $formatting = $formatting . "【多选题】" . $m_title . "<br>[选项]<br>";
                            $mm_option = array();
                            $mmm_answer = array();
                            $mm_answer = "";
                            foreach ($values['subOptionList'] as $key => $value) {
                                array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                if ($value['IsAnswer'] === "True") {
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
                            $m_title = $values['subTitle'];
                            $mm_option = array();  //选项
                            $mm_answer = "";       //答案



                            if ($values['subQuestionAnswer'] == 1) {
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
                $aansw = $result;
                break;

            case 9:  //完形填空
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                $formatting  = "【完形填空】:" . $title;

                // $aansw = $aansw.$title;/
                // $questionId =  $context['questionId'];
                $subquestion = $questiondata['questionInfo']['subQuestionList'];

                foreach ($subquestion as $key => $value) {
                    $values = $value;
                    $subtype = $values['subQuestionType'];
                    switch ($subtype) {
                        case 1: //单选题
                            $m_title = $values['subTitle'];
                            $formatting = $formatting . "【单选题】" . $m_title . "<br>[选项]<br>";
                            $mm_option = array();
                            $mm_answer = "";
                            foreach ($values['subOptionList']  as $key => $value) {
                                array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                if ($value['IsAnswer'] === "True") {

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
                            $m_title = $values['subTitle'];
                            $formatting = $formatting . "【多选题】" . $m_title . "<br>[选项]<br>";
                            $mm_option = array();
                            $mmm_answer = array();
                            $mm_answer = "";
                            foreach ($values['subOptionList']  as $key => $value) {
                                array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                                $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                                if ($value['IsAnswer'] === "True") {
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
                            $m_title = $values['subTitle'];
                            $mm_option = array();  //选项
                            $mm_answer = "";       //答案



                            if ($values['subQuestionAnswer'] == 1) {
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
                $aansw = $result;
                break;
            case 11: //试听题
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = $questiondata["questionInfo"]["questionId"];   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "";  //格式化完成结果

                $formatting = $formatting . "<br>【试听题】<br>" . $title;


                // $questionId =  $context['questionId'];
                $subquestion = $questiondata['questionInfo']['subQuestionList'];;

                foreach ($subquestion as $key => $value) {
                    $values = $value;
                    $m_title = $values['subTitle'];


                    // $option ;// 选项
                    // $answer;  //答案
                    $m_option = array();  //选项
                    $m_z_answer = ""; //答案
                    $mm_option = array();  //选项

                    $formatting = $formatting . "<br>【单选题】:" . $m_title . "<br>[选项]<br>";

                    foreach ($values['subOptionList']  as $key => $value) {
                        array_push($mm_option, chr(65 + $key) . ":" . $value['Content']);
                        $formatting = $formatting . chr(65 + $key) . ":" . $value['Content'] . "<br>";
                        if ($value['IsAnswer'] === "True") {
                            $m_z_answer = chr(65 + $key);
                        }
                    }
                    $formatting = $formatting . "[答案]" . $m_z_answer;
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

                $aansw = $result;
                break;

            default:
                $questionId = $questiondata["questionInfo"]["questionId"];  //职教云题目ID
                $moocquestionId = "";   //mooc 题目ID
                $title = $questiondata["questionInfo"]["questionTitle"];  //标题
                $option = array(); //选项
                $answer = array();  //答案
                $formatting = "<br>" . $title . "<br>本题目没答案<br>";  //格式化完成结果
                $result = array(
                    "questionId" => $questionId,
                    "moocquestionId" => $moocquestionId,
                    "title" => $title,
                    "option" => $option,
                    "answer" => $answer,
                    "formatting" => $formatting,
                );
                $aansw = $result;
                break;
        }


        return $aansw;
    }






    // 解析单个作业答案
    function GETAWAW($daan)
    {
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
                $aansw = '<br>';
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
                            $aansw = $aansw . "<br>[多选题]:" . $title . "<br>" . "【答案】";
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


                            $aansw = $aansw . "<br>[判断题]:" . $title . "<br>" . "【答案】";
                            if ($subanswer == 1) {
                                $aansw = $aansw . "√";
                            } else {
                                $aansw = $aansw . "×";
                            }

                            //$aansw = "【答案】".$aansw;
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
}


class Get_daan
{
    function Getanseer1($type, $context)
    {
        /*
            1.单选
            2.多选
    
            3.判断
            4.填空
    
            5.填空（主观）
    
            6.问答
    
            7.匹配
    
    
            8.阅读理解
            9.完形填空
            10.文件做答题
            11.试听题
            
        */
        $aansw = '';
        switch ($type) {
            case 1:
                # code...

                $dxjson = json_decode($context['dataJson'], TRUE);
                $answercode = $context['answer'];
                $title = $context['title'];

                $studentAnswer = $context['studentAnswer'];
                $aansw = $aansw . "<br>[单选题]:" . $title . "<br>";
                $getScore = $context['getScore'];  //分数
                $aansw .= "【学生得分】" . $getScore . "<br>";
                foreach ($dxjson as $key => $value) {
                    # code...

                    if ((string)$value['SortOrder'] === (string)$studentAnswer) {
                        // dd($value['SortOrder'] );
                        $aansw .= "【学生答案】" . (string)$value['Content'] . "<br>";
                    }


                    if ((string)$value['SortOrder'] === (string)$answercode) {
                        $aansw = $aansw . "【参考答案】" . (string)$value['Content'] . "<br>";
                    }
                }

                break;

            case 2:
                $dxjson = json_decode($context['dataJson'], TRUE); //答案json
                $title = $context['title'];

                $answercode = $context['answer'];
                $answ = explode(',', $answercode);

                $studentAnswer = $context['studentAnswer'];
                $studentAnswerArray = explode(',', $studentAnswer);

                $aansw = $aansw . "<br><br>[多选题]:" . $title . "<br>";

                $aansw .= "【学生得分】" . $context['getScore'] . "<br>"; //分数

                $aansw .= "【学生答案】";

                foreach ($studentAnswerArray as $key => $value) {
                    # code...
                    foreach ($dxjson as $key => $dxval) {
                        # code...
                        if ((string)$dxval['SortOrder'] === (string)$value) {
                            $aansw .= $dxval['Content'] . '\t';
                        }
                    }
                }


                $aansw = $aansw . "<br>【参考答案】";

                foreach ($answ as $key => $value) {
                    # code...
                    foreach ($dxjson as $key => $dxval) {
                        # code...
                        if ((string)$dxval['SortOrder'] === (string)$value) {
                            $aansw = $aansw . $dxval['Content'] . '\t';
                        }
                    }
                }
                //$aansw = "【答案】".$aansw;
                break;

            case 3:

                $answercode = $context['answer'];  //参考答案
                $studentAnswer = $context['studentAnswer'];  //学生答案
                $title = $context['title'];
                $aansw = $aansw . "<br><br>[判断题]:" . $title . "<br>";
                $aansw .= "【学生得分】" . $context['getScore'] . "<br>"; //分数

                if ((string)$studentAnswer === (string)1) {
                    $aansw .= "【学生答案】" . "√<br>";
                } else if ((string)$studentAnswer === (string)0) {
                    $aansw .= "【学生答案】" . "×<br>";
                }
                if ((string)$answercode === (string)1) {
                    $aansw = $aansw . "【参考答案】" . "√";
                } else {
                    $aansw = $aansw . "【参考答案】" . "×";
                }

                break;

            case 4:
                //填空题客观
                $answercode = $context['answer'];   //参考答案
                $title = $context['title'];
                $studentAnswer = $context['studentAnswer'];  //学生答案

                $aansw = $aansw . "<br><br>[填空题(客观)]:" . $title . "<br>";
                $aansw .= "【学生得分】" . $context['getScore'] . "<br>"; //分数

                $studentAnswerArray = json_decode($studentAnswer, TRUE);
                $aansw .= "【学生答案】";
                foreach ($studentAnswerArray as $key => $value) {
                    $aansw  .= $value['Content'] . "\t";
                }
                $aansw .= "<br>【参考答案】";
                $answjson = json_decode($answercode, TRUE);
                foreach ($answjson as $key => $value) {
                    $aansw = $aansw . $value['Content'] . "\t";
                }
                break;

            case 5:
                //主观填空题
                $answercode = $context['answer'];
                $title = $context['title'];
                $studentAnswer = $context['studentAnswer'];  //学生答案

                $aansw = $aansw . "<br><br>[填空题(主观)]:" . $title . "<br>";
                $answjson = json_decode($answercode, TRUE);
                $aansw .= "【学生得分】" . $context['getScore'] . "<br>"; //分数


                $aansw .= "【学生答案】";
                $studentAnswerArray = json_decode($studentAnswer, TRUE);
                foreach ($studentAnswerArray as $key => $value) {
                    $aansw  .= $value['Content'] . "\t";
                }


                $aansw .= "<br>【参考答案】";
                $answjson = json_decode($answercode, TRUE);
                foreach ($answjson as $key => $value) {
                    $aansw = $aansw . $value['Content'] . "\t";
                }
                break;

            case 6:
                # code...

                $answercode = $context['answer'];    //参考答案
                $studentAnswer = $context['studentAnswer'];  //学生答案

                $title = "<br><br>[问答题]" . $context['title'];
                $aansw = $aansw . "<br>" . $title . "<br>";
                $aansw .= "【学生得分】" . $context['getScore'] . "<br>"; //分数
                $aansw .= "【学生答案】" . $studentAnswer . "<br>";
                $aansw .= "【参考答案】" . $answercode;

                break;

            case 7:
                # code...
                // dd($context);       
                $dxjson = json_decode($context['dataJson'], TRUE); //匹配json
                $answercode = json_decode($context['answer'], TRUE);    //参考答案
                $studentAnswer = $context['studentAnswer'];  //学生答案
                // $aansw.="【学生得分】".$context['getScore']."<br>";//分数
                $studentAnswerArray = explode(',', $studentAnswer);  //学生答案      数组
                // dd($studentAnswerArray);            
                $title = $context['title'];

                $aansw = $aansw . "<br><br>[匹配题]:" . $title . "<br>" . "【学生得分】" . $context['getScore'] . "<br>" . "【学生答案】<br>";

                foreach ($studentAnswerArray as $key => $value) {
                    $aansw .= chr(65 + $key) . "->" . chr(65 + (int)$value) . "<br>";
                }

                $aansw .= "<br>【参考答案】<br>";

                foreach ($dxjson as $key => $value) {
                    $openselectcontent = $value['optionSelectContent'];
                    $answercontent = $value['optionContent']; //题目
                    $optioncontent = $answercode[(int)$openselectcontent]['optionAnswerContent'];
                    $aansw = $aansw . $answercontent . "->" . $optioncontent . "<br>";
                }
                break;
            case 8:
                $title = $context['title'];
                $aansw = $aansw . "<br><br>[阅读理解]<br>" . $title . "【学生得分】" . $context['getScore'] . "<br>";
                //$answercode = json_decode($context['answer'],TRUE);

                $subquestion = $context['subQuestionList'];
                foreach ($subquestion as $key => $value) {
                    # code...
                    $subtype = $value['subQuestionType'];
                    switch ($subtype) {
                        case 1:
                            $dxjson = json_decode($value['dataJson'], TRUE);
                            $answercode = $value['questionAnswer'];
                            $title = $value['title'];

                            $studentAnswer = $value['studentAnswer'];
                            $aansw = $aansw . "<br>[单选题]:" . $title . "<br>";
                            $getScore = $value['getScore'];  //分数
                            $aansw .= "【学生得分】" . $getScore . "<br>";
                            foreach ($dxjson as $key => $value) {
                                # code...

                                if ((string)$value['SortOrder'] === (string)$studentAnswer) {
                                    // dd($value['SortOrder'] );
                                    $aansw .= "【学生答案】" . (string)$value['Content'] . "<br>";
                                }


                                if ((string)$value['SortOrder'] === (string)$answercode) {
                                    $aansw = $aansw . "【参考答案】" . (string)$value['Content'] . "<br>";
                                }
                            }

                            break;
                        case 2:

                            $dxjson = json_decode($value['dataJson'], TRUE); //答案json
                            $title = $value['title'];

                            $answercode = $value['questionAnswer'];
                            $answ = explode(',', $answercode);

                            $studentAnswer = $value['studentAnswer'];
                            $studentAnswerArray = explode(',', $studentAnswer);

                            $aansw = $aansw . "<br><br>[多选题]:" . $title . "<br>";

                            $aansw .= "【学生得分】" . $value['getScore'] . "<br>"; //分数

                            $aansw .= "【学生答案】";

                            foreach ($studentAnswerArray as $key => $value) {
                                # code...
                                foreach ($dxjson as $key => $dxval) {
                                    # code...
                                    if ((string)$dxval['SortOrder'] === (string)$value) {
                                        $aansw .= $dxval['Content'] . '\t';
                                    }
                                }
                            }


                            $aansw = $aansw . "<br>【参考答案】";

                            foreach ($answ as $key => $value) {
                                # code...
                                foreach ($dxjson as $key => $dxval) {
                                    # code...
                                    if ((string)$dxval['SortOrder'] === (string)$value) {
                                        $aansw = $aansw . $dxval['Content'] . '\t';
                                    }
                                }
                            }
                            //$aansw = "【答案】".$aansw;
                            break;

                        case 3:
                            # code...


                            // $title = $value['title'];

                            // $subanswer = $value['questionAnswer'];


                            // $aansw = $aansw . "<br><br>[判断题]:".$title."<br>"."【答案】";
                            // if ($subanswer == 1) {
                            //     $aansw = $aansw . "√";
                            // }else {
                            //     $aansw = $aansw . "×";
                            // }

                            // //$aansw = "【答案】".$aansw;
                            // break;

                            $answercode = $value['questionAnswer'];  //参考答案
                            $studentAnswer = $value['studentAnswer'];  //学生答案
                            $title = $value['title'];
                            $aansw = $aansw . "<br><br>[判断题]:" . $title . "<br>";
                            $aansw .= "【学生得分】" . $value['getScore'] . "<br>"; //分数

                            if ((string)$studentAnswer === (string)1) {
                                $aansw .= "【学生答案】" . "√<br>";
                            } else if ((string)$studentAnswer === (string)0) {
                                $aansw .= "【学生答案】" . "×<br>";
                            }
                            if ((string)$answercode === (string)1) {
                                $aansw = $aansw . "【参考答案】" . "√";
                            } else {
                                $aansw = $aansw . "【参考答案】" . "×";
                            }

                            break;

                        default:
                            # code...
                            break;
                    }
                }



                break;


            case 9:
                # code...
                $aansw = $aansw . "<br><br>[完形填空]";
                $title = $context['title'];
                $subquestion = $context['subQuestionList'];
                foreach ($subquestion as $key => $value) {
                    # code...
                    $subtype = $value['subQuestionType'];
                    switch ($subtype) {
                        case 1:
                            $dxjson = json_decode($value['dataJson'], TRUE);
                            $answercode = $value['questionAnswer'];
                            $title = $value['title'];

                            $studentAnswer = $value['studentAnswer'];
                            $aansw = $aansw . "<br>[单选题]:" . $title . "<br>";
                            $getScore = $value['getScore'];  //分数
                            $aansw .= "【学生得分】" . $getScore . "<br>";
                            foreach ($dxjson as $key => $value) {
                                # code...

                                if ((string)$value['SortOrder'] === (string)$studentAnswer) {
                                    // dd($value['SortOrder'] );
                                    $aansw .= "【学生答案】" . (string)$value['Content'] . "<br>";
                                }


                                if ((string)$value['SortOrder'] === (string)$answercode) {
                                    $aansw = $aansw . "【参考答案】" . (string)$value['Content'] . "<br>";
                                }
                            }

                            break;
                        case 2:

                            $dxjson = json_decode($value['dataJson'], TRUE); //答案json
                            $title = $value['title'];

                            $answercode = $value['questionAnswer'];
                            $answ = explode(',', $answercode);

                            $studentAnswer = $value['studentAnswer'];
                            $studentAnswerArray = explode(',', $studentAnswer);

                            $aansw = $aansw . "<br><br>[多选题]:" . $title . "<br>";

                            $aansw .= "【学生得分】" . $value['getScore'] . "<br>"; //分数

                            $aansw .= "【学生答案】";

                            foreach ($studentAnswerArray as $key => $value) {
                                # code...
                                foreach ($dxjson as $key => $dxval) {
                                    # code...
                                    if ((string)$dxval['SortOrder'] === (string)$value) {
                                        $aansw .= $dxval['Content'] . '\t';
                                    }
                                }
                            }


                            $aansw = $aansw . "<br>【参考答案】";

                            foreach ($answ as $key => $value) {
                                # code...
                                foreach ($dxjson as $key => $dxval) {
                                    # code...
                                    if ((string)$dxval['SortOrder'] === (string)$value) {
                                        $aansw = $aansw . $dxval['Content'] . '\t';
                                    }
                                }
                            }
                            //$aansw = "【答案】".$aansw;
                            break;

                        case 3:
                            $answercode = $value['questionAnswer'];  //参考答案
                            $studentAnswer = $value['studentAnswer'];  //学生答案
                            $title = $value['title'];
                            $aansw = $aansw . "<br><br>[判断题]:" . $title . "<br>";
                            $aansw .= "【学生得分】" . $value['getScore'] . "<br>"; //分数

                            if ((string)$studentAnswer === (string)1) {
                                $aansw .= "【学生答案】" . "√<br>";
                            } else if ((string)$studentAnswer === (string)0) {
                                $aansw .= "【学生答案】" . "×<br>";
                            }
                            if ((string)$answercode === (string)1) {
                                $aansw = $aansw . "【参考答案】" . "√";
                            } else {
                                $aansw = $aansw . "【参考答案】" . "×";
                            }

                            break;

                        default:
                            # code...
                            break;
                    }
                }



                break;

            case 11:

                $title = $context['title'];
                $subquestion = $context['subQuestionList'];
                # code...

                $aansw = $aansw . "<br><br>[试听题]<br>";

                foreach ($subquestion as $key => $value) {
                    # code...
                    $title = $value['title'];
                    $datajson = json_decode($value['dataJson'], TRUE);
                    $subanswer = $value['questionAnswer'];     //参考答案
                    $studentAnswer  = $value['studentAnswer'];  //学生答案

                    $fenshu = $value['getScore'];

                    $aansw .= "<br><br>[单选题]:" . $title . "<br>【学生得分】" . $fenshu . "<br>";

                    foreach ($datajson as $key => $value) {
                        # code...
                        if ($value['SortOrder'] == $studentAnswer) {
                            $studentex = "<br>【学生答案】" . (string)$value['Content'] . "<br>";
                        }
                        if ($value['SortOrder'] == $subanswer) {
                            $cankao = "<br>【参考答案】" . (string)$value['Content'] . "<br>";
                        }
                    }

                    $aansw .= $studentex . $cankao;
                }
                break;
            default:
                break;
        }
        return $aansw;
    }
}





class Curl_Request
{
    protected $curl;
    protected $getcookie_url = 'https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn';
    public function __construct()
    {
        $this->curl = curl_init(); //初始化CURL

        curl_setopt($this->curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 10); //设置延迟时间
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); //设置返回结果为字符串流而不是直接输出
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); //验证curl对等证书
        //curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($this->curl, CURLOPT_SSLVERSION,0);


    }

    public function curl_get($url, $header)
    {
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header); //设置http请求时的协议头

        $data = curl_exec($this->curl);

        if (curl_error($this->curl)) {
            return "Error: " . curl_error($this->curl);
        } else {
            return ($data);
        }
    }

    public function curl_post($url, $header, $postdata)
    {

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header); //设置http请求时的协议头

        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($postdata));


        $data = curl_exec($this->curl);
        if (curl_error($this->curl)) {
            return "Error: " . curl_error($this->curl);
        } else {
            return ($data);
        }
    }

    public function Get_cookie($user, $pwd)
    {

        $subdata['userName'] = $user;
        $subdata['userPwd'] = $pwd;
        $subdata['verifyCode'] = '';
        $subdata['clientId'] = md5($user);
        $subdata['sourceType'] = 2;
        $subdata['appVersion'] = $this->Get_version();
        $subdata['equipmentAppVersion'] = $this->Get_version();
        $subdata['equipmentModel'] = 'Android';
        $subdata['equipmentApiVersion'] = '7.2.1';
        $emit = time() . '000';
        $header = ['emit:' . $emit, 'device:' . $this->GetSecret($subdata['equipmentModel'], $subdata['equipmentApiVersion'], $subdata['equipmentAppVersion'], $emit)];
        //dd($header);
        curl_setopt($this->curl, CURLOPT_HEADER, 1); //其值设置为1即可返回header所有信息包括cookie

        curl_setopt($this->curl, CURLOPT_URL, $this->getcookie_url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header); //设置http请求时的协议头

        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($subdata));

        $data_cookie = [];
        $data = curl_exec($this->curl);
        if (curl_error($this->curl)) {
            echo "Error: " . curl_error($this->curl);
        } else {
            // dd($data);
            $zzbds1 = '#acw_tc=(.*?);#';
            $zzbds2 = '#auth=(.*?);#';

            preg_match($zzbds1, $data, $m);
            preg_match($zzbds2, $data, $m1);
            //strpos 查找字符串第一次出现的位置
            $wz = strpos($data, "Content-Length: ") - 1 + strlen('Content-Length: ') + 7;
            $redata = substr($data, $wz);
            //dd($data);
            //$redata = substr_replace($redata,"",'"',1);
            //$redata = substr_replace($redata,"",'"');
            $jsonall = json_decode($redata, TRUE);
            $code = $jsonall['code'];
            //dd($code);
            if ($code == -1) {
                return FALSE;
            }

            $temp = 'acw_tc=' . $m[1] . ';' . 'auth=' . $m1[1];
            $rearr = array($temp, $redata);
            // echo($temp);
            $data_cookie['cookie'] = $rearr[0];
            $data_cookie['data'] = $redata;
            //dd($data_cookie);
            return $data_cookie;
        }
    }

    public function login($user, $pwd)
    {

        $login_post_data = array(
            'userName' => $user,
            'userPwd' => $pwd,
            'verifyCode' => '',
            'clientId' => md5($user),
            'sourceType' => 2,
            'appVersion' => '2.8.25',
            'equipmentAppVersion' => '2.8.25',
            'equipmentModel' => 'Android',
            'equipmentApiVersion' => '7.2.1'
        );

        $emit = time() . '000';
        $login_header = ['emit:' . $emit, 'device:' . $this->GetSecret($login_post_data['equipmentModel'], $login_post_data['equipmentApiVersion'], $login_post_data['equipmentAppVersion'], $emit)];

        curl_setopt($this->curl, CURLOPT_URL, $this->getcookie_url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $login_header); //设置http请求时的协议头

        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->Initarr($login_post_data));

        $Redata = curl_exec($this->curl);
        return ($Redata);
    }

    protected function GetSecret($equipmentModel, $equipmentApiVersion, $equipmentAppVersion, $emit)
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

    protected function Initarr(array $arr)
    {
        $temp = '';
        foreach ($arr as $key => $value) {
            $temp = $temp . $key . "=" . $value . '&';
        }
        return $temp;
    }

    //获取职教云app版本号
    protected function Get_version()
    {
        $version_info = $this->curl_get("https://zjyapp.icve.com.cn/newmobileapi/AppVersion/getAppVersion", []);
        $json_version = json_decode($version_info, TRUE);
        return $json_version["data"]["appVersionInfo"]["versionCode"];
    }

    /* 析构函数 */
    function __destruct()
    {
        curl_close($this->curl);
    }
}

class Work_zjy
{
    protected $curl;
    protected $Get_info = "https://zjy2.icve.com.cn/api/student/Studio/index";
    public function __construct()
    {
        $this->curl = new Curl_Request();
    }
    /*
    获取用户数据的接口的签名
    */
    public function GetLoginSign($token, $t)
    {
        return md5("token=" . $token . "&t=" . $t . "&13f3c400fcfa97e4e41ec4c6f154c357");
    }
    /*
    获取用户职教云cookie
    */
    public function Get_cookie($token)
    {
        //dd();
        $data = [
            "token" => $token,
            "t" => time(),
            "sign" => $this->GetLoginSign($token, time()),
        ];
        $json_username = json_decode($this->curl->curl_post("http://yry.对接易如意域名/api.php?act=get_info&app=10000", [], $data), TRUE);
        if ($json_username['code'] === 200) {
            $yry_user = $json_username['msg']['email'];
            // dd($yry_user);

            $foron = DB::table('zjy_user')->where('yry_user', '=', $yry_user)->first();  //通过token查询本账号所绑定的职教云账号
            $cookie = $foron->zjy_cookie;
            $userid  = $foron->zjy_userid;
            $newtoken  = $foron->zjy_newtoken;
            $json_username = json_decode($this->curl->curl_post($this->Get_info, ['cookie', $cookie], $data), TRUE);  //验证cookie

            if ($json_username == null) {
                //重新获取cookie
                return $this->Setcookie($foron);
            } else {
                //直接返回cookie
                $return = array(
                    'cookie' => $cookie,
                    'userid' => $userid,
                    'newtoken' => $newtoken,
                );
                return $return;
            }
        }
    }

    /*
    将cookie 存入数据库当中 
    */

    public function Setcookie($foron)
    {
        $user = [];
        $data_cookie = $this->curl->Get_cookie($foron->zjy_user, $foron->zjy_pwd);
        $cookie = $data_cookie['cookie'];
        //dd(strval($cookie));
        $data = $data_cookie['data'];
        $jsonfall = json_decode($data, TRUE);
        $userid = $jsonfall['userId'];
        $newtoken = $jsonfall['newToken'];
        //dd($jsonfall['code']);
        if ($cookie == FALSE) {
            return 1;
        } else {

            $ret = DB::table('zjy_user')->where('yry_user', '=', $foron->yry_user)->update(
                [
                    'zjy_cookie' => "$cookie",
                    'zjy_userid' => $userid,
                    'zjy_newtoken' => $newtoken,
                ]
            );
            //dd($ret);
            if ($ret == TRUE) {
                $return = array(
                    'cookie' => $cookie,
                    'userid' => $userid,
                    'newtoken' => $newtoken,
                );
                return $return;
            } else {
                return 2;
            }
        }
    }
}

class Api_get_aw_Controller
{

    /*--------------------------------------------------*/
    protected $SaveExamTest_url = "https://mooc.icve.com.cn/design/HomeworkExamTest/Save";

    protected $g_ExamList_url = "https://mooc.icve.com.cn/design/onlineExam/getOnlineExamList";
    /*--------------------------------------------------*/

    protected $curl;
    public function __construct()
    {
        $this->curl = new Curl_Request_base();
    }

    public function Getaw($questionids, $cookie)
    {

        // $coo/kie = "themes=; acw_tc=707c9fc316143443579574963e60468f88f4faee8dc3d2ebf8102718d3bf1c; Hm_lvt_a3821194625da04fcd588112be734f5b=1614082215,1614082436,1614255013,1614344365; Hm_lpvt_a3821194625da04fcd588112be734f5b=1614344365; verifycode=56E5C1EF6FC4263169A91755F97B32CD@637499699590411854; auth=01025A780B5956DAD808FE5A88B72AAADAD8080116750069003700680061006C006F00720034006B003100700061006C006F006C007A003300720078007100670000012F00FF7665F3F6A37A165545FC14EBA7538E18B18EC014; token=9epzadusd7bprd30xf1amq; ui7halor4k1palolz3rxqgpageSize=50";


        $p_titles = array();
        $p_questionid_ben = array();






        // $questionids = ["pdmkaborek1g1z7wqhhwlq","v2f1alor4pbh8nvafudaq","q0akabori7hlyblfpevtdq","v2f1alorsznj0a1cu96zvg","sbhgaaosrizngdxndgmdg","v2f1alorxbpm3uqhiznhxq","v2f1alor55zmvk21wwwpnq","lrvcaaos37zk2lgq21lcg","v2f1alorj49mz4ejtjahog","q0akaborf5joexzourkya","q0akaborgl5d0yib2sdbq","zi0qaborebnd61y0sram2g","v2f1alorfolpaqdacd0wcq","ehlgaaosob1bawggksrmmq","v2f1alorrzfl9ypxwtgfag","v2f1alorop5itsypx7nhqg","lrvcaaosujregdj1njpcca","lrvcaaoshrdi6xvpjbbiuq","pdmkaborsr9kdh0xqm9c2a","v2f1alorlzpcbl2qsudp8g","pdmkaborw5bp1udkqqqsba","lrvcaaos24tdpx4wu9acma","sbhgaaosaoxgw9u1dbrfqg","v2f1alorzrponcqs9uobna","q0akaborrklkdjcy0mge4q","pdmkabora1jus1nga0cqg","pdmkaborzoxb0juf5s3q2w","v2f1alorvblnam7e2ealug","q0akaborl7vnjqpdmpo1jw","lrvcaaosrqtocyvjuxut6a","q0akabor5tpsj4a8ldbva","v2f1alors6rjpsivtnrwcq","v2f1alordijo7wm91xffjg","v2f1alor2j9pgzxqfihxlq","sbhgaaoscpxkjnseicw25g","q0akaborpllhevltptb74q","pdmkaborrjjkqy9yltqwa","ehlgaaos071gysemeytlsg","pdmkaboro75li30tczinlq","pdmkabor6zvfu5wpaxl9fa","v2f1alorvzpdyzm4uqzawq","pdmkabor8kxbljl03u24yq","oxgkaborkzdjjg5xef1ma","ehlgaaosarbpmmeq3lq45q","q0akaborpjfgbrpyusmaza","v2f1alorbqfelwkakimq","pdmkaborgp9cih6zjtm2bg","lrvcaaostipe8dtmzpyvhq","v2f1alorx55ozvokgouola","sbhgaaoslbbcdlqw9vtf2g","pdmkabori4lfit1odon3kq","v2f1alorn4lhpxl1czrfa","v2f1aloril1hbwyjhbzjqg","v2f1alor4zvigm0cmy8cgw","sbhgaaos6fd21wtqjvv0a","v2f1alorppfk3xdcibejg","v2f1alorxydgmvvzsynyhg","v2f1aloryjfkvzrzentrdq","lrvcaaosc55liym8qo39ma","v2f1alorb4vn6azlagnrha","v2f1alorv7jjtmviston7w","v2f1alord6denywdlfyvpg","q0akaborcivkf8vkqqbkva","v2f1alorqqtjhexktpqe0g","v2f1alorgink57ljstllq","pdmkaborjlpmujshrp10xw","lrvcaaosiobiyvcklepmha","q0akaborcalpykb7jw5f6q","sbhgaaosijbioiwbji0ka","q0akaborvpbgduqzvsug2q","v2f1alorr5reicam8cdg5g","q0akaborro5hdledyqaz2w","v2f1alordzllu7igmllg","sbhgaaos675ffeeyj7mnva","pdmkabory6lb6w1c6peuxg","q0akabor1kxlsyumo6jyfg","sbhgaaoslr1ptm1k9lm8nq","v2f1alorn4tadpl1cw9oda","lrvcaaosbrbgu0kfofsnfa","v2f1alorpo1b9hinohmegq","pdmkaborpafphwnxc8zpca","q0akabord7pmomt9vzmixg","lrvcaaoszpdgxfrdlb6wgw","sbhgaaosiztmsw6cptbcqg","q0akaborj4pbw5fof0jcw","v2f1aloroaraynhpiubvw","pdmkaborpkjg7ffkb8ntga","v2f1alorq6hfmlk60stwg","v2f1aloruppntowipzvvg","v2f1alorrbrmgbuk3200ow","v2f1alorlltdf8nwmlhna","sbhgaaosxoberltcxipfzg","v2f1alorezdfiozyh5iewg","lrvcaaosgkfbi5ljs2sig","hexvacurjkxceztdf2qr2a","v2f1alorrlff7uo53wunhg","v2f1alor76nkdm9dpyiksg","v2f1aloreb1llgy7vfnw2q","lrvcaaosw6defubfelz8w","hqyqaborv49kcwic0bjy9a","trukaborybdnj8w5zbucww","uthbangrjjtju65grpodrw","v2f1alorebdekuvmqeyrg","v2f1alor1atlp89rsgfprg","v2f1aloryjtafwmvu2gfna","v2f1alorc7jozfrylm9hsa","lrvcaaosvrzcptqsmgrimg","lrvcaaossy9pumftikkjg","v2f1alorkypcin7cntcvfa","lrvcaaoserzewey63u8mzg","lrvcaaosxqxafnbqscdknw","lrvcaaosi51n5i6ghw3fmg","lrvcaaosy4zfu4srkxoxw","trukaborok5bzmoi6chw","v2f1alorw5vpzd7kwlt8ow","lrvcaaosirpki9l7bfo7a","v2f1alorl4fo2o1egg8tya","trukaborspjgr73y8cskcg","q0akabor5yvhfquaft1eoq","v2f1alornp9asdka9w65xa","v2f1alordbdkqc4ncvswbw","lrvcaaoslo5j8t6g69gea","o8uqaborlkhb0q1inlmkw","7t8kaborayjothqfsck1cw","7t8kaborcpbhnqi8lftjbg","q0akabortixjxk6zya4rg","pdmkaborpbbgdepcaiccq","7t8kaborvknebbdhzsphow","zcyoaborib1fv8dfdemzew","7t8kabord4zgyvil3ujhnq","q0akaborzandyqahmg9w","zcyoaborb7vmltei4sddea","zcyoaborqzpmc5g6incaw","7t8kaborqido3z1jla9pq","zcyoaboraqbaw44m77buba","7t8kaborfabhrs6nbulhaq","7t8kaborfanpytbt94poaq","7t8kaborjyjlx0qyklwn0w","7t8kaborgbre01etsoxjq","q0akabor6zeu9syrmib9g","zcyoaborkjxovtoyq4txhw","zcyoaboraarh0ccoak1uew","zcyoaborzlriwuc5ibgwqq","q0akabor3khcq2o0aaehla","hexvacure6jlnhzdxhrqtw","pdmkabornjjmy3i9hjlqg","uthbangrtivefvmwdv2k0a","pdmkaborolphpbxuiozi5a","zcyoaborfohigew6vwjkiw","zcyoaborjjfearjxxnygwq","pdmkaborbqzjp6bps6m0bw","zcyoaborno9am2d1mimxow","pdmkaborwatcuvjklqvdnq","wmzae6rhb1ck9slyyr3xa","7t8kabor5kxmuf5sjeiha","qhitabor7ztee6md9efbka","fxlgaaosiyfdbttccrgyow","xhgaaosrapikh0zq6z0ig","lrhgaaospyrmkzcwfc0ww","cbhgaaosz6tki2p9vwsezq","bxhgaaosjq1pvvkifcioyq","5xdgaaospkhmklahlxzvxw","hg7gaaosw6lmuqthivelpq","ya7gaaoseztawz6sod797g","tw3gaaosp7dl79h5fr2e8a","hwgaaosjzgqdky0mtzq","fa7gaaosnkznezs4wa8wvq","zggaaosc4lnedffxhmdrw","bxhgaaosclrflxsbjh7fxq","kxdgaaos8ihaeppvuyhg","lrhgaaossztj1snxn6jeq","brdgaaoskotefazczvzn1w","irdgaaosy5fgaznsisdug","owgaaoseldpwfxfeq6vrq","vg7gaaosa7nlhwgsw9mlw","txhgaaos95hmh3yyybukxa","kxdgaaospbdgynzqrsfbqg","xhgaaosoabkftcbochpa","txhgaaos5a1kvls0sfiza","q3gaaos951lxtlaoxymxw","0ggaaos56vequv1brfdsq","ps4rabortqzm7w58rnya","5xdgaaoskrrlqvkcq2skza","tbdgaaosu69afvbaj4uiw","chdgaaosk5rmngnvwobehw","7ggaaoslkxp0cef4fsyig","vg7gaaosxzbekrrjn6wla","sggaaoshplchani1fjlbg","wrlgaaos6jgpk1f2bsow","chdgaaosv7tnjiodvrdqtq","2hhgaaosoo9ixhydlwpiua","iq3gaaoswbdnoph4cnrz8a","dq3gaaoso9jh5j4jqmxrg","og7gaaosclngynrfuyqyw","rggaaosu5nbvt8jvnkunw","aa3gaaospzreopqffvzpa","xhdgaaosqafnfggqcgljg","ehlgaaos6i9ckg03hsm64g","xhdgaaos1j1ideqdk0kvsa","sw3gaaos57rdrf3q1w6vw","tw3gaaosx6pc8opsqenr9g","fxlgaaosf5bps1uhi3kmfw","kqgaaost6fjcfm4lebcqq","jxhgaaoskbhlefkyakgc9w","nq7gaaosbaxbquo8pq4kxw","0w3gaaosga1ot5ywimiuw","0w3gaaosv6bpdtomvsh7a","hg7gaaosppphv0nkz1xewq","dq3gaaosbjne4qjov19eg","7ggaaoskplo0fsx3ejyog","wrlgaaosjajo5jqrewfajw","2hhgaaosk4ze7mn80suda","kqgaaosgida2acmfr6mma","kg3gaaos8r5lzgl7pj5wcg","aa3gaaosilxdknytt85wsw","iq3gaaosoq5ipowiytxoxa","fa7gaaosbzjjknhpgzxyna","hwgaaosuyndorjmfrenq","irdgaaosa5bgmipv1dfrlg","brdgaaosi4thcaul2zrt4w","sw3gaaospzxax5kmrhyyua","0ggaaosz6zdnapyxtgbwa","nq7gaaospypa6ud8exqig","q3gaaoskppdnufqdnn44g","ya7gaaos1rbk0rbbqv6ztw","jxhgaaosu75encaa0xtymw","3g7gaaosq5jlt8bcfvxmka","zggaaoszbtfojd6pdypg","sbhgaaospkxexbmcntpx8w","cbhgaaosj7bk22yp92xnpg","tbdgaaoss6jbg4pkyowzlw","kg3gaaoss4rnctj2n2vzlw","og7gaaosd6zmiuiqdbophg","3g7gaaos0rthamledipuea","owgaaoseklcors5oyvilg","afiraborkrviy31ceg42qw","fposaborz4vpwtgvggltlw","xhgaaosulxizz31wyugg","hg7gaaoshptoiauprmm1lw","5xdgaaosb5faieehsfkybq","xhdgaaos3affhbvqavpteq","2hhgaaos6p9gklp0lw8mna","aa3gaaos0zjclr06scp5mw","fxlgaaoscbblgms4j2rqrq","tw3gaaosoxmbslogmf51a","kg3gaaos2rdkvkq3oy0zgg","chdgaaoszynf5zkai1lvsw","irdgaaosj5ngrcbl2vnhg","og7gaaosqqvfayie7yka","sw3gaaosna1gvpdww2zgiw","cbhgaaosoz1fwbu7pnbnuq","sggaaosqqfnpxzl52wkbw","jxhgaaos3otgq6dgw4zbpw","kqgaaosc6vialcp5vydiw","wrlgaaosxlvp5p1cm1tc6q","0w3gaaoslinfn9giy9ppbg","q3gaaoslbzbftpqcoim5w","sbhgaaosbqtnw9ve08ahgw","hwgaaosa7zfhyazm3khmg","bxhgaaosxalmlxauxb75ig","fa7gaaos1pljixzgdzotcw","0ggaaosl51ngyztmpob7a","dq3gaaos34pot9utg6xk1a","txhgaaoswrrg8xlpfwlqxa","brdgaaosepjpxvuledosra","3g7gaaosgkteua7ljfcw2g","nq7gaaosvbvggfk1ovgovw","ya7gaaosblrdz35r68a00g","owgaaosgrjmhcrmkpzfqq","kxdgaaoskpxbryt9nk1rqq","vg7gaaosj71nnitgyn4ew","7ggaaos9l9oe2hzrzjnxq","iq3gaaoslzdjcdhlqimog","tbdgaaosq7vgvajnxilfza","zggaaoskkfmvn58erpooq","ehlgaaoscjrlmyufhh3rha","zaqtabor4ovhki38yrmw3w","lrhgaaosuj5mc5jg8htslg","whooabor1rnizmxzgf9cag","whooaborfa5hn22ycfsieq","whooaborioxp9dhadzu5vq","whooaborn7jd40800reg0g","whooaborrydgjx1exvhow","whooabortonh7jxecq6wgw","whooaborfjvn7recvzkj4g","whooabori6hdpwwyahadq","whooabort5fdfvlaysoewq","enatabor7inkpnhxcirarw","whooaborg55ifrdouhlpza","oxgkabor1jxg9ejxg644q","ni8oaborikdhgxwvlshppa","pvkkaborma1nsgni1imt0w","ni8oaborgabedo2dwu1b9w","oxgkaboroq1olgasw1qcw","oxgkabor1prbckzuvm9tdq","ni8oaboruzlee9iw8mqcjq","oxgkabora6zodxu5ztjkaq","oxgkabora6fl7qnjil9kfg","oxgkaborkj9cu8wuju19xw","ni8oaboryb5myowj2iqhq","qiyuaborkjcnnexc2uug","ni8oabor7qdbkrld9nsytq","oxgkaborkqtjkbqxygbia","y6mangrqolcwunopeq3zq","ni8oaborfa5fci8mh6k93g","ni8oaborq4fimouat83zsw","ni8oabort6hnd4i2eabuva","pvkkaboryltgv0gywbxwsg","oxgkaborvkpdsxigcuenjg","oxgkaborkldchdjqju7u0w","oxgkaborjqlaztcvg1bnq","ni8oabor25bkqaaii3tuw","ni8oabor2anpymdyjkrzra","mpsuaborbahbh0h7b7ltwa","rooangrd5zb7zwpyk9tw","y6mangrfydoj0bsvdviuq"];
        //1. 查询自己的数据库 找到的就存数组 格式为  ["pdmkaborek1g1z7wqhhwlq"=>"formatting字段"]
        //2. 没找到的 存lse一个新的数组 格式为 $questionids = ["pdmkaborek1g1z7wqhhwlq","v2f1alor4pbh8nvafudaq",]
        //3. 

        /* 查询数据库未找到id的存入次数组 */
        $p_dbnullarr = array();
        /* 查询数据库找到id的存入该数组 */
        $p_dbisarr = array();

        /* 返回从api中解析好的数组 */
        $p_awallarr = array();

        foreach ($questionids as $key => $value) {
            $p_dbrs = $this->Select($value);
            if ($p_dbrs == null) {
                array_push($p_dbnullarr, $value);
            } else {
                $p_dbisarr[$value] = $p_dbrs;
            }
        }


        $arrlen = sizeof($p_dbnullarr);
        // print_r($arrlen );

        $isnull = false;  //这个是false的话就证明他是空的不用请求

        if ($arrlen > 100) {
            //生成多张试卷
            $intdiv = (int)($arrlen / 100);
            $hundred = array();
            $forstart = 0;
            for ($i = 1; $i <= $intdiv; $i++) {
                $forend = $i * 100;
                $hundred = array();
                for ($j = $forstart; $j < $forend; $j++) {
                    array_push($hundred, $p_dbnullarr[$j]);
                }
                array_push($p_questionid_ben, $hundred);
                array_push($p_titles, $this->generate($hundred, 1, $cookie));
                $forstart = $forend;
            }
            $intval =  $intdiv * 100;
            $rem = $arrlen - $intval;
            if ($rem != 0) {
                $hundred = array();
                for ($i = $intval; $i < $arrlen; $i++) {
                    array_push($hundred, $p_dbnullarr[$i]);
                }
                array_push($p_questionid_ben, $hundred);
                array_push($p_titles, $this->generate($hundred, 1, $cookie));
            }
        } else if ($arrlen == 0) {
            //证明不用生成试卷
            $isnull = true;
        } else {
            //生成一张试卷
            array_push($p_questionid_ben, $p_dbnullarr);
            array_push($p_titles, $this->generate($p_dbnullarr, 1, $cookie));  //生成考试
        }

        // dd("5215415221212121212");



        if (!$isnull) {
            // print_r("=======p_titles->");
            // print_r($p_titles);
            // print_r("=======");
            $p_ids = $this->GetExtList($p_titles, $cookie);

            $p_it_ids = array_reverse($p_ids);

            // print_r($p_it_ids);

            $p_allawarr = array();
            // dd($p_questionid_ben);
            // foreach ($p_it_ids as $key => $value) {
            //     $p_it_ids[$key]=str_replace("\n","",$value);
            // }
            // echo "p_it_ids->";
            // print_r($p_it_ids);
            foreach ($p_it_ids as $key => $value) {
                // echo "p_it_ids->key".$key;
                // echo "\n-------------------------------------------------------\n";

                $p_aawarr = $this->Getanseer($value, $p_questionid_ben[$key], $cookie);
                // var_dump($p_aawarr);
                if ($p_aawarr != null) {
                    $p_allawarr = array_merge($p_allawarr, $p_aawarr);
                }
            }



            foreach ($p_allawarr as $key => $value) {
                $this->insert($value['questionId'], $value['moocquestionId'], $value['title'], $value['option'], $value['answer'], $value['formatting']);

                $p_dbisarr[$value['questionId']] = $value['formatting'];
            }
        }

        // var_dump ($questionids );
        // 


        // dd($p_dbisarr);
        // 

        $retulist = array();
        foreach ($questionids as $key => $value) {
            try {
                array_push($retulist, $p_dbisarr[$value]);
            } catch (\Throwable $th) {
                array_push($retulist, "<br>本题目解析失败<br>");
            }
        }


        return $retulist;
    }



    function multi2array($arr)
    {
        if (is_array($arr)) {
            return json_encode($arr);
        }

        return $arr;
    }



    // questionbase
    public function insert($questionId, $moocquestionId, $title, $option, $answer, $formatting)
    {
        try {
            //code...
            $stroption = "";
            $stranswer = "";
            if (is_array($option)) {
                $stroption = $this->multi2array($option);
            }

            if (is_array($answer)) {
                $stranswer = $this->multi2array($answer);
            }

            $db = DB::table('questionbase')->insert(
                [
                    'questionId' => $questionId,
                    'moocquestionId' => $moocquestionId,
                    'title' => $title,
                    'option' => $stroption,
                    'answer' => $stranswer,
                    'formatting' => $formatting
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function Select($questionId)
    {
        $db = DB::select('select formatting from questionbase where questionId = ?', [$questionId]);

        if (sizeof($db) <= 0) {
            return null;
        }
        return ($db[0]->formatting);
    }

    public function generate($questionids, $count, $cookie)
    {

        $p_title = md5($this->get_total_millisecond() + rand(1, 10000) + rand(1, 100));



        $arr = array();
        $questions = "";
        foreach ($questionids as $key => $val) {
            $arr[$key]['Id'] = '';
            $arr[$key]['questionId'] = $val;
            $arr[$key]['Score'] = 1;
            $arr[$key]['questiontype'] = 1;

            if ($questions == "") {
                $questions = $val;
            } else {
                $questions = $questions . "," . $val;
            }
        }

        $arrlen = sizeof($questionids);

        $FristScore = 100 - $arrlen;
        $arr[0]['Score'] = $FristScore + 1;

        $qqq =  array(
            $questionDataarr = array(
                'bigQuestionId' => '',
                'bigQuestionTitle' => '第' . $this->numToWord($count) . '大题',
                'bigQuestionRemark' => '学习学个屁',
                'sortOrder' => 0,
                'totalScore' => 100,
                'fixedQuestion' => json_encode($arr)

            )
        );



        $alldata = array(
            'courseOpenId' => 'uw1aadisiabhzzpzjicqna',
            'courseId' => 'ifnaadisxzdp9qh0zteilw',
            'resId' => '',
            'totalScore' => 100,
            'questionIds' => $questions,
            'type' => 2,
            'Title' => $p_title,
            'replyCount' => 1,
            'Remark' => '请在规定时间内完成',
            'limitTime' => 60,
            'questionData' => json_encode($qqq),
            'zjyQuestionIds' => $questions,
            'zhzjQuestionIds' => '',
            'moocQuestionIds' => '',
            'DateLine' => '2099-01-01',
            'IsAllowDownLoad' => 0
        );

        $header = array(
            'Content-type: application/x-www-form-urlencoded',
            'Cookie: ' . $cookie
        );

        $p_addexrs = $this->curl->curl_post($this->SaveExamTest_url, $header, $alldata);
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print($p_addexrs);
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        // print_r("\n");
        $p_addexrs_json = json_decode($p_addexrs, TRUE);
        $p_addexrs_code = $p_addexrs_json['code'];

        // print_r("p_addexrs_json->");
        // print_r($p_addexrs_json);
        if ($p_addexrs_code == 1) {
            return ($p_title);
        }
        return 0;
    }


    function GetExtList($titlearr, $cookie)
    {

        $p_ids_arr = array();

        $p_header = array(
            'Content-type: application/x-www-form-urlencoded',
            'Cookie: ' . $cookie
        );

        $p_header_data = array(
            'courseOpenId' => 'uw1aadisiabhzzpzjicqna',
            'pageSize' => 50,
            'page' => 100000000
        );

        $p_getextts_data = $this->curl->curl_post($this->g_ExamList_url, $p_header, $p_header_data);
        $p_getextts_json = json_decode($p_getextts_data, TRUE);
        $p_code = $p_getextts_json['code'];
        if ($p_code == 1) {
            $p_list = $p_getextts_json['list'];
            // echo "titlearr->";
            // print_r($titlearr);
            for ($i = (sizeof($p_list) - 1); $i >= 0; $i--) {

                foreach ($titlearr as $key => $value) {
                    // echo "titlearr[value]->".$value."\n";
                    // echo "p_list[$i]['title']->".$p_list[$i]['Title']."\n";
                    if ($value == $p_list[$i]['Title']) {
                        $p_aid = $p_list[$i]['Id'];
                        array_push($p_ids_arr, $p_aid);
                    }
                }
            }
        }

        return $p_ids_arr;
    }


    /**--根据考试ID获取答案--**/
    function Getanseer($examId, $questionIds, $cookie)
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
        $aansw = array();

        $header = array(
            'Content-type: application/x-www-form-urlencoded',
            'cookie: ' . $cookie,
        );
        $brdata = array(
            'courseOpenId' => "uw1aadisiabhzzpzjicqna",
            "examId" => $examId,
        );
        $wawrete = $this->curl->curl_post('https://mooc.icve.com.cn/design/onlineExam/onLineExamPreview', $header, $brdata);

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
            // dd($questionsjson);
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

                        $aansw[$result["questionId"]] = $result;
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
                        $aansw[$result["questionId"]] = $result;
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
                        $aansw[$result["questionId"]] = $result;
                        break;
                    case 4:   //填空
                        // print_r($questionIds);
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
                        // print_r($formatting);
                        // dd($result);
                        $aansw[$result["questionId"]] = $result;

                        break;
                    case 5:  //填空
                        // print_r($questionIds);
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
                        $aansw[$result["questionId"]] = $result;
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
                        $aansw[$result["questionId"]] = $result;
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
                        $aansw[$result["questionId"]] = $result;

                        break;
                    case 8:  //阅读理解
                        // var_dump($questionIds[$key]);
                        // var_dump($context);
                        $questionId = $questionIds[$key];  //职教云题目ID
                        $moocquestionId = $context['questionId'];   //mooc 题目ID
                        $title = $context['Title'];  //标题
                        $option = array(); //选项
                        $answer = array();  //答案
                        $formatting = "";  //格式化完成结果



                        $formatting  = "【阅读理解】:" . $title;

                        // $questionId =  $context['questionId'];
                        $subquestion = $awajsonfull['paperData']['subQuestions'];
                        foreach ($subquestion as $key => $value) {
                            if ($value['QuestionId'] != $moocquestionId) {
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
                        $aansw[$result["questionId"]] = $result;
                        break;

                    case 9:
                        $questionId = $questionIds[$key];  //职教云题目ID
                        $moocquestionId = $context['questionId'];   //mooc 题目ID
                        $title = $context['Title'];  //标题
                        $option = array(); //选项
                        $answer = array();  //答案
                        $formatting = "";  //格式化完成结果

                        $formatting  = "【完形填空】:" . $title;

                        // $aansw = $aansw.$title;/
                        // $questionId =  $context['questionId'];
                        $subquestion = $awajsonfull['paperData']['subQuestions'];

                        foreach ($subquestion as $key => $value) {
                            if ($value['QuestionId'] != $moocquestionId) {
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
                        $aansw[$result["questionId"]] = $result;
                        break;
                    case 11:

                        $questionId = $questionIds[$key];  //职教云题目ID
                        $moocquestionId = $context['questionId'];   //mooc 题目ID
                        $title = $context['Title'];  //标题
                        $option = array(); //选项
                        $answer = array();  //答案
                        $formatting = "";  //格式化完成结果

                        $formatting = $formatting . "<br>【试听题】<br>" . $title;


                        // $questionId =  $context['questionId'];
                        $subquestion = $awajsonfull['paperData']['subQuestions'];

                        foreach ($subquestion as $key => $value) {
                            # code...

                            if ($value['QuestionId'] != $moocquestionId) {
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
                            $formatting = $formatting . "[答案]" . $m_z_answer;
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

                        $aansw[$result["questionId"]] = $result;
                        break;

                    default:
                        $questionId = $questionIds[$key];  //职教云题目ID
                        $moocquestionId = "";   //mooc 题目ID
                        $title = $context['Title'];  //标题
                        $option = array(); //选项
                        $answer = array();  //答案
                        $formatting = "<br>" . $title . "<br>本题目没答案<br>";  //格式化完成结果
                        $result = array(
                            "questionId" => $questionId,
                            "moocquestionId" => $moocquestionId,
                            "title" => $title,
                            "option" => $option,
                            "answer" => $answer,
                            "formatting" => $formatting,
                        );

                        $aansw[$result["questionId"]] = $result;
                        break;

                        break;
                }







                // } catch (\Throwable $th) {
                //     return null;
                // }
                // return null;
            }
            // } catch (\Throwable $th) {
            //     return null;
            // }
            //返回东西
            return $aansw;
        }
        return null;
    }



    /* 返回字符串的毫秒级时间戳 */
    function get_total_millisecond()
    {
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }


    /* 数字转一二 */
    function numToWord($num)
    {
        $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        $chiUni = array('', '十', '百', '千', '万', '十', '百', '千', '亿');
        $chiStr = '';
        $num_str = (string)$num;
        $count = strlen($num_str);
        $last_flag = true; //上一个 是否为0
        $zero_flag = true; //是否第一个
        $temp_num = null; //临时数字
        $chiStr = ''; //拼接结果
        if ($count == 2) { //两位数
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        } else if ($count > 2) {
            $index = 0;
            for ($i = $count - 1; $i >= 0; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag) {
                        $chiStr = $chiNum[$temp_num] . $chiStr;
                        $last_flag = true;
                    }

                    if ($index == 4 && $temp_num == 0) {
                        $chiStr = "万" . $chiStr;
                    }
                } else {
                    if ($i == 0 && $temp_num == 1 && $index == 1 && $index == 5) {
                        $chiStr = $chiUni[$index % 9] . $chiStr;
                    } else {
                        $chiStr = $chiNum[$temp_num] . $chiUni[$index % 9] . $chiStr;
                    }
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index++;
            }
        } else {
            $chiStr = $chiNum[$num_str[0]];
        }
        return $chiStr;
    }
}