<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBookPost;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Administrativo']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('book');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookPost $request)
    {
        $book = new Book;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publication_date = $request->publication_date;
        $book->status = 1;
        $book->save();
        return response()->json([
            'statusCode' =>200,
            'message' =>"Datos guardados correctamente",
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBookPost $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publication_date = $request->publication_date;
        $book->save();
        return response()->json([
            'statusCode' =>200,
            'message' =>"Datos modificados correctamente",
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
        return response()->json([
            'statusCode' =>200,
            'message' =>"Registro eliminado correctamente",
        ]);
    }

    public function listBooks()
    {
        $books = Book::select([
            'id',
            'title',
            'author',
            'publication_date',
            'status',
            DB::raw('(CASE WHEN status = 0 THEN "Inactivo" WHEN status = 1 THEN "Disponible" WHEN status = 2 THEN "Prestado" ELSE "Inactivo" END) AS estatus'),
            'created_at'
        ])
        ->with('booksUsers')
        ->latest()
        ->get();

        return [
            'books' => $books,
        ];
    }
}
