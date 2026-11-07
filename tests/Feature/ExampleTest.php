<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_user_penglihat_can_view_home_and_detail_pages(): void
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

        // Guest (tanpa login) bisa membuka beranda & detail berita
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Artikel Uji Coba Bootstrap 5')
            ->assertSee('Teknologi');

        $this->get('/berita/' . $article->slug)
            ->assertStatus(200)
            ->assertSee('Artikel Uji Coba Bootstrap 5')
            ->assertSee('Isi lengkap artikel uji coba.');

        // Guest tidak bisa akses CRUD
        $this->get('/articles')->assertRedirect('/login');
        $this->get('/categories')->assertRedirect('/login');

        // User Penglihat (dengan login) bisa baca berita tapi tidak bisa akses CRUD
        $penglihat = User::factory()->create(['role' => User::ROLE_PENGLIHAT]);
        $this->actingAs($penglihat)->get('/')->assertStatus(200);
        $this->actingAs($penglihat)->get('/articles')->assertRedirect('/');
        $this->actingAs($penglihat)->get('/categories')->assertRedirect('/');
    }

    public function test_penulis_can_manage_articles_but_not_categories_page(): void
    {
        $penulis = User::factory()->create(['role' => User::ROLE_PENULIS]);
        $category = Category::create([
            'name' => 'Sains',
            'slug' => 'sains',
            'color' => 'info',
        ]);

        $this->actingAs($penulis)
            ->get('/articles')
            ->assertStatus(200);

        $this->actingAs($penulis)
            ->post('/articles', [
                'title' => 'Artikel Karya Penulis',
                'category_id' => $category->id,
                'author' => $penulis->name,
                'content' => 'Isi artikel oleh penulis.',
                'is_published' => '1',
            ])
            ->assertRedirect('/articles');

        $this->actingAs($penulis)
            ->get('/categories')
            ->assertRedirect('/');

        // Penulis tidak bisa mengakses halaman pendaftaran user/penulis milik Admin
        $this->actingAs($penulis)
            ->get('/users')
            ->assertRedirect('/');
    }

    public function test_public_register_only_creates_user_penglihat_and_admin_can_register_penulis_or_user(): void
    {
        // Daftar dari halaman user publik otomatis jadi 'penglihat' meskipun mengirim input role 'penulis'
        $this->post('/register', [
            'name' => 'Pembaca Baru',
            'email' => 'pembaca@test.com',
            'role' => 'penulis',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'pembaca@test.com',
            'role' => User::ROLE_PENGLIHAT,
        ]);

        // Admin bisa mendaftarkan Penulis atau User di halaman /users
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get('/users')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->post('/users', [
                'name' => 'Penulis Baru',
                'email' => 'penulisbaru@test.com',
                'role' => User::ROLE_PENULIS,
                'password' => 'password123',
            ])
            ->assertRedirect('/users');

        $this->assertDatabaseHas('users', [
            'email' => 'penulisbaru@test.com',
            'role' => User::ROLE_PENULIS,
        ]);
    }

    public function test_admin_can_crud_article_and_category(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->post('/categories', [
            'name' => 'Olahraga',
            'color' => 'danger',
            'description' => 'Seputar olahraga',
        ])->assertRedirect('/categories');

        $category = Category::firstOrFail();

        $this->actingAs($admin)->post('/articles', [
            'title' => 'Timnas Menang Telak',
            'category_id' => $category->id,
            'author' => 'Redaksi',
            'content' => 'Konten berita olahraga lengkap.',
            'is_published' => '1',
        ])->assertRedirect('/articles');

        $article = Article::firstOrFail();
        $this->assertEquals($category->id, $article->category_id);

        $this->actingAs($admin)->put("/articles/{$article->id}", [
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

        $this->actingAs($admin)->delete("/articles/{$article->id}")
            ->assertRedirect('/articles');

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
