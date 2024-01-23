<?php

namespace App;

use Crypt;
use App\User;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    protected $fillable = [
       'user_id', 
       'to_ids', 
       'cc_ids', 
       'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

     

    public function user()
    {   
        return $this->belongsTo('App\User', 'user_id'); 
    }

    public function details()
    {
        return $this->hasMany('App\WeeklyReportDetail', 'report_id');
    }

    public function notification(){

      return $this->belongsTo('App\Notification','activity_id');
    
    }

    public function read(){

      return $this->hasMany('App\WeeklyReportRead', 'report_id');
    
    }

    public function notificationread(){
     
      return $this->hasMany('App\NotificationRead','notification_id');
    
    }

    public static function toAndCCUsers($reports, $toArray = 0){

        if(!$reports) return $reports;

        if(is_a($reports, 'Illuminate\Database\Eloquent\Collection')){

            foreach ($reports as $report) {
                $report['to_users'] = User::whereIn('id', explode(',', $report['to_ids']))->get();
                $report['cc_users'] = User::whereIn('id', explode(',', $report['cc_ids']))->get();

                if($toArray){

                    $report['to_users'] = $report['to_users']->toArray();  
                    $report['cc_users'] = $reports['cc_users']->toArray();  
                }
            }
        }else{

            $reports['to_users'] = User::whereIn('id', explode(',', $reports['to_ids']))->get();
            $reports['cc_users'] = User::whereIn('id', explode(',', $reports['cc_ids']))->get();
            
            if($toArray){
                $reports['to_users'] = $reports['to_users']->toArray();  
                $reports['cc_users'] = $reports['cc_users']->toArray();  
            }
        }
        return $reports;
    }


    public function ccUsers(){
        return User::whereIn('id', explode($this->attributes['cc_ids']))->get();
    }

     /**
     * Set encrypted ids.
     *
     * @param  string  $value
     * @return string
     */
    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }


    /*-------- Validations ---------*/
    protected static function saveReportVd($data){
      
      $rules = [
        'project_id.0'   => 'required',
        'des.0.0'          => 'required',
        'send_to.0'       => 'required'
      ];

      $row = self::getRowCount($data);
      $subRowData = self::getSubRowCount($data, $row); 
      

      foreach ($subRowData as $pk => $subRow) { 
        $rules['project_id.'.$subRow['parent']] = 'required';
      }

      return $rules;
    }

    /*-------- Validations ---------*/

    
    /**/
    public static function getRowCount($data){
      
      $resp = [
        'keys' => [],
        'count' => 0
      ];

      $rowCount = 0;
      $keys = [];

      /*--- Logic for calculating how many rows user had entered ---*/
      if(isset($data['project_id'])){
          $rowCount = ($tempCount=count($data['project_id'])) > $rowCount ? $tempCount : $rowCount;
          $keys = array_merge($keys, array_keys($data['project_id'])); 
      }

      if(isset($data['des'])){
          $rowCount = ($tempCount=count($data['des'])) > $rowCount ? $tempCount : $rowCount;
          $keys = array_merge($keys, array_keys($data['des'])); 
      }        
      /*---end . Logic for calculating how many rows user had entered ---*/
      $keys = array_unique($keys);
      sort($keys);

      $resp = [
        'count' => $rowCount,
        'keys' => $keys        
      ];

      return $resp;
    }

    
    /**/
    public static function getSubRowCount($data, $row){
      
      $subKeys = [];
      $tempCount = 0;
      
      foreach ($row['keys'] as $k => $pkey) {
        
        $tempKeys = [];
        
        if(isset($data['des']) && isset($data['des'][$pkey])){

          $tempKeys = array_keys($data['des'][$pkey]);
        }

        $tempKeys = array_unique($tempKeys);
        sort($tempKeys);

        $subKeys[] = [
          'keys' => $tempKeys,
          'parent' => $pkey,
          'count' => count($tempKeys) 
        ]; 
      }

      return $subKeys;
    }


}
