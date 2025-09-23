// tests/Feature/GrupoTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\GrupoEconomico;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrupoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_authenticated_can_create_grupo()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resp = $this->post(route('grupos.store'), ['nome' => 'Grupo Teste']);
        $resp->assertRedirect(route('grupos.index'));
        $this->assertDatabaseHas('grupo_economicos', ['nome' => 'Grupo Teste']);
    }
}
