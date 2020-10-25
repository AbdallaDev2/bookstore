<?php

namespace App\Controllers;
use App\Models\Author;
use App\Models\Book;
use App\Models\Cat;
use Core\Db;
use Core\File;
use Core\Request;
use Core\Session;
use Core\Validation\Validator;
use Core\View;

class AdminBookController
{

    public function index()
    {
        $data['books'] = Db::getInstance()->joinTables(['books','authors','cats'])
            ->selectMultiple([
                'books'=>['id','name','img','price','desc'],
                'authors'=>['name'],
                'cats'=>['name'],
            ])->on([
                ['books.author_id' , 'authors.id'],
                ['books.cat_id' , 'cats.id'],
            ])->get();

        View::load('admin/book/index',$data);
    }

    public function create()
    {
        $data['authors'] = Author::connectTable()
                ->select('id,name')
                ->get();
        $data['cats'] = Cat::connectTable()
                ->select('id,name')
                ->get();
        View::load('admin/book/create',$data);

    }

    public function store()
    {
        $request = new Request;
        extract($_POST);
        $file = new File($_FILES['img']);

        $request_prepared = [
            [
                'name' => 'img',
                'value' => $file->imageSize,
                'rules' => 'size'
            ],
            [
                'name' => 'img',
                'value' => $file->imageType,
                'rules' => 'required|image|str'
            ],
            [
                'name' => 'name',
                'value' => $name,
                'rules' => 'required|str'
            ],
            [
                'name' => 'price',
                'value' => $price,
                'rules' => 'required|numeric'
            ],
            [
                'name' => 'desc',
                'value' => $desc,
                'rules' => 'required|str'
            ],
            [
                'name' => 'author_id',
                'value' => $author_id,
                'rules' => 'required'
            ],
            [
                'name' => 'cat_id',
                'value' => $cat_id,
                'rules' => 'required'
            ],
        ];

        $errors = Validator::make($request_prepared);

        if(! empty($errors)) {
            $session = new Session;
            $session->set("errors", $errors);
        } else {

            $file->rename('books')->upload('books');
            Book::connectTable()->insert([
                'name' => $name,
                'price' => $price,
                'desc' => $desc,
                'img' => $file->imageName,
                'author_id' => $author_id,
                'cat_id'=> $cat_id,
            ])->save();
            $session = new Session;
            $session->set("success","The process of adding a new book was successful");
        }

        $request->redirect("dashboard/books/create");
    }

}