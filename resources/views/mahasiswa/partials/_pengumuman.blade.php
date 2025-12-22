@if($pengumuman->count() > 0)
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            Pengumuman Terbaru 
            <span class="ml-2 px-2 py-0.5 bg-white bg-opacity-30 rounded-full text-xs">
                {{ $pengumuman->count() }}
            </span>
        </h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($pengumuman as $item)
                <div class="border-l-4 border-orange-500 bg-orange-50 p-4 rounded-r-lg hover:shadow-md transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-900">{{ $item->judul }}</h4>
                                @if($item->created_at->isToday())
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-semibold">
                                        Baru
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <p class="text-sm text-gray-700 mb-2 leading-relaxed">{{ $item->isi }}</p>

                            <!-- Footer -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $item->created_at->diffForHumans() }}
                                </div>

                                @if($item->program_studi_id)
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded font-medium">
                                        {{ $item->programStudi->nama }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded font-medium">
                                        Untuk Semua
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Button (Optional) -->
        @if($pengumuman->count() > 3)
        <div class="mt-4 text-center">
            <button class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                Lihat Semua Pengumuman →
            </button>
        </div>
        @endif
    </div>
</div>
@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-md p-8 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
        </svg>
    </div>
    <p class="text-gray-500">Belum ada pengumuman</p>
</div>
@endif