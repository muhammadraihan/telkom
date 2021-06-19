<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemReplaceStockDetail extends Model
{
    use HasFactory;
    use Uuid;
    use LogsActivity;

    protected $fillable = [
        'item_repair_uuid', 'module_category_uuid', 'module_name_uuid', 'module_brand_uuid', 'module_type_uuid', 'part_number', 'serial_number', 'serial_number_msc', 'accesories', 'created_by', 'edited_by'
    ];

    protected $casts = [
        'accesories' => 'array'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'item_replace';

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
<<<<<<< HEAD:app/Models/Item_replace.php

    public function repairItem(){
        return $this->belongsTo(Repair_item::class, 'item_repair_uuid', 'uuid');
    }

    public function stock(){
        return $this->belongsTo(Stock_item::class, 'item_replace_detail_from_stock', 'uuid');
    }
=======
>>>>>>> origin:app/Models/ItemReplaceStockDetail.php
}
