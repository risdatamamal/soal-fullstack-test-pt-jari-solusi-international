<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = [
        'book_id', 'user_id', 'borrow_date'
    ];

    protected $with = ['book', 'user'];

    public function book()
    {
        return $this->belongsTo('App\Book', 'book_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    protected $casts = [
        'borrow_date' => 'date:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];
}
