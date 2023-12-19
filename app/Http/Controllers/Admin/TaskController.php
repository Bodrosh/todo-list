<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\StoreRequest;
use App\Http\Requests\Admin\Task\UpdateRequest;
use App\Http\Resources\Admin\TaskResource;
use App\Models\Task;
use Inertia\Inertia;
use App\Services\Task\Service;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        protected Service $service
    ){}

    public function index(): Response
    {
        $tasks = $this->service->getTasks();
        return Inertia::render('Admin/ToDo/Index', compact('tasks'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $task = $this->service->store($data);
        return TaskResource::make($task)->resolve();
    }

    public function toggle(int $id)
    {
        $userTask = Task::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        $userTask->status = $userTask->status ? 0 : 1;
        $userTask->save();
    }

    public function update(int $id, UpdateRequest $request)
    {
        $data = $request->validated();
        $update = Task::where('user_id', auth()->id())
            ->where('id', $id)
            ->update($data);

        return $update;
    }
    public function destroy(int $id)
    {
        Task::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }
}
