{{-- Barbería Landing Page Mejorada y Profesional --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barbería - Servicios</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-hover:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.12); transform: translateY(-4px) scale(1.03); }
        .btn-cart { background: linear-gradient(90deg,#f53003 0%,#f8b803 100%); color: #fff; }
        .btn-cart:hover { background: linear-gradient(90deg,#f8b803 0%,#f53003 100%); }
        .carousel-img { display: none; }
        .carousel-img.active { display: block; }
        .carousel-btn { background: rgba(0,0,0,0.5); color: #fff; }
        .hair-icon { color: #f8b803; margin-right: 2px; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 via-white to-gray-300 min-h-screen font-sans antialiased">
    <header class="bg-white shadow-xl py-6 mb-8 sticky top-0 z-30">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="https://img.icons8.com/ios-filled/50/000000/barbershop.png" class="w-10 h-10" alt="Barbería" />
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center">
                    Cuatro Pelos
                    <span class="ml-2 flex">
                        @for ($i = 0; $i < 4; $i++)
                            <svg class="hair-icon w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C7.03 2 2.5 6.03 2.5 11c0 4.97 4.53 9 9.5 9s9.5-4.03 9.5-9c0-4.97-4.53-9-9.5-9zm0 16c-3.86 0-7-3.14-7-7 0-3.86 3.14-7 7-7s7 3.14 7 7c0 3.86-3.14 7-7 7z"/></svg>
                        @endfor
                    </span>
                </h1>
            </div>
            <nav>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/') }}" class="text-sm px-4 py-2 rounded btn-cart shadow font-semibold transition">Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm px-4 py-2 rounded btn-cart shadow font-semibold transition">Ingresar</a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4">
        <section class="mb-12">
            <h2 class="text-4xl font-extrabold mb-8 text-gray-900 text-center tracking-tight drop-shadow-lg">Nuestros Servicios</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                @php
                    use App\Models\Service;
                    $services = Service::with(['category', 'serviceImages'])->where('status', 'active')->get();
                @endphp
                @forelse ($services as $service)
                    @php
                        $images = [];
                        if ($service->cover_image_url) {
                            $images[] = asset('storage/' . $service->cover_image_url);
                        }
                        if ($service->serviceImages && $service->serviceImages->count()) {
                            foreach ($service->serviceImages as $img) {
                                $images[] = asset('storage/' . $img->image);
                            }
                        }
                    @endphp
                    <div class="bg-white rounded-3xl shadow-2xl card-hover p-8 flex flex-col relative transition-all duration-300 border border-gray-100 hover:border-orange-400">
                        <div class="mb-4 relative">
                            <div class="relative w-full h-60 overflow-hidden rounded-2xl bg-gradient-to-br from-orange-50 via-yellow-50 to-gray-100">
                                @foreach ($images as $idx => $imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $service->name }}" class="carousel-img {{ $idx === 0 ? 'active' : '' }} w-full h-60 object-cover absolute top-0 left-0 rounded-2xl transition-all duration-500 shadow-lg">
                                @endforeach
                                @if (count($images) > 1)
                                    <button type="button" class="carousel-btn absolute left-2 top-1/2 -translate-y-1/2 rounded-full px-3 py-2 shadow-lg" onclick="prevImg(this)">&#8592;</button>
                                    <button type="button" class="carousel-btn absolute right-2 top-1/2 -translate-y-1/2 rounded-full px-3 py-2 shadow-lg" onclick="nextImg(this)">&#8594;</button>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2 flex items-center tracking-tight">
                            {{ $service->name }}
                            <span class="ml-2 flex">
                                @for ($i = 0; $i < 4; $i++)
                                    <svg class="hair-icon w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C7.03 2 2.5 6.03 2.5 11c0 4.97 4.53 9 9.5 9s9.5-4.03 9.5-9c0-4.97-4.53-9-9.5-9zm0 16c-3.86 0-7-3.14-7-7 0-3.86 3.14-7 7-7s7 3.14 7 7c0 3.86-3.14 7-7 7z"/></svg>
                                @endfor
                            </span>
                        </h3>
                        <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-orange-200 to-yellow-200 text-orange-800 text-sm font-bold mb-3 shadow">{{ $service->category?->name }}</span>
                        <p class="text-gray-700 mb-3 flex-1 text-base leading-relaxed">{!! $service->description !!}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-3xl font-extrabold text-green-600 drop-shadow">{{ $service->formatted_price }}</span>
                            <button class="btn-cart px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center gap-2 text-base hover:scale-105 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.3h12.2a1 1 0 00.9-1.3L17 13M7 13V6a1 1 0 011-1h9a1 1 0 011 1v7" />
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center text-gray-500 text-lg">No hay servicios registrados.</div>
                @endforelse
            </div>
        </section>
        <section class="bg-white rounded-2xl shadow-lg p-8 mt-16 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h2 class="text-2xl font-bold mb-2 text-gray-800">Contáctanos</h2>
                <p class="text-gray-700 mb-2">Dirección: Calle Principal 123, Ciudad</p>
                <p class="text-gray-700 mb-2">Teléfono: (01) 234-5678</p>
                <p class="text-gray-700">Horario: Lunes a Sábado 9:00am - 8:00pm</p>
            </div>
            <div class="flex gap-4">
                <a href="#" class="btn-cart px-4 py-2 rounded-lg font-semibold shadow transition flex items-center gap-2 text-sm hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path d="M24 4.557a9.93 9.93 0 01-2.828.775 4.932 4.932 0 002.165-2.724c-.951.564-2.005.974-3.127 1.195a4.916 4.916 0 00-8.38 4.482C7.691 8.094 4.066 6.13 1.64 3.161c-.543.929-.855 2.006-.855 3.17 0 2.188 1.115 4.117 2.823 5.254a4.904 4.904 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.936 4.936 0 01-2.224.084c.627 1.956 2.444 3.377 4.6 3.417A9.867 9.867 0 010 21.543a13.94 13.94 0 007.548 2.209c9.142 0 14.307-7.721 13.995-14.646A9.936 9.936 0 0024 4.557z"/></svg>
                    Twitter
                </a>
                <a href="#" class="btn-cart px-4 py-2 rounded-lg font-semibold shadow transition flex items-center gap-2 text-sm hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.308.975.974 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.308 3.608-.974.975-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.308-.975-.974-1.246-2.242-1.308-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.85c.059-1.282.292-2.394 1.272-3.374.981-.981 2.093-1.213 3.374-1.272C8.332 2.175 8.712 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.771.131 4.659.363 3.678 1.344c-.98.98-1.213 2.092-1.272 3.374C2.013 5.741 2 6.151 2 12c0 5.849.013 6.259.072 7.638.059 1.282.292 2.394 1.272 3.374.981.981 2.093 1.213 3.374 1.272C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.281-.059 2.393-.291 3.374-1.272.98-.98 1.213-2.092 1.272-3.374.059-1.379.072-1.789.072-7.638 0-5.849-.013-6.259-.072-7.638-.059-1.282-.292-2.394-1.272-3.374-.981-.981-2.093-1.213-3.374-1.272C15.668.013 15.259 0 12 0z"/></svg>
                    Instagram
                </a>
            </div>
        </section>
    </main>
    <footer class="mt-16 py-6 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Barbería Moderna. Todos los derechos reservados.
    </footer>
    <script>
        // Simulación de agregar al carrito
        document.querySelectorAll('.btn-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (btn.textContent.includes('Agregar')) {
                    e.preventDefault();
                    btn.textContent = 'Agregado!';
                    btn.classList.add('scale-110');
                    setTimeout(() => {
                        btn.textContent = 'Agregar';
                        btn.classList.remove('scale-110');
                    }, 1200);
                }
            });
        });
        // Carrusel de imágenes por servicio
        window.prevImg = function(btn) {
            const card = btn.closest('.card-hover');
            const imgs = card.querySelectorAll('.carousel-img');
            let idx = Array.from(imgs).findIndex(img => img.classList.contains('active'));
            imgs[idx].classList.remove('active');
            idx = (idx - 1 + imgs.length) % imgs.length;
            imgs[idx].classList.add('active');
        }
        window.nextImg = function(btn) {
            const card = btn.closest('.card-hover');
            const imgs = card.querySelectorAll('.carousel-img');
            let idx = Array.from(imgs).findIndex(img => img.classList.contains('active'));
            imgs[idx].classList.remove('active');
            idx = (idx + 1) % imgs.length;
            imgs[idx].classList.add('active');
        }
    </script>
</body>
</html>
