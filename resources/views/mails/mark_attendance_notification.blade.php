<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>DSR Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



</head>

<body style="font-family: 'Poppins', sans-serif;font-size: 15px; color: #555; background-color: #f8f8f8; line-height: 20px;">

<div class="outer-div" style="background: #f8f8f8; padding: 15px 5px;">
    <table class="mobile-table" style="background: #fff;border-radius: 3px;overflow: hidden;border-collapse: separate !important; width:600px" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td align="center" valign="middle" style="padding: 20px 0px 0px;text-align: center;"><img src="{{ asset('images/logo-TT.jpg') }}" style="width: 170px; height: auto;" width="170" alt=""/></td>
        </tr>
        <tr>
            <td align="center" valign="top" style="padding: 15px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td align="left" style="font-size:24px;padding: 15px 0px; font-weight:bold; color:#272727; text-align: center;">
                                                <span style="margin-left:5px">Attendance Notification</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr><td style="border-top: 1px solid #ddd;"></td></tr>
                        <tr><td height="20"></td></tr>
                        <tr>
                            <td align="left" valign="top"><b>Hello {{$user->first_name}},</b><br>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;OpenSans&quot;,Arial,sans-serif;font-size:15px;font-weight:300;line-height:normal;margin:0;padding:0 25px 15px 25px;text-align:left">
                            @php
                            $message = '';
                            if($type == 'time_in')
                            {
                                if($role == 'self') {
                                    $message = 'You have marked the attendance today at '. $attendance->time_in;
                                } else {
                                    $message = $attendance->user_profile->first_name.' '. $attendance->user_profile->last_name. ' have marked the attendance today at '. $attendance->time_in;
                                }
                            } elseif($type == 'time_out')
                            {
                                if($role == 'self') {
                                    $message = 'You have timed out today at'.$attendance->time_out.' and your total logged hour is '.$attendance->total_working_hour;
;
                                } else {
                                    $message = $attendance->user_profile->first_name.' '. $attendance->user_profile->last_name. ' have timed out today at '.$attendance->time_out.' and your total logged hour is '.$attendance->total_working_hour;
                                }
                            }
                                
                            @endphp

                            {{$message}}.
                            </td>
                        </tr>
                    <tr>
                        <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top"  style="padding:20px;background-color: #d8e0fd;border: 1px solid #aab5e2;color: #333;font-size: 14px;font-weight: bold;padding: 10px 20px;border-radius: 3px;">Sincerely,<br>Team TalentOne
                        </td>
                    </tr>

               <tr><td height="25"></td></tr>
               
               <tr><td style="border-top:1px solid #ddd;"></td></tr>
               
                <tr><td height="20"></td></tr>
                
                <tr>
                 <td style="text-align: center;">
                    <div style="display:inline-block;">
                        <span style="display:inline-block;margin-top: 1px;float: left;margin-right: 10px; ">Follow us on:</span>
                        <div style="display:inline-block;">
                            <a href="https://twitter.com/talentelgia" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/twitter.png') }}"></a>
                            <a href="https://www.facebook.com/TalentelgiaTechnologies/" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/fb.png') }}"></a> 
                            <a href="https://www.instagram.com/talentelgiatechnologie/" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/insta.png') }}"></a>
                            <a href="https://www.youtube.com/@talentelgia" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/youtube.png') }}"></a>
                            <a href="https://www.linkedin.com/company/talentelgia-technologies" style="display:inline-block; margin-right:2px;"><img style="height:27px;" src="{{ asset('images/linkedin.png') }}"></a>
                            <a href="https://in.pinterest.com/talentelgia/" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/pin.png') }}"></a>
                        </div>                        
                        
                    </div>
                 </td>
                </tr>
                
                <tr><td height="20"></td></tr>
                
                <tr>
                    <td style="color:#282828;font-size:13px;text-align: center;font-weight:600; ">Visit us on: <a href="https://www.talentelgia.com/" style="color:#0d59c9;">www.talentelgia.com</a></td>                </tr>
                
              
                <tr>
                    <td style="color:#aaa;text-align: center;font-size:12px;font-weight:normal; ">Copyright © 2022 Talentelgia. All Rights Reserved.</td>
                </tr>
              
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>




</div>

</body>
</html>
