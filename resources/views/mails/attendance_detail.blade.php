<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Untitled Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
.mobile-table{
    position: absolute;
    left: 20%;
}
</style>
<body style="font-family: 'Poppins', sans-serif;font-size: 15px; color: #555; background-color: #f8f8f8; line-height: 20px;">
    <div class="outer-div" style="background: #f8f8f8; padding: 15px 5px;">
        <table class="mobile-table"cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td align="center" valign="middle" style="padding: 20px 0px 0px;text-align: center;"><img src="{{ asset('images/logo-TT.jpg') }}" style="width: 170px; height: auto;" width="170" alt="" /></td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding: 15px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 56rem">
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td align="left"
                                                        style="font-size:24px;padding: 15px 0px; font-weight:bold; color:#272727; text-align: center;">
                                                        <span style="margin-left:5px">Attendance Details</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #ddd;"></td>
                                </tr>
                                <tr>
                                    <td height="20"></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><b>Hello
                                            {{ $user_detail['first_name'] . ' ' . $user_detail['last_name'] }},</b>
                                        <br>Here is your attendance details from date <b>{{ $date_range[0] }} - {{ $date_range[1] }}</b>.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                        <table width="100%" style="border: solid 1px #d7d9da;background-color: #f0f3f3" border="0" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">Date</th>
                                                    <th align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">Time In</th>
                                                    <th align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">Time Out</th>
                                                    <th align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">Total Working Hour</th>
                                                    <th align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">Time Spend in Office</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($attendance->isNotEmpty())
                                                    @foreach ($attendance as $details)
                                                        <tr>
                                                            <td align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">{{ date('d-m-Y', strtotime($details['created_at'])) }}</td>                                                        
                                                            <td align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">{{ isset($details['time_in']) && !empty($details['time_in']) ? date('H:i:s', strtotime($details['time_in'])) : '-' }}</td>
                                                            <td align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center;">{{ isset($details['time_out']) && !empty($details['time_out']) ? date('H:i:s', strtotime($details['time_out'])) : '-' }}</td>
                                                            <td align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center; color: {{ isset($details['total_working_hour']) && $details['total_working_hour'] < '09:30:00' ? 'red' : 'inherit' }};">{{ isset($details['total_working_hour']) ? $details['total_working_hour'] : '-' }}</td>                                                            
                                                            <td align="left" valign="top" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px; text-align: center; color: {{ isset($details['biometric_total_times_by_date']) && $details['biometric_total_times_by_date'] < '08:30:00' ? 'red' : 'inherit' }};">{{ isset($details['biometric_total_times_by_date']) ? $details['biometric_total_times_by_date'] : '-' }}</td>                                                            
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td align="center" colspan="4" style="border-bottom: solid 1px #d7d9da; padding: 10px 12px">No Record Found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td align="left" valign="top">&nbsp;</td></tr>
                                <tr><td align="left" valign="top">&nbsp;</td></tr>
                                <tr><td align="center" valign="top">&nbsp;</td></tr>
                                <tr>
                                    <td align="left" valign="top" style="padding:20px;background-color: #d8e0fd;border: 1px solid #aab5e2;color: #333;font-size: 14px;font-weight: bold;padding: 10px 20px;border-radius: 3px;">
                                        Sincerely,<br>Team TalentOne
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
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/twitter.png') }}"></a>
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/fb.png') }}"></a>
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/insta.png') }}"></a>
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/youtube.png') }}"></a>
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/tiny.png') }}"></a>
                                                <a href="#" style="display:inline-block; margin-right:2px;"><img style="height:25px;" src="{{ asset('images/pin.png') }}"></a>
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                                <tr><td height="20"></td></tr>
                                <tr><td style="color:#282828;font-size:13px;text-align: center;font-weight:600; ">Visit us on: <a href="https://www.talentelgia.com" style="color:#4083e7;">www.talentelgia.com</a></td></tr>
                                <tr><td style="color:#aaa;text-align: center;font-size:12px;font-weight:normal; ">Copyright © 2022 Talentelgia. All Rights Reserved.</td></tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
=