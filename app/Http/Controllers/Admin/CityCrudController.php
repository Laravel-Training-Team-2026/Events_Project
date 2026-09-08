<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Event;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

class CityCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use UpdateOperation;

    public function setup(): void
    {
        CRUD::setModel(City::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/city');
        CRUD::setEntityNameStrings('city', 'cities');
    }

    protected function setupListOperation(): void
    {
        $this->crud->query->withCount('events');

        CRUD::column('name')->label('Name');
        CRUD::column('events_count')->label('Events')->type('number');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(CityRequest::class);
        CRUD::field('name')->label('Name');
    }

    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(CityRequest::class);
        CRUD::field('name')->label('Name');
    }

    /**
     * Keep existing events aligned with a renamed city without changing their
     * ownership or any event/booking behavior.
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $id = $request->input($this->crud->model->getKeyName());
        $city = City::findOrFail($id);
        $previousName = $city->name;

        DB::transaction(function () use ($city, $previousName, $request) {
            $this->crud->update($city->getKey(), $this->crud->getStrippedSaveRequest($request));

            if ($previousName !== $city->fresh()->name) {
                Event::where('city', $previousName)->update([
                    'city' => $city->fresh()->name,
                ]);
            }
        });

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($city->getKey());
    }

    /**
     * Events store the city name, so deletion is blocked while that name is in
     * use. This preserves all linked event, favorite, interest and booking data.
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $city = City::findOrFail($id);

        if ($city->events()->exists()) {
            return response()->json([
                'error' => ['This city cannot be deleted because it still has events.'],
            ]);
        }

        return $this->crud->delete($id);
    }
}
