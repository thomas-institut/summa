<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Chunk
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Chunk extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_chunks";
    protected $primaryKey = "id";
    public $timestamps = false;

    function article()
    {
        return $this->hasOne('App\Http\Models\Article', 'id', 'article_id');
    }


}