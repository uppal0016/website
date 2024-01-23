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
                                                <span style="margin-left:5px">{{$date}} DSR</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr><td style="border-top: 1px solid #ddd;"></td></tr>
                        <tr><td height="20"></td></tr>
                        <tr>
                            <td align="left" valign="top"><b>Hello {{$receiver['first_name']}},</b>
                              <br>
                              {{$sender['first_name']}} {{$sender['last_name']}} has just sent you a DSR on <a href="https://talentone.teamtalentelgia.com" target="_blank">DSR Manager</a>
                              <br><br>
                                      <?php $tte = 0;?>
                                      @foreach($dsr_details as $dsr)
                                      <div style="float:left;width:100%;text-decoration: underline;font-size: 14px;margin-bottom: 10px;margin-top: 5px">
                                        <b>Project: {{$dsr['project'] ? $dsr['project']['name'] : 'Other'}}</b>
                                      </div>

                                      <?php $i = 1;?>
                                      
                                      @foreach($dsr['details'] as $detail)
                                      <?php
                                        $te = explode('.', number_format( (float) $detail['total_hours'], 2, '.', ''));
                                        $detail['hours'] = $te[0];
                                        $detail['minutes'] = isset($te[1]) ? $te[1] : "0";

                                      ?>
                                      <div style="font-size: 14px">
                                        
                                        <div style="float:left;width:100%;font-size: 14px">
                                          <div style="float:left;width:70%">
                                            <b>{{$i.". ".$detail['task']}}</b><br>
                                          </div>
                                          <div style="float:left;width:30%;text-align: right;">
                                            <b>Time: {{$detail['hours']}} Hr<?php if($detail['hours'] > 1 || $detail['hours'] === 0) {echo "s";}?> {{$detail['minutes']}} Min<?php if($detail['minutes'] > 1 || $detail['minutes'] === 0) {echo "s";}?></b>
                                            <?php 
                                            $tte += ($detail['hours']*60)+$detail['minutes']; 
                                            ?>
                                          </div>
                                        </div>
                                        <div style="float:left;width:70%;padding-left: 20px;margin-bottom:10px;">
                                          {!! str_replace(["\r\n", "\r", "\n"], "<br/>", $detail['description']) !!}
                                        </div>
                                      </div>
                                      <?php $i++; ?>
                                      @endforeach
                                      
                                      @endforeach

                                      <div style="float:left;width:100%;text-align: right;margin-top: 20px;border:1px dashed #ccc;padding:5px">
                                        Total Time Estimate: {{$tte > 0 ? explode('.', $tte/60)[0] : 0}} Hrs {{$tte > 0 ? $tte%60 : 0}} Mins<br>
                                      </div>

                           <div style="float:left;width:100%;text-align: left;margin-top: 20px;padding:5px">
                                      Kindly click on  link "<a href="https://talentone.teamtalentelgia.com/admin/dsr" target="_blank"> Click Here</a> " to accept and reject the DSR.
                                      </div>
                                      <br>
                                      <br>
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
