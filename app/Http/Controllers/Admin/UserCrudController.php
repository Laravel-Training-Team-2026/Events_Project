<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Users are intentionally read-only in the admin panel. Deleting a user
     * could cascade to their events and related records, while editing account
     * fields belongs to the account/authentication flow.
     */
    protected function setupListOperation(): void
    {
        $this->crud->query->withCount(['events', 'bookings']);

        CRUD::column('name')->label('Name');
        CRUD::column('email')->label('Email');
        CRUD::column('role')->label('Role');
        CRUD::column('events_count')->label('Events Created')->type('number');
        CRUD::column('bookings_count')->label('Bookings')->type('number');
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
    }
}
