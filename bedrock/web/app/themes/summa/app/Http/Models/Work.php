<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_works";
    protected $primaryKey ="id";
    public $timestamps = false;

    function books()
    {
        return $this->hasMany('App\Http\Models\Book', 'work_id', 'id')->with("chapters");
    }










}