<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamMemberChart extends Controller
{
  public function index(Request $request)
  {
    if ($request != null) {
      $search_user_name = $request->get('employee_name');
    }

    if (in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [4])) {
      return redirect()->back();
    }

    $auth = User::all();

    if (in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [1, 2, 3, 5])) {
      $users = User::with('department', 'designation')->where('is_deleted', 0)->where('status', 1);
    } else {
      $users = User::with('department', 'designation')->where('is_deleted', 0)->where('status', 1);
    }

    $designation_value_mapping = [
      'Founder and MD' => 0,
      'Co-Founder and MD' => 1,
      'Director Operations' => 2,
      'Director Service Delivery' => 3,
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
    ];
    
    $designation_name = null;
    $designation_value = null;
    if (Auth::user()->designation) {
      $designation_name = Auth::user()->designation->name;
      $designation_value = isset($designation_value_mapping[$designation_name]) ? $designation_value_mapping[$designation_name] : null;
    }

    $view = 'team_members_chart.team_members_chart';
    $users = $users->orderBy('id', 'desc')->get();

    $designation_values = [];
    foreach ($users as $user) {
      if ($user->designation && $user->designation->name) {
        $designation_name = $user->designation->name;

        if (isset($designation_value_mapping[$designation_name])) {
          $user->designation_value = $designation_value_mapping[$designation_name];
        } else {
          $user->designation_value = null;
        }
        $user->designation_name = $designation_name;
      } else {
        $user->designation_value = null;
        $user->designation_name = null;
      }
      // Check if reporting_manager_id is not empty
      if (!empty($user->reporting_manager_id)) {
          // Assuming $user->reporting_manager_id is a comma-separated string
      $user->reporting_manager_id = explode(',', $user->reporting_manager_id);
      } else {
          // Set reporting_manager_id to an empty array if it's empty
          $user->reporting_manager_id = [];
      }
    }

    $all_users = $users;

    $filteredUsers = $users->filter(function ($user) {
      $designation_name = $user->designation->name ?? null;

      return in_array($designation_name, [
        'Founder and MD',
        'Co-Founder and MD',
      ]);
    });

    // Sort the filtered users by designation value
    $users = $filteredUsers->sortBy(function ($user) use ($designation_value_mapping) {
      $designation_name = $user->designation->name ?? null;
      return $designation_value_mapping[$designation_name] ?? PHP_INT_MAX;
    });

    $users = $users->mapWithKeys(function ($user, $index) {
      return [$user->id => $user];
    });

    $matching_users = $auth->where('first_name', $search_user_name);
    if ($matching_users->count() > 0) {
      $all_users = $matching_users->first();
      $reporting_manager_ids = explode(',', $all_users->reporting_manager_id);
      $reporting_managers = $auth->whereIn('id', $reporting_manager_ids);
      $all_users->reporting_manager_names = $reporting_managers->map(function ($manager) {
        return [
          'id' => $manager->id,
          'first_name' => $manager->first_name,
          'last_name' => $manager->last_name,
          'designation_id' => $manager->designation_id,
          'reporting_manager_id' => $manager->reporting_manager_id,
        ];
      });
      foreach ($reporting_managers as $reporting_manager) {
        $main_reporting_manager_id = explode(',', $reporting_manager->reporting_manager_id);
        $main_reporting_manager = $auth->whereIn('id', $main_reporting_manager_id);
        $all_users->reporting_manager_names = $all_users->reporting_manager_names->merge($main_reporting_manager->map(function ($manager) {
          return [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name,
            'designation_id' => $manager->designation_id,
            'reporting_manager_id' => $manager->reporting_manager_id,
          ];
        }));
      }
      $all_users->reporting_manager_names = $all_users->reporting_manager_names->unique('first_name');
      $idsToRemove = [38, 39];
      $all_users->reporting_manager_names = $all_users->reporting_manager_names->reject(function ($manager) use ($idsToRemove) {
        return in_array($manager['id'], $idsToRemove);
      });
    }
    return view($view, compact('users', 'all_users'));
  }
}
