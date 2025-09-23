// database/migrations/2025_09_22_000000_create_core_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreTables extends Migration
{
    public function up()
    {
        Schema::create('grupo_economicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('bandeiras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('grupo_economico_id')->constrained('grupo_economicos')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->string('cnpj', 20)->unique();
            $table->foreignId('bandeira_id')->constrained('bandeiras')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('cpf', 20)->unique();
            $table->foreignId('unidade_id')->constrained('unidades')->cascadeOnDelete();
            $table->timestamps();
        });

        // tabela para auditoria do activitylog (spatie cria a própria migration com vendor:publish)
    }

    public function down()
    {
        Schema::dropIfExists('colaboradores');
        Schema::dropIfExists('unidades');
        Schema::dropIfExists('bandeiras');
        Schema::dropIfExists('grupo_economicos');
    }
}
