<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Uploaders\SingleFile;
use Backpack\CRUD\app\Library\Widget;

class CategoryCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use UpdateOperation;

    public function setup(): void
    {
        CRUD::setModel(Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    protected function setupListOperation(): void
    {
        $this->crud->query->withCount('events');

        // Reuse the existing Events List lightbox for category thumbnails.
        Widget::add()->type('style')->content('css/admin/event-image-modal.css');
        Widget::add()->type('script')->content('js/admin/event-image-modal.js');

        CRUD::addColumn([
            'label' => 'Image',
            'type' => 'category_image',
            'name' => 'image',
            'height' => '50px',
            'width' => '50px',
        ]);
        CRUD::column('name')->label('Name');
        CRUD::column('events_count')->label('Events')->type('number');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(CategoryRequest::class);
        $this->addCategoryFields();
    }

    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(CategoryRequest::class);

        Widget::add()->type('style')->content('css/admin/category-edit-image.css');
        Widget::add()->type('script')->content('js/admin/category-edit-image.js');

        $this->addCategoryFields(showImagePreview: true);
    }

    protected function setupDeleteOperation(): void
    {
        $this->addImageUploadField();
    }

    /**
     * Keep events safe: the current database foreign key cascades category
     * deletion to events, so categories with events must not be deleted.
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $category = Category::findOrFail($id);

        if ($category->events()->exists()) {
            return response()->json([
                'error' => ['This category cannot be deleted because it still has events.'],
            ]);
        }

        return $this->crud->delete($id);
    }

    private function addCategoryFields(bool $showImagePreview = false): void
    {
        CRUD::field('name')->type('text')->label('Name');

        $this->addImageUploadField($showImagePreview);
    }

    private function addImageUploadField(bool $showPreview = false): void
    {
        CRUD::field('image')
            ->type($showPreview ? 'category_image_upload' : 'upload')
            ->label('Image')
            ->withFiles([
                'disk' => 'public',
                'path' => 'categories',
                'uploader' => SingleFile::class,
            ]);
    }
}
