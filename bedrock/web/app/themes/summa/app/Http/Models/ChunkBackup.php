<?php


namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChunkBackup
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class ChunkBackup extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_chunks_bu";
    protected $primaryKey = "id";
    public $timestamps = false;
}