
              @if(!$qrcode->isEmpty())
              <div class="row" id ="download" style="margin-left:1px" >
                @foreach($qrcode as $index=>$value)
              
    <div class="col-20" >
    <div id="print_qr"><style> 
    .col-20
    {
      width:20%;
    } 
  #print_qr {
    text-align: left;
  }
  .Qr-outer {
    display: inline-block;
    padding: 5px;
    border: 1px solid #d4d4d4;
    border-radius: 10px;
    text-align: center;
  }
  .QR-code {
    overflow: hidden;
    border: 1px solid #d4d4d4;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 5px;
  }
  /* .QR-code img {
    width: 70px;
  } */

  .logo-img {
    width: 50px;
  }

</style> <div class="Qr-outer">0{{$value->id}}<div class="QR-code"><img src="{{asset('images/qrcode/'.$value->qr_image )}}"></div><img class="logo-img" src="https://uploads-ssl.webflow.com/616818d939434d23bf997966/63340352fe95fbe37bcd31f4_logo.png"></div></div>
<br>
    </div>
    

                 
                @endforeach
                </div>
              @else
              <div colspan="7" class="text-center"><b>No record found</b>
              @endif
              
            <div class="pagination">
                {{ $qrcode->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
              </div>
           