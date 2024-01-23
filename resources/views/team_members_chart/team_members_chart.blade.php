@extends('layouts.page')
@section('content')
    {{-- include css file --}}
    @include('team_members_chart.team_members_chart_css')

    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7 secLeft">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Company Hierarchical Chart</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="col-6" style="position: relative; display: flex; justify-content: end; ">
                            <div class="customSearch" style="width: 50%;">
                               <input type="text" id="employee_name_search" class="form-control" placeholder="Search by name" autocomplete="off" name="employee_name" aria-describedby="button-addon6" style="height: 3rem;">
                            </div>
                        <div class="px-3">
                            <a href="{{url('/team_member_chart')}}" id="team_members_reset_button">
                                <button class="btn btn-danger" type="button" name="submit" style="width: 53px !important; height: 100% !important;" title="Reset the search filters" id="reset_button">
                                    <i class="fa fa-times"></i>
                                </button>
                            </a>
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
                    <div class="card-header border-0 d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">Team Members List</h3>
                    </div>
                    @php
                        // Group users by reporting manager
                        $users_by_reporting_manager = $users->where('is_deleted', 0)->where('status', 1);

                        // in future, if any name need to show in above then just add the designation name here.
                        $designation_value_mapping = [
                            'Founder and MD' => 0,
                            'Co-Founder and MD' => 1,
                            'Director Service Delivery' => 2,
                            'Director Operations' => 3,
                            'Director HR' => 4,
                            'Business Development Manager L2' => 5,
                            'Marketing Manager L1' => 6,
                            'Senior Manager L2' => 7,
                            'Senior Manager L1' => 8,
                            'Manager L2' => 9,
                            'Manager L1' => 10,
                            'Senior Software Engineer L2' => 11,
                            'Senior Software Engineer L1' => 12,
                            'Software Engineer L2' => 13,
                            'Software Engineer L1' => 14,
                            'Junior Software Engineer' => 15,
                            'Senior HR Manager' => 16,
                            'Senior Admin Manager' => 17,
                            'Account Officer'=> 18,
                        ];

                        $merged_users_by_reporting_manager = [];
                        foreach ($users_by_reporting_manager as $reporting_manager_id => $users) {
                            $manager_ids = explode(',', $reporting_manager_id);
                            foreach ($manager_ids as $id) {
                                if (!isset($merged_users_by_reporting_manager[$id])) {
                                    $merged_users_by_reporting_manager[$id] = $users;
                                } else {
                                    $merged_users_by_reporting_manager[$id] = $merged_users_by_reporting_manager[$id]->merge($users);
                                }
                            }
                        }

                        // $all_users_by_reporting_manager = collect($all_users);
                        // $all_users_details = $all_users_by_reporting_manager->groupBy('reporting_manager_id');

                        $all_users_by_reporting_manager = collect($all_users);
                        $all_users_details_fixed = $all_users_by_reporting_manager->groupBy('reporting_manager_id');

                        $all_users_details = collect();

                        foreach ($all_users_details_fixed as $key => $collection) {
                            $fixed_key = trim($key); // Remove leading and trailing spaces
                            $all_users_details[$fixed_key] = $all_users_details[$fixed_key] ?? collect();
                            $all_users_details[$fixed_key] = $all_users_details[$fixed_key]->merge($collection);
                        }

                        $designation_order = ['Founder and MD', 'Co-Founder and MD', 'Director Service Delivery', 'Director Operations', 'Director HR', 'Business Development Manager L2', 'Marketing Manager L1', 'Senior Manager L2', 'Senior Manager L1', 'Manager L2', 'Manager L1', 'Senior Software Engineer L2', 'Senior Software Engineer L1', 'Software Engineer L2', 'Software Engineer L1', 'Junior Software Engineer'];

                        // Sort the designations based on the order
                        uasort($designation_order, function ($a, $b) use ($designation_value_mapping) {
                            return $designation_value_mapping[$a] - $designation_value_mapping[$b];
                        });

                    @endphp
                    <div class="color-code">
                        <h3>Stat colors:</h3>
                        <ul class="list-inline">
                            <li class="list-inline-item"><p class="parent color-box m-0 mr-2"></p>Founder and MD & Co-Founder and MD, </li>
                            <li class="list-inline-item"><p class="child1 color-box m-0 mr-2"></p>DIR Service Delivery & DIR Operations & DIR HR, </li>
                            <li class="list-inline-item"><p class="child2 color-box m-0 mr-2"></p>BD Manager L2, </li>
                            <li class="list-inline-item"><p class="child5 color-box m-0 mr-2"></p>Sr. Manager L1, </li>
                            <li class="list-inline-item"><p class="child6 color-box m-0 mr-2"></p>Manager L2, </li>
                            <li class="list-inline-item"><p class="child7 color-box m-0 mr-2"></p>Manager L1, </li>
                            <li class="list-inline-item"><p class="child8 color-box m-0 mr-2"></p>Sr. Software Engineer L2, </li>
                            <li class="list-inline-item"><p class="child9 color-box m-0 mr-2"></p>Sr. Software Engineer L1, </li>
                            <li class="list-inline-item"><p class="child10 color-box m-0 mr-2"></p>Software Engineer L2, </li>
                            <li class="list-inline-item"><p class="child11 color-box m-0 mr-2"></p>Software Engineer L1, </li>
                            <li class="list-inline-item"><p class="child12 color-box m-0 mr-2"></p>Jr Software Engineer </li>
                        </ul>
                    </div>
                    <div class="container-wrap">
                        <div class="team-list child-chart">
                            <div class="org-chart">
                                <ul class="org_list">
                                <li>
                            <div class="main_wrap">
                            @foreach ($merged_users_by_reporting_manager as $reporting_ids => $users_group)
                                @php
                                    $reporting_manager = \App\User::find($reporting_ids);
                                    if (!$reporting_manager) {
                                        continue; // Skip to the next reporting manager
                                    }
                                    $designation = $reporting_manager->designation;
                                    $designationName = $designation->name;
                                @endphp

                                <a href="#" class="designation_list founder_card">
                                    <span class="arrow_icon"><i class="fa-solid fa-circle-arrow-down"></i></span> 
                                    <b class="heading_designation_list">{{ $designation->name }}</b><br>
                                    {{ $reporting_manager->first_name }} {{ $reporting_manager->last_name }}
                                </a>
                            @endforeach
                        </div>

                        <!-- Display employee_name_list outside the loop -->
                        <ul class="employee_name_list mb-4 sub_list">
                            @php
                                $classMap = [
                                    'Director Service Delivery' => 'director_card',
                                    'Director Operations' => 'director_card',
                                    'Director HR' => 'director_hr_card',
                                    'Business Development Manager L2' => 'business_development_l2_card',
                                    'Marketing Manager L1' => 'marketing_l2_card',
                                    'Senior Manager L2' => 'senior_manager_l2_card',
                                    'Senior Manager L1' => 'senior_manager_l1_card',
                                    'Manager L2' => 'manager_l2_card',
                                    'Manager L1' => 'manager_l1_card',
                                    'Senior Software Engineer L2' => 'senior_software_engineer_l2_card',
                                    'Senior Software Engineer L1' => 'senior_software_engineer_l1_card',
                                    'Software Engineer L2' => 'software_engineer_l2_card',
                                    'Software Engineer L1' => 'software_engineer_l1_card',
                                    'Junior Software Engineer' => 'junior_software_engineer_card',
                                    'Senior HR Manager' => 'senior_hr_manager',
                                ];
                            @endphp
                            @foreach ($all_users_details as $current_reporting_manager_id => $users)
                                {{-- getting data from team_members_chart_details page --}}
                                @include('team_members_chart.team_members_chart_details', ['current_reporting_manager_id'=>$current_reporting_manager_id])
                            @endforeach
                        </ul>
                    </div>
                        
                    </li>
                </ul>
              </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('script')
    {{-- include js file --}}
    @include('team_members_chart.team_members_chart_js')
@endsection
@endsection
