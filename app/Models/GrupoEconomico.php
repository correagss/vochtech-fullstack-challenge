// app/Models/GrupoEconomico.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GrupoEconomico extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nome'];

    // activitylog
    protected static $logAttributes = ['nome'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'grupo_economico';

    public function bandeiras()
    {
        return $this->hasMany(Bandeira::class);
    }
}
