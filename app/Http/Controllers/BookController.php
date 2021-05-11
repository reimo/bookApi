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
    public function index()
    {
        $books = Book::all();
        return $books;
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
            'title' => ['required', 'min:6'],
            'author' => 'required',
            //'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // New subadmin object
        $book = new Book;

        /*
        $path = Storage::disk('public')->putFile('cover',$request->cover_image('file'));
        $image_path = $path;
*/
        // Save to database
        $book->title = $request->title;
        $book->description = $request->description;
        //$book->cover_image = $image_path;
        $book->amount = $request->amount;
        $book->save();


        return response()->json([
            "data" => $book,
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

        $book = Book::find($id);

        // Save to database
        $book->title = $request->title;
        $book->description = $request->description;
        //$book->cover_image = $image_path;
        $book->amount = $request->amount;
        $book->save();
        return 'Book updated succesfull';
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

    public function search(Request $request)
    {
        $search_query = $request->search_query;
        $data = Book::where('title', 'LIKE', "%$search_query%")
            ->take(5)
            ->get();
        return response()->json($data);
    }
}
