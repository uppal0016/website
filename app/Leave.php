<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $table = 'leave';

    const STATUS_PENDING = 0;

    const STATUS_ACCEPTED = 1;

    const STATE_REJECTED = 2;

    const STATUS_CANCEL = 3;

    public $fillable = [
        'user_id',
        'title',
        'description',
        'attachment',
        'attachment_type',
        'attachment_size',
        'type',
        'date',
        'status',
        'leave_time'
    ];

    
    public function getStatus()
	{
		$list = self::getStatusOption();
		return isset($list[$this->status]) ? $list[$this->status] : 'Not Defined';
	}

	public static function getStatusOption()
	{
		return  [
			self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED      => 'Accepted', 
            self::STATE_REJECTED  => 'Rejected', 
            self::STATUS_CANCEL => 'Cancelled',
			
		];
	}

    public function getTypeIdToValue($value)
    {
        $type = [
            'full_day' => 'Full Day',
            'half_day' => 'Half Day',
            'short_leave' => 'Short Leave',
        ];
        if(isset($value)){
            return $type[$value];
        }
    }
}