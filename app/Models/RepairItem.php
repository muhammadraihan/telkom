<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;

class RepairItem extends Model
{
    use HasFactory;
    use Uuid;
    use LogsActivity;

    protected $fillable = [
        'ticket_uuid', 'module_category_uuid', 'module_name_uuid', 'module_brand_uuid', 'module_type_uuid', 'part_number', 'serial_number', 'serial_number_msc', 'accessories', 'complain', 'warranty_status', 'repair_status', 'replace_status', 'created_by', 'edited_by'
    ];

    protected $casts = [
        'accessories' => 'array'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'repair_item';

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

    public function ticket()
    {
        return $this->belongsTo(Ticketing::class, 'ticket_uuid', 'uuid');
    }

    public function ModuleCategory()
    {
        return $this->belongsTo(ModuleCategory::class, 'module_category_uuid', 'uuid');
    }

    public function ModuleName()
    {
        return $this->belongsTo(ModuleName::class, 'module_name_uuid', 'uuid');
    }

    public function ModuleBrand()
    {
        return $this->belongsTo(ModuleBrand::class, 'module_brand_uuid', 'uuid');
    }

    public function ModuleType()
    {
        return $this->belongsTo(ModuleType::class, 'module_type_uuid', 'uuid');
    }
}
