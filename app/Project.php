<?php

namespace App;

use Crypt;
use App\DsrDetail;
use App\ProjectAssigned;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $fillable = [
      'name', 'status','start_date','end_date','client_name','address','physical_address','project_manager','team_lead','hours_approved_or_spent',
       'project_url','technology','dev_server_url','qa_server_url','git_or_svn','project_document_url','project_management_tool','project_video',
       'current_status', 'is_deleted'
   ];

  protected static function boot() {
    parent::boot();

    static::deleting(function($project) {
      
      $assigned = ProjectAssigned::whereRaw('FIND_IN_SET('.$project->id.', project_id)')->get();
      foreach ($assigned as $value) {
        
        $projectIds = explode(',', $value['project_id']);
        $k = array_search($project->id, $projectIds);
        if($k === 0 || $k != null){

          unset($projectIds[$k]);
          ProjectAssigned::where('id', $value['id'])->update([
            'project_id' => implode(',', $projectIds)
          ]);
        }
      }
    });
  }

  public function project_assigned()
  {
      
      return $this->hasMany('App\ProjectAssigned','project_id');

  }

  
  public function details()
  {
      
    return $this->hasMany('App\DsrDetail', 'project_id');

  }

  public function getEnIdAttribute()
  {
      return Crypt::encrypt($this->attributes['id']);
  }

}
