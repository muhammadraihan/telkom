<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Gudang_job_order extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'repair_item_uuid', 'item_status', 'keterangan', 'item_replace_uuid', 'job_status', 'created_by', 'edited_by'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'gudang_job_order';

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

    public function repairItem(){
        return $this->belongsTo(Repair_item::class, 'repair_item_uuid', 'uuid');
    }

    public function itemReplace(){
        return $this->belongsTo(Item_replace::class, 'item_replace_uuid', 'uuid');
    }
}
