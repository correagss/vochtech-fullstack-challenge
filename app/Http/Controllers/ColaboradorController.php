// app/Http/Controllers/ColaboradorController.php (metodo export)
public function export(Request $request)
{
    $filters = $request->only(['nome','email','unidade_id']);
    ExportColaboradoresJob::dispatch($filters, auth()->id());
    return back()->with('success','Exportação enfileirada. Você receberá o arquivo quando pronto.');
}
