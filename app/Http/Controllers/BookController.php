<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $book = Book::whereNotNull('id');

        if ($request->input("search")) {
            $search = $request->input("search");
            $book->where('title', 'LIKE', "%{$search}%");
        }
        return $book->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'title' => ['required', 'min:6', 'unique:books,title'],
            'author' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amount' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // check if file exist
        $path = "";
        if ($request->hasFile("cover_image")) {
            // Handle File upload
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('images'), $imageName);
            $path = "/images/" . $imageName;
        }



        // New  object
        $book = new Book;

        // Save to database
        $book->title = $request->title;
        $book->description = $request->description;
        $book->cover_image = $path;
        $book->amount = $request->amount;
        $book->save();

        return response()->json([
            "data" =>  $book,
            "message" => 'Book save succesfull'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $book = Book::find($id);
        return response()->json($book);
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

        return $request->all();

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'title' => ['required', 'min:6', 'unique:books,title'],
            'author' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amount' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $book = Book::find($id);

        // check if file exist
        if ($request->hasFile("cover_image")) {
            // Handle File upload
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('images'), $imageName);
            $book->cover_image = "/images/" . $imageName;
        }

        // Save to database
        $book->title = $request->title;
        $book->description = $request->description;
        $book->amount = $request->amount;
        $book->save();

        return response()->json([
            "data" =>  $book,
            "message" => 'Book updated succesfull'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return 'Book deleted succesfull';
    }
}
