@extends('layouts.page')
@section('content')
<style>
  .row{
    margin-bottom: 1rem;
  }

  i.fa.fa-asterisk {
    position: absolute;
    top: 6px;
    margin-left: 1%;
  }

  .input-group.input-group-merge.input-group-alternative{
    border: #b3aaaa 0.5px solid;
  }
</style>

<div class="header pb-8 d-flex align-items-center" style="background-image: url(../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
      <!-- Mask -->
      <span class="mask bg-gradient-default opacity-8"></span>
      <!-- Header container -->
      <!-- <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="display-2 text-white ">Hello {{ Auth::user()->first_name.' ' .Auth::user()->last_name}}</h1>
            <p class="text-white">Edit profile</p>
          </div>
        </div>
      </div> -->
    </div>
	
	<div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
		  <img src="{{URL::asset('images/profile-background.jpg')}}" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
				@if(!empty($user->image))
					<img class="img-upload-preview rounded-circle" width="150" src="{{URL::asset('images/profile_picture/'.$user->image)}}" alt="preview">
				@else
					<img class="img-upload-preview rounded-circle" width="150" src="{{URL::asset('images/no-image.png')}}" alt="preview">
				@endif
                 
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
              <form class="d-flex justify-content-between align-items-start" method="post" id="update-profile-pic" action="{{ url('/change-profile-picture') }}" enctype="multipart/form-data">
                    <div class="edit-profile">

                      <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                      <input class="form-control-file valImage" id="uploadImg1" name="image" type="file">
                      
                    </div>
                    <button class="btn btn-sm btn-info">Upload Profile Picture</button>
              </form>
              <div class="text-left">
                  <a href="{{ url('/remove-profile-picture') }}" id="removeButton" style="{{ $user->image ? 'display: block;' : 'display: none;' }}">
                      <span>Remove Picture</span>
                  </a>
              </div>
            </div>
            
             
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Edit profile </h3>
                </div>
	        	@if(session()->has('error_flash_message'))
	        	  <div class="alert alert-danger">
	        	    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	        	    {{ session()->get('error_flash_message') }}
	        	  </div>
	        	@endif
              </div>
            </div>
            <div class="card-body">
			
              <div>
                <h2 class="heading-muted mb-4">View Profile</h2>
      <div class="row">
        <div class="col-lg-6">
            <label for="exampleInputEmail1">First Name</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input type="text" name="first_name" value="{{ $user->first_name }}" id="first_name"
                    placeholder="First name" class="form-control" autocomplete="off" disabled style="color: #3a3939; "/>
            </div>
        </div>
        <div class="col-lg-6">
            <label for="exampleInputEmail1">Last Name</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input type="text" name="last_name" value="{{ $user->last_name }}" id="last_name"
                    placeholder="Last name" class="form-control" autocomplete="off" disabled style="color: #3a3939; "/>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <label for="exampleInputEmail1">Employee Code</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input type="text" name="employee_code" id="employee_code" value="{{ $user->employee_code }}"
                    placeholder="Employee Code" class="form-control" autocomplete="off" disabled style="color: #3a3939; ">
            </div>
        </div>
    
        <div class="col-lg-6">
            <label for="exampleInputEmail1">Email Address</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input type="email" name="email" value="{{ $user->email }}" id="email"
                    placeholder="Email Address" class="form-control" autocomplete="off" disabled style="color: #3a3939; "/>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <label for="exampleInputEmail1">Department</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input name="department" class="form-control" value="{{ $user->department }}" disabled style="color: #3a3939; " placeholder="Department" >
            </div>
        </div>
    
        <div class="col-lg-6">    
            <label for="exampleInputEmail1">Designation</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input name="designations" class="form-control" value="{{ $user->designation }}" disabled style="color: #3a3939; " placeholder="Designation" >
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <label for="exampleInputEmail1">Mobile Number</label>
            <div class="input-group input-group-merge input-group-alternative">
                <input type="text" name="mobile_number" value="{{ $user->mobile_number }}" id="mobile_number"
                    placeholder="Mobile number" class="form-control" autocomplete="off" disabled style="color: #3a3939; "/>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <label for="exampleInputEmail1">Address</i></label>
            <div class="input-group input-group-merge input-group-alternative">
                <textarea type="text" rows="3" cols="100" name="permanent_address" id="permanent_address"
                    placeholder="Address" class="form-control" autocomplete="off" disabled style="color: #3a3939; ">{{ $user->permanent_address }}</textarea>
            </div>
        </div>
    </div>
    </div>
    
    <hr>
    <form id="change_password" action="{{ url('change_password') }}" method="post" autocomplete="off"/>               
    <h2 class="heading-muted mb-4">Change password</h2>
                <div class="">
				<div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="old_password">Current Password</label>
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
						<input type="password" name="old_password" value="{{old('old_password')}}" id="old_password" placeholder="Old password" class="required form-control"/>
						<span class="text-danger">
							{{$errors->first('old_password')}}
						</span>
                      </div>
                    </div>
				</div>
					<div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="new_password">New Password</label>
						<input type="password" name="new_password" id="new_password" value="{{old('new_password')}}"placeholder="New password" class="required form-control" />
						<span class="text-danger">
							{{$errors->first('new_password')}}
						</span>                      
					</div>
                    </div>
                  
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="confirm_password">Confirm Password</label>
						<input type="password" name="confirm_password" id="confirm_password" value="{{old('confirm_password')}}"placeholder="Confirm password" class="required form-control" />
						<span class="text-danger">
							{{$errors->first('confirm_password')}}
						</span>                      
					</div>
                    </div>
                   
                  </div>
                </div>
				<button type="submit" class="btn  btn-info  mr-4  add-user-btn " name="submit">Update</button>

                <!--hr class="my-4" /-->                
              </form>
            </div>
          </div>
        </div>
      </div>

@section('script')
<script>
//For date of birth
$(document).ready(function() {
	jQuery(document).on('change', '.valImage', function () {
        var file = this.files[0];
        var fileType = file["type"];
        var validImageTypes = ["image/jpg", "image/jpeg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            toaster('Error', 'Please upload jpeg,jpg, gif and png files only', 2000);	
            $(this).val('');
        } else {
			var reader = new FileReader();
			reader.onload = function () {
				$('.img-upload-preview').attr('src', reader.result);
			}
			reader.readAsDataURL(file);
		}
    });
    setTimeout(function() {        
         $("#loader-body").fadeOut();
    
      },200)
});
</script>

<script>
  $(document).ready(function() {
      var defaultImageSet = {{ $user->image ? 'true' : 'false' }};

      if (!defaultImageSet) {
          $('#removeButton').hide();
      }

      $('#pictureUploadInput').change(function() {
          if (this.files && this.files[0]) {
              $('#removeButton').show();
          } else if (!defaultImageSet) {
              $('#removeButton').hide();
          }
      });
  });
</script>

<script>
  $(document).ready(function() {
    $('.valImage').on('change', function() {
      var fileSize = this.files[0].size; // Size in bytes
      var maxSize = 2 * 1000 * 1000; // 2MB in bytes

      if (fileSize > maxSize) {
        // Clear the selected file
        $(this).val('');
        alert('The image file size should not exceed 2MB.');
      }
    });
  });
</script>
@endsection
@endsection


