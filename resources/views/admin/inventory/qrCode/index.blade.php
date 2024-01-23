@extends('layouts.page')
@section('content')
<style>

.card .top_header > h3{
flex: 1;
}
.btn_wrapper {
    display: flex;
    margin-top: 10px;
}
@media(max-width: 480px){
  .card .top_header{
    flex-wrap: wrap;
    flex-direction: column;
    justify-content: flex-start !important;
    align-items: flex-start !important;
}

}
</style>
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('admin/assigned_stock') }}"></a>Manage QR Code</li>
              </ol>
            </nav>
          </div>
          <div class="col-lg-6 col-7 text-right formResponsive userFOrm">
          <div class="input-group custom-searchfeild">
          
              
               </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid mt--6">
    <div class="row">
      <div class="col">
        <div class="card minHeight">
          <!-- Card header -->
          <div class="top_header card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Manage QR Code</h3>
            <div class="btn_wrapper">            
              <input type='button' class="btn btn-primary" value='Print ' onclick="Export()" />
              <a href="" data-href="{{ url('admin/unassign_destroy') }}" class="delete_action btn btn-danger" data-name=" un assign qr code"  title="Delete unassign qr code ">Delete</i>
                          </a>
              <div class="lastBtn">
                  <div class="plusBtn">
                    <a href="{{ URL('admin/qr_code/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Qr code">+</a>
                  </div>
                </div>
            </div>
            </div>
          <!-- Light table -->
          <div id ="dynamicContent">
        
              @if(!$qrcode->isEmpty())
              <div class="row" id ="download" style="margin-left:1px">
              
                @foreach($qrcode as $index=>$value)
                 <input type="hidden" id="ids" value="{{$value->id}}">
               
    <div class="col-20">     
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
 
  .logo-img {
    width: 50px;
  }

</style> <div class="Qr-outer"><div class="QR-code"><img src="{{asset('images/qrcode/'.$value->qr_image )}}"></div><img class="logo-img" src="https://uploads-ssl.webflow.com/616818d939434d23bf997966/63340352fe95fbe37bcd31f4_logo.png"></div></div>
<br>
    </div>
         
                @endforeach
           
                </div>
               
              @else
                <div colspan="7" class="text-center"><b>No record found</b></div>
              @endif
            
            
            </div>
          </div>
          <!-- Card footer -->
        </div>
      </div>
    </div>
  

@section('script')
<script>
  function Export() {  
  var divToPrint = document.getElementById("download");  
  var printContents = divToPrint.innerHTML;
  var originalContents = document.body.innerHTML;      
  document.body.innerHTML = `<div class="row"> ${printContents}</div>`; 
  divToPrint.focus(); 
  window.print();  
  location.reload(); 
}


var searchUrl = 'inventoryItem-search';
jQuery('.stock_drpDwn').val(0);
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
