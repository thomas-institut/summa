<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ChunkBackup extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_chunks_bu";
    protected $primaryKey ="id";
    public $timestamps = false;
}