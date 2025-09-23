// app/Models/Bandeira.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Bandeira extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nome', 'grupo_economico_id'];
    protected static $logAttributes = ['nome', 'grupo_economico_id'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'bandeira';

    public function grupoEconomico()
    {
        return $this->belongsTo(GrupoEconomico::class, 'grupo_economico_id');
    }

    public function unidades()
    {
        return $this->hasMany(Unidade::class);
    }
}
