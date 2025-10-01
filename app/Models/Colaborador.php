// app/Models/Colaborador.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Colaborador extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nome', 'email', 'cpf', 'unidade_id'];
    protected static $logAttributes = ['nome','email','cpf','unidade_id'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'colaborador';

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}
