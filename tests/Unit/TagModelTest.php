<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class TagModelTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function 中間テーブルを介して、1つのタグが複数のお問い合わせに紐づいている(): void
    {
        // Arrange
        $tag = Tag::factory()->create();
        $contacts = Contact::factory()->count(3)->create();
        $tag->contacts()->attach($contacts->pluck('id'));

        // Act
        $results = $tag->contacts;

        // Assert
        $this->assertCount(3, $results);
        foreach ($contacts as $index => $contact) {
            $this->assertEquals($contact->id, $results[$index]->id);
        }
    }
}
