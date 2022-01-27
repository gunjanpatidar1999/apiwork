<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\taskapi;
use App\Models\task;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get("/tasks",[taskapi::class,"index"]);
// Route::post("/addtask",[taskapi::class,"addtask"]);

Route::apiResource("tasks",taskapi::class);

// Route::get('/test',[taskapi::class,'test']);