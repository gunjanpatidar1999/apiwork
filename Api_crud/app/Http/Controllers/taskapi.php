<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\task;
use Dotenv\Parser\Value;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class taskapi extends Controller
{
    
    public function index()
    {
        // $data = task::all();
         $data = task::latest()->get();
        //   $data = ["msg"=>"This is test mesg"];
        // return response()->json($data);
        return response(['tasks'=>TaskResource::collection($data)]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'name'=>'required|string|max:255',
            'description'=>'required',
        ]);
        if($validator->fails())
        {
            // return response()->json($validator->errors());
            return response(['error'=>$validator->errors(),"Validation Error"]);
        }
        else
        {
            $task = new task;
            $task->name = $req->name;
            $task->description = $req->description;
            if($task->save())
            {
                // return response()->json(['msg'=>'Task Added']);
                return response(['tasks'=> new TaskResource($task),'message'=>'Create Successfully']);
            }
            else
            {
                return response(['msg'=>'Task Not Added']);
            }
        }
    }

    public function show($id)
    {
        $data = task::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = task::find($id);
        $data->delete();
        return response()->json(['msg'=>'data deleted']);

    }

    public function update(Request $req, task $task)
    {

        $data = $req->all();
        $validator = Validator::make($data,[
           'name'=>'required|string|max:255',
           'description'=>'required',
        ]);

        if($validator->fails())
        {
            return response(['error'=>$validator->errors(),"validation error"]);
        }
        else
        {
            $task->name = $req->name;
            $task->description = $req->description;

            if($task->save())
            {
                return response()->json(['msg'=>'Updated successfully']);
            }
            else
            {
                return response(['msg'=>' Not Updated']);
            }
        }
    }

    
}
