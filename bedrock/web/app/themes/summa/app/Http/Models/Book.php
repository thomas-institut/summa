<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_books";
    protected $primaryKey ="id";
    public $timestamps = false;

    function questions()
    {
        return $this->hasMany('App\Http\Models\Question', 'book_id', 'id')->with("articles");
    }










}