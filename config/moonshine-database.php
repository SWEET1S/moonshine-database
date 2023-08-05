<?php

return [

    /*
     * This parameter indicates which icon will be displayed in the menu.
     * You can use icons from either Heroicons or Moonshine Icons libraries.
     */

    'icon' => 'heroicons.outline.circle-stack',

    /*
     * This parameter is responsible for the link.
     */

    'slug' => 'database',

    /*
     * This block is responsible for authorizing methods in the package's controller.
     *
     * The "enable" parameter determines the functionality of the authorization.
     *
     * The "permissions" parameter indicates which permissions the package is currently utilizing.
     * Permissions cannot be deleted; their values can only be edited.
     */

    'auth' => [
        'enable' => false,
        'permissions' => [
            'viewAny' => 'Database.viewAny',
            'view' => 'Database.view',
            'create' => 'Database.create',
            'update' => 'Database.update',
            'delete' => 'Database.delete'
        ]
    ]
];
