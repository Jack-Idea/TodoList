<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormRequest;

class TodoController extends Controller
{
    public function index() {
    	
		if (!Auth::check()) {
            return redirect('login');
        }

    	return view('welcome');
    }

    public function apiShow() {
    	
		return [
            'data' => Todo::all()
        ];
    }




    public function submit(Request $request) {
    	
    	Todo::create([
            'title' => request('title'),
            'flag' => request('flag')
        ]);

    }

    public function editTitle(Request $request, $id) {
    	
    	$contact = Todo::find($id);

    	$contact->title = request('title');

    	$contact->save();

    }

    public function editFlag($id, Request $req) {
    	
    	$contact = Todo::find($id);

    	$contact->flag = $req->input('flag');

    	$contact->save();

    }

    public function removeCard($id) {

        Todo::destroy($id);

        return response()->json(null, 204);

    }

}
