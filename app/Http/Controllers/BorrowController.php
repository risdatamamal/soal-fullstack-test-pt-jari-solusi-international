<?php

namespace App\Http\Controllers;

use App\Book;
use App\Borrow;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables, Auth;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('borrows');
    }

    public function getBorrowList(Request $request)
    {
        $data  = Borrow::get();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                if (Auth::user()->can('manage_user')) {
                    return '<div class="table-actions">
                                <a href="' . url('borrow/' . $data->id) . '" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="' . url('borrow/delete/' . $data->id) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        try {
            $users = User::pluck('name', 'id');
            $books = Book::pluck('name', 'id');

            return view('create-borrow', compact('users', 'books'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book'     => 'required',
            'user'     => 'required',
            // 'borrow_date' => 'required | date ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try {
            $borrow = Borrow::create([
                'book_id'     => $request->book,
                'user_id'     => $request->user,
                // 'borrow_date' => $request->borrow_date,
            ]);

            if ($borrow) {
                return redirect('borrows')->with('success', 'New borrow created!');
            } else {
                return redirect('borrows')->with('error', 'Failed to create new borrow! Try again.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id)
    {
        try {
            $borrow  = Borrow::with('books', 'users')->find($id);

            if ($borrow) {
                $borrow_user = $borrow->user->first();
                $borrow_book = $borrow->book->first();

                $users = User::pluck('name', 'id');
                $books = Book::pluck('name', 'id');

                return view('borrow-edit', compact('borrow', 'users', 'books', 'borrow_book', 'borrow_user'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book'     => 'required',
            'user'     => 'required',
            // 'borrow_date' => 'required | date ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try {

            $borrow = Borrow::find($request->id);

            $borrow->update([
                'book_id'     => $request->book,
                'user_id'     => $request->user,
                // 'borrow_date' => $request->borrow_date,
            ]);

            return redirect()->back()->with('success', 'Book information updated succesfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }


    public function delete($id)
    {
        $book   = Book::find($id);
        if ($book) {
            $book->delete();
            return redirect('books')->with('success', 'Book removed!');
        } else {
            return redirect('books')->with('error', 'Book not found');
        }
    }
}
