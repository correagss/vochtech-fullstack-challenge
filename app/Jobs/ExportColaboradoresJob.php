// app/Jobs/ExportColaboradoresJob.php
namespace App\Jobs;

use App\Models\Colaborador;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Exports\ColaboradoresExport;

class ExportColaboradoresJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $filters;
    public $userId;

    public function __construct(array $filters = [], $userId = null)
    {
        $this->filters = $filters;
        $this->userId = $userId;
    }

    public function handle()
    {
        $filename = 'colaboradores_export_'.now()->format('Ymd_His').'.xlsx';
        // ColaboradoresExport é uma exportação que aceita filtros
        Excel::store(new ColaboradoresExport($this->filters), $filename, 'local');
        // notifica usuário, registra activity
    }
}
