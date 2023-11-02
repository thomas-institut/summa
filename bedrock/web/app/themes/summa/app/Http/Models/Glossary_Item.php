<?php


namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Translator
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Glossary_Item extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_glossary_items";
    protected $primaryKey = "id";
    public $timestamps = false;



    public function relations() {
        return $this->belongsToMany(Glossary_Item::class, Glossary_Relation::class, 'subject_id', 'object_id')
            ->orderBy("name", "asc");
    }



}
