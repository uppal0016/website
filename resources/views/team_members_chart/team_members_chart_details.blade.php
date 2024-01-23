@if ($current_reporting_manager_id == $reporting_ids)
    @php
        $unique_designation_names = array_unique($users->pluck('designation_name')->toArray());
    @endphp

    @foreach ($unique_designation_names as $designation_name)
        @php
            // Check if $designation_name exists in $designation_order array
            $designation_order = isset($designation_value_mapping[$designation_name]) ? $designation_value_mapping[$designation_name] : null;
        @endphp

        @if ($designation_order !== null)
        <li class="manager_details">
            <ul class="employee_subordinates">
                @foreach ($users as $user)
                    @if ($user->designation_name === $designation_name)
                    <li class="employee_subordinates_list">
                        <a class="mb-2 mt-1 employee_subordinates_list  {{ $classMap[$designation_name] ?? '' }}" style="display: block"><span class="arrow_icon"><i class="fa-solid fa-circle-arrow-down"></i></span> <b>{{ $designation_name }}</b><br/> {{ $user->first_name }} {{ $user->last_name }} </a>

                        <ul class="subordinates_list" style="display: none">
                            @php
                                $reporting_manager_id = $user->id;
                                $subordinates = \App\User::where('is_deleted', 0)->where('status', 1)->get()->filter(function ($subordinate) use ($reporting_manager_id) {
                                        $managerIds = explode(',', $subordinate->reporting_manager_id);
                                        return in_array($reporting_manager_id, $managerIds);
                                    });
                                $matched_subordinates = $subordinates->whereIn('designation.name', array_keys($designation_value_mapping))->groupBy('designation.name');
                                $unmatched_subordinates = $subordinates->whereNotIn('designation.name', array_keys($designation_value_mapping))->groupBy('designation.name');
                            @endphp

                            {{-- Display unmatched subordinates in any order --}}
                            @foreach ($unmatched_subordinates as $designation_id => $subordinates_group)
                                @foreach ($subordinates_group as $subordinate)
                                    <li class="subordinate_designation_name" style="display: block">
                                        <a class="mb-2 mt-1 subordinate_designation_name {{ $classMap[$subordinate->designation->name] ?? '' }}" style="display: block"><b>{{ optional($subordinate->designation)->name }}</b><br/> {{ $subordinate->first_name }} {{ $subordinate->last_name }} </a>
                                    </li>
                                @endforeach
                            @endforeach

                            {{-- Display matched subordinates in the specified order --}}
                            @foreach ($designation_value_mapping as $employee_designation_name => $order)
                                @if (isset($matched_subordinates[$employee_designation_name]))
                                    @foreach ($matched_subordinates[$employee_designation_name] as $subordinate)
                                        <li class="subordinate_designation_name {{ $employee_designation_name === 'Manager L1' ? ' manager_card' : '' }}" style="display:block">
                                            @php
                                                $sub_reporting_manager_id = $subordinate->id;
                                                $sub_subordinates = \App\User::where('is_deleted', 0)
                                                    ->where('status', 1)
                                                    ->get()->filter(function ($sub_subordinate) use ($sub_reporting_manager_id) {
                                                        $managerIds = explode(',', $sub_subordinate->reporting_manager_id);
                                                        return in_array($sub_reporting_manager_id, $managerIds);
                                                    });

                                                $sub_matched_subordinates = $sub_subordinates->whereIn('designation.name', array_keys($designation_value_mapping))->groupBy('designation.name');
                                                $sub_unmatched_subordinates = $sub_subordinates->whereNotIn('designation.name', array_keys($designation_value_mapping));
                                            @endphp

                                            <a class="subordinate_employee_details mb-2 mt-1 {{ $classMap[$subordinate->designation->name] ?? '' }}">
                                                @if (!$sub_matched_subordinates->isEmpty() || !$sub_unmatched_subordinates->isEmpty())
                                                    <span class="arrow_icon"><i class="fa-solid fa-circle-arrow-down"></i></span>
                                                @endif
                                                <b>{{ $subordinate->designation->name }}</b><br />{{ $subordinate->first_name }} {{ $subordinate->last_name }}
                                            </a>

                                            {{-- Display unmatched data that comes under subordinates in any order --}}
                                            @if (!$sub_unmatched_subordinates->isEmpty())
                                                <ul class="sub_subordinates_list" style="display: none">
                                                    @foreach ($sub_unmatched_subordinates as $sub_subordinate)
                                                        <li class="subordinate_designation_name" style="display: block">
                                                            <a class="subordinate_employee_details mb-2 mt-1 ">
                                                                <b>{{ optional($sub_subordinate->designation)->name }}</b><br />{{ $sub_subordinate->first_name }} {{ $sub_subordinate->last_name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            {{-- Display matched data in the specified order --}}
                                            @if (!$sub_matched_subordinates->isEmpty())
                                                <ul class="sub_subordinates_list" style="display: none">
                                                    @foreach ($designation_value_mapping as $sub_employee_designation_name => $sub_order)
                                                        @if (isset($sub_matched_subordinates[$sub_employee_designation_name]))
                                                            @php
                                                                $subordinates = $sub_matched_subordinates[$sub_employee_designation_name]->groupBy('designation_id')->flatten();
                                                            @endphp
                                                            @foreach ($subordinates as $sub_subordinate)
                                                                <li class="subordinate_designation_name" style="display: block">
                                                                    <a class="{{ $classMap[$sub_employee_designation_name] ?? '' }}"><b>{{ $sub_employee_designation_name }}</b>
                                                                        {{-- <div class="sub_ordinate_employee_name mb- mt-1" style="display: block"> --}}
                                                                            {{ $sub_subordinate->first_name }} {{ $sub_subordinate->last_name }}
                                                                        {{-- </div> --}}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @endif
                @endforeach
            </ul>
        </li>
        @endif
    @endforeach
@endif