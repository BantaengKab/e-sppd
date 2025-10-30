<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPPD Bantaeng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-2xl font-bold">E-SPPD</div>
                        <div class="text-sm opacity-90">Pemerintah Kabupaten Bantaeng</div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="btn btn-primary bg-white text-blue-600 hover:bg-gray-100 px-6 py-2 rounded-lg font-semibold transition-colors">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="flex-grow">
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        Sistem Perjalanan Dinas Elektronik
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        E-SPPD adalah sistem manajemen perjalanan dinas yang terintegrasi untuk Pemerintah Kabupaten Bantaeng.
                        Permudah pengajuan SPT, persetujuan, dan pelaporan perjalanan dinas secara digital.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="text-blue-600 text-3xl mb-4">📝</div>
                            <h3 class="text-lg font-semibold mb-2">Pengajuan Mudah</h3>
                            <p class="text-gray-600">Ajukan Surat Perintah Tugas (SPT) secara online dengan proses yang simpel dan cepat</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="text-green-600 text-3xl mb-4">✅</div>
                            <h3 class="text-lg font-semibold mb-2">Persetujuan Digital</h3>
                            <p class="text-gray-600">Multi-stage approval workflow untuk supervisor, finance, dan verifikator</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="text-purple-600 text-3xl mb-4">📊</div>
                            <h3 class="text-lg font-semibold mb-2">Pelaporan Terintegrasi</h3>
                            <p class="text-gray-600">Realisasi biaya perjalanan dengan upload bukti dan laporan PDF</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg text-left">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Fitur Utama:</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            <li>Role-based access control untuk setiap level pengguna</li>
                            <li>Estimasi biaya perjalanan dinas yang transparan</li>
                            <li>Generate Surat Perjalanan Dinas (SPPD) otomatis</li>
                            <li>Tracking realisasi biaya dengan bukti digital</li>
                            <li>Audit logging untuk kepatuhan dan pelaporan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <p class="mb-2">© 2025 Pemerintah Kabupaten Bantaeng</p>
                    <p class="text-gray-400 text-sm">Sistem Perjalanan Dinas Elektronik - Dinas Komunikasi dan Informatika</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>