<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CommonControllerFunctions
{
    public function commonIndex($view, $items)
    {
            return view($view, $items);
    }

    public function commonCreate($model, $view,$items = [])
    {
        $this->authorize("create", $model);
        return view($view,$items);
    }

    public function commonStore($validated, $model, $service, $route, $name,$items=null)
    {
        $this->authorize("create", $model);
        $item = $service->create($validated,$items);
        if (!$item)
            return redirect()->back()->with("error", "there is a problem");

        return redirect()->route($route)->with("success", "New" . $name . "added successfully");
    }

    public function commonShow(string $id, $service, $view, $name)
    {
        $item = $service->getById($id);
        if (!$item)
            abort(404);
        return view($view, [$name => $item]);
    }

    public function commonEdit($item, $view, $items=[])
    {
        if (!$item)
            abort(404);
        $this->authorize("update", $item);
        return view($view, $items);
    }

    public function commonUpdate($validated, $item, $service, $route, $name,$updatedRelatedItems=null)
    {
        if (!$item)
            abort(404);
        $this->authorize("update", $item);
        $service->update($item, $validated,$updatedRelatedItems);
        return redirect()->route($route)->with("success", $name . " updated successfully");
    }

    public function commonDestroy(string $id, $service, $name)
    {
        $item = $service->getById($id);
        if (!$item)
            abort(404);
        $this->authorize("delete", $item);
        $service->delete($item);
        return redirect()->back()->with("success", $name . " deleted successfully");
    }
}
