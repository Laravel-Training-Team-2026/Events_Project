<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class BookingCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Booking::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/booking');
        CRUD::setEntityNameStrings('booking', 'bookings');
    }

    /**
     * Booking history is read-only here. Changing status outside Event's
     * booking/cancellation methods could make capacity availability incorrect.
     */
    protected function setupListOperation(): void
    {
        CRUD::with(['user', 'event']);

        CRUD::column('id')->label('ID');
        CRUD::addColumn([
            'label' => 'User',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
        ]);
        CRUD::addColumn([
            'label' => 'Event',
            'type' => 'select',
            'name' => 'event_id',
            'entity' => 'event',
            'attribute' => 'title',
            'model' => Event::class,
        ]);
        CRUD::column('status')->label('Status');
        CRUD::column('quantity')->label('Quantity')->type('number');
        CRUD::column('unit_price')->label('Unit Price')->type('number')->decimals(2);
        CRUD::column('total_price')->label('Total Price')->type('number')->decimals(2);
        CRUD::column('booked_at')->label('Booked At')->type('datetime');
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
    }
}
