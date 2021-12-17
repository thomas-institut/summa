<?php


namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Work
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Work extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_works";
    protected $primaryKey = "id";
    public $timestamps = false;

    function books()
    {
        return $this->hasMany('App\Http\Models\Book', 'work_id', 'id')->with("chapters");
    }

    function booksNoChunks()
    {
        return $this->hasMany('App\Http\Models\Book', 'work_id', 'id')->with("chaptersNoChunks");
    }


}