<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //by Default
        //$posts = Post::all();

        //the best one
        //$posts = Post::orderBy('title','asc')->get();

        //through DataBase
        //$posts = DB::select('SELECT * FROM posts');

        //pagination
        $posts = Post::orderBy('created_at','desc')->paginate(3);
        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        //Handle file Upload
        if($request->hasFile('cover_image')) {

            //Get filename with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get Just filename
            $fileNemeOnly = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just Ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //File Name to store
            $fileNameToStore = $fileNemeOnly.'_'.time().'.'.$extension;

            //Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);

        } else {
            $fileNameToStore = 'noImage.jpg';
        }

        //crate post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        //check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect ('/posts')->with('error', 'Unauthorized access or trying');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        if($request->hasFile('cover_image')) {

            //Get filename with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get Just filename
            $fileNemeOnly = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just Ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //File Name to store
            $fileNameToStore = $fileNemeOnly.'_'.time().'.'.$extension;

            //Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);

        } 
        //crate post code.
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Your Post Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        //check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect ('/posts')->with('error', 'Unauthorized access or trying');
        }

        if($post->cover_image != 'noImage.jpg') {
            //Delete Image
            Storage::delete('public/cover_image/'.$post->cover_image);
        }

        $post->delete();
        return redirect('/posts') -> with('success', 'Success Deleted Post');
    }
}
