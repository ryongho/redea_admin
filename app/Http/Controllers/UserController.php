<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Login;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer; 

class UserController extends Controller
{

    public static function get_list(Request $request){
        $row = 30;

        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }
        $offset = (($page_no-1) * $row);
        

        $rows = DB::table('login')
                    ->select('name',
                            'email',
                            'organization',
                            'user_idx',
                            DB::raw('(select count(*) from table_users where table_users.user_idx = login.user_idx) as table_cnt '))
                    ->limit($row)->orderby('user_idx','desc')->offset($offset)->get();

        $count = DB::table('login')->select('*')->count();

        $list = new \stdClass;

        $list->total_page = floor($count/$row)+1;
        
        $list->page_no = $page_no;
        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $rows;
        
        return $list;
        
    }

    public static function get_table_list(Request $request){
        $row = 30;

        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }
        $offset = (($page_no-1) * $row);
        
        $search_type = $request->search_type;
        $search_keyword = $request->search_keyword;

        $search = null;
        if($search_type){
            $search = $request->search_type.",".$request->search_keyword;
        }

        $rows = DB::table('redea_tables')
                ->select(   'table_idx',
                            'name',
                            'field_count',
                            'record_count',
                            DB::raw('(select count(*) from table_users where table_users.table_idx = redea_tables.table_idx) as user_cnt ')
                        )
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    if($search_arr[0] == "name"){
                        return $query->where('name',"like", "%".$search_arr[1]."%");
                    }else{
                        $table_arr = DB::table('table_users')->where('user_idx',$search_arr[1])->pluck('table_idx')->toArray();
                        return $query->whereIn('table_idx' , $table_arr );
                    }
                        
                })
                ->limit($row)->orderby('table_idx','desc')->offset($offset)->get();
            
        $count = DB::table('redea_tables')->select('*')
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    if($search_arr[0] == "name"){
                        return $query->where('name',"like", "%".$search_arr[1]."%");
                    }else{
                        $table_arr = DB::table('table_users')->where('user_idx',$search_arr[1])->pluck('table_idx')->toArray();
                        return $query->whereIn('table_idx' , $table_arr );
                    }
                        
                })->count();

        $list = new \stdClass;

        $list->total_page = floor($count/$row)+1;
        
        $list->page_no = $page_no;
        $list->status = "200";
        $list->msg = "success";
        $list->search_type = $request->search_type;
        $list->search_keyword = $request->search_keyword;
        
        $list->data = $rows;
        
        return $list;
        
    }

    public static function get_wait_list(Request $request){

        $row = 30;

        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }
        $offset = (($page_no-1) * $row);
        
        $rows = DB::table('register_waitlist')->limit($row)->orderby('wait_idx','desc')->offset($offset)->get();

        $count = DB::table('register_waitlist')->count();

        $list = new \stdClass;

        $list->total_page = floor($count/$row)+1;
        
        $list->page_no = $page_no;
        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $rows;
        
        return $list;
        
    }

    public function regist_proc(Request $request)
    {
        
        /* ?????? ?????? - start*/
        $email_cnt = User::where('email',$request->email)->count();
        $id_cnt = User::where('user_id',$request->user_id)->count();

        $return = new \stdClass;

        if($email_cnt){
            $return->status = "602";
            $return->msg = "???????????? ?????????";
            $return->email = $request->email;
            return redirect()->back()->with('alert',$return->msg);
        }elseif($id_cnt){
            $return->status = "601";
            $return->msg = "???????????? ?????????";
            $return->user_id = $request->id;
            return redirect()->back()->with('alert',$return->msg);
        }else{
            $result = User::insertGetId([
                'email' => $request->email, 
                'password' => $request->password, 
                'user_id' => $request->user_id,
                'name' => $request->name,
                'created_at' => Carbon::now(),
                'password' => Hash::make($request->password)
            ]);

            if($result){

                Auth::loginUsingId($result);
                $login_user = Auth::user();
                $return->status = "200";
                $return->msg = "success";
                $return->data = $request->name;

                return redirect()->route('main');
            }else{
                $return->status = "500";
                $return->msg = "?????? ?????? ??????";
                return redirect()->back()->with('alert',$return->msg);
            }
        }
        

        
    }

    public static function accept(Request $request){
        $idx = $request->idx;
        $key = 'accepted';
        $value = 1;

        $result = DB::table('register_waitlist')->where('wait_idx', $idx)->update([$key => $value]);

        $wait = DB::table('register_waitlist')->where('wait_idx', $idx)->first();
        $emails = array();
        $emails[0] = $wait->email;

        $mail = new PHPMailer(true); 

        try {
 
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $addr = env('MAIL_ADDR');
            $mail->setFrom($addr, 'Redea');
            $mail->addReplyTo($addr, 'Redea');
            
            
            $mail->SMTPAuth = true;
            
            
            $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
            $mail->Port = 587;                          // port - 587/465
            
            
            $mail->addAddress($wait->email);
            
            //$mail->addCC($request->emailCc);
            //$mail->addBCC($request->emailBcc);
 
            
 
            if(isset($_FILES['emailAttachments'])) {
                for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }
            
 
 
            $mail->isHTML(true);                // Set email content format to HTML
            $mail->SMTPKeepAlive = true;
 
            $mail->Subject = "We are prepared to serve you!";
            $mail->Body = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/lib/mail_templates/welcome.html');
 
            // $mail->AltBody = plain text version of email body;
 
            if( !$mail->send() ) {
                return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
            }
            
            else {
                return back()->with("success", "Email has been sent.");
            }
 
        } catch (Exception $e) {
             return back()->with('error','Message could not be sent.');
        }


    
       return redirect()->route('wait_list');

    
        /*if($result){
            return redirect()->route('wait_list');
        }else{
            $return->status = "500";
            $return->msg = "?????? ?????? ??????";
            return redirect()->back()->with('alert',$return->msg);
        }*/


    }

    public static function un_accept(Request $request){
        $idx = $request->idx;
        $key = 'accepted';
        $value = 0;

        $result = DB::table('register_waitlist')->where('wait_idx', $idx)->update([$key => $value]);

        return redirect()->route('wait_list');

        /*if($result){
            return redirect()->route('wait_list');
        }else{
            $return->status = "500";
            $return->msg = "?????? ?????? ??????";
            return redirect()->back()->with('alert',$return->msg);
        }*/


    }

    public static function send_mail($email_vars, $subject, $template_url, $contact) {
        //returns true on success,
        //returns info on failure
    
        $mail = new PHPMailer(true); 
    
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        // $mail->Host = 'smtp.gmail.com';      // Specify main and backup server
        $mail->Host = 'email-smtp.ap-northeast-2.amazonaws.com';      // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        // $mail->Username = 'redea.help@gmail.com';                   // SMTP username
        $mail->Username = 'AKIAZJ2MNH4RPTLVQDMT';                   // SMTP username
        // $mail->Password = GMAILPASSW;               // SMTP password
        $mail->Password = "BIHFnXfQ1sayzkg4YHqBO3sTf9eL6Dw0mXGxGmroGsRX";               // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
        $mail->setFrom('redea.help@gmail.com', 'Redea');     //Set who the message is to be sent from
        $mail->addAddress($contact);  // Add a recipient
        // $mail->addAddress('ellen@example.com');               // Name is optional
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        // $mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
        // $mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet); //////// FOR AWS SES
    
        $mail->Subject = $subject;
        // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $body = file_get_contents('/lib/mail_templates/'.$template_url);
        if(isset($email_vars)){
            foreach($email_vars as $k=>$v){
                $body = str_replace('{'.strtoupper($k).'}', $v, $body);
            }
        }
        $mail->msgHTML($body, dirname(dirname(__FILE__))."/mail_templates");
    
        if(!$mail->send()) {
           return $mail->ErrorInfo;
       }
       return true;
    }

    

    public function login(Request $request){
        $user = User::where('user_id' , $request->id)->first();

        $return = new \stdClass;

        if($request->id != "admin"){
            $return->status = "501";
            $return->msg = "????????? ??? ??? ?????? ????????? ?????????.";
            
            return redirect()->back()->with('alert',$return->msg);
        }else if ($request->password == $user->password) {

            
            //echo("????????? ??????");
            Auth::loginUsingId($user->id);
            $login_user = Auth::user();

            $token = $login_user->createToken('user');

            $return->status = "200";
            $return->msg = "??????";
            $return->dormant = $login_user->dormant;
            $return->token = $token->plainTextToken;

            //dd($token->plainTextToken);
            return redirect()->route('wait_list');   
        }else{
            $return->status = "500";
            $return->msg = "????????? ?????? ??????????????? ???????????? ????????????.";
            
            return redirect()->back()->with('alert',$return->msg);
        }
    }

    public function login_temp(Request $request){

        $user = User::where('email' , $request->email)->where('leave','N')->first();

        $return = new \stdClass;

        if(!$user){
            $return->status = "501";
            $return->msg = "???????????? ?????? ????????? ?????????.";
            
            return redirect()->back()->with('alert',$return->msg);
        }else if (Hash::check($request->password, $user->password)) {
            //echo("????????? ??????");
            Auth::loginUsingId($user->id);
            $login_user = Auth::user();

            $token = $login_user->createToken('user');

            $return->status = "200";
            $return->msg = "??????";
            $return->dormant = $login_user->dormant;
            $return->token = $token->plainTextToken;

            //dd($token->plainTextToken);
            return redirect()->route('main');   
        }else{
            $return->status = "500";
            $return->msg = "????????? ?????? ??????????????? ???????????? ????????????.";
            
            return redirect()->back()->with('alert',$return->msg);
        }
    }


    public function page(Request $request){
        $user = User::where('user_id' , $request->user_id)->where('leave','N')->first();

        //$question = Question::where('id',$question_id)->first();
        $answers = Answer::where('user_id',$user->id)->orderby('id','desc')->get();
        $ques = array();
        $i = 0;

        foreach($answers as $answer){
            $question = Question::where('id',$answer->question_id)->first();
            $answers[$i]['question'] = $question['question'];
            $i++;
        }


        $list = new \stdClass;

        $list->my_que_cnt = Question::where('user_id',$user->id)->count();
        $list->my_ans_cnt = Answer::where('user_id',$user->id)->count();

        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $answers;
        $list->user = $user;
    
        return view('page', ['list' => $list]);

    }

    public function mypage(Request $request){
        $user_info = Auth::user();
        $user = User::where('id', $user_info->id)->first();

        $answers = Answer::where('user_id',$user->id)->orderby('id','desc')->get();
        $ques = array();
        $i = 0;

        foreach($answers as $answer){
            $question = Question::where('id',$answer->question_id)->first();
            $answers[$i]['question'] = $question['question'];
            $i++;
        }

        $list = new \stdClass;

        $list->my_que_cnt = Question::where('user_id',$user->id)->count();
        $list->my_ans_cnt = Answer::where('user_id',$user->id)->count();

        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $answers;
        $list->user = $user;

        return view('page', ['list' => $list]);

    }

    public function logout(Request $request){

        Auth::guard('web')->logout();

        Auth::user()->tokens()->delete();
    
        return redirect()->route('main');
    }

    public function login_check(Request $request){
        
        $return = new \stdClass;
        //$login_user = Auth::user();
        //$user_id = $login_user->getId();

        if(Auth::check()){
            $return->status = "200";
            $return->login_status = "Y";
        }    

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        
    }
    

    public function find_user_id(Request $request){
        $user = User::where('phone' , $request->phone)->first();
        
        if (isset($user->id)) {
            echo("????????? ???????????? ".$user->user_id." ?????????.");       
        }else{
            echo("???????????? ?????? ????????? ?????????.");       
        }
    }

    public function user_list(Request $request){
        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }

        $row = 50;
        
        $offset = (($page_no-1) * $row);

        $start_date = "2021-01-01";
        $end_date = date('Y-m-d H:i:s');
        if($request->start_date){
            $start_date = $request->start_date;
        }

        if($request->end_date){
            $end_date = $request->end_date;
        }
        
        $search_type = $request->search_type;
        $search_keyword = $request->search_keyword;

        $search = null;
        if($search_type){
            $search = $request->search_type.",".$request->search_keyword;
        }
        
        
        $rows = User::where('user_type','0')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where($search_arr[0] ,"like", "%".$search_arr[1]."%");
                })
                ->offset($offset)
                ->orderBy('id', 'desc')
                ->limit($row)->get();

        $count = User::where('user_type','0')
                    ->when($start_date, function ($query, $start_date) {
                        return $query->where('created_at' ,">=", $start_date);
                    })
                    ->when($end_date, function ($query, $end_date) {
                        return $query->where('created_at' ,"<=", $end_date);
                    })
                    ->when($search , function ($query, $search) {
                        $search_arr = explode(',',$search);
                        return $query->where($search_arr[0] ,"like", "%".$search_arr[1]."%");
                    })
                    ->count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->start_date = $start_date;
        $list->end_date = $end_date;
        $list->search_type = $request->search_type;
        $list->search_keyword = $request->search_keyword;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('user_list', ['list' => $list]);
        
    }

    public function list_download(Request $request){
        ob_start();
        $start_no = $request->start_no;
        $row = $request->row;
        
        $rows = User::select(   'id',
                                'name',
                                'nickname',
                                'phone',
                                'email',
                                'user_id',
                                'created_at',
                                'updated_at',
                                'leave',
                )->where('id' ,">=", $start_no)->where('user_type','0')->orderBy('id', 'desc')->orderBy('id')->limit($row)->get();

        

        $list = array();
        $i = 0;

        foreach($rows as $row){
            
            $list[$i]['id'] = $row->id;
            $list[$i]['user_id'] = $row->user_id;
            $list[$i]['name'] = $row->name;
            $list[$i]['phone'] = $row->phone;
            $list[$i]['email'] = $row->email;
            $list[$i]['created_at'] = $row->created_at;
            $list[$i]['updated_at'] = $row->updated_at;
            $list[$i]['nickname'] = $row->nickname;
            $list[$i]['leave'] = $row->leave;

            $i++;
        }
        //dd($list);
        
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Seoul');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        set_time_limit(120); 
        ini_set("memory_limit", "256M");

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                    ->setLastModifiedBy("Maarten Balliauw")
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Office 2007 XLSX Test Document")
                                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '???????????????')
                    ->setCellValue('B1', '??????')
                    ->setCellValue('C1', '?????????')
                    ->setCellValue('D1', '?????????')
                    ->setCellValue('E1', '?????????')
                    ->setCellValue('F1', '?????????')
                    ->setCellValue('G1', '?????????')
                    ->setCellValue('H1', '????????????');
        $i = 2;
        foreach ($list as $row){

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $row['user_id'])
                        ->setCellValue('B'.$i, $row['name'])
                        ->setCellValue('C'.$i, $row['phone'])
                        ->setCellValue('D'.$i, $row['email'])
                        ->setCellValue('E'.$i, $row['nickname'])
                        ->setCellValue('F'.$i, $row['created_at'])
                        ->setCellValue('G'.$i, $row['updated_at'])
                        ->setCellValue('H'.$i, $row['leave']);
            $i++;
        }
                                
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('user_list');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client???s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="user_list.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }


    

    public function info(){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->data = $login_user;

        if($login_user->user_type == 1){
            $hotel_info = Hotel::where('partner_id',$login_user->id)->first();
            if($hotel_info){
                $return->hotel_id = $hotel_info->id;
            }
            
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update(Request $request){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "?????? ??????";
        $return->key = $request->key;
        $return->value = $request->value;

        $key = $request->key;
        $value = $request->value;
        $user_id = $login_user->id;

        if($key == "password"){
            $value = Hash::make($request->value);
        }

        $result = User::where('id', $user_id)->update([$key => $value]);

        if(!$result){
            $return->status = "500";
            $return->msg = "?????? ??????";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update_password(Request $request){
        //dd($request);
        $return = new \stdClass;

        $return->key = $request->key;
        $return->value = $request->value;

        $key = $request->key;
        $value = $request->value;
        $email = $request->email;

        $user_info = User::where('email',$email)->first(); // ?????? ?????? ??? ?????? ??????
        $user_id= $user_info->id;

        $imp_uid = $request->imp_uid;

        
        $_api_url = env('IMPORT_GETTOKEN_URL');     // ???????????? ??? access_token ??????
        $_param['imp_key'] = env('IMPORT_KEY');
        $_param['imp_secret'] = env('IMPORT_SECRET');    // ???????????? ?????????
       
        $_curl = curl_init();
        curl_setopt($_curl,CURLOPT_URL,$_api_url);
        curl_setopt($_curl,CURLOPT_POST,true);
        curl_setopt($_curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($_curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($_curl,CURLOPT_POSTFIELDS,$_param);
        $_result = curl_exec($_curl);
        curl_close($_curl);
        $_result = json_decode($_result);
        
        $access_token = $_result->response->access_token;
        $headers = [
            'Authorization:'.$access_token
        ];
        $url = "https://api.iamport.kr/certifications/".$imp_uid; // ?????? ?????? url - access_token ??????
        $_curl2 = curl_init();
        curl_setopt($_curl2,CURLOPT_URL,$url);
        curl_setopt($_curl2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($_curl2,CURLOPT_RETURNTRANSFER,true);
        $_result2 = curl_exec($_curl2);
        $_result2 = json_decode($_result2);
            
        $user_infos= $_result2->response; // ?????? ??? ?????? ??????
        
        $return = new \stdClass;
        
        $user_infos->phone = str_replace ( "-", "", $user_infos->phone); 
        
        if($user_infos->phone == $user_info->phone ){
            $value = Hash::make($request->password);
            $result = User::where('id', $user_id)->update(['password' => $value]);
            $return->status = "200";
            $return->updated_id = $user_id;
            $return->updated_email = $email;

        }else{
            $return->status = "500";
            $return->msg = "????????? ????????? ??????????????? ???????????? ????????????.";
            $return->updated_id = $user_id;
            $return->updated_email = $email;
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update_info(Request $request){
        //dd($request);
        $return = new \stdClass;

        $return->status = "200";
        $return->msg = "?????? ??????";
        
        $result = User::where('id', $request->user_id)->update([
            'name'=> $request->name ,
            'nickname'=> $request->nickname ,
            'email' => $request->email, 
            'phone' => $request->phone, 
            'user_type' => $request->user_type,
            'push' => $request->push,
            'push_event' => $request->push_event,
        ]);

        if(!$result){
            $return->status = "500";
            $return->msg = "?????? ??????";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function leave(Request $request){
        //dd($request);
        $return = new \stdClass;
        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "???????????? ??????";

        $user_id = $login_user->id;

        $result = User::where('id', $user_id)->update(['leave' => 'Y']);

        if(!$result){
            $return->status = "500";
            $return->msg = "???????????? ??????";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }


    


}
