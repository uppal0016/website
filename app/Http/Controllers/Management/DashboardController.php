<?php

namespace App\Http\Controllers\Management;

use Auth;
use App\Dsr;
use App\User;
use App\Project;
use App\DsrDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   Display dashboard view with Total project and dsrs recived counts 
    * @params          :   
    * @return          :   response as []
    */
    public function index()
    {
        $auth = Auth::user();
        $projectsCount = 0;

        $totalDsrs = Dsr::whereRaw("FIND_IN_SET(".$auth->id.", to_ids)")
                        ->orWhereRaw("FIND_IN_SET(".$auth->id.", cc_ids)")->count();

        $totalProjects =  Project::Where([
            ['status','!=',1],
            ['is_deleted',0]
        ])->count();

        $totalUsers = User::whereIn('role_id', [3,4])->where('is_deleted',0)->count();

        $dashboardCounts = [
            "total_projects" => $totalProjects,
            "dsrs_received" => $totalDsrs,
            "total_users" => $totalUsers
        ];

        return view('dashboard.index', compact('dashboardCounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
