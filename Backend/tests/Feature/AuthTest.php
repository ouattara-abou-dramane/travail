
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        $response = $this->postJson('/api/enregistrer', [
            'nom' => 'Jane Doe',
            'numero' => '1234567890',
            'quartier' => 'Quartier Central',
            'password' => 'securepassword',
            'password_confirmation' => 'securepassword',
            'statut' => 'client'
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'nom' => 'Jane Doe',
                     'numero' => '1234567890',
                     'quartier' => 'Quartier Central',
                   
                 ]);
    }

    public function testRegisterValidationErrors()
    {
        $response = $this->postJson('/api/enregistrer', [
            'nom' => '', // Nom manquant
            'numero' => '1234567890',
            'quartier' => 'Quartier Central',
            'password' => 'securepassword',
            'password_confirmation' => 'differentpassword', // Confirmation du mot de passe incorrecte
            'statut' => 'client'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nom', 'password']);
    }
}