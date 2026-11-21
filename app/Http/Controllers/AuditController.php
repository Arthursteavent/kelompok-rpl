<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Audit;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    /**
     * Halaman Admin: Daftar Log Audit (Audit Trail) dengan filter event, modul, dan pencarian.
     */
    public function index(Request $request): View
    {
        $event = trim((string) $request->query('event', ''));
        $model = trim((string) $request->query('model', ''));
        $search = trim((string) $request->query('q', ''));

        $modelMap = [
            'article' => Article::class,
            'category' => Category::class,
            'user' => User::class,
        ];

        $audits = Audit::with('user')
            ->when($event !== '', fn ($q) => $q->where('event', $event))
            ->when(isset($modelMap[$model]), fn ($q) => $q->where('auditable_type', $modelMap[$model]))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('auditable_label', 'like', "%{$search}%")
                        ->orWhere('event', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhere('tags', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('audits.index', compact('audits', 'event', 'model', 'search'));
    }
}
