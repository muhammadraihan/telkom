<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Ticketing extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'uuid_pelanggan', 'ticket_number', 'keterangan', 'ticket_status', 'job_status', 'created_by', 'edited_by'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'ticketing';

    /**
     * Logging only the changed attributes
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * Prevent save logs items that have no changed attribute
     *
     * @var boolean
     */
    protected static $submitEmptyLogs = false;

    /**
     * Custom logging description
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Data has been {$eventName}";
    }

    public function userCreate(){
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function userEdit(){
        return $this->belongsTo(User::class, 'edited_by', 'uuid');
    }

    public function customer(){
        return $this->belongsTo(Customer::class 'uuid_pelanggan', 'uuid');
    }
}
