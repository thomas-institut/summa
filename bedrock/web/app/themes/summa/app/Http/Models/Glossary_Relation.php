<?php


namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Translator
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Glossary_Relation extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_glossary_relations";
    protected $primaryKey = "id";
    public $timestamps = false;


}
