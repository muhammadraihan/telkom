<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;

class RepairJobOrder extends Model
{
    use HasFactory;
    use Uuid;
    use LogsActivity;

    protected $fillable = [
        'repair_item_uuid', 'urgent_status', 'item_status', 'job_status', 'repair_notes', 'component_used', 'repair_cost', 'time_to_repair', 'assign_to', 'created_by', 'edited_by'
    ];

    protected $casts = [
        'component_used' => 'array'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'repair_job_order';

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

    public function userCreate()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function userEdit()
    {
        return $this->belongsTo(User::class, 'edited_by', 'uuid');
    }

    public function repair()
    {
        return $this->belongsTo(RepairItem::class, 'repair_item_uuid', 'uuid');
    }

    public function UserAssign()
    {
        return $this->belongsTo(User::class, 'assign_to', 'uuid');
    }
}
