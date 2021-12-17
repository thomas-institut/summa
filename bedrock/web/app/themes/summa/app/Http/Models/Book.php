<?php


namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Book extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_books";
    protected $primaryKey = "id";
    public $timestamps = false;

    function chapters()
    {
        return $this->hasMany('App\Http\Models\Chapter', 'book_id', 'id')->with("articles");
    }

    function chaptersNoChunks()
    {
        return $this->hasMany('App\Http\Models\Chapter', 'book_id', 'id')->with("articlesNoChunks");
    }

    function chaptersNoArticles()
    {
        return $this->hasMany('App\Http\Models\Chapter', 'book_id', 'id')->with("translator");
    }


}