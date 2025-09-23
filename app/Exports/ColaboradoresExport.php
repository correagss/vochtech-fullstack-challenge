
namespace App\Exports;

use App\Models\Colaborador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ColaboradoresExport implements FromCollection, WithHeadings
{
    protected $filters;
    public function __construct($filters = []) { $this->filters = $filters; }

    public function collection()
    {
        $q = Colaborador::query()->with('unidade.bandeira.grupoEconomico');

        if(!empty($this->filters['nome'])) $q->where('nome','like','%'.$this->filters['nome'].'%');
        if(!empty($this->filters['email'])) $q->where('email','like','%'.$this->filters['email'].'%');
        if(!empty($this->filters['unidade_id'])) $q->where('unidade_id',$this->filters['unidade_id']);

        return $q->get()->map(function($c){
            return [
                'id' => $c->id,
                'nome' => $c->nome,
                'email' => $c->email,
                'cpf' => $c->cpf,
                'unidade' => $c->unidade->nome_fantasia ?? null,
                'bandeira' => $c->unidade->bandeira->nome ?? null,
                'grupo_economico' => $c->unidade->bandeira->grupoEconomico->nome ?? null,
                'created_at' => $c->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID','Nome','Email','CPF','Unidade','Bandeira','Grupo Econômico','Criado em'];
    }
}
