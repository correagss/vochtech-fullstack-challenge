// app/Models/Unidade.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nome_fantasia', 'razao_social', 'cnpj', 'bandeira_id'];
    protected static $logAttributes = ['nome_fantasia','razao_social','cnpj','bandeira_id'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'unidade';

    public function bandeira()
    {
        return $this->belongsTo(Bandeira::class);
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class);
    }
}
