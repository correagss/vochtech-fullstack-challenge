// app/Http/Controllers/GrupoEconomicoController.php
namespace App\Http\Controllers;

use App\Models\GrupoEconomico;
use Illuminate\Http\Request;

class GrupoEconomicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // exige autenticação
    }

    public function index()
    {
        $grupos = GrupoEconomico::orderBy('nome')->paginate(15);
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255|unique:grupo_economicos,nome',
        ]);

        $grupo = GrupoEconomico::create($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($grupo)
            ->withProperties(['attributes' => $grupo->toArray()])
            ->log('Criou grupo econômico');

        return redirect()->route('grupos.index')->with('success', 'Grupo criado.');
    }

    public function edit(GrupoEconomico $grupo)
    {
        return view('grupos.edit', compact('grupo'));
    }

    public function update(Request $request, GrupoEconomico $grupo)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255|unique:grupo_economicos,nome,' . $grupo->id,
        ]);

        $grupo->update($data);

        activity()->causedBy(auth()->user())->performedOn($grupo)->log('Atualizou grupo econômico');

        return redirect()->route('grupos.index')->with('success', 'Grupo atualizado.');
    }

    public function destroy(GrupoEconomico $grupo)
    {
        $grupo->delete();

        activity()->causedBy(auth()->user())->performedOn($grupo)->log('Deletou grupo econômico');

        return redirect()->route('grupos.index')->with('success', 'Grupo deletado.');
    }
}
