<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller {
    public function list() {
        return [
            'status' => true,
            'result' => Book::orderBy('name')->with('author')->get()
        ];
    }

    public function store(Request $request) {
        $response = [
            'status' => false,
            'message' => 'Unexpected error while storing data',
            'result' => null,
            'authors' => []
        ];
        $book = null;
        if (isset($request->id)) {
            $request->validate([
                'name' => 'required|string|max:100',
                'isbn' => 'required|string|max:11',
                'author_id' => 'required|exists:authors,id'
            ]);

            if (Book::whereId($request->id)->update($request->all())) {
                $book = Book::whereId($request->id)->with('author')->first();
            }
        } else {
            $request->validate([
                'name' => 'required|string|max:100',
                'isbn' => 'required|string|max:11|unique:books,isbn',
                'author_id' => 'required|exists:authors,id'
            ]);

            $book = Book::create($request->all());
            $book = Book::whereId($book->id)->with('author')->first();
        }

        if ($book) {
            $response['result'] = $book;
            $response['authors'] = Author::orderBy('l_name')->orderBy('f_Name')->with('books')->get();
            $response['status'] = true;
            $response['message'] = 'Book has been '.(isset($request->id)?'updated':'created').' successfully.';
        }

        return $response;
    }

    public function edit(Request $request) {
        return $this->store($request);
    }

    public function destroy(Request $request) {
        $response = [
            'status' => false,
            'message' => 'Unexpected error while deleting data'
        ];
        $book = Book::whereId($request->id)->first();
        if ($book) {
            if (Book::whereId($request->id)->delete()) {
                $response['message'] = 'Book has been deleted successfully.';
                $response['status'] = true;
            } else {
                $response['message'] = 'Error while deleting book`s data.';
            }
        } else {
            $response['message'] = 'Book is not found!';
        }
        return $response;
    }
}
