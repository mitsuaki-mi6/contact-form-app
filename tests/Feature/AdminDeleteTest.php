<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\User;

class AdminDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function お問い合わせが削除される(): void
    {
        // Arrange
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        // Act
        $response = $this->actingAs($user)->delete('admin/contacts/' . $contact->id);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function 削除後adminにリダイレクトされる(): void
    {
        // Arrange
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        // Act
        $response = $this->actingAs($user)->delete('admin/contacts/' . $contact->id);

        // Assert
        $response->assertRedirect('/admin');
    }
}
