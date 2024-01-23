<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'id';
    public $fillable = [
        'name',
        'code',
        'status'
    ];

    /*-------- Validations ---------*/
    protected static function saveDepartmentVd(){
        return [
            'name' => 'required|unique:departments|regex:/^[a-zA-Z ]+$/',
            'code' => 'required|unique:departments',
            'status' => 'required',
        ];
    }

    protected function updateDepartmentVd(){
        return [
            'name' => 'required|regex:/^[a-zA-Z ]+$/|unique:departments,name,'.$this->id,
            'code' => 'required'
        ];
    }
}
