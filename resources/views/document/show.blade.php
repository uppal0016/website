<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Open PDF</title>
  <style>
    .blur-background {
      filter: blur(5px);
    }

        /* Style for the blur overlay */
        .blur-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Adjust the color and opacity as needed */
        backdrop-filter: blur(5px); /* Adjust the blur amount as needed */
        z-index: 9999; /* Make sure it's above other elements */
        display: none;
    }
    
    /* Style for the loader */
    #loader-body {
        position: absolute;
        top: 70rem;
        left: 46%;
        z-index: 10000; /* Make sure it's above the overlay */
    }

    @media only screen and (max-width: 1700px) and (min-width: 200px){
      #Request{
          position: absolute !important;
          right: 19rem !important;
      }
    }

  </style>
  <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">   
  <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>
<style>
  .row {
    margin-bottom: 20px;
    text-align: right;
  }
</style>
<body>
<canvas id="the-canvas"></canvas>
<div class="container">
  <div class="blur-overlay" id="blur-overlay"></div>
  <div id="loader-body" style="display: none">
    <div id="loader" style="background: #fff;height: 100px;width: 100px;text-align: center;line-height: 100px;border-radius: 100%;">
      <img src="{{asset('images/loader.gif')}}" style="max-width: 80px;padding: 10px;margin-top: 0px;margin-left: 0px;"></div>
</div>

  <div class="row" >
    <button id="prev" class="btn btn-primary">Previous</button>
    <button id="next" class="btn btn-primary">Next</button>
    <input type="hidden" id="doc_id" value="{{$doc_id}}">
    <input type="hidden" id="page_time" value="">
    <input type="hidden" id="total_time" value="">
    &nbsp; &nbsp;
    <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
    @if($user_id != 5)
      <button id="Request" class="btn btn-primary" style="position: absolute; top: 3vh; right: 11.5vw;">Request for copy</button>
      @if($document->favorite_document == 'Yes')
      <button id="favorite" class="btn btn-primary" style="position: absolute; top: 3vh; right: 4vw; " disabled title="The document is already favorited!">Add to favorite</button>
      @else
      <button id="favorite" class="btn btn-primary" style="position: absolute; top: 3vh; right: 4vw; ">Add to favorite</button>
      @endif
      <div id="favorite-error" class="alert alert-danger" style="display: none;"></div>
    @endif
  </div>
  @if($user_id != 5)
  <div class="row">
    
  </div>
@endif
</div>

  <div class="modal" id="myModal"  tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="position: relative; ">
          <h3 class="modal-title">Request for copy</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 1rem; right: 1.5rem;">x</button>
        </div>
        <form id="RequestForm" method="POST" action="{{ route('request_document') }}">
          @csrf
        <div class="modal-body">
          <p>You want to get a copy of this document. Please select the options from below whether you want hard copy or soft copy.</p>
          <div class="options">
            <div class="form-check">
              <input class="form-check-input softCopy" type="radio" name="requestType" id="softCopy" value="softCopy">
              <label class="form-check-label softCopy" for="softCopy">
                Soft Copy
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input hardCopy" type="radio" name="requestType" id="hardCopy" value="hardCopy">
              <label class="form-check-label hardCopy" for="hardCopy">
                Hard Copy
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="close" class="btn btn-secondary" id="closeBtn" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
          <input type="hidden" name="document_id" id="document_id" value="{{$doc_id}}">
          <input type="hidden" name="requestType" id="requestType" value="">
        </div>
      </form>
      </div>
    </div>
  </div>


{{-- options not visible when user click on right button --}}
<script>document.addEventListener('contextmenu', event => event.preventDefault());</script>
<script language="javascript">
  var noPrint=true;
  var noCopy=true;
  var noScreenshot=true;
  var autoBlur=false;
  </script>
  <script type="text/javascript" src="https://pdfanticopy.com/noprint.js"></script>

<script>
  var time = parseInt('<?php echo $time; ?>') || 0;
  var pages = '<?php echo $page; ?>';
  var total_page = '<?php echo $totalpage; ?>';
  var page_no = parseInt('<?php echo $page_no; ?>') || 1;
  var startTime = new Date();
  var pageStartTime = null; 
  var totalTime = 0;
  var url = '{{ asset('images/document/'.$pdfPath)}}';

  // Loaded via <script> tag, create shortcut to access PDF.js exports.
  var pdfjsLib = window['pdfjs-dist/build/pdf'];

  // The workerSrc property shall be specified.
  pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
  var pdfDoc = null,
    pageNum = page_no ? page_no : 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 2.0,
    canvas = document.getElementById('the-canvas'),
    ctx = canvas.getContext('2d');

  /**
   * Get page info from document, resize canvas accordingly, and render page.
   * @param num Page number.
   */
  function renderPage(num) {
    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {
      var viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      // Render PDF page into canvas context
      var renderContext = {
        canvasContext: ctx,
        viewport: viewport
      };
      var renderTask = page.render(renderContext);

      // Wait for rendering to finish
      renderTask.promise.then(function() {
        pageRendering = false;
        if (pageNumPending !== null) {
          // New page rendering is pending
          renderPage(pageNumPending);
          pageNumPending = null;
        }
      });
    });

    // Update page counters
    document.getElementById('page_num').textContent = num;
  }

  /**
   * If another page rendering is in progress, wait until the rendering is finished.
   * Otherwise, execute rendering immediately.
   */
  function queueRenderPage(num) {
    if (pageRendering) {
      pageNumPending = num;
    } else {
      renderPage(num);
    }
  }

  /**
   * Display previous page.
   */
  function onPrevPage() {
    if (pageNum <= 1) {
      return;
    }
    pageNum--;
    queueRenderPage(pageNum);
    var currentPageTime = Math.floor((new Date() - pageStartTime) / 1000);
    totalTime += currentPageTime;
    $("#total_time").val(totalTime);
    var docId = $("#doc_id").val();
    var total_time = $("#total_time").val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      method: "post",
      url: "{{ url('page_count_time') }}",
      data: {
        docid: docId,
        total_time: total_time,
        page: pageNum,
        totalPage: total_page,
        pages: pages
      },
      success: function(result) {

      }
    });
    pageStartTime = new Date();
  }

  document.getElementById('prev').addEventListener('click', onPrevPage);

  /**
   * Display next page.
   */
  function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
      return;
    }
    pageNum++;
    queueRenderPage(pageNum);
    var currentPageTime = Math.floor((new Date() - pageStartTime) / 1000);
    totalTime += currentPageTime;
    $("#total_time").val(totalTime);
    var docId = $("#doc_id").val();
    var total_time = $("#total_time").val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      method: "post",
      url: "{{ url('page_count_time') }}",
      data: {
        docid: docId,
        total_time: total_time,
        page: pageNum,
        totalPage: total_page,
        pages: pages
      },
      success: function(result) {

      }
    });
    pageStartTime = new Date();
  }

  document.getElementById('next').addEventListener('click', onNextPage);

  /**
   * Asynchronously download PDF.
   */
  pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
    pdfDoc = pdfDoc_;
    document.getElementById('page_count').textContent = pdfDoc.numPages;
    pageStartTime = new Date();
    renderPage(pageNum);
  });

  $(document).ready(function() {
    totalTime = parseInt(time);
    
    
    setInterval(() => {
      var currentTime = new Date();
      var elapsedTime = Math.floor((currentTime - startTime) / 1000);
      totalTime += elapsedTime;
      $("#total_time").val(totalTime);
      var docId = $("#doc_id").val();
      var total_time = $("#total_time").val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "post",
        url: "{{ url('page_count_time') }}",
        data: {
          docid: docId,
          total_time: total_time,
          page: pageNum,
          totalPage: total_page,
          pages: pages
        },
        success: function(result) {

        }
      }); 
    }, 30000);

    window.onbeforeunload = function(event) {
      
      var currentTime = new Date();
      var elapsedTime = Math.floor((currentTime - startTime) / 1000);
      totalTime += elapsedTime;
      $("#total_time").val(totalTime);
      var docId = $("#doc_id").val();
      var total_time = $("#total_time").val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "post",
        url: "{{ url('page_count_time') }}",
        data: {
          docid: docId,
          total_time: total_time,
          page: pageNum,
          totalPage: total_page,
          pages: pages
        },
        success: function(result) {

        }
      }); 
    };

    $(document).ready(function() {
      var currentPage = <?php echo $page_no ? $page_no : 1; ?>;
      updateButtonVisibility();
      
      $("#prev").click(function() {
        currentPage--;
        updateButtonVisibility();
      });

      $("#next").click(function() {
        currentPage++;
        updateButtonVisibility();
      });

      function updateButtonVisibility() {
        if (currentPage === 1) {
          $("#prev").hide();
        } else {
          $("#prev").show();
        }

        if (currentPage === parseInt(total_page)) {
          $("#next").hide();
          $("#Request").show();
        } else {
          $("#next").show();
          $("#Request").hide();
        }
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
      $("#favorite").click(function() {
        $("#favorite").prop("disabled", true).attr("title", "The document is already favorited!");
          var currentUrl = window.location.href;
          var document_id = currentUrl.split('/').pop();
          var $blurOverlay = $("#blur-overlay");
          $('#loader-body').fadeIn();
          $blurOverlay.fadeIn();

          $.ajax({
              url: '/favorite/' + document_id,
              type: 'GET',
              success: function(response) {
                $('#loader-body').fadeOut();
                $blurOverlay.fadeOut();
                alert("Document favorited successfully!");
              },
              error: function(xhr, status, error) {
                $('#loader-body').fadeOut();
                $blurOverlay.fadeOut();
                var errorMessage = "The document is already favorited!";
                alert(errorMessage);
            }
          });
      });
  });
</script>

<script>
var button = document.getElementById("Request");
var modal = document.getElementById("myModal");
var closeButton = document.querySelector(".btn-close");
var closeBtn = document.getElementById("closeBtn");
var background = document.getElementById("the-canvas");
const Requestbutton = document.getElementById('Request');

button.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

button.addEventListener("click", function() {
  modal.style.display = "block";
  background.classList.add("blur-background");
});

closeButton.addEventListener("click", function() {
  modal.style.display = "none";
  background.classList.remove("blur-background");
});

closeBtn.addEventListener("click", function() {
  modal.style.display = "none";
  background.classList.remove("blur-background");
});

window.addEventListener("click", function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    background.classList.remove("blur-background");
  }
});

$(document).ready(function() {
  $("#RequestForm").submit(function(e) {
      e.preventDefault();

      var requestType = $("input[name='requestType']:checked").val();

      if (!requestType) {
          alert("Please select a copy type.");
          return;
      }

      $('#loader-body').fadeIn();
      $("#submitBtn").prop("disabled", true);
      $("#Request").prop("disabled", true).attr("title", "Request already send.");
      var $blurOverlay = $("#blur-overlay");
      $blurOverlay.fadeIn();

      $.ajax({
        url: "{{ route('request_document') }}",
        method: "POST",
        data: {
          _token: $("input[name='_token']").val(),
          requestType: requestType,
          document_id: $("#document_id").val(),
        },
        success: function(response) {
          $('#loader-body').fadeOut();
          $blurOverlay.fadeOut();
          alert("Request submitted successfully!");
          modal.style.display = "none";
          background.classList.remove("blur-background");
        },
        error: function(xhr, status, error) {
          $('#loader-body').fadeOut();
          $blurOverlay.fadeOut();
          var errorMessage = 'An error occurred. Please try again.';
          alert(errorMessage);
        },
      });
    });

     $("#closeBtn").click(function(e) {
    e.preventDefault();
    modal.style.display = "none";
    background.classList.remove("blur-background");
  });

  });

</script>
</body>
</html>
