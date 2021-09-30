<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_chapters";
    protected $primaryKey ="id";
    public $timestamps = false;

    function book()
    {
        return $this->hasOne('App\Http\Models\Book', 'id', 'book_id');
    }

    function articles()
    {
        return $this->hasMany('App\Http\Models\Article', 'chapter_id', 'id')->with('chunks');
    }

    function translator()
    {
        return $this->hasOne('App\Http\Models\Translator', 'id', 'translator_id');
    }

    function articlesNoChunks()
    {
        return $this->hasMany('App\Http\Models\Article', 'chapter_id', 'id');
    }









}