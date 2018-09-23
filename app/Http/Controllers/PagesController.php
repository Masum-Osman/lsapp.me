<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $title = 'Welcome from title';
        return view('pages.index');
    }

    public function about(){
        return view('pages.about');
    }

    public function services(){
        $data = array(
            'title' => 'Services',
            'services' => ['web', 'programming', 'SEO']
        );
        return view('pages.services')->with($data);
    }

    public function contact(){
        return view('pages.contact');
    }
}
