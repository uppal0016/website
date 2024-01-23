<?php

namespace App;

use Crypt;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Dsr extends Model
{
    protected $fillable = [
      'user_id', 'to_ids', 'cc_ids','status','dsr_rejection_reason', 'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'id', 'user_id', 'to_ids', 'cc_ids'
    // ];

    public function user()
    {   
        return $this->belongsTo('App\User', 'user_id'); 
    }

    public function details()
    {
        return $this->hasMany('App\DsrDetail', 'dsr_id');
    }

    public function files()
    {        
        
        return $this->hasMany('App\DsrFile', 'dsr_id');
    
    }

    public function notification(){

      return $this->belongsTo('App\Notification','activity_id');
    
    }

    public function read(){

      return $this->hasMany('App\DsrRead', 'dsr_id');
    
    }
    public function comment(){

      return $this->hasMany('App\DsrComment','dsr_id');
    
    }

    public function notificationread(){
     
      return $this->hasMany('App\NotificationRead','notification_id');
    
    }

    public static function toAndCCUsers($dsrs, $toArray = 0){

        if(!$dsrs) return $dsrs;

        if(is_a($dsrs, 'Illuminate\Database\Eloquent\Collection')){

            foreach ($dsrs as $dsr) {
                $dsr['to_users'] = User::whereIn('id', explode(',', $dsr['to_ids']))->get();
                $dsr['cc_users'] = User::whereIn('id', explode(',', $dsr['cc_ids']))->get();

                if($toArray){

                    $dsr['to_users'] = $dsr['to_users']->toArray();  
                    $dsr['cc_users'] = $dsrs['cc_users']->toArray();  
                }
            }
        }else{

            $dsrs['to_users'] = User::whereIn('id', explode(',', $dsrs['to_ids']))->get();
            $dsrs['cc_users'] = User::whereIn('id', explode(',', $dsrs['cc_ids']))->get();
            
            if($toArray){
                $dsrs['to_users'] = $dsrs['to_users']->toArray();  
                $dsrs['cc_users'] = $dsrs['cc_users']->toArray();  
            }
        }
        return $dsrs;
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
    protected static function saveDsrVd($data){
      
      $rules = [
        'project_id.0'   => 'required',
        // 'timeEstimate.0.0' => 'required',
        'des.0.0'          => 'required',
        'task.0.0'          => 'required',
        'send_to.0'       => 'required'
        // 'documents'    => 'max:2048|mimes:jpg,jpeg,png,doc,docx,pdf,xls,csv'
      ];

      $row = self::getRowCount($data);
      $subRowData = self::getSubRowCount($data, $row); 
      

      foreach ($subRowData as $pk => $subRow) {
        
        $rules['project_id.'.$subRow['parent']] = 'required';
        foreach ($subRow['keys'] as $k => $skey) { 

          // $rules['timeEstimate.'.$subRow['parent'].'.'.$skey] = $rules['des.'.$subRow['parent'].'.'.$skey] = $rules['task.'.$subRow['parent'].'.'.$skey] = 'required';
          $rules['des.'.$subRow['parent'].'.'.$skey] = $rules['task.'.$subRow['parent'].'.'.$skey] = 'required';
        }

      }

      if(isset($data['documents'])){

        foreach ($data['documents'] as $key => $value) {

          // $rules['documents.'.$key] = 'max:2048|mimes:jpg,jpeg,png,doc,docx,pdf,xls,xlsx,csv'; 
          // $rules['documents.'.$key] = 'max:2048|mimeTypes:image/jpeg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv'; 
          $rules['documents.'.$key] = 'max:2048|file'; 
        }
      }


      return $rules;
    }

    protected static function validateDsrDocs($docs){

      $exts = ['jpg','jpeg','png','doc','docx','pdf','xls','xlsx','csv'];
      foreach ($docs as $doc) {
        
        if(!in_array(strtolower($doc->getClientOriginalExtension()), $exts)) return false;        
      }

      return true;
    }

    protected static function saveDsrVdM($rules){

      $messages = [];

      foreach ($rules as $key => $rule) {
        
        $orgKey = explode('.', $key)[0];
        if($orgKey !== 'documents'){

          $messages[$key.".required"] = 'Please fill all the required fields.';
        }
        
        if($orgKey === 'documents'){
          
          $messages[$key.".max"] = 'The file size should not exceed 2MB.';
          // $messages[$key.".mimes"] = 'The file extension must be one of .jpg, .jpeg, .png, .doc, .docx, pdf, xls, xlsx or csv.';
          $messages[$key.".file"] = 'The attachment field must contain a file.';
        }
      }

      // $messages["documents.max"] = 'The file size should not exceed 2MB.';
      // $messages["documents.mimes"] = 'The file extension must be one of .jpg, .jpeg, .png, .doc, .docx or pdf.';
      return $messages;
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

      // if(isset($data['timeEstimate'])){
      //     $rowCount = ($tempCount=count($data['timeEstimate'])) > $rowCount ? $tempCount : $rowCount;
      //     $keys = array_merge($keys, array_keys($data['timeEstimate'])); 
      // }

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

        // if(isset($data['timeEstimate']) && isset($data['timeEstimate'][$pkey])){

        //   $tempKeys = array_merge($tempKeys, array_keys($data['timeEstimate'][$pkey]));
        // }

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
