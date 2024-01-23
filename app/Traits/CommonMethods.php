<?php

namespace App\Traits;

use App\DsrRead;
use App\NotificationRead;
use App\WeeklyReportRead;

trait CommonMethods
{


  /**
  * @developer       :   Akshay
  * @modified by     :   
  * @created date    :   03-08-2018 (dd-mm-yyyy)
  * @modified date   :   
  * @purpose         :   update dsr as read
  * @params          :   dsr_id, user_id
  * @return          :   response as [] 
  */
  public function markDsrRead($dsrId, $userId){

    $updated = DsrRead::updateOrCreate([
      "dsr_id" => $dsrId,
      "user_id" => $userId,
    ], [
      "is_read" => 1
    ]);

    $updated = $updated ? 1 : 0;

    return $updated;
  }

  public function markReportRead($dsrId, $userId){

    $updated = WeeklyReportRead::updateOrCreate([
      "report_id" => $dsrId,
      "user_id" => $userId,
    ], [
      "is_read" => 1
    ]);

    $updated = $updated ? 1 : 0;

    return $updated;
  }



  /**
  * @developer       :   Ajmer
  * @modified by     :   
  * @created date    :   22-08-2018 (dd-mm-yyyy)
  * @modified date   :   
  * @purpose         :   update notification as read
  * @params          :   notification_id, user_id
  * @return          :   response as [] 
  */
  public function markNotificationRead($notificationId, $userId){

    $updated = NotificationRead::updateOrCreate([
      "notification_id" => $notificationId,
      "user_id" => $userId,
    ], [
      "is_read" => 1
    ]);

    $updated = $updated ? 1 : 0;

    return $updated;
  }

  public function convertToLocalTz($date, $defaultimezone = 'Asia/Kolkata')
  {
      $this->setUtcTimezone();
      $timezone = $defaultimezone ? $defaultimezone : Auth::user()->timezone;
      $usersTimezone = new \DateTimeZone($timezone);

      $coverted_start_time = new \DateTime($date);
      return $coverted_start_time->setTimeZone($usersTimezone);
  }

  public function setUtcTimezone()
  {   
      date_default_timezone_set("UTC");
  }

    public function sendDSRMails($user, $data)
    {
        $address = env('MAIL_USERNAME');
        $password = env('MAIL_PASSWORD');
        $subject = 'DSR - '.$data['sender']['first_name'];
        $name = env('MAIL_FROM_NAME');
        $sender = $data['sender'];
        $receiver = $data['receiver'];
        $dsr_details = $data['dsr_details'];
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug  = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Host       = env('MAIL_HOST');
        $mail->Port       = env('MAIL_PORT');
        $mail->IsHTML(true);
        $mail->Username = $address;
        $mail->Password = $password;
        $mail->SetFrom($address, $name);
        $mail->Subject = $subject;
        $mail->AddAddress($receiver['email'], $receiver['first_name']);

        $dsrDetails = '';
        $tte = 0;
        foreach($dsr_details as $dsr){
            if(isset($dsr['project']['name'])){
                $project_name = $dsr['project']['name'];
            }else{
                $project_name = 'Other';
            }
            $dsrDetails .= '<div style="float:left;width:100%;text-decoration: underline;font-size: 14px;margin-bottom: 10px;margin-top: 5px">'.
                '<b>Project: '.$project_name.'</b></div>';
            $i = 1;
            foreach($dsr['details'] as $detail){
                $te = explode('.', number_format( (float) $detail['total_hours'], 2, '.', ''));
                $detail['hours'] = $te[0];
                $detail['minutes'] = isset($te[1]) ? $te[1] : "0";
                $dsrDetails .= '<div style="font-size: 14px"><div style="float:left;width:100%;font-size: 14px">
                                              <div style="float:left;width:70%">
                                                <b>'.$i.". ".$detail['task'].'</b><br>
                                              </div>
                                              <div style="float:left;width:30%;text-align: right;">
                                                <b>Time: '.$detail['hours'].' Hr';
                if($detail['hours'] > 1 || $detail['hours'] === 0) {
                    $dsrDetails .= 's ';
                }
                $dsrDetails .= $detail['minutes'].' Min';
                if($detail['minutes'] > 1 || $detail['minutes'] === 0) {
                    $dsrDetails .= 's';
                }
                $dsrDetails .= '</b>';
                $tte += ($detail['hours']*60)+$detail['minutes'];
                $dsrDetails .= '</div></div><div style="float:left;width:70%;padding-left: 20px;margin-bottom:10px;">'.
                    str_replace(["\r\n", "\r", "\n"], "<br/>", $detail['description']).'</div></div>';
                $i++;
            }
        }
        $total_time_estimate = '0 Hrs 0 Mins';
        if($tte > 0) {
            $explode_time = explode('.', $tte/60);
            $remainder = $tte%60;
            $total_time_estimate = $explode_time[0] .' Hrs '.$remainder.' Mins';
        }else{
            $total_time_estimate = '0 Hrs 0 Mins';
        }


        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>DSR Manager</title>
      </head>
      <body text="#000000" bgcolor="#FFFFFF">
        <div><br>
          <div dir="ltr">
            <div><br>
              <div style="padding:0;margin:0">
                <center>
                  <table style="background:#ffffff;max-width:520px" width="520" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                    <tbody>
                      <tr>
                        <td style="background:#eeeeee" width="20" bgcolor="#eeeeee"><br></td>
                        <td width="480">
                          <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <td style="background:#eeeeee" bgcolor="#eeeeee" height="20">   <br>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <table style="border-bottom:1px solid #eeeeee" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                      <tr>
                                        <td height="30"><br></td>
                                      </tr>
                                      <tr>
                                        <td style="color:#4285f4;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:20px;font-weight:300;line-height:36px;margin:0;padding:0 25px 0 25px;text-align:center" align="center">Hi '.$receiver['first_name'].'!
                                        </td>
                                      </tr>
                                      <tr>
                                        <td height="20" style="color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:15px;font-weight:300;line-height:normal;margin:0;padding:0 25px 15px 25px;text-align:center" align="center">
                                          <strong>
                                            Welcome to DSR Manager
                                          </strong><br>
                                          <br>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:15px;font-weight:300;line-height:normal;margin:0;padding:0 25px 15px 25px;text-align:left">'.
            $sender['first_name'].''. $sender['last_name'] .'has just sent you a DSR on DSR Manager <br>
                                          <br>'.$dsrDetails.'
                                          <div style="float:left;width:100%;text-align: right;margin-top: 20px;border:1px dashed #ccc;padding:5px">
                                            Total Time Estimate: '.$total_time_estimate.'<br>
                                          </div>

                                          <br>
                                          <br>

                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="border-top:1px solid #ccc;color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:15px;font-weight:300;line-height:normal;margin:0;padding:0px 25px;text-align:center" align="center">
                                        </td>
                                      </tr>
                                      <tr>
                                        <td height="30" style="color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:15px;font-weight:300;line-height:normal;margin:0;padding:15px 25px;">
                                          <br/><br/>Thanks<br/>
                                          Team Talentelgia<br/>
                                          <br>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                      <tr>
                                        <td style="background:#eeeeee" bgcolor="#eeeeee" height="19"><br></td>
                                      </tr>
                                      <tr>
                                        <td style="background:#eee;color:#777;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:14px;font-weight:300;line-height:14px;margin:0;padding:0 6px 0 6px;text-align:center" valign="middle" align="center">             &copy;2018, Talentelgia Technologies Pvt. Ltd
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td style="background:#eeeeee" bgcolor="#eeeeee" height="18">   <br>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        <td style="background:#eeeeee" width="20" bgcolor="#eeeeee"><br></td>
                      </tr>
                    </tbody>
                  </table>
                  <div style="display:none;white-space:nowrap;font:15px courier;line-height:0">
                  </div>
                </center>
              </div>
            </div>
            <br>
          </div>
        </div>
      </body>
    </html>';
        $mail->Body    = $body;
        if(!$mail->send()){
            return false;
        };

        return true;
    }




}
