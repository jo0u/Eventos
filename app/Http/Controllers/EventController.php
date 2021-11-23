<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index(){
        $search = request('search');

        if($search):
          
            $events = Event::where([
                ['title' , 'like','%'.$search.'%']
            ])->get();
        else:
            $events = Event::all();
        endif;
        
        

        return view('welcome',['events' => $events, 'search' => $search]);
    }

    public function create(){
        return view('events.create');
    
    }
    
    public function store(Request $request){

        $event = new Event;
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        //Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()){
            
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imagemName = md5($requestImage->getClientOriginalName() . strtotime("now")).".".$extension;

            $requestImage->move(public_path('img/events'),$imagemName);

            $event->image = $imagemName;

        }

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg','Evento criado com sucesso!');

    }

    public function show($id){
        $event = Event::findOrFail($id);


        $user = auth()->user();
        $hasUserJoined = false;


        if($user){
            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvent){

                if($userEvent['id'] == $id):
                    $hasUserJoined = true;

                endif;


            }



        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();



        return view('events.show',['event' => $event, 'eventOwner' => $eventOwner, $eventOwner, 'hasUserJoined' => $hasUserJoined]);
        

    }

    public function dashboard(){
        $user = auth()->user();

        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);
    }

    public function destroy($id){
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg','Evento excluído com sucesso!');
    }





    public function edit($id){

        $user = auth()->user();
        $events = Event::findOrFail($id);

        if($user->id != $events->user_id):
            return view('events.dashboard');
        endif;

        return view('events.edit',['events' => $events]);
    }











    public function update(Request $request){

        $data = $request->all();

         //Image Upload
         if($request->hasFile('image') && $request->file('image')->isValid()){
            
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imagemName = md5($requestImage->getClientOriginalName() . strtotime("now")).".".$extension;

            $requestImage->move(public_path('img/events'),$imagemName);

            $data['image'] = $imagemName;

        }





        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg','Evento editado com sucesso!');
    }


    public function joinEvent($id){
        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);
        return redirect('/dashboard')->with('msg','Sua presença está confirmada no event' . $event->title);
    }

    public function leaveEvent($id){

        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);
    
        return redirect('/dashboard')->with('msg','Sua presença foi removida no evento ' . $event->title);
    
    }

}
