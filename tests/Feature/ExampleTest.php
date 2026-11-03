<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_detail_pages_load_with_category_relation(): void
    {
        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
            'color' => 'primary',
            'description' => 'Berita teknologi terkini',
        ]);

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'Artikel Uji Coba Bootstrap 5',
            'slug' => 'artikel-uji-coba-bootstrap-5',
            'author' => 'Admin',
            'excerpt' => 'Ringkasan uji coba',
            'content' => 'Isi lengkap artikel uji coba.',
            'is_published' => true,
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Artikel Uji Coba Bootstrap 5')
            ->assertSee('Teknologi');

        $this->get('/berita/' . $article->slug)
            ->assertStatus(200)
            ->assertSee('Artikel Uji Coba Bootstrap 5')
            ->assertSee('Isi lengkap artikel uji coba.');
    }

    public function test_crud_article_and_category_work(): void
    {
        $this->post('/categories', [
            'name' => 'Olahraga',
            'color' => 'danger',
            'description' => 'Seputar olahraga',
        ])->assertRedirect('/categories');

        $category = Category::firstOrFail();

        $this->post('/articles', [
            'title' => 'Timnas Menang Telak',
            'category_id' => $category->id,
            'author' => 'Redaksi',
            'content' => 'Konten berita olahraga lengkap.',
            'is_published' => '1',
        ])->assertRedirect('/articles');

        $article = Article::firstOrFail();
        $this->assertEquals($category->id, $article->category_id);

        $this->put("/articles/{$article->id}", [
            'title' => 'Timnas Menang Telak (Updated)',
            'category_id' => $category->id,
            'author' => 'Redaksi',
            'content' => 'Konten diperbarui.',
            'is_published' => '1',
        ])->assertRedirect('/articles');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Timnas Menang Telak (Updated)',
        ]);

        $this->delete("/articles/{$article->id}")
            ->assertRedirect('/articles');

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
