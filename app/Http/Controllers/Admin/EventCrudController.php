<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Uploaders\SingleFile;
use Backpack\CRUD\app\Library\Widget;

class EventCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use UpdateOperation;

    public function setup(): void
    {
        CRUD::setModel(Event::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/event');
        CRUD::setEntityNameStrings('event', 'events');
    }

    protected function setupListOperation(): void
    {
        CRUD::with(['category', 'user']);

        Widget::add()->type('style')->content('css/admin/event-image-modal.css');
        Widget::add()->type('script')->content('js/admin/event-image-modal.js');

        CRUD::column('title')->label('Title');
        CRUD::addColumn([
            'label' => 'Image',
            'type' => 'event_image',
            'name' => 'image',
            'height' => '50px',
            'width' => '50px',
        ]);
        CRUD::addColumn([
            'label' => 'Category',
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => Category::class,
        ]);
        CRUD::addColumn([
            'label' => 'Owner',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
        ]);
        CRUD::column('start_date')->type('date')->label('Start Date');
        CRUD::column('city')->label('City');
        CRUD::column('price')->type('number')->label('Price')->decimals(2);
        CRUD::column('capacity')->type('number')->label('Capacity');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(CreateEventRequest::class);

        CRUD::field('title')
            ->type('text')
            ->label('Title')
            ->on('saving', function (Event $event) {
                $event->user_id = backpack_user()->id;
            });

        $this->addEventFieldsAfterTitle();
    }

    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(UpdateEventRequest::class);

        // These assets are used only by the image preview in the Edit form.
        Widget::add()->type('style')->content('css/admin/event-edit-image.css');
        Widget::add()->type('script')->content('js/admin/event-edit-image.js');

        CRUD::field('title')->type('text')->label('Title');

        $this->addEventFieldsAfterTitle(showImagePreview: true);
    }

    protected function setupDeleteOperation(): void
    {
        $this->addImageUploadField();
    }

    private function addEventFieldsAfterTitle(bool $showImagePreview = false): void
    {
        CRUD::addField([
            'name' => 'category_id',
            'label' => 'Category',
            'type' => 'select',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => Category::class,
        ]);

        CRUD::field('description')->type('textarea')->label('Description');

        $this->addImageUploadField($showImagePreview);

        CRUD::field('start_date')->type('date')->label('Start Date');
        CRUD::field('end_date')->type('date')->label('End Date');
        CRUD::field('start_time')->type('time')->label('Start Time');
        CRUD::field('end_time')->type('time')->label('End Time');
        CRUD::field('location')->type('text')->label('Location');
        CRUD::addField([
            'name' => 'city',
            'label' => 'City',
            'type' => 'select_from_array',
            'options' => City::query()->orderBy('name')->pluck('name', 'name')->all(),
        ]);
        CRUD::field('price')->type('number')->label('Price')->attributes(['step' => 1, 'min' => 0]);
        CRUD::field('capacity')->type('number')->label('Capacity')->attributes(['step' => 1, 'min' => 1]);
    }

    private function addImageUploadField(bool $showPreview = false): void
    {
        CRUD::field('image')
            ->type($showPreview ? 'event_image_upload' : 'upload')
            ->label('Event Image')
            ->withFiles([
                'disk' => 'public',
                'path' => 'events',
                // The custom Edit field still uses Backpack's normal file handler.
                'uploader' => SingleFile::class,
            ]);
    }
}
