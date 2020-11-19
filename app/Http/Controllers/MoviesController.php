<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\Movie;
use App\Models\Thumbs_ups;
use App\Models\User;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get data from DB
        $thumbs = Thumbs_ups::all();
        $movies = Movie::all();
        $user = [];

        // count thumbs up for each movie
        foreach($thumbs as $thumb) {
            foreach($movies as $key => $movie){
                $thumb->movie_id === $movie->id ? $movies[$key]->thumbs++ : '';
            }
        }

        //check if user is logged in
        if(Auth::check()){
            $user = Auth::user()->role;
            $user_id = Auth::user()->id;
        }

        // render view
        return Inertia::render('Movies', [
            'movies'       => $movies,
            'user'         => [
                'user_id'  => $user_id,
                'user_role'=> $user 
            ],
            'sort'         => '',
            'thumbs_error' => ''
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check if user is allowed to add a movie
        $role = Auth::user()->role;
        switch ($role) {
            case 'admin':
              return Inertia::render('Insert');
              break;
        
            default:
              return redirect()->route('movies.index'); 
            break;
        }
    }
    
    /**
     * Add thumbs up for a movie
     *
     * @return \Illuminate\Http\Response
     */
    public function thumbs(Request $request)
    {
        // extract ids
        $ids = $request->all();

        // check if user voted for that movie
        $hasUserVoted = Thumbs_ups::where([
            ['user_id', '=', $ids['user_id']],
            ['movie_id', '=', $ids['movie_id']]
            ])->get();


        // add vote in case user hasn't voted
        if($hasUserVoted->isEmpty()){
            Thumbs_ups::create([
                'user_id'  => $ids['user_id'],
                'movie_id' => $ids['movie_id']
            ]);
        }

        return $hasUserVoted ?
        ['status' => 'failed', 'message' => "One vote is allowed per movie"]:
        ['status' => 'success'];
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        // type cast into a float 
        $data['rating'] = (float)$data['rating'];

        $request->validate([
            'name'   => 'required | string | max:255',
            // 'image'  => 'required | file | image | size:1024',
            'url'    => 'required | string',
            'rating' => 'required | numeric',
        ]);

        Movie::create([
            'name' => $request->name,
            'url' => $request->url,
            'rating' => $request->rating,
        ]);

        // store image in public folder
        // $newPath = $request->photo->store('images', $request->photo->name);


        return redirect()->route('/admin')->with('successMessage', 'Movie was successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movie = Movie::find($id); 
        return Inertia::render('Insert', [
            'movie' => $movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::user()->role === 'admin'){
            $request->validate([
                'name'   => 'required | string | max:255',
                // 'image'  => 'required | file',
                'url'    => 'required | string',
                'rating' => 'required | numeric | max:10 | min:0',
            ]);
    
            Movie::where('id', '=',$request['id'])->update([
                'name'   => $request['name'],
                // 'image'  => $request['image'],
                'url'    => $request['url'],
                'rating' => $request['rating'],
            ]);
            $status =  ['status' => 'success', 'message' => "Movie had been updated"];
        } else {
            $status =  ['status' => 'failed', 'message' => "User not alloew to edit"];
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
