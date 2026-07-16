<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function お問い合わせが特定のカテゴリに属し、複数のタグと同期（sync）できる(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $contacts = Contact::factory()->create([
            'category_id' => $category->id
        ]);
        $tags = Tag::factory()->count(3)->create();

        // Act
        $contacts = $category->contacts;
        $contact = $contacts->first();
        $contact->tags()->sync($tags->pluck('id'));

        // Assert
        $this->assertEquals($category->id, $contact->category_id);
        $this->assertCount(3, $contact->refresh()->tags);
    }
}
