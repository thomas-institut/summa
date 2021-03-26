<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_questions";
    protected $primaryKey ="id";
    public $timestamps = false;

    function book()
    {
        return $this->hasOne('App\Http\Models\Book', 'id', 'book_id');
    }

    function articles()
    {
        return $this->hasMany('App\Http\Models\Article', 'question_id', 'id');
    }









}