<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends \Spatie\Permission\Models\Permission
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name',
    ];

    /**
     * The attibutes for logging the event change
     *
     * @var array
     */
    protected static $logAttributes = ['name', 'guard_name'];

    /**
     * Logging name
     *
     * @var string
     */
    protected static $logName = 'permission';

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

    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'view_permissions',
            'add_permissions',
            'edit_permissions',
            'delete_permissions',

            'view_logs',

            'view_witel',
            'add_witel',
            'edit_witel',
            'delete_witel',

            'view_unit',
            'add_unit',
            'edit_unit',
            'delete_unit',

            'view_accessory',
            'add_accessory',
            'edit_accessory',
            'delete_accessory',

            'view_category',
            'add_category',
            'edit_category',
            'delete_category',

            'view_name',
            'add_name',
            'edit_name',
            'delete_name',

            'view_brand',
            'add_brand',
            'edit_brand',
            'delete_brand',

            'view_type',
            'add_type',
            'edit_type',
            'delete_type',

            'view_material',
            'add_material',
            'edit_material',
            'delete_material',

            'view_stock',
            'add_stock',
            'edit_stock',
            'delete_stock',

            'view_ticketing',
            'add_ticketing',
            'edit_ticketing',
            'delete_ticketing',

            'view_repair',
            'add_repair',
            'edit_repair',
            'delete_repair',

            'view_repair-job',
            'add_repair-job',
            'edit_repair-job',
            'delete_repair-job',

            'view_warehouse',
            'add_warehouse',
            'edit_warehouse',
            'delete_warehouse',

        ];
    }
}
