<?php

namespace App\Services\Task;

use App\Http\Resources\Admin\TaskListResource;
use App\Models\Task;

class Service
{
    public function getTasks(): array
    {
        $userTasks = Task::where('user_id', auth()->id())->latest()->get();
        return TaskListResource::collection($userTasks)->resolve();
    }

    public function store(array $data): Task
    {
        $data['user_id'] = auth()->id();
        return Task::create($data);
    }
}
