!(function ($) {
  $(document).on("click", "ul.nav li.parent > a ", function () {
    $(this).find("em").toggleClass("fa-minus");
  });
  $(".sidebar span.icon").find("em:first").addClass("fa-plus");
})
(window.jQuery);

$(window).on("resize", function () {
  if ($(window).width() > 768) $("#sidebar-collapse").collapse("show");
});

$(window).on("resize", function () {
  if ($(window).width() <= 767) $("#sidebar-collapse").collapse("hide");
});

$(document).on("click", ".panel-heading span.clickable", function (e) {
  var $this = $(this);
  if (!$this.hasClass("panel-collapsed")) {
    $this.parents(".panel").find(".panel-body").slideUp();
    $this.addClass("panel-collapsed");
    $this.find("em").removeClass("fa-toggle-up").addClass("fa-toggle-down");
  } else {
    $this.parents(".panel").find(".panel-body").slideDown();
    $this.removeClass("panel-collapsed");
    $this.find("em").removeClass("fa-toggle-down").addClass("fa-toggle-up");
  }
});

var i = 1;
$("body").on("click", ".add-rows", function () {
  var tpl =
    '<div class="row hardwareListList"><div class="col-md-5"><div class="form-group"><input type="" name="add_more_[' +
    i +
    '][hardware_name]" autocomplete="off" placeholder="Enter  Name" class="form-control" required></div></div>' +
    '<div class="col-md-5"><div class="form-group"><input type="" name="add_more_[' +
    i +
    '][hardware_value]"autocomplete="off" placeholder="Enter  value" class="form-control" required></div></div>' +
    '<div class="col-md-1"><a href="javascript:void(0);" data-row="[' +
    i +
    ']" data-sub-row="[' +
    i +
    ']" class="btn btn-danger btn-sm btn-circle remove-inventory" style="border-radius:15px;"><i _ngcontent-jll-c150="" aria-hidden="true" class="fa fa-minus"></i></a></div></div></div>';
  $(".input-lists").append(tpl);
  i++;
});

$(document).on("click", ".remove-inventory", function () {
  var id = $(this).attr("id");

  $(this).closest(".hardwareList").remove();

  return true;
});

$("body").on("click", ".open_popop", function () {
  var inventoryid = $(this).data("url");
  $.get("inventory_details?id=" + inventoryid, function (response) {
    $("#inventory_details").modal("show");
    chtml = "";
    i = 1;
    $("#inventory_details").modal("show");

    response.InventoryDetails.forEach(function (data, k) {
      chtml +=
        "<tr><td>" +
        i +
        "</td><td>" +
        data.hardware_name +
        "</td><td>" +
        data.hardware_value +
        "</td></tr>";
      i++;
    });
    $(".datails").html(chtml);
  });
});

$("form").on("submit", function () {
  $(".add-user-btn").attr("disabled", "disabled");
});
$("form").bind("change keyup", function (event) {
  $(".add-user-btn").attr("disabled", false);
});

/* start Search functionality */
var dsr_search_timer;
$("#search").on("keyup", function (event) {
  var searchKeyword = $('input[name="search"]').val();
  var action = $('input[name="action"]').val();
  clearTimeout(dsr_search_timer);
  dsr_search_timer = setTimeout(function () {
    getSearchData(action, searchKeyword);
  }, 1000);
});

$("#status").change(function () {
  var searchKeyword = $('input[name="search"]').val();
  var action = $('input[name="action"]').val();
  getSearchData(action, searchKeyword);
});

$("#search").on("keyup", function (event) {
  if (event.which === 13) {
    event.preventDefault();
    $(".searchButton").trigger("click");
  }
});

$(document).on("click", ".searchButton", function (event) {
  var searchKeyword = $('input[name="search"]').val();
  var action = $('input[name="action"]').val();
  /*if(!$('input[name="search"]').val()){
		return false;
	}*/
  getSearchData(action, searchKeyword);
});

function getSearchData(action, searchKeyword) {
  // $('.loader').show();
  $(".loader_body").css("display", "block");
  // $('#dynamicContent').hide();
  if (
    window.location.href == ADMIN_URL + "/users" ||
    window.location.href == ADMIN_URL + "/birthday" ||
    window.location.href == ADMIN_URL + "/festival" ||
    window.location.href == ADMIN_URL + "/projects" ||
    window.location.href == ADMIN_URL + "/designations" ||
    window.location.href == ADMIN_URL + "/department" ||
    window.location.href == ADMIN_URL + "/dsr" ||
    window.location.href == ADMIN_URL + "/category" ||
    window.location.href == ADMIN_URL + "/inventory_item" ||
    window.location.href == ADMIN_URL + "/vendor" ||
    window.location.href == ADMIN_URL + "/assigned_stock"
  ) {
    url = ADMIN_URL;
  }

  if (
    window.location.href == BASE_URL + "/reports" ||
    window.location.href == BASE_URL + "/reports-list" ||
    window.location.href == BASE_URL + "/leave"
  ) {
    url = BASE_URL;
  }

  if (
    window.location.href == PM_URL + "/users" ||
    window.location.href == PM_URL + "/projects" ||
    window.location.href == PM_URL + "/designations" ||
    window.location.href == PM_URL + "/department" ||
    window.location.href == PM_URL + "/dsr" ||
    window.location.href == PM_URL + "/inventory_item"
  ) {
    url = PM_URL;
  }
  if (
    window.location.href == HR_URL + "/users" ||
    window.location.href == HR_URL + "/projects" ||
    window.location.href == HR_URL + "/designations" ||
    window.location.href == HR_URL + "/department" ||
    window.location.href == HR_URL + "/dsr"
  ) {
    url = HR_URL;
  }
  if (
    window.location.href == EMP_URL + "/users" ||
    window.location.href == EMP_URL + "/projects" ||
    window.location.href == EMP_URL + "/team_dsr" ||
    window.location.href == EMP_URL + "/designations" ||
    window.location.href == EMP_URL + "/department" ||
    window.location.href == EMP_URL + "/dsr" ||
    window.location.href == EMP_URL + "/inventory_item"
  ) {
    url = EMP_URL;
  }
  $.ajax({
    type: "post",
    dataType: "html",
    data: $("#searchForm").serializeArray(),
    url: url + action,
    success: function (response) {
      // $('.loader').hide();
      // $('.loader_body').css('display','none');
      $("#dynamicContent").show();
      if (response) {
        $("#dynamicContent").html(response);
      }
    },
  });
}

/* Pagination */
$(document).on("click", ".pagination a", function (e) {
  e.preventDefault();
  var url = $(this).attr("href");
  const inventory = url.slice(0, 14);
  if (inventory == "inventory_item") {
    $.get(url, $("#searchForm").serialize(), function (data) {
      // $('.loader').hide();
      $("#dynamicContent").show();
      $("#dynamicContent").html(data);
    });
  }
  $("#dynamicContent").hide();
  // $('.loader').show();
  $.post(url, $("#searchForm").serialize(), function (data) {
    // $('.loader').hide();
    $("#dynamicContent").show();
    $("#dynamicContent").html(data);
  });
});

//view qr code and genrate
$("body").on("click", ".qr_code", function () {
  var itemid = $(this).attr("itemid");
  $.get("view_qr_code?id=" + itemid, function (response) {
    $("#viewqrcode").modal("show");
    var chtml =
      '<div class="QR-code"><img src="' +
      response.imagepath +
      '" alt="Qr code"></div><img class="logo-img" src="https://uploads-ssl.webflow.com/616818d939434d23bf997966/63340352fe95fbe37bcd31f4_logo.png"></div>';
    $("#caption").html(chtml);
  });
});

// print qr code
function saveDiv() {
  var divToPrint = document.getElementById("print_qr");
  var printContents = divToPrint.innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  location.reload();
}
function qrcodevalidation(e) {
  const pattern = /^[0-9]$/;
  return pattern.test(e.key);
}

$(document).on("click", ".delete_action", function (e) {
  e.preventDefault();
  var url = $(this).data("href");
  var title = $(this).data("name");
  var button = $(this);
  bootbox.dialog({
    size: "small",
    title: "Confirm !!!",
    message: "Are you sure you want to delete this " + title + "?",
    buttons: {
      Cancel: {
        label: "Cancel",
        className: "btn-danger btn-sm",
      },
      confirm: {
        label: "OK",
        className: "btn-info btn-sm",
        callback: function () {
          $.ajax({
            type: "GET",
            url: url,
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
              if (response.status == "success") {
                $(".ajax-success-alert-message").text(response.message);
                $(".ajax-success-alert").show();
                setTimeout(function () {
                  $(".ajax-success-alert").fadeOut();
                }, 5000);
                location.reload();
              }
            },
          });
        },
      },
    },
    callback: function (result) {
      //null
    },
  });
});

// inventory item filter
var condition = {};
jQuery(document).on("change keyup", ".inventory_item_filter", function (e) {
  jQuery(".inventory_item_filter").each(function () {
    var type = jQuery(this).attr("rel").trim();
    var id = jQuery(this).val().trim();
    if (type == "d_o_p" && id != "") {
      var dateAr = id.split("/");
      var id = dateAr[2] + "-" + dateAr[0].slice(-2) + "-" + dateAr[1];
    }
    if (id != "") condition[type] = id;
    else delete condition[type];
  });
  var loc = window.location.href;
  if (loc.search("/admin/") != -1) {
    ajaxHit(
      "inventory_item_filter",
      "POST",
      ADMIN_URL + "/inventoryItem-search",
      JSON.stringify(condition)
    );
  }
  if (loc.search("/pm/") != -1) {
    ajaxHit(
      "inventory_item_filter",
      "POST",
      PM_URL + "/inventoryItem-search",
      JSON.stringify(condition)
    );
  }
});

// inventory dashboard filter
jQuery(document).on("change", ".inventory_dashboard_filter", function (e) {
  var category_id = jQuery(this).val().trim();
  var formData = new FormData();
  formData.append("category_id", category_id);
  var loc = window.location.href;
  if (loc.search("/admin/") != -1) {
    ajaxHit(
      "inventory_item_filter",
      "POST",
      ADMIN_URL + "/inventoryItem-search",
      JSON.stringify(condition)
    );
    ajaxHit(
      "inventory_dashboard_filter",
      "POST",
      ADMIN_URL + "/inventory-filter",
      formData
    );
  }
  if (loc.search("/") != -1) {
    ajaxHit(
      "inventory_dashboard_filter",
      "POST",
      "/inventory-filter",
      formData
    );
  }
});

// status popup
jQuery("body").on("click", ".change_status", function () {
  var id = jQuery(this).attr("rel").trim();
  var type = jQuery(this).attr("data-type").trim();
  var url = jQuery(this).attr("ref").trim();
  jQuery(".item_id").val(id);
  var formData = new FormData();
  formData.append("id", id);
  formData.append("type", type);
  setTimeout(function () {
    jQuery(".url_class").val(url);
  }, 200);
  var loc = window.location.href;
  if (loc.search("/admin/") != -1) {
    ajaxHit(
      "get_item_details",
      "POST",
      ADMIN_URL + "/get_item_details",
      formData
    );
  }
  if (loc.search("/pm/") != -1) {
    ajaxHit("get_item_details", "POST", PM_URL + "/get_item_details", formData);
  }
  if (loc.search("/employee/") != -1) {
    ajaxHit("get_item_details", "POST", "/get_item_details", formData);
  }
});

var attendance_search_timer;
$("#attendance-search").on("keyup", function (event) {
  if (event.keyCode === 13) {
    let searchKeyword = $('input[name="search"]').val();
    let dates = $("#dates").val();
    let work_mode = $("#work_mode").val();
    var entriesperpage = jQuery(".entriesperpage :selected").val();
    searchAttendance(searchKeyword, dates, entriesperpage, work_mode);
  }
});

//attendance date range and search common function
function searchAttendance(search = "", daterange = "", entriesperpage, work_mode, apply = '') {
  jQuery.ajax({
    type: "GET",
    cache: false,
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    url: searchUrl,
    data: {
      search: search,
      daterange: daterange,
      entriesperpage: entriesperpage,
      work_mode: work_mode,
      apply : apply,
    },
    success: function (data) {
      jQuery("#paginationData").empty();
      jQuery("#paginationData").html(data);
      jQuery(".loader").hide();
    },
  });
}

function searchMonthlyAttendance(url) {
  jQuery.ajax({
    type: "GET",
    cache: false,
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    url: url,
    data: {
      
    },
    success: function (data) {
      jQuery("#paginationData").empty();
      jQuery("#paginationData").html(data);
      jQuery(".loader").hide();
    },
  });
}

$(document).on("change", "#leave_status_filter", function (event) {
  let status = $(this).val();
  let entriesperpage = jQuery(".entriesperpage :selected").val();
  let dates = $("#dates").val();
  searchLeave(status, dates, entriesperpage);
});
$(".fa-clock").click(function () {
  $("#Time").html($(this).attr("currentTimeIn"));
  $("#reason").html($(this).attr("lateReason"));
});

//leave date range and search common function
function searchLeave(status = "", daterange = "", entriesperpage) {
  $.ajax({
    type: "get",
    dataType: "html",
    data: {
      status: status,
      daterange: daterange,
      entriesperpage: entriesperpage,
    },
    url: EMP_URL + "/leave",
    success: function (response) {
      // $('.loader').hide();
      // $('.loader_body').css('display','none');
      $("#dynamicContent").show();
      if (response) {
        $("#dynamicContent").html(response);
      }
    },
  });
}

//update inventory Details
$("body").on("click", ".remove-inventory-rows", function () {
  var id = $(this).attr("id");
  var button = $(this);
  bootbox.dialog({
    size: "small",
    title: "Confirm !!",
    message: "Are you sure you want to delete ?",
    buttons: {
      Cancel: {
        label: "Cancel",
        className: "btn-danger btn-sm",
      },
      confirm: {
        label: "OK",
        className: "btn-info btn-sm",
        callback: function () {
          $.ajax({
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: EMP_URL + "/admin/remove_inventoryDetails",
            method: "get",
            data: { id: id },
            success: function (result) {
              location.reload();
            },
          });
        },
      },
    },
    callback: function (result) {
      //null
    },
  });
});

//request password genrate
$("body").on("click", ".request_password_genrate", function () {
  var request = $(this).attr('data');   
 $.ajax({
  headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },              
 url:EMP_URL+'/reuest_genrate',
 method: 'post',
 data:{request:request},     
 success: function(){
 location.reload();
 }
 });
     
 });



// harmony dashboard filter
$("#harmony_ticket_category").on("change", function () {
  const category = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("category_id", category);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

// harmony dashboard search
$("#ticket_name_search").on("keyup", function () {
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#ticket_status").on("change", function () {
  const status = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("status", status);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#it_ticket_category").on("change", function () {
  const category = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("category", category);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#it_ticket_severity").on("change", function () {
  const severity = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("severity", severity);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#doc_sort_by").on("change", function () {
  const sort_by = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("sort_by", sort_by);
  var url = "document_management" + "?" + params.toString();
  window.location.href = url;
});

$("#doc_sort_by_type").on("change", function () {
  const sort_by = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("sort_by", sort_by);
  var url = "document" + "?" + params.toString();
  window.location.href = url;
});

$("#it_ticket_name_search").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#doc_name_search").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = "document_management" + "?" + params.toString();
  window.location.href = url;
});

$("#doc_name_search_index").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = "document" + "?" + params.toString();
  window.location.href = url;
});

$("#datepickerTicket").on("change", function () {
  const date = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("date", date);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#datepickerTicket").datepicker({
  startDate: "-1m",
  format: "yyyy-mm-d",
  autoclose: true,
});

// reference search
$("#reference_name_search").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("name_search", search);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

$("#reference_technology_search").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("technology_search", search);
  var url = "list" + "?" + params.toString();
  window.location.href = url;
});

// document detials dashboard search
$("#document_details").on("keyup", function () {
  const search = $(this).val();
  const path = new URL(window.location.href).pathname;
  const pathSegments = path.split('/');
  if (pathSegments.length > 1) {
    documentId = pathSegments[2];
  }
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = documentId + "?" + params.toString();
  window.location.href = url;
});

$("#document_details_name").on("keyup", function () {
  $(".loader_body").css("display", "block");
  const search = $(this).val();
  var params = new URLSearchParams(window.location.search);
  params.set("search", search);
  var url = "document" + "?" + params.toString();
  window.location.href = url;
});
