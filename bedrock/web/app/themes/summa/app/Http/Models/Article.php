<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_articles";
    protected $primaryKey ="id";
    public $timestamps = false;

    function question()
    {
        return $this->hasOne('App\Http\Models\Question', 'id', 'question_id');
    }










}