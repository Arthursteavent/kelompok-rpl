@extends('layouts.app')

@section('title', 'Audit Trail - Narasi Digital')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Audit Trail (Log Aktivitas & Perubahan)</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                Rekam jejak otomatis untuk setiap penambahan, perubahan, penghapusan data (Artikel, Kategori, User) serta aktivitas autentikasi.
            </p>
        </div>

        <a href="{{ route('articles.index') }}" class="text-xs text-zinc-500 hover:underline">
            &larr; Ke Daftar Artikel
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="mb-6 rounded-2xl p-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
        <form action="{{ route('audits.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-4">
                <label for="q" class="block text-xs font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Cari Aktivitas / Pelaku</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Nama user, target data, IP address..."
                    class="w-full px-3.5 py-2 rounded-lg text-xs bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                >
            </div>

            <div class="sm:col-span-3">
                <label for="model" class="block text-xs font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Modul / Entitas</label>
                <select
                    id="model"
                    name="model"
                    class="w-full px-3.5 py-2 rounded-lg text-xs bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                >
                    <option value="">Semua Modul</option>
                    <option value="article" @selected($model === 'article')>Artikel</option>
                    <option value="category" @selected($model === 'category')>Kategori</option>
                    <option value="user" @selected($model === 'user')>User / Akun</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="event" class="block text-xs font-semibold mb-1 text-zinc-700 dark:text-zinc-300">Jenis Aksi (Event)</label>
                <select
                    id="event"
                    name="event"
                    class="w-full px-3.5 py-2 rounded-lg text-xs bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                >
                    <option value="">Semua Event</option>
                    <option value="created" @selected($event === 'created')>Tambah Data (Created)</option>
                    <option value="updated" @selected($event === 'updated')>Ubah Data (Updated)</option>
                    <option value="deleted" @selected($event === 'deleted')>Hapus Data (Deleted)</option>
                    <option value="login" @selected($event === 'login')>Login Berhasil</option>
                    <option value="logout" @selected($event === 'logout')>Logout</option>
                    <option value="register" @selected($event === 'register')>Registrasi Akun</option>
                    <option value="login_failed" @selected($event === 'login_failed')>Login Gagal</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button
                    type="submit"
                    class="flex-1 py-2 px-3 rounded-lg text-xs font-semibold bg-zinc-900 text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 cursor-pointer"
                >
                    Filter
                </button>

                @if ($search !== '' || $event !== '' || $model !== '')
                    <a
                        href="{{ route('audits.index') }}"
                        class="py-2 px-3 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Audit Table -->
    <div class="rounded-2xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 text-xs border-b border-zinc-200 dark:border-zinc-800">
                        <th class="py-3 pl-5 pr-3">Waktu</th>
                        <th class="py-3 px-3">Pelaku (User)</th>
                        <th class="py-3 px-3">Aksi / Event</th>
                        <th class="py-3 px-3">Modul & Target Data</th>
                        <th class="py-3 px-3">IP Address</th>
                        <th class="py-3 pl-3 pr-5 text-right">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($audits as $audit)
                        <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3.5 pl-5 pr-3 text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                <div class="font-medium text-zinc-800 dark:text-zinc-200">
                                    {{ $audit->created_at?->translatedFormat('d M Y, H:i:s') }}
                                </div>
                                <div class="text-[11px] text-zinc-400">
                                    {{ $audit->created_at?->diffForHumans() }}
                                </div>
                            </td>

                            <td class="py-3.5 px-3 text-xs">
                                @if ($audit->user)
                                    <div class="font-semibold text-zinc-900 dark:text-white">{{ $audit->user->name }}</div>
                                    <div class="text-zinc-400">{{ $audit->user->email }} &bull; {{ $audit->user->roleLabel() }}</div>
                                @else
                                    <span class="text-zinc-400 italic">Tamu / Sistem</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 text-xs whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 rounded-md border font-medium {{ $audit->eventBadgeClass() }}">
                                    {{ $audit->eventLabel() }}
                                </span>
                            </td>

                            <td class="py-3.5 px-3 text-xs">
                                <div class="font-semibold text-zinc-900 dark:text-white">
                                    {{ $audit->modelLabel() }}
                                </div>
                                <div class="text-zinc-500 dark:text-zinc-400 truncate max-w-xs" title="{{ $audit->auditable_label }}">
                                    {{ $audit->auditable_label ?: ($audit->auditable_id ? substr($audit->auditable_id, 0, 8) . '...' : '-') }}
                                </div>
                            </td>

                            <td class="py-3.5 px-3 text-xs font-mono text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                {{ $audit->ip_address ?: '-' }}
                            </td>

                            <td class="py-3.5 pl-3 pr-5 text-right text-xs whitespace-nowrap">
                                <button
                                    type="button"
                                    onclick="openModal('auditModal{{ $audit->id }}')"
                                    class="px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 border border-zinc-300 dark:border-zinc-700 font-medium cursor-pointer"
                                >
                                    Lihat Trail
                                </button>

                                <!-- Modal Detail Audit -->
                                <div
                                    id="auditModal{{ $audit->id }}"
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 text-left"
                                >
                                    <div class="w-full max-w-2xl max-h-[85vh] overflow-y-auto rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 shadow-xl">
                                        <div class="flex items-start justify-between gap-4 pb-4 mb-4 border-b border-zinc-200 dark:border-zinc-800">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md border text-xs font-medium {{ $audit->eventBadgeClass() }}">
                                                        {{ $audit->eventLabel() }}
                                                    </span>
                                                    <span class="text-xs text-zinc-400 font-mono">#{{ substr($audit->id, 0, 8) }}</span>
                                                </div>
                                                <h3 class="text-base font-bold text-zinc-900 dark:text-white whitespace-normal">
                                                    {{ $audit->modelLabel() }}: {{ $audit->auditable_label ?: '-' }}
                                                </h3>
                                            </div>
                                            <button
                                                type="button"
                                                onclick="closeModal('auditModal{{ $audit->id }}')"
                                                class="px-2.5 py-1 rounded-lg text-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer"
                                            >
                                                Tutup
                                            </button>
                                        </div>

                                        <!-- Metadata Audit -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 text-xs">
                                            <div>
                                                <div class="text-zinc-400">Pelaku</div>
                                                <div class="font-medium text-zinc-900 dark:text-white">
                                                    {{ $audit->user ? $audit->user->name . ' (' . $audit->user->email . ')' : 'Tamu / Sistem' }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-zinc-400">Waktu Kejadian</div>
                                                <div class="font-medium text-zinc-900 dark:text-white">
                                                    {{ $audit->created_at?->translatedFormat('d F Y, H:i:s') }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-zinc-400">IP Address & Tag</div>
                                                <div class="font-mono text-zinc-800 dark:text-zinc-200">
                                                    {{ $audit->ip_address ?: '-' }} {{ $audit->tags ? '• ' . $audit->tags : '' }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-zinc-400">URL Aksi</div>
                                                <div class="font-mono text-zinc-800 dark:text-zinc-200 truncate" title="{{ $audit->url }}">
                                                    {{ $audit->url ?: '-' }}
                                                </div>
                                            </div>
                                            @if ($audit->user_agent)
                                                <div class="sm:col-span-2">
                                                    <div class="text-zinc-400">User Agent</div>
                                                    <div class="font-mono text-[11px] text-zinc-600 dark:text-zinc-400 break-all whitespace-normal">
                                                        {{ $audit->user_agent }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Perbandingan Old Values vs New Values -->
                                        @php
                                            $oldVals = $audit->old_values ?? [];
                                            $newVals = $audit->new_values ?? [];
                                            $allKeys = array_unique(array_merge(array_keys($oldVals), array_keys($newVals)));
                                        @endphp

                                        @if (count($allKeys) > 0)
                                            <div class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                                Rincian Perubahan Atribut (Old Values vs New Values)
                                            </div>
                                            <div class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                                                            <th class="py-2 px-3 w-1/4">Kolom / Field</th>
                                                            <th class="py-2 px-3 w-3/8">Nilai Lama (Old)</th>
                                                            <th class="py-2 px-3 w-3/8">Nilai Baru (New)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                                        @foreach ($allKeys as $fieldKey)
                                                            <tr>
                                                                <td class="py-2.5 px-3 font-mono font-semibold text-zinc-800 dark:text-zinc-200 align-top">
                                                                    {{ $fieldKey }}
                                                                </td>
                                                                <td class="py-2.5 px-3 text-red-600 dark:text-red-400 align-top whitespace-normal break-words">
                                                                    @if (array_key_exists($fieldKey, $oldVals) && $oldVals[$fieldKey] !== null)
                                                                        {{ is_bool($oldVals[$fieldKey]) ? ($oldVals[$fieldKey] ? 'true' : 'false') : (is_array($oldVals[$fieldKey]) ? json_encode($oldVals[$fieldKey]) : \Illuminate\Support\Str::limit((string) $oldVals[$fieldKey], 250)) }}
                                                                    @else
                                                                        <span class="text-zinc-400 italic">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="py-2.5 px-3 text-emerald-600 dark:text-emerald-400 align-top whitespace-normal break-words">
                                                                    @if (array_key_exists($fieldKey, $newVals) && $newVals[$fieldKey] !== null)
                                                                        {{ is_bool($newVals[$fieldKey]) ? ($newVals[$fieldKey] ? 'true' : 'false') : (is_array($newVals[$fieldKey]) ? json_encode($newVals[$fieldKey]) : \Illuminate\Support\Str::limit((string) $newVals[$fieldKey], 250)) }}
                                                                    @else
                                                                        <span class="text-zinc-400 italic">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 text-center text-xs text-zinc-500">
                                                Tidak ada perubahan atribut model pada event ini.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-xs text-zinc-500 dark:text-zinc-400">
                                Belum ada catatan audit trail yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($audits->hasPages())
            <div class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
@endsection
