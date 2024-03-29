<?php



namespace App\Http\Models;
//use DareOne\models\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * @package App\Http\Models
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Article extends Model
{
    protected $fillable = [

    ];

    protected $table = "s_articles";
    protected $primaryKey ="id";
    public $timestamps = false;

    function question()
    {
        return $this->hasOne('App\Http\Models\Chapter', 'id', 'question_id');
    }

    function chunks()
    {
        return $this->hasMany('App\Http\Models\Chunk', 'article_id', 'id')->orderBy("project_id", "ASC");
    }












}
