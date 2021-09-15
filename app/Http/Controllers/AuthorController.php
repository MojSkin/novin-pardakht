<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorController extends Controller {
    public function list() {
        return [
            'status' => true,
            'result' => Author::orderBy('l_name')->orderBy('f_Name')->with('books')->get()
        ];
    }

    public function store(Request $request) {
        $response = [
            'status' => false,
            'message' => 'Unexpected error while storing data',
            'result' => null,
        ];
        $request->validate([
            'f_name' => 'required|string|max:50',
            'l_name' => 'required|string|max:50',
        ]);
        $author = null;
        if (isset($request->id)) {
            if (Author::whereId($request->id)->update($request->all())) {
                $author = Author::whereId($request->id)->with('books')->first();
            }
        } else {
            $author = Author::create($request->all());
            $author = Author::whereId($author->id)->with('books')->first();
        }

        if ($author) {
            $response['result'] = $author;
            $response['status'] = true;
            $response['message'] = 'Author has been '.(isset($request->id)?'updated':'created').' successfully.';
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
        $author = Author::whereId($request->id)->first();
        if ($author) {
            if (Author::whereId($request->id)->delete()) {
                Book::whereAuthorId($request->id)->delete();
                $response['message'] = 'Author has been deleted successfully.';
                $response['status'] = true;
            } else {
                $response['message'] = 'Error while deleting authors`s data.';
            }
        } else {
            $response['message'] = 'Author is not found!';
        }
        return $response;
    }
}
