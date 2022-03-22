<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Administrativo|Prestatario']);
    }

    public function index()
    {
        return view('loanBooks');
    }

    public function indexReturnBook()
    {
        return view('returnBooks');
    }

    public function listAvailableBooks()
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
        ->where('status', 1)
        ->latest()
        ->get();

        return [
            'books' => $books,
        ];
    }

    public function listBorrowedBooks()
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
        ->whereHas('booksUsers', function($query){
            return $query->where('user_id', Auth::user()->id);
        })
        ->latest()
        ->get();

        return [
            'books' => $books,
        ];
    }


    public function loanBook($id)
    {
        $book = Book::findOrFail($id);
        $book->status = 2;
        $book->save();
        $book->booksUsers()->attach(Auth::user()->id);
        return response()->json([
            'statusCode' =>200,
            'message' =>"Se te ha prestado el libro. ¡Disfrútalo!",
        ]);


    }

    public function returnBook($id)
    {
        $book = Book::findOrFail($id);
        $book->status = 1;
        $book->save();
        $book->booksUsers()->detach(Auth::user()->id);
        return response()->json([
            'statusCode' =>200,
            'message' =>"Gracias por devolver el libro. ¡Préstalo cuando quieras!",
        ]);
    }
}
