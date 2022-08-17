<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreateController extends Controller
{
    public function __invoke()
    {
        Todo::query()->create([
            'title' => request()->title,
            'description' => request()->description,
            'assigned_to_id' => request()->assigned_to_id,
            'file' => $this->getUploadedFilePath()
        ]);

        return redirect()->route('todo.index');
    }

    public function getUploadedFilePath()
    {
        if(!request()->hasFile('file')) {
            return null;
        }
        return request()->file('file')
            ->storeAs('todo', request()->file('file')->getClientOriginalName(), ['disk' => 'public']);
    }
}
