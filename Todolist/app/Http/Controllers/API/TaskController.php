<?php

namespace App\Http\Controllers;


use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Task as TaskResource;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTodayTaskOngoing()
    {
        $id= Auth::id();
        $tasks= Task::where('user_id', $id)->where('the_day', '=', 1)->where('status', '=', 1)->get();
        return $this->sendResponse(TaskResource::collection($tasks), ' All Today Tasks');
        
    }

    public function showTaskCompleted()
    {
        $id= Auth::id();
        $tasks= Task::where('user_id', $id)->where('the_day', '=', 1)->where('status', '=', 0)->get();
        return $this->sendResponse(TaskResource::collection($tasks), ' All Today Tasks');
        
    }


    public function showTomorrowTask()
    {
        $id= Auth::id();
        $tasks= Task::where('user_id', $id)->where('the_day', '=', 0)->get();
        return $this->sendResponse(TaskResource::collection($tasks), ' All Tomorrow Tasks');
        
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTodayTask(Request $request)
    {
        $input=$request->all();
         $validator=Validator::make($input,[

            'content'=>'required'
        ]);

         if ($validator->fails()) {
            return $this->sendErorr('Validate Error',$validator->errors());
        }

        $user=Auth::user();
        $input['user_id']=$user->id;
        $task=Task::create($input);
        return $this->sendResponse($task, 'Task added successfully');
    }

    public function createTomorrowTask(Request $request)
    {
        $input=$request->all();
         $validator=Validator::make($input,[

            'content'=>'required'
        ]);

         if ($validator->fails()) {
            return $this->sendErorr('Validate Error',$validator->errors());
        }

        $user=Auth::user();
        $input['user_id']=$user->id;
        $input['the_day']=0;
        $input['status']=0;
        $task=Task::create($input);
        return $this->sendResponse($task, 'Task added successfully');
    }

    


    public function update(Request $request, Task $task)
    {
        $input=$request->all();
         $validator=Validator::make($request->all(),[
            'content'=>'required'
        ]);

       
         if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }

        if ($validator->fails()) {
            return $this->sendErorr('Validation error', $validator->errors());
        }

        $task->content= $input['content'];
        $task->save();
        return $this->sendResponse(new TaskResource($task), 'Task updated successfully');
    }


    public function transportTaskToTomorrow(Request $request, Task $task)
    {
        $input=$request->all();
    
        if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }

        if ($validator->fails()) {
            return $this->sendErorr('Validation error', $validator->errors());
        }

        $task->the_day= 0;
        $task->save();
        return $this->sendResponse(new TaskResource($task), 'Task updated successfully');
    }

    public function transportTaskToTomorrow(Request $request, Task $task)
    {
        $input=$request->all();
    
        if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }

        if ($validator->fails()) {
            return $this->sendErorr('Validation error', $validator->errors());
        }

        $task->the_day= 1;
        $task->save();
        return $this->sendResponse(new TaskResource($task), 'Task updated successfully');
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task=Task::find($id);
        if (is_null($task)) {
            return $this->sendErorr('Task not found');
        }

        if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }

        $task->delete();
        return $this->sendResponse(new TaskResource($task), 'Task deleted successfully');
    }
}
