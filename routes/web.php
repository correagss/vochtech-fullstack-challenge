// routes/web.php
use App\Http\Controllers\GrupoEconomicoController;
use App\Http\Controllers\BandeiraController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\ColaboradorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){ return redirect()->route('grupos.index'); });

Auth::routes(); // se usar Breeze, adapta

Route::middleware('auth')->group(function(){
    Route::resource('grupos', GrupoEconomicoController::class);
    Route::resource('bandeiras', BandeiraController::class);
    Route::resource('unidades', UnidadeController::class);
    Route::resource('colaboradores', ColaboradorController::class);

    // exportadores
    Route::get('relatorios/colaboradores/export', [ColaboradorController::class, 'export'])->name('colaboradores.export');
    Route::get('relatorios/colaboradores', [ColaboradorController::class, 'relatorio'])->name('colaboradores.relatorio');
});
