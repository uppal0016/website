<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Leave Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #tbl {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #tbl td,
        #tbl th {
            border: 1px solid black;
            padding: 8px;
        }

        #tbl tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #tbl tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }


        #tbl {
            width: 50%;
            border-collapse: collapse;
        }

        #tbl {
            width: 50%;
            border-collapse: collapse;
        }
    </style>


</head>

<body
    style="font-family: 'Poppins', sans-serif;font-size: 15px; color: #555; background-color: #f8f8f8; line-height: 20px;">

    <div class="outer-div" style="background: #f8f8f8; padding: 15px 5px;">
        <table class="mobile-table"
            style="background: #fff;border-radius: 3px;overflow: hidden;border-collapse: separate !important; width:600px"
            width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" valign="middle" style="padding: 20px 0px 0px;text-align: center;"><img
                        src="{{ asset('images/logo-TT.jpg') }}" style="width: 170px; height: auto;" width="170"
                        alt="" /></td>
            </tr>
            <hr />
            <tr>
                <td align="left" valign="top" style="padding-left: 3%;">Hello @if ($data['action'] == 'update')
                        Sir
                    @else
                        {{ $data['name'] }}
                    @endif
                    ,
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-left: 3%;">
                    <p> This is the response of the leave that is applied by you. Below are the details:</p>
                </td>
            </tr>
            <tr>
                <table id="tbl" class="mobile-table"
                    style="background: #fff;boarder-radius: 3px;overflow: hidden;border-collapse: separate !important; width:600px; padding-top:20px;"
                    width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tbody>
                        </br>
                        @if ($data['action'] == 'update')
                        <tr>
                            <td align="left" valign="top"><strong>Start Date</strong></td>
                            <td>{{ $data['start_date'] }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"><strong>End Date</strong></td>
                            <td>{{ $data['end_date'] }}</td>
                        </tr>
                        @endif
                        @if ($data['status'] == 'approved')
                            <tr>
                                <td align="left" valign="top"><strong>Leave Status</strong></td>
                                <td>Approved</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Leave Approved By</strong></td>
                                <td>{{ $data['employee_name'] }}</td>
                            </tr>
                        @else
                            <tr>
                                <td align="left" valign="top"><strong>Leave Status</strong></td>
                                <td>Rejected</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Leave Rejected By</strong></td>
                                <td>{{ $data['employee_name'] }}</td>
                            </tr>
                        @endif
                        @if ($data['leaveType'] == '1')
                            <tr>
                                <td align="left" valign="top"><strong>Leave Type</strong></td>
                                <td>Full Day</td>
                            </tr>
                        @elseif($data['leaveType'] == '2')
                            <tr>
                                <td align="left" valign="top"><strong>Leave Type</strong></td>
                                <td>Half Day</td>
                            </tr>
                        @elseif($data['leaveType'] == '4')
                            <tr>
                                <td align="left" valign="top"><strong>Leave Type</strong></td>
                                <td>Work From Home</td>
                            </tr>
                        @else
                            <tr>
                                <td align="left" valign="top"><strong>Leave Type</strong></td>
                                <td>Short Leave</td>
                            </tr>
                        @endif
                        @if ($data['status'] == 'not_approved')
                            <tr>
                                <td align="left" valign="top"><strong>Reason</strong></td>
                                <td>{{ $data['message'] }}</td>
                            </tr>
                        @endif

                        <tr>
                            <td align="left" valign="top"><strong>Leave Start Date</strong></td>
                            <td>{{ $data['start_date'] }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"><strong>Leave End Date</strong></td>
                            <td>{{ $data['end_date'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <table class="mobile-table"
                style="background: #fff;border-radius: 3px;overflow: hidden;border-collapse: separate !important; width:600px"
                width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody>

                    <tr>
                        <td align="center" valign="top" style="padding: 15px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="left"
                                                            style="font-size:24px;padding: 15px 0px; font-weight:bold; color:#272727; text-align: center;">
                                                            <span style="margin-left:5px"> </span>
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
                                    <td style="text-align: center;">
                                        <div style="display:inline-block;">
                                            <span
                                                style="display:inline-block;margin-top: 1px;float: left;margin-right: 10px; ">
                                                Please click " <a href="{{ env('APP_URL') }}/leave"> here </a> " to
                                                confirm
                                                the leave status.</span>

                                        </div>
                                    </td>

                                    <tr>
                                        <td align="left" valign="top"
                                            style="padding:20px;background-color: #d8e0fd;border: 1px solid #aab5e2;color: #333;font-size: 14px;font-weight: bold;padding: 10px 20px;border-radius: 3px;">
                                            Sincerely,<br>Team TalentOne
                                        </td>
                                    </tr>

                                    <tr>
                                        <td height="25"></td>
                                    </tr>

                                    <tr>
                                        <td style="border-top:1px solid #ddd;"></td>
                                    </tr>

                                    <tr>
                                        <td height="20"></td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="display:inline-block;">
                                                <span
                                                    style="display:inline-block;margin-top: 1px;float: left;margin-right: 10px; ">Follow
                                                    us on:</span>
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

                                    <tr>
                                        <td height="20"></td>
                                    </tr>

                                    <tr>
                                        <td style="color:#282828;font-size:13px;text-align: center;font-weight:600; ">
                                            Visit us on: <a href="https://www.talentelgia.com/" style="color:#0d59c9;">www.talentelgia.com</a>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="color:#aaa;text-align: center;font-size:12px;font-weight:normal; ">
                                            Copyright © 2022 Talentelgia. All Rights Reserved.</td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</body>

</html>
