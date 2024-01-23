//	Validations for Form

$(document).ready(function () {
  // dsr validation
  $(".vaidation").bind("change keyup", function (event) {
    var row = $(this).attr("data-id");
    var project_id = $(".select-project" + row).val();
    var des = $(".dsr-area" + row).val();
    if (des == "" && project_id == "") {
      $("#des" + row).html("Please Enter Description");
      $("#projectid" + row).html("Please Select Project");
      return false;
    } else {
      $("#des" + row).html("");
      $("#projectid" + row).html("");
    }
    if (project_id == "") {
      $("#projectid" + row).html("Please Select Project");
      return false;
    } else {
      $("#projectid" + row).html("");
    }
    if (des == "") {
      $("#des" + row).html("Please Enter Description");
      return false;
    } else {
      $("#des" + row).html("");
    }
  });

  jQuery.validator.addMethod(
    "require_from_group",
    function (value, element, options) {
      var numberRequired = options[0];
      var selector = options[1];
      var fields = $(selector, element.form);
      var filled_fields = fields.filter(function () {
        // it's more clear to compare with empty string
        return $(this).val() != "";
      });
      var empty_fields = fields.not(filled_fields);
      // we will mark only first empty field as invalid
      if (filled_fields.length < numberRequired && empty_fields[0] == element) {
        return false;
      }
      return true;
      // {0} below is the 0th item in the options field
    },
    "Enter valid time"
  );

  // validate for form on keyup and submit
  $.validator.addMethod("noNumbers", function(value, element) {
      return this.optional(element) || /^[^\d\s]+$/.test(value);
  }, "Please enter a valid value without numbers.");

  $.validator.addMethod("noSpecialChars", function(value, element) {
      return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
  }, "Please enter a valid value without special characters.");


  $("#add_user").validate({
    rules: {
      // employee_code: {
      //   required: true,
      //   maxlength: 30,
      // },
      first_name: {
        required: true,
        number: false,
        maxlength: 30,
        noLeadingSpaces:true,
        noNumbers: true,
        noSpecialChars: true
      },
      last_name: {
        required: true,
        number: false,
        maxlength: 30,
        noLeadingSpaces:true,
        noNumbers: true,
        noSpecialChars: true
      },
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 6,
        maxlength: 20,
      },
      mobile_number: {
        required: true,
        number: true,
        minlength: 10,
        maxlength: 12,
      },
      address: {
        required: true,
      },
      permanent_address: {
        required: true,
      },
      date_of_joining: {
        required: true,
      },

      designations: {
        required: true,
      },
      department: {
        required: true,
      },
      reportingManager: {
        required: function (elem) {
          if ($("#add_user .user_role:checked").val() == 4) {
            return true;
          } else {
            $(elem).closest(".form-group").removeClass("has-error");
            $("#reporting_manager_one").removeClass("error");
            return false;
          }
        },
      },
      image: {
        // required: true,
        accept: "image/jpeg, image/jpg,image/png",
      },
      role_id: {
        required: true,
      },
      "permission[]": {
        required: function (elem) {
          return $(".is_admin_cls").val() == 0;
        },
      },
      status: {
        required: true,
      },
    },

    messages: {
      first_name: {
        required: "Please enter first name.",
      },
      last_name: {
        required: "Please enter last name.",
        number: "Please enter only alphabet",
      },
      email: {
        required: "Please enter employee email address.",
        email: "Please enter a valid email address",
      },
      password: {
        required: "Please enter password.",
        minlength: "Your password must be at least 6 digits long.",
        maxlength: "Your password must be maximum of 20 digits long.",
      },
      mobile_number: {
        required: "Please enter mobile number.",
      },
      address: {
        required: "Please enter address.",
      },
      permanent_address: {
        required: "Please enter permanent address.",
      },
      date_of_joining: {
        required: "Please enter date of joining.",
      },
      role_id: {
        required: "Please select role.",
      },

      designations: {
        required: "Please select designation.",
      },
      department: {
        required: "Please select department.",
      },
      work_mode: {
        required: "Please select work mode.",
      },
      reportingManager: {
        required:
          "Reporting manager field is required when role type is employee.",
      },
      "permission[]": {
        required: "Please select permission.",
      },
      status: {
        required: "Please select status.",
      },
      image: {
        accept: "Please upload valid file. Only  JPEG, PNG & JPG allowed",
      },
    },
    errorPlacement: function (error, element) {
      jQuery("#loader-body").hide();
      $(element).closest(".form-group").addClass("has-error");
      if ($(element).next().hasClass("help-block")) {
        $(element).next().remove();
      }
      $(element).closest(".input-group").after(error);
      $(element).on("keypress keyup change", function () {
        var resp = $(this).valid();
        if (resp === false) {
          $(element).closest(".form-group").addClass("has-error");
        } else {
          $(element).closest(".form-group").removeClass("has-error");
        }
      });
    },
    success: function (element) {
      $(element).closest(".form-group").removeClass("has-error");
    },
    submitHandler: function (form) {
      // $('.employee_loader').show();
      jQuery("#loader-body").show();
      $(form).find(':submit').prop('disabled', true);
      form.submit();
    },
  });

  // validate form for update user
  $("#update_user").validate({
    rules: {
      first_name: {
        required: true,
        maxlength: 30,
      },
      last_name: {
        required: true,
        number: false,
        maxlength: 30,
      },
      email: {
        required: true,
        email: true,
      },
      mobile_number: {
        required: true,
        number: true,
        minlength: 10,
        maxlength: 12,
      },
      address: {
        required: true,
      },
      permanent_address: {
        required: true,
      },
      date_of_joining: {
        required: true,
      },
      role_id: {
        required: true,
      },

      designations: {
        required: true,
      },
      department: {
        required: true,
      },
      work_mode: {
        required: true,
      },
      reportingManager: {
        required: function (elem) {
          if ($("#add_user .user_role:checked").val() == 4) {
            return true;
          } else {
            $(elem).closest(".form-group").removeClass("has-error");
            $("#reporting_manager_one").removeClass("error");
            return false;
          }
        },
      },
      status: {
        required: true,
      },
    },

    messages: {
      first_name: {
        required: "Please enter first name.",
      },
      last_name: {
        required: "Please enter last name.",
        number: "Please enter only alphabet",
      },
      email: {
        required: "Please enter employee email address.",
        email: "Please enter a valid email address",
      },
      password: {
        required: "Please enter password.",
        minlength: "Your password must be at least 6 digits long.",
        maxlength: "Your password must be maximum of 20 digits long.",
      },
      mobile_number: {
        required: "Please enter mobile number.",
      },
      address: {
        required: "Please enter address.",
      },
      permanent_address: {
        required: "Please enter permanent address.",
      },
      date_of_joining: {
        required: "Please enter date of joining.",
      },

      role_id: {
        required: "Please select role.",
      },

      designations: {
        required: "Please select designation.",
      },
      department: {
        required: "Please select department.",
      },
      work_mode: {
        required: "Please select work mode.",
      },
      reportingManager: {
        required:
          "Reporting manager field is required when role type is employee.",
      },
      status: {
        required: "Please select select.",
      },
    },
  });

  //*----validation for change password-----*//
  $("#change_password").validate({
    rules: {
      old_password: {
        required: true,
      },
      new_password: {
        required: true,
        minlength: 6,
      },
      confirm_password: {
        required: true,
        equalTo: "#new_password",
      },
    },

    messages: {
      old_password: {
        required: "Please enter old password",
      },

      new_password: {
        required: "Please enter new password",
      },

      confirm_password: {
        required: "Please enter confirm password",
        equalTo: "New password and confirm password does not match",
      },
    },
  });
});

//*------------validation for add dsr--------------*//

$("#add_dsr").on("submit", function (event) {
  var fieldLength = 1,
    rules = {},
    messages = {};
  // groups={"names":""};

  $(".dsr").each(function (e, v) {
    if (v.name.match(/hours.*/) || v.name.match(/minutes.*/)) {
      var keys = v.name
        .replace("hours", "")
        .replace("minutes", "")
        .replace(new RegExp("]", "g"), "")
        .replace("[", "_")
        .replace("[", "_");

      rules[v.name] = {
        require_from_group: [1, ".hours-minutes" + keys],
        // min:1
      };
    } else {
      rules[v.name] = {
        required: true,
        normalizer: function (value) {
          return $.trim(value);
        },
      };

      messages[v.name] = {
        required: "This field is required",
      };
    }

    fieldLength++;
  });

  $(this).validate({
    rules: rules,
    messages: messages,
  });

  if (!$(this).validate().form()) {
    event.preventDefault();
    console.log("does not validate");
    return false;
  }

  $(this).find(":input[type=submit]").prop("disabled", true);

  var values = {};
  $.each($(this).serializeArray(), function (i, field) {
    values[field.name] = field.value;
  });

  // console.log('Form is validated, do the work')
});

//*------------validation for add project--------------*//
$(document).ready(function () {
  $.validator.addMethod("greaterThanOrEqual", function(value, element, param) {
    var startDate = new Date($("#start_date").val());
    var endDate = new Date(value);

    // Check if end_date is not empty and startDate is less than or equal to endDate
    return value === "" || startDate <= endDate;
  }, "End date must be greater than or equal to start date.");

  // validate for form on keyup and submit
  // ignore[];
  /*$("#add_project_form").validate({
			rules: {
				'name':{
					required: true
				},
				'start_date':{
					required: true
				},
				'end_date':{
					required: true
				}
			},
			phone2: {
				minlength: "Phone 2 must be at least 10 characters long",
				maxlength: "Phone 2 must be less than 12 characters long",
				number: "Phone 2 must be number"
			},
    }
  });

	//add inventoryItem
	$("#add_inventoryItem").validate({
    rules:
    {
      generate_id: {
        required: true,
      },
      category_id: {
        required: true,
      },
      name: {
        required: true,
      },
		company_name: {
		required: true,
		},
		serial_no: {
		required: true,
	  },
    },
    messages:
    {
      generate_id: {
        required: "Please enter generate id",
      },
      category_id: {
        required: "Please select category",
      },
      name: {
        required: "Please enter item name",
      },
		company_name: {
			required: "Please enter company name",
		},
		serial_no: {
			required: "Please enter serial number",
		},
    }
  });

	// add_reason
	$("#add_reason").validate({
    rules:
    {
      reason: {
        required: $(".reason_cls").length == 1,
      },
      avilability_status: {
        required: $(".avl_cls").length == 1,
      },
      assigned_to: {
        required: $(".usr_cls").length == 1,
      },
    },
    messages:
    {
      reason: {
        required: "Please enter Reason",
      },
      avilability_status: {
        required: "Please select status",
      },
      assigned_to: {
        required: "Please select User",
      },
    },
  });

 // validation for assing or spare inventory
 $("#inventory_assign_spare").validate({
	submitHandler: function(form) {
		$("#item_id").change(function() {
			var selectedItem = $(this).val();
			if (selectedItem !== "") {
				$("#inv_err").hide()
			}else{
				$("#inv_err").show()
				$("#inv_err").text("Please select an item to assign");
			}
		});
	*/
  $("#add_project_form").validate({
    rules: {
      name: {
        required: true,
      },
      start_date: {
        required: true,
        date: true,
      },
      end_date: {
        date: true,
        greaterThanOrEqual: true
      },
      client_name: {
        number: false,
      },
      project_manager: {
        required: true,
      },
      team_lead: {
        required: true,
      },
      hours_approved_or_spent: {
        required: true,
        min:0,
      },
      project_url: {
        url: true,
      },
      technology: {
        required: true,
      },
      dev_server_url: {
        url: true,
      },
      qa_server_url: {
        url: true,
      },
      git_url: {
        url: true,
      },
      project_document_url: {
        url: true,
      },
      project_video: {
        url: true,
      },
      current_status: {
        required: true,
        number: false,
      },
    },
    messages: {
      name: {
        required: "Please enter project name.",
      },
      start_date: {
        required: "Please select start date",
        date: "Please enter valid date",
      },
      end_date: {
        date: "Please enter valid date",
      },
      project_manager: {
        required: "Please select project manager",
      },
      team_lead: {
        required: "Please select team lead",
      },
      hours_approved_or_spent: {
        required: "Please enter hours",
      },
      project_url: {
        url: "Please enter valid url",
      },
      technology: {
        required: "Please enter technology",
      },
      dev_server_url: {
        url: "Please enter valid url",
      },
      qa_server_url: {
        url: "Please enter valid url",
      },
      git_url: {
        url: "Please enter valid url",
      },
      project_document_url: {
        url: "Please enter valid url",
      },
      project_video: {
        url: "Please enter valid url",
      },
      current_status: {
        required: "Please enter current status of the project",
        number: "Please enter only text",
      },
    },
    errorPlacement: function (error, element) {
      //console.log(element);
      $(element).closest(".form-group").addClass("has-error");
      if ($(element).next().hasClass("help-block")) {
        $(element).next().remove();
      }
      $(element).closest(".input-group").after(error);
      $(element).on("keypress keyup change", function () {
        var resp = $(this).valid();
        if (resp === false) {
          $(element).closest(".form-group").addClass("has-error");
        } else {
          $(element).closest(".form-group").removeClass("has-error");
        }
      });
    },
    success: function (element) {
      //console.log('success');
      $(element).closest(".form-group").removeClass("has-error");
    },
    submitHandler: function (form) {
      $(form).find(":submit").prop("disabled", true);
      formSubmit(form);
    },
  });

  $("#add_category").validate({
    rules: {
      name: {
        required: true,
      },
    },
    messages: {
      name: {
        required: "Please enter category name",
      },
    },
  });

  $("#add_vendor").validate({
    rules: {
      name: {
        required: true,
      },
      phone1: {
        minlength: 10,
        maxlength: 12,
        number: true,
      },
      phone2: {
        minlength: 10,
        maxlength: 12,
        number: true,
      },
    },
    messages: {
      name: {
        required: "Please enter vendor name",
      },
      phone1: {
        minlength: "Phone 1 must be at least 10 characters long",
        maxlength: "Phone 1 must be less than 12 characters long",
        number: "Phone must be number",
      },
      phone2: {
        minlength: "Phone 2 must be at least 10 characters long",
        maxlength: "Phone 2 must be less than 12 characters long",
        number: "Phone 2 must be number",
      },
    },
  });

  //add inventoryItem
  $("#add_inventoryItem").validate({
    rules: {
      generate_id: {
        required: true,
      },
      category_id: {
        required: true,
      },
      name: {
        required: true,
      },
      company_name: {
        required: true,
      },
      serial_no: {
        required: true,
      },
    },
    messages: {
      generate_id: {
        required: "Please enter generate id",
      },
      category_id: {
        required: "Please select category",
      },
      name: {
        required: "Please enter item name",
      },
      company_name: {
        required: "Please enter company name",
      },
      serial_no: {
        required: "Please enter serial number",
      },
    },
  });

  // add_reason
  $("#add_reason").validate({
    rules: {
      reason: {
        required: $(".reason_cls").length == 1,
      },
      avilability_status: {
        required: $(".avl_cls").length == 1,
      },
      assigned_to: {
        required: $(".usr_cls").length == 1,
      },
    },
    messages: {
      reason: {
        required: "Please enter Reason",
      },
      avilability_status: {
        required: "Please select status",
      },
      assigned_to: {
        required: "Please select User",
      },
    },
  });

  //*----validation for Leave Request-----*//
  $("#create-leave-form").validate({
    rules: {
      title: {
        required: true,
      },
      description: {
        required: true,
      },
      type: {
        required: true,
      },
      start_date: {
        required: true,
      },
      attachment: {
        accept: "image/jpeg, image/jpg,image/png, pdf,doc,docx,csv",
      },
      leave_time: {
        required: function (element) {
          return (
            $("#type").val() == "half_day" || $("#type").val() == "short_leave"
          );
        },
      },
    },
  });

  //*----validation for update profile pic-----*//
  $("#update-profile-pic").validate({
    rules: {
      image: {
        required: true,
        accept: "image/jpeg, image/jpg,image/png",
      },
    },
    messages: {
      image: {
        accept: "Please select valid photo with png jpg jpeg extension",
      },
    },
    submitHandler: function (form) {
      formSubmit(form);
    },
  });


  // validation for assing or spare inventory
  $("#inventory_assign_spare").validate({
    submitHandler: function (form) {
      $("#item_id").change(function () {
        var selectedItem = $(this).val();
        if (selectedItem !== "") {
          $("#inv_err").hide();
        } else {
          $("#inv_err").show();
          $("#inv_err").text("Please select an item to assign");
        }
      });

      // $("#assigned_to").change(function() {
      // 	var selectedItem = $(this).val();
      // 	if (selectedItem !== "") {
      // 		$("#user_err").hide()
      // 	}else{
      // 		$("#user_err").show()
      // 		$("#user_err").text("Please select an  user to assign");
      // 	}
      //   });

      if ($("#item_id").val()) {
        $("#inventory_assign_spare").submit();
      } else {
        $("#inv_err").text("Please select an item to assign");
      }
    },
  });

  
  $("#add_ticket_reply_tickets").validate({
    rules: {
      reply: {
        required: true,
        noLeadingSpaces:true
      },
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element); // Insert error message after the input element
      element.closest(".form-group").addClass("has-error"); // Add 'has-error' class to the parent form-group element
    },
    submitHandler: function (form) {
      $(form).find(":submit").prop("disabled", true);
      formSubmit(form);
    },
  });


    
  $("#archive_store").validate({
    rules: {
      reply: {
        required: true,
        noLeadingSpaces:true
      },
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element); // Insert error message after the input element
      element.closest(".form-group").addClass("has-error"); // Add 'has-error' class to the parent form-group element
    },
    submitHandler: function (form) {
      $(form).find(":submit").prop("disabled", true);
      formSubmit(form);
    },
  });


  $.validator.addMethod(
    "otherCategoryFilled",
    function (value, element) {
      var categorySelect = $("#category_id");
      var otherCategoryInput = $("#other_category");
      return (
        categorySelect.val() !== "other" || otherCategoryInput.val() !== ""
      );
    },
    "Please fill in this field if Other option is selected."
  );

  $.validator.addMethod(
    "noLeadingSpaces",
    function (value, element) {
      return this.optional(element) || /^\S/.test(value);
    },
    "Leading spaces are not allowed."
  );

  $("#add_ticket").validate({
    rules: {
      description: {
        required: true,
        noLeadingSpaces: true,
      },
      ticketAttachmentInput: {
        required: true,
        accept:
          "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet",
      },
      category_id: {
        required: true,
      },
      other_category: {
        required: true,
        maxlength: 50,
        noLeadingSpaces: true,
      },
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element); // Insert error message after the input element
      element.closest(".form-group").addClass("has-error"); // Add 'has-error' class to the parent form-group element
    },
    submitHandler: function (form) {
      $(form).find(":submit").prop("disabled", true);
      formSubmit(form);
    },
  });
//*----validation for update profile pic-----*//
$("#update-profile-pic").validate({
	rules: {
		image:{
			required: true,
			accept: "image/jpeg, image/jpg,image/png"
		}
	},
	messages:
    {
		image: {
			accept: "Please select valid photo with png jpg jpeg extension",
      	}
    },
	submitHandler: function (form) {
		formSubmit(form);
	}
});
 // validation for assing or spare inventory
 $("#inventory_assign_spare").validate({
	submitHandler: function(form) {
		$("#item_id").change(function() {
			var selectedItem = $(this).val();
			if (selectedItem !== "") {
				$("#inv_err").hide()
			}else{
				$("#inv_err").show()
				$("#inv_err").text("Please select an item to assign");
			}
		  });

		// $("#assigned_to").change(function() {
		// 	var selectedItem = $(this).val();
		// 	if (selectedItem !== "") {
		// 		$("#user_err").hide()
		// 	}else{
		// 		$("#user_err").show()
		// 		$("#user_err").text("Please select an  user to assign");
		// 	}
		//   });
		  

		if($("#item_id").val()){
			$("#inventory_assign_spare").submit();
		}
	  else{
			$("#inv_err").text("Please select an item to assign");
		}	
	}
  }); 

$('#add_ticket_reply_1').validate({
	rules: {
		'reply': {
			required: true,
      noLeadingSpaces:true
		}
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element); // Insert error message after the input element
		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
	},
	submitHandler: function (form) {
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
})
$('#add_ticket_reply').validate({
	rules: {
		'reply': {
			required: true
		}
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element); // Insert error message after the input element
		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
	},
	submitHandler: function (form) {
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
})

$('#edit_ticket_reply_it').validate({
	rules: {
		'reply_edit': {
			required: true,
      noLeadingSpaces:true
		},
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element); // Insert error message after the input element
		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
	},
	submitHandler: function (form) {
		localStorage.removeItem('reply_attachment_deleted');
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
})


$('#edit_ticket_reply_harmony').validate({
	rules: {
		'reply_edit': {
			required: true,
      noLeadingSpaces:true
		},
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element); // Insert error message after the input element
		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
	},
	submitHandler: function (form) {
		localStorage.removeItem('reply_attachment_deleted');
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
})

$.validator.addMethod('otherCategoryFilled', function(value, element) {
	var categorySelect = $('#category_id');
	var otherCategoryInput = $('#other_category');
	return categorySelect.val() !== 'other' || otherCategoryInput.val() !== '';
}, 'Please fill in this field if Other option is selected.');

$.validator.addMethod('noLeadingSpaces', function(value, element) {
    return this.optional(element) || /^\S/.test(value);
}, 'Leading spaces are not allowed.');


$('#add_ticket').validate({
	rules: {
		'description': {
			required: true,
		},
		'ticketAttachmentInput': {
			required: true,
			accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
		  },
		'category_id':{
			required: true
		},
		'other_category': {
			required: true
		}
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element); // Insert error message after the input element
		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
	},
	submitHandler: function (form) {
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
	
})

$('#edit1_ticket').validate({
	rules: {
		'edit_description': {
			required: true,
			noLeadingSpaces:true
		},
		'category_id': {
			required: true
		},
		'severity':{
			required: true
		},
		edit_ticketAttachment: {
			required: true,
			accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
		  }
	},
	errorPlacement: function (error, element) {
		$(element).closest('.form-group').addClass('has-error');
		if ($(element).next().hasClass('help-block')) {
			$(element).next().remove();
		}
		$(element).closest('.input-group').after(error);
	},
	submitHandler: function (form) {
		$(form).find(':submit').prop('disabled', true);
		formSubmit(form);
	}
})


$("#add_department").validate({
	rules: {
		'department_code': {
			required: true
		},
		'department_name': {
			required: true
		}
	},
	errorPlacement: function (error, element) {
		$(element).closest('.form-group').addClass('has-error');
		if ($(element).next().hasClass('help-block')) {
			$(element).next().remove();
		}
		$(element).closest('.input-group').after(error);
	}
});


$("#add_birthday_card").validate({
	rules: {
		'employee_id': {
			required: true
		},
		'birthday_date': {
			required: true
		}
	},
	errorPlacement: function (error, element) {
		$(element).closest('.form-group').addClass('has-error');
		if ($(element).next().hasClass('help-block')) {
			$(element).next().remove();
		}
		$(element).closest('.input-group').after(error);
	},
  submitHandler: function (form) {
    $(form).find(":submit").prop("disabled", true);
    formSubmit(form);
  },
});







 // validation for assing or spare inventory
 $("#inventory_assign_spare").validate({
	submitHandler: function(form) {
		$("#item_id").change(function() {
			var selectedItem = $(this).val();
			if (selectedItem !== "") {
				$("#inv_err").hide()
			}else{
				$("#inv_err").show()
				$("#inv_err").text("Please select an item to assign");
			}
		  });

		// $("#assigned_to").change(function() {
		// 	var selectedItem = $(this).val();
		// 	if (selectedItem !== "") {
		// 		$("#user_err").hide()
		// 	}else{
		// 		$("#user_err").show()
		// 		$("#user_err").text("Please select an  user to assign");
		// 	}
		//   });
		  

		if($("#item_id").val()){
			$("#inventory_assign_spare").submit();
		}
	  else{
			$("#inv_err").text("Please select an item to assign");
		}	
	}
  }); 
  
//*----validation for Leave Request-----*//
$("#create-leave-form").validate({
	rules: {

		'title': {
			required: true,
		},
		'description': {
			required: true,
		  },
		},
		messages:
		{
		  name: {
			required: "Please enter category name",
		  },
		}
	  });
	
		$("#add_vendor").validate({
		rules:
		{
		  name: {
			required: true,
		  },
				phone1: {
					minlength:10,
					maxlength:12,
					number: true
				},
				phone2: {
					minlength:10,
					maxlength:12,
					number: true
				},
		},
		messages:
		{
		  name: {
			required: "Please enter vendor name",
		  },
				phone1: {
					minlength: "Phone 1 must be at least 10 characters long",
					maxlength: "Phone 1 must be less than 12 characters long",
					number: "Phone must be number"
				},
				phone2: {
					minlength: "Phone 2 must be at least 10 characters long",
					maxlength: "Phone 2 must be less than 12 characters long",
					number: "Phone 2 must be number"
				},
		}
	  });
	
		//add inventoryItem
		$("#add_inventoryItem").validate({
		rules:
		{
		  generate_id: {
			required: true,
		  },
		  category_id: {
			required: true,
		  },
		  name: {
			required: true,
		  },
			company_name: {
			required: true,
			},
			serial_no: {
			required: true,
		  },
		},
		messages:
		{
		  generate_id: {
			required: "Please enter generate id",
		  },
		  category_id: {
			required: "Please select category",
		  },
		  name: {
			required: "Please enter item name",
		  },
			company_name: {
				required: "Please enter company name",
			},
			serial_no: {
				required: "Please enter serial number",
			},
		}
	  });
	
		// add_reason
		$("#add_reason").validate({
		rules:
		{
		  reason: {
			required: $(".reason_cls").length == 1,
		  },
		  avilability_status: {
			required: $(".avl_cls").length == 1,
		  },
		  assigned_to: {
			required: $(".usr_cls").length == 1,
		  },
		},
		messages:
		{
		  reason: {
			required: "Please enter Reason",
		  },
		  avilability_status: {
			required: "Please select status",
		  },
		  assigned_to: {
			required: "Please select User",
		  },
		}
	  });
	
	  
	  
	//*----validation for Leave Request-----*//
	$("#create-leave-form").validate({
		rules: {
	
			'title': {
				required: true,
			},
			'description': {
				required: true,
			},
			'type': {
				required: true,
			},
			'start_date': {
				required: true,
			},
			'attachment':{
				accept: "image/jpeg, image/jpg,image/png, pdf,doc,docx,csv"
			},
			'leave_time': {
				required: function(element) {
				  return (($('#type').val()=='half_day') || ($('#type').val()=='short_leave'));
				}
			}
	
	
		},
	
	});
	
	//*----validation for update profile pic-----*//
	$("#update-profile-pic").validate({
		rules: {
			'image':{
				required: true,
				accept: "image/jpeg, image/jpg,image/png"
			}
		},
		messages:
		{
			image: {
				accept: "Please select valid photo with png jpg jpeg extension",
			  }
		},
		submitHandler: function (form) {
			formSubmit(form);
		}
	});
	 // validation for assing or spare inventory
	 $("#inventory_assign_spare").validate({
		submitHandler: function(form) {
			$("#item_id").change(function() {
				var selectedItem = $(this).val();
				if (selectedItem !== "") {
					$("#inv_err").hide()
				}else{
					$("#inv_err").show()
					$("#inv_err").text("Please select an item to assign");
				}
			  });
	
			// $("#assigned_to").change(function() {
			// 	var selectedItem = $(this).val();
			// 	if (selectedItem !== "") {
			// 		$("#user_err").hide()
			// 	}else{
			// 		$("#user_err").show()
			// 		$("#user_err").text("Please select an  user to assign");
			// 	}
			//   });
			  
	
			if($("#item_id").val()){
				$("#inventory_assign_spare").submit();
			}
		  else{
				$("#inv_err").text("Please select an item to assign");
			}	
		}
	  }); 
	
	$('#add_ticket_reply_1').validate({
		rules: {
			'reply': {
				required: true
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	})
	$('#add_ticket_reply').validate({
		rules: {
			'reply': {
				required: true
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
})

	
	$.validator.addMethod('otherCategoryFilled', function(value, element) {
		var categorySelect = $('#category_id');
		var otherCategoryInput = $('#other_category');
		return categorySelect.val() !== 'other' || otherCategoryInput.val() !== '';
	}, 'Please fill in this field if Other option is selected.');
	
	$.validator.addMethod('noLeadingSpaces', function(value, element) {
		return this.optional(element) || /^\S/.test(value);
	}, 'Leading spaces are not allowed.');
	
  $('#add_it_ticket').validate({
    rules: {
      'description': {
        required: true,
        noLeadingSpaces:true
      },
      'category_id': {
        required: true
      }
    },
    errorPlacement: function(error, element) {
      error.insertAfter(element); // Insert error message after the input element
      element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
    },
    submitHandler: function (form) {
      $(form).find(':submit').prop('disabled', true);
      formSubmit(form);
    }
    
  })
  
	$('#add_ticket').validate({
		rules: {
			'description': {
				required: true,
				noLeadingSpaces:true
			},
			'ticketAttachmentInput': {
				required: true,
				accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
			  },
			'category_id':{
				required: true
			},
			'other_category': {
				required: true,
				maxlength: 50,
				noLeadingSpaces:true
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
		
	})
	
// 	$('#add_it_ticket').validate({
// 		rules: {
// 			'description': {
// 				required: true,
// 				noLeadingSpaces:true
// 			},
// 			'category_id': {
// 				required: true
// 			},
// 			'severity':{
// 				required: true
// 			},
// 			'gallery[]': {
// 				required: true,
// 			}
// 		},
// 		edit_ticketAttachmentInput: {
// 			accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
// 		  },
// 	errorPlacement: function(error, element) {
// 		error.insertAfter(element); // Insert error message after the input element
// 		element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
// 	},
// 	submitHandler: function (form) {
// 		$(form).find(':submit').prop('disabled', true);
// 		formSubmit(form);
// 	}
// })

$("#edit_ticket_reply").validate({
  rules: {
    reply_edit: {
      required: true,
    },
  },
  errorPlacement: function (error, element) {
    error.insertAfter(element); // Insert error message after the input element
    element.closest(".modal").addClass("has-error"); // Add 'has-error' class to the parent form-group element
  },
  submitHandler: function (form) {
    $(form).find(":submit").prop("disabled", true);
    formSubmit(form);
  },
  });

  $('#documentCreateForm').validate({
    rules: {
      'document': {
        required: true,
        accept: 'pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        },
      'protected_file':{
        required: true
      },
    },
    messages: {
      'document': {
        required: "Please upload a document.",
        accept: "Please upload PDF, docx and doc format files only."
      },
      'protected_file': {
        required: "Please select this field."
      }
    },
    errorPlacement: function(error, element) {
      error.insertAfter(element);
      element.closest('.form-group').addClass('has-error');
      console.log(error);
    },
    submitHandler: function (form) {
      $(form).find(':submit').prop('disabled', true);
      formSubmit(form);
    }
    
  })
});

$('#documentEditForm').validate({
  rules: {
    'edit_document': {
      accept: 'pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
      },
    'protected_file':{
      required: true
    },
  },
  messages: {
    'edit_document': {
      accept: "Please upload PDF, docx and doc format files only."
    },
    'protected_file': {
      required: "Please select this field."
    }
  },
  errorPlacement: function(error, element) {
    error.insertAfter(element);
    element.closest('.form-group').addClass('has-error');
    console.log(error);
  },
  submitHandler: function (form) {
    $(form).find(':submit').prop('disabled', true);
    formSubmit(form);
  }
  
});

$('#edit1_ticket').validate({
	rules: {
		'edit_description': {
			required: true,
			noLeadingSpaces:true
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	}
})
	
	$('#edit_ticket').validate({
		rules: {
			'edit_description': {
				required: true,
				noLeadingSpaces:true
			},
			'category_id': {
				required: true
			},
			'other_category': {
				required: true,
				maxlength: 50,
				noLeadingSpaces:true
			},
			edit_ticketAttachmentInput: {
				accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
			  }
		},
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	})
	
	$('#edit1_ticket').validate({
		rules: {
			'edit_description': {
				required: true,
				noLeadingSpaces:true
			},
			'category_id': {
				required: true
			},
			'severity':{
				required: true
			},
			edit_ticketAttachment: {
				required: true,
				accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet"
			  }
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').addClass('has-error');
			if ($(element).next().hasClass('help-block')) {
				$(element).next().remove();
			}
			$(element).closest('.input-group').after(error);
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	})
	
	
	$("#add_department").validate({
		rules: {
			'department_code': {
				required: true
			},
			'department_name': {
				required: true
			}
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').addClass('has-error');
			if ($(element).next().hasClass('help-block')) {
				$(element).next().remove();
			}
			$(element).closest('.input-group').after(error);
		}
	});
	
	$("#edit_department").validate({
		rules: {
			'code': {
				required: true
			},
			'name': {
				required: true
			}
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').addClass('has-error');
			if ($(element).next().hasClass('help-block')) {
				$(element).next().remove();
			}
			$(element).closest('.input-group').after(error);
		}
	});
	
	$("#add_designation").validate({
		rules: {
			'name': {
				required: true,
			}
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').addClass('has-error');
			if ($(element).next().hasClass('help-block')) {
				$(element).next().remove();
			}
			$(element).closest('.input-group').after(error);
		}
	});
	
  $.validator.addMethod("lettersOnly", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Only alphabets are allowed.");

    $.validator.addMethod("letterOnly", function(value, element) {
      return this.optional(element) || /^[^\s]+$/.test(value);
      }, "Spaces are not allowed. ");

    $.validator.addMethod("numbersOnly", function (value, element) {
      return this.optional(element) || /^[0-9.\s]+$/.test(value);
      }, "Only numbers are allowed." );

    $.validator.addMethod('maxValue', function(value, element, params) {
      return this.optional(element) || parseFloat(value) <= parseFloat(params);
    }, 'Number should not be greater than 30.');

    $.validator.addMethod('notEmptyDot', function(value, element) {
        return value.trim() !== '.'; // Check if the value is not just a dot
    }, "Value can't just be a dot.");

  $('#add_reference').validate({
		rules: {
			'first_name': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 50,
        lettersOnly: true
			},
      'last_name': {
				noLeadingSpaces:true,
        maxlength: 50,
        letterOnly: true,
        lettersOnly: true
			},
      'mobile_number':{
        required: true,
        number: true,
        maxlength: 10,
        minlength: 10,
        noLeadingSpaces:true,
      },
			'department': {
				required: true
			},
      'experience': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 5,
        numbersOnly: true,
        notEmptyDot: true,
        maxValue: 30
			},
      'resume': {
				required: true,
        accept: "pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, 	application/vnd.oasis.opendocument.text",
			},
      'reference_platform': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 50,
			},
		},
    messages: {
      resume: {
        accept: "Please upload valid file. Only PDF, DOC, DOCX and ODT allowed",
      },
      mobile_number: {
        minlength: "Please enter at least 10 digits."
      },
      experience: {
        maxValue: "Number should not be greater than 30"
      },
    },
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	})
	
  
  $('#edit_reference').validate({
		rules: {
			'first_name': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 50,
        lettersOnly: true
			},
      'last_name': {
				noLeadingSpaces:true,
        maxlength: 50,
        letterOnly: true,
        lettersOnly: true
			},
      'mobile_number':{
        required: true,
        number: true,
        maxlength: 10,
        minlength: 10,
        noLeadingSpaces:true,
      },
			'department': {
				required: true
			},
      'experience': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 5,
        numbersOnly: true,
        notEmptyDot: true,
        maxValue: 30 
			},
      'resume': {
				required: true,
        accept: "pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, 	application/vnd.oasis.opendocument.text",
			},
      'reference_platform': {
				required: true,
				noLeadingSpaces:true,
        maxlength: 50,
			},
		},
    messages: {
      resume: {
        accept: "Please upload valid file. Only PDF, DOC, DOCX and ODT allowed",
      },
      mobile_number: {
        minlength: "Please enter at least 10 digits."
      },
      experience: {
        maxValue: "Number should not be greater than 30"
      },
    },
		errorPlacement: function(error, element) {
			error.insertAfter(element); // Insert error message after the input element
			element.closest('.form-group').addClass('has-error'); // Add 'has-error' class to the parent form-group element
		},
		submitHandler: function (form) {
			$(form).find(':submit').prop('disabled', true);
			formSubmit(form);
		}
	})
	
