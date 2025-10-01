// app/Http/Controllers/BandeiraController.php
<?php

namespace App\Http\Controllers;

use App\Models\Bandeira;
use App\Models\GrupoEconomico; // Precisamos do modelo de GrupoEconomico
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Para validação de unicidade condicional

class BandeiraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Exige autenticação
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bandeiras = Bandeira::with('grupoEconomico')->orderBy('nome')->paginate(15);
        return view('bandeiras.index', compact('bandeiras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gruposEconomicos = GrupoEconomico::orderBy('nome')->get(); // Para popular o select
        return view('bandeiras.create', compact('gruposEconomicos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bandeiras')->where(function ($query) use ($request) {
                    return $query->where('grupo_economico_id', $request->grupo_economico_id);
                }),
            ],
            'grupo_economico_id' => 'required|exists:grupo_economicos,id',
        ]);

        $bandeira = Bandeira::create($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($bandeira)
            ->withProperties(['attributes' => $bandeira->toArray()])
            ->log('Criou bandeira');

        return redirect()->route('bandeiras.index')->with('success', 'Bandeira criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bandeira  $bandeira
     * @return \Illuminate\Http\Response
     */
    public function edit(Bandeira $bandeira)
    {
        $gruposEconomicos = GrupoEconomico::orderBy('nome')->get();
        return view('bandeiras.edit', compact('bandeira', 'gruposEconomicos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bandeira  $bandeira
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bandeira $bandeira)
    {
        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bandeiras')->where(function ($query) use ($request) {
                    return $query->where('grupo_economico_id', $request->grupo_economico_id);
                })->ignore($bandeira->id), // Ignora a própria bandeira ao atualizar
            ],
            'grupo_economico_id' => 'required|exists:grupo_economicos,id',
        ]);

        $bandeira->update($data);

        activity()->causedBy(auth()->user())->performedOn($bandeira)->log('Atualizou bandeira');

        return redirect()->route('bandeiras.index')->with('success', 'Bandeira atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bandeira  $bandeira
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bandeira $bandeira)
    {
        // Verificar se existem unidades associadas antes de deletar
        if ($bandeira->unidades()->count() > 0) {
            return redirect()->route('bandeiras.index')->with('error', 'Não é possível deletar esta bandeira, pois possui unidades associadas.');
        }

        $bandeira->delete();

        activity()->causedBy(auth()->user())->performedOn($bandeira)->log('Deletou bandeira');

        return redirect()->route('bandeiras.index')->with('success', 'Bandeira deletada com sucesso.');
    }
}