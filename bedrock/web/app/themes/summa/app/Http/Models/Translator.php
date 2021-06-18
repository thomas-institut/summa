<?php



namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_translator";
    protected $primaryKey ="id";
    public $timestamps = false;


    function transcriped_chapters()
    {
        return $this->hasMany('App\Http\Models\Chapter', 'translator_id', 'id');
    }










}