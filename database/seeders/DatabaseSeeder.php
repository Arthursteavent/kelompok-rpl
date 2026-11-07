<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Role Admin
        User::updateOrCreate(
            ['email' => 'admin@narasidigital.com'],
            [
                'name' => 'Admin Narasi Digital',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('password'),
            ]
        );

        // 2. Akun Role Penulis
        User::updateOrCreate(
            ['email' => 'penulis@narasidigital.com'],
            [
                'name' => 'Penulis Narasi Digital',
                'role' => User::ROLE_PENULIS,
                'password' => Hash::make('password'),
            ]
        );

        // 3. Akun Role User Penglihat
        User::updateOrCreate(
            ['email' => 'user@narasidigital.com'],
            [
                'name' => 'User Narasi Digital',
                'role' => User::ROLE_PENGLIHAT,
                'password' => Hash::make('password'),
            ]
        );

        // 4. Data Dummy Kategori Berita (5 Kategori)
        $categoriesData = [
            [
                'name' => 'Teknologi & AI',
                'slug' => 'teknologi-ai',
                'color' => 'primary',
                'description' => 'Perkembangan terbaru seputar rekayasa perangkat lunak, kecerdasan buatan, dan inovasi digital.',
            ],
            [
                'name' => 'Pendidikan & Kampus',
                'slug' => 'pendidikan-kampus',
                'color' => 'success',
                'description' => 'Berita dunia pendidikan tinggi, riset mahasiswa, dan prestasi akademik nasional.',
            ],
            [
                'name' => 'Ekonomi Digital',
                'slug' => 'ekonomi-digital',
                'color' => 'warning',
                'description' => 'Tren startup, UMKM digital, fintech, dan pertumbuhan ekonomi kreatif di Indonesia.',
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'color' => 'danger',
                'description' => 'Kabar terkini sepak bola nasional, bulu tangkis, dan ajang olahraga internasional.',
            ],
            [
                'name' => 'Sains & Lingkungan',
                'slug' => 'sains-lingkungan',
                'color' => 'info',
                'description' => 'Eksplorasi ilmiah, energi terbarukan, dan pelestarian lingkungan hidup.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 5. Data Dummy 12 Artikel Berita
        $articlesData = [
            [
                'category_slug' => 'teknologi-ai',
                'title' => 'Transformasi Rekayasa Perangkat Lunak di Era Agentic AI Tahun 2026',
                'slug' => 'transformasi-rekayasa-perangkat-lunak-di-era-agentic-ai-tahun-2026',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Pengembangan aplikasi modern kini semakin cepat berkat kolaborasi antara software engineer dan asisten AI cerdas dalam siklus Software Development Life Cycle (SDLC).',
                'content' => "Perkembangan teknologi kecerdasan buatan (AI) telah membawa perubahan besar dalam dunia Rekayasa Perangkat Lunak (RPL). Jika beberapa tahun lalu otomatisasi hanya sebatas melengkapi baris kode sederhana, kini sistem Agentic AI mampu membantu tim pengembang merancang arsitektur database, menguji keamanan, hingga melakukan debugging secara menyeluruh.\n\nDi berbagai perguruan tinggi dan industri teknologi di Indonesia, kolaborasi manusia dan AI menjadi fokus utama kurikulum baru. Mahasiswa tidak hanya dituntut menguasai sintaks bahasa pemrograman seperti PHP dengan framework Laravel atau JavaScript, tetapi juga kemampuan analisis kebutuhan sistem (system requirements) serta pola desain perangkat lunak.\n\nDengan fondasi arsitektur MVC (Model-View-Controller) dan manajemen autentikasi multi-role yang terstruktur, tim pengembang dapat menghasilkan aplikasi web yang jauh lebih aman, terukur, dan mudah dipelihara untuk jangka panjang.",
                'image_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 1420,
            ],
            [
                'category_slug' => 'pendidikan-kampus',
                'title' => 'Tim Mahasiswa RPL Raih Juara Nasional Lewat Inovasi Portal Informasi Kampus Pintar',
                'slug' => 'tim-mahasiswa-rpl-raih-juara-nasional-lewat-inovasi-portal-informasi-kampus-pintar',
                'author' => 'Admin Narasi Digital',
                'excerpt' => 'Mengusung arsitektur aplikasi berbasis web yang ringan dan responsif, tim mahasiswa berhasil memukau dewan juri pada ajang kompetisi teknologi tingkat nasional.',
                'content' => "Sebuah tim mahasiswa program studi Rekayasa Perangkat Lunak berhasil menyabet gelar juara pertama dalam ajang Kompetisi Inovasi Digital Mahasiswa tingkat nasional. Karya yang mereka tampilkan adalah platform portal berita dan kolaborasi akademik terintegrasi.\n\nAplikasi yang dikembangkan menerapkan pembagian hak akses (multi-role authentication) yang jelas antara Administrator, Penulis Redaksi, serta Pembaca umum. Dengan demikian, alur publikasi artikel ilmiah populer dan berita kegiatan kampus dapat terverifikasi dengan cepat.\n\n\"Kami merancang antarmuka yang bersih menggunakan Tailwind CSS dengan dukungan tema gelap dan terang, serta mengamankan seluruh ID tabel menggunakan UUID,\" ujar ketua tim pengembang usai menerima penghargaan.",
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 985,
            ],
            [
                'category_slug' => 'ekonomi-digital',
                'title' => 'UMKM Nusantara Tumbuh Pesat Berkat Ekosistem Pembayaran Digital dan Cloud',
                'slug' => 'umkm-nusantara-tumbuh-pesat-berkat-ekosistem-pembayaran-digital-dan-cloud',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Adopsi layanan cloud computing dan transaksi nirsentuh mendorong peningkatan omzet pelaku usaha mikro hingga 45 persen sepanjang kuartal ini.',
                'content' => "Transformasi digital tidak lagi hanya dinikmati oleh perusahaan korporasi besar. Ribuan pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) di berbagai daerah kini telah beralih menggunakan pencatatan keuangan berbasis cloud serta pembayaran digital QRIS lintas negara.\n\nKemudahan integrasi katalog produk online dengan layanan pengiriman instan membuat produk lokal semakin mudah menjangkau pasar nasional. Para analis ekonomi digital menilai bahwa literasi digital dan ketersediaan platform web yang ramah pengguna menjadi kunci utama keberlanjutan bisnis lokal.\n\nPemerintah bersama komunitas pengembang lokal juga terus mengadakan pelatihan pembuatan website dan manajemen konten digital agar pelaku usaha dapat membangun citra merek secara mandiri.",
                'image_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 760,
            ],
            [
                'category_slug' => 'olahraga',
                'title' => 'Timnas Indonesia Siap Hadapi Laga Kualifikasi dengan Dukungan Sport Science Modern',
                'slug' => 'timnas-indonesia-siap-hadapi-laga-kualifikasi-dengan-dukungan-sport-science-modern',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Pemanfaatan analisis data performa pemain secara real-time membantu tim pelatih menyusun strategi taktik yang lebih akurat di lapangan.',
                'content' => "Persiapan intensif terus dilakukan oleh skuad Garuda menjelang pertandingan krusial pekan depan. Menariknya, pemusatan latihan kali ini didukung penuh oleh teknologi sport science berbasis sensor biometrik dan analisis video real-time.\n\nSetiap pergerakan, jarak tempuh, hingga detak jantung pemain dipantau langsung oleh tim analis data untuk mencegah risiko cedera sekaligus menjaga kebugaran puncak selama 90 menit pertandingan.\n\nAntusiasme suporter pun terlihat sangat tinggi. Puluhan ribu tiket pertandingan dikabarkan telah habis terjual hanya dalam hitungan jam sejak dibuka secara daring.",
                'image_url' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 1890,
            ],
            [
                'category_slug' => 'sains-lingkungan',
                'title' => 'Inovasi Panel Surya Generasi Baru Mampu Hasilkan Energi Lebih Efisien di Wilayah Tropis',
                'slug' => 'inovasi-panel-surya-generasi-baru-mampu-hasilkan-energi-lebih-efisien-di-wilayah-tropis',
                'author' => 'Admin Narasi Digital',
                'excerpt' => 'Peneliti mengembangkan sel fotovoltaik berbahan perovskite-silikon yang tetap bekerja optimal meski pada suhu udara tinggi dan cuaca berawan.',
                'content' => "Transisi menuju energi bersih mendapatkan angin segar setelah tim peneliti gabungan berhasil menguji coba panel surya generasi terbaru yang dirancang khusus untuk iklim khatulistiwa.\n\nBerbeda dengan panel konvensional yang mengalami penurunan efisiensi saat suhu permukaan meningkat tajam, material hibrida terbaru ini mampu menyerap spektrum cahaya matahari secara lebih luas dan stabil.\n\nSejumlah gedung kampus dan fasilitas publik kini mulai memasang prototipe panel tersebut yang dipadukan dengan sistem pemantauan IoT untuk melacak produksi listrik harian secara transparan.",
                'image_url' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 640,
            ],
            [
                'category_slug' => 'teknologi-ai',
                'title' => 'Pentingnya Penggunaan UUID pada Database Aplikasi Web Modern Demi Keamanan Data',
                'slug' => 'pentingnya-penggunaan-uuid-pada-database-aplikasi-web-modern-demi-keamanan-data',
                'author' => 'Admin Narasi Digital',
                'excerpt' => 'Mengganti ID angka berurutan (auto-increment) dengan Universally Unique Identifier (UUID) mencegah serangan tebak URL dan kebocoran jumlah data.',
                'content' => "Dalam pengembangan aplikasi web profesional, penggunaan ID angka berurutan seperti 1, 2, 3 pada URL sering kali membuka celah keamanan Insecure Direct Object References (IDOR). Pihak luar dapat dengan mudah menebak ID pengguna atau dokumen lain hanya dengan mengganti angka di bilah alamat browser.\n\nDengan menerapkan UUID (Universally Unique Identifier) melalui trait HasUuids di Laravel, setiap baris data pada tabel users, categories, maupun articles memiliki identitas unik sepanjang 36 karakter yang acak dan tidak dapat ditebak.\n\nSelain meningkatkan keamanan, penggunaan UUID juga memudahkan penggabungan data (data merging) antar server terdistribusi tanpa khawatir terjadi bentrok primary key.",
                'image_url' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 1340,
            ],
            [
                'category_slug' => 'pendidikan-kampus',
                'title' => 'Kolaborasi Proyek Kelompok RPL: Kunci Sukses Mengelola Git dan Database Tim',
                'slug' => 'kolaborasi-proyek-kelompok-rpl-kunci-sukses-mengelola-git-dan-database-tim',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Menggunakan migration dan seeder di Laravel memudahkan seluruh anggota kelompok menyamakan struktur database dan data awal tanpa repot.',
                'content' => "Mengerjakan tugas proyek kelompok pada mata kuliah Rekayasa Perangkat Lunak sering kali menghadirkan tantangan tersendiri, mulai dari perbedaan versi PHP di laptop anggota tim hingga sinkronisasi struktur tabel database.\n\nMelalui fitur Migration dan Database Seeder yang dimiliki Laravel, setiap anggota tim cukup menjalankan satu perintah untuk membangun ulang tabel beserta data dummy yang seragam di perangkat masing-masing.\n\nDitambah dengan pembagian tugas yang jelas antara perancang antarmuka, pengembang logika autentikasi, dan penguji fitur (testing), proyek aplikasi web kelompok dapat diselesaikan tepat waktu dengan kualitas setara standar industri.",
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 830,
            ],
            [
                'category_slug' => 'ekonomi-digital',
                'title' => 'Tren Pekerjaan Remote Software Engineer dari Indonesia untuk Perusahaan Global',
                'slug' => 'tren-pekerjaan-remote-software-engineer-dari-indonesia-untuk-perusahaan-global',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Semakin banyak talenta pengembang web tanah air yang direkrut oleh startup mancanegara berkat portofolio open-source dan penguasaan teknologi modern.',
                'content' => "Bekerja dari mana saja (work from anywhere) kini telah menjadi budaya permanen bagi banyak perusahaan teknologi dunia. Hal ini membuka peluang emas bagi lulusan teknologi informasi dan rekayasa perangkat lunak di Indonesia untuk berkarier di kancah internasional tanpa harus berpindah negara.\n\nKemampuan komunikasi yang baik, kedisiplinan manajemen waktu, serta penguasaan framework populer seperti Laravel, Tailwind CSS, dan ekosistem cloud menjadi nilai jual utama para engineer lokal.\n\nKomunitas IT di berbagai kota besar maupun daerah pun aktif menggelar sesi berbagi pengalaman dan simulasi wawancara teknis guna mempersiapkan talenta muda menghadapi standar rekrutmen global.",
                'image_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 910,
            ],
            [
                'category_slug' => 'olahraga',
                'title' => 'Ganda Putra Bulu Tangkis Muda Indonesia Tembus Final Turnamen Super 1000',
                'slug' => 'ganda-putra-bulu-tangkis-muda-indonesia-tembus-final-turnamen-super-1000',
                'author' => 'Admin Narasi Digital',
                'excerpt' => 'Tampil percaya diri sejak babak awal, pasangan muda Pelatnas Cipayung sukses menumbangkan unggulan pertama lewat pertarungan tiga gim.',
                'content' => "Kejutan besar terjadi pada babak semifinal turnamen bulu tangkis bergengsi level Super 1000. Pasangan ganda putra muda Indonesia berhasil melaju ke partai puncak usai menaklukkan pasangan peringkat satu dunia dalam duel sengit berdurasi 68 menit.\n\nPermainan cepat di depan net dan pertahanan rapat yang diperagakan sejak gim kedua membuat lawan kesulitan mengembangkan pola serangan. Dukungan penuh penonton di stadion turut membakar semangat juang wakil Merah Putih.\n\nKeberhasilan ini menjadi bukti positif berjalannya regenerasi atlet muda nasional yang siap meneruskan tradisi emas bulu tangkis Indonesia di panggung dunia.",
                'image_url' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 1150,
            ],
            [
                'category_slug' => 'sains-lingkungan',
                'title' => 'Program Kampus Hijau Dorong Pengelolaan Sampah Plastik Menjadi Bahan Bangunan Ramah Lingkungan',
                'slug' => 'program-kampus-hijau-dorong-pengelolaan-sampah-plastik-menjadi-bahan-bangunan-ramah-lingkungan',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Melalui laboratorium daur ulang terpadu, limbah plastik di lingkungan kampus diolah menjadi paving block berdaya tahan tinggi.',
                'content' => "Upaya mengurangi jejak karbon dan limbah plastik terus digalakkan melalui gerakan Kampus Hijau (Green Campus) di berbagai universitas. Salah satu inovasi nyata yang berhasil diterapkan adalah pengolahan sampah plastik kemasan menjadi bata ringan dan paving block.\n\nHasil uji kuat tekan di laboratorium teknik menunjukkan bahwa campuran limbah plastik terolah dan pasir silika memiliki daya tahan yang sangat baik terhadap beban maupun cuaca ekstrem.\n\nSelain menjaga kebersihan lingkungan akademik, program ini juga mengedukasi mahasiswa mengenai prinsip ekonomi sirkular yang bernilai guna bagi masyarakat sekitar.",
                'image_url' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 575,
            ],
            [
                'category_slug' => 'teknologi-ai',
                'title' => 'Desain Antarmuka Modern dengan Tailwind CSS: Mengapa Fitur Dark Mode Sangat Disukai Pembaca',
                'slug' => 'desain-antarmuka-modern-dengan-tailwind-css-mengapa-fitur-dark-mode-sangat-disukai-pembaca',
                'author' => 'Penulis Narasi Digital',
                'excerpt' => 'Dukungan tema gelap dan terang memberikan kenyamanan visual maksimal bagi pembaca portal berita, baik di siang hari maupun malam hari.',
                'content' => "Kenyamanan membaca merupakan faktor terpenting dalam merancang sebuah portal berita digital. Riset pengalaman pengguna (User Experience) menunjukkan bahwa mayoritas pembaca berita menyukai kebebasan memilih antara tema terang (light mode) dan tema gelap (dark mode).\n\nDengan pendekatan utility-first pada Tailwind CSS, pengembang dapat merancang palet warna kontras tinggi yang nyaman di mata hanya dengan menambahkan prefix class dark pada komponen halaman.\n\nPenyimpanan preferensi tema di localStorage browser juga memastikan pilihan pengguna tetap tersimpan secara otomatis setiap kali mereka berpindah halaman atau kembali mengunjungi situs.",
                'image_url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 1045,
            ],
            [
                'category_slug' => 'ekonomi-digital',
                'title' => 'Pertumbuhan Industri Kreatif dan Game Lokal Indonesia Tembus Pasar Asia Tenggara',
                'slug' => 'pertumbuhan-industri-kreatif-dan-game-lokal-indonesia-tembus-pasar-asia-tenggara',
                'author' => 'Admin Narasi Digital',
                'excerpt' => 'Mengangkat unsur budaya nusantara dengan kualitas grafis modern, sejumlah studio pengembang lokal sukses mencuri perhatian jutaan pemain.',
                'content' => "Industri kreatif digital Indonesia kembali mencatatkan pencapaian membanggakan. Sejumlah karya studio pengembang game dan animasi lokal berhasil masuk dalam daftar unduhan terpopuler di kawasan Asia Tenggara sepanjang tahun ini.\n\nKekuatan cerita yang mengangkat cerita rakyat serta pemandangan khas nusantara menjadi daya tarik unik yang membedakan karya kreator Indonesia di pasar global.\n\nSinergi antara lembaga pendidikan vokasi, komunitas kreator, dan dukungan akses pendanaan diharapkan terus memperkuat daya saing ekosistem kreatif nasional di masa depan.",
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200&q=80',
                'is_published' => true,
                'views' => 890,
            ],
        ];

        foreach ($articlesData as $artData) {
            $category = $categories[$artData['category_slug']];

            Article::updateOrCreate(
                ['slug' => $artData['slug']],
                [
                    'category_id' => $category->id,
                    'title' => $artData['title'],
                    'author' => $artData['author'],
                    'excerpt' => $artData['excerpt'],
                    'content' => $artData['content'],
                    'image_url' => $artData['image_url'],
                    'is_published' => $artData['is_published'],
                    'views' => $artData['views'],
                ]
            );
        }
    }
}
