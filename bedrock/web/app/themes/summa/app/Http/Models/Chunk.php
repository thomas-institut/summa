<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Chunk extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_chunks";
    protected $primaryKey ="id";
    public $timestamps = false;

    function article()
    {
        return $this->hasOne('App\Http\Models\Article', 'id', 'article_id');
    }










}