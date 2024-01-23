@extends('layouts.page')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="content-wrapper">
        <div class="container-fluid" style="margin-top: 4%">

          <!-- Example DataTables Card-->
          <div class="card mb-3">

            <div class="card-header">

              <i class="fa fa-table"></i> <b> User List</b>
            </div>

            </div>

                <table class="table table-bordered"  width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>User ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                    </tr>
                  </thead>

                  <tbody>
                      @foreach($users as $value)
                        <tr>
                          <td>{{$value->id}}</td>
                          <td>{{$value->first_name}}</td>
                          <td>{{$value->last_name}}</td>
                          <td>{{$value->email}}</td>
                        </tr>
                      @endforeach

                  </tbody>
                </table>
                {{ $users->appends(\Request::except('page'))->render() }}

              </div>
            </div>

</div>
@endsection
