<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Item_replace_vendor_detail extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'vendor_name', 'item_model', 'item_merk', 'item_type', 'part_number', 'serial_number', 'barcode', 'kelengkapan', 'created_by', 'edited_by'
    ];

    protected $casts = [
        'kelengkapan' => 'array'
    ];

    protected static $logAttributes = ['*'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'item_replace_vendor_detail';

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
}
