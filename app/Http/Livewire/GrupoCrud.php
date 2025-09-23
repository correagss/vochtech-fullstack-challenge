// app/Http/Livewire/GrupoCrud.php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\GrupoEconomico;
use Livewire\WithPagination;

class GrupoCrud extends Component
{
    use WithPagination;

    public $search = '';
    public $nome;
    public $editingId = null;

    protected $rules = [
        'nome' => 'required|string|max:255|unique:grupo_economicos,nome'
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function create()
    {
        $this->reset(['nome','editingId']);
    }

    public function edit($id)
    {
        $g = GrupoEconomico::findOrFail($id);
        $this->editingId = $g->id;
        $this->nome = $g->nome;
    }

    public function save()
    {
        $rules = $this->rules;

        if($this->editingId){
            $rules['nome'] = 'required|string|max:255|unique:grupo_economicos,nome,'.$this->editingId;
        }

        $this->validate($rules);

        if($this->editingId){
            $g = GrupoEconomico::find($this->editingId);
            $g->update(['nome'=>$this->nome]);
            activity()->causedBy(auth()->user())->performedOn($g)->log('Atualizou (Livewire) grupo');
        } else {
            $g = GrupoEconomico::create(['nome'=>$this->nome]);
            activity()->causedBy(auth()->user())->performedOn($g)->log('Criou (Livewire) grupo');
        }

        session()->flash('success','Salvo com sucesso');
        $this->reset(['nome','editingId']);
    }

    public function delete($id)
    {
        $g = GrupoEconomico::findOrFail($id);
        $g->delete();
        activity()->causedBy(auth()->user())->performedOn($g)->log('Deletou (Livewire) grupo');
        session()->flash('success','Deletado');
    }

    public function render()
    {
        $grupos = GrupoEconomico::where('nome','like','%'.$this->search.'%')->orderBy('nome')->paginate(10);
        return view('livewire.grupo-crud', compact('grupos'));
    }
}
