<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon as Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\DB as DB ;
use App\Http\Resources\Task as TaskResource;

class TaskController extends BaseController
{
    // show all tasks which are ongoing & in Today list 

    public function showTodayTaskOngoing()
    {
        $id= Auth::id();
        $tasks= Task::where('user_id', $id)->get();
        $date1=Carbon::now()->toDateString();

        if (count((array)$tasks) > 0){
            foreach ($tasks as $task) {
            if (Carbon::parse($task->task_dat)->lt($date1)) {
            $task->the_day=1;
            $task->save();
           } }
        
        }

        $id= Auth::id();
        $tasks= Task::where('user_id', $id)
        ->where('the_day', 1)
        ->where('status',1)->get();
        if (count($tasks) > 0) {

            return $this->sendResponse(TaskResource::collection($tasks), ' All Today Tasks');
        }
        else {
            return $this->sendErorr('Today Tasks list is empty');
        }    
        
    }

    // show all tasks which are completed & in Today list

    public function showTaskCompleted()
    {
        $id= Auth::id();
        $tasks= Task::where('user_id', $id)
        ->where('status',0)
        ->get();
        $date1=Carbon::now()->toDateString();

        if (count((array)$tasks) > 0){
            foreach ($tasks as $task) {
            if (Carbon::parse($task->task_dat)->lt($date1)) {
            $task->delete();
           } }
        
        }

        $id= Auth::id();
        $tasks= Task::where('user_id', $id)
        ->where('status',0)->get();
        if (count($tasks) > 0) {

            return $this->sendResponse(TaskResource::collection($tasks), ' All Today completed Tasks');
        }
        else {
            return $this->sendErorr(' you did not complete any Task yet');
        }              
    }

    // show all tasks which are in Tomorrow list

    public function showTomorrowTask()
    {

        $id= Auth::id();
        $tasks= Task::where('user_id', $id)->get();
        $date1=Carbon::now()->toDateString();

        if (count((array)$tasks) > 0){
            foreach ($tasks as $task) {
            if (Carbon::parse($task->task_dat)->lt($date1)) {
            $task->the_day=1;
            $task->save();
           } }
        
        }

        $id= Auth::id();
        $tasks= Task::where('user_id', $id)
        ->where('the_day',0)
        ->get();

        if (count($tasks) > 0) {         

            return $this->sendResponse(TaskResource::collection($tasks), ' All Tomorrow Tasks');
        }
        else {
            return $this->sendErorr('Tomorrow Tasks list is empty');
        }
        
    }

    // create a new Task in Today list

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
        $input['task_dat']=Carbon::now()->toDateString();
        $task=Task::create($input);
        return $this->sendResponse(new TaskResource($task), 'Task added successfully');
    }

    // create a new Task in Tomorrow list 

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
        $input['task_dat']=Carbon::now()->toDateString();
        $task=Task::create($input);
        return $this->sendResponse(new TaskResource($task), 'Task added successfully');
    }

    
    // Edit your task in any list

    public function update(Request $request,  $id)
    {
        $task=Task::find($id);
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

    // transport Task 

    public function transportTask($id)
    {
        $task=Task::find($id);
    
        if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }
        if ($task->the_day == 1 and $task->status == 1 ) {
            $task->the_day = 0;
            $task->save();
         }
         else{
            $task->the_day= 1;
            $task->save();
         }
        
        $task->save();
        return $this->sendResponse(new TaskResource($task), ' Task tarnsported successfully');
    }


     // make any task completed or ongoing 

    public function makeTaskCompletedOrOngoing($id)
    {
         $task=Task::find($id);
    
        if ( $task->user_id != Auth::id()) {
            return $this->sendErorr('You do not have rights');
        }
        if ($task->status == 1 and $task->the_day=1 ) {
            $task->status = 0;
            $task->save();
         }
         else{
            $task->status= 1;
            $task->save();
         }
        
        $task->save();
        return $this->sendResponse(new TaskResource($task), ' completed Task');
    }

   // delete any task in any list 

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



   

    



