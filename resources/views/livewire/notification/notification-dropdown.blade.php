<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
        <span
            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <div x-show="open"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg overflow-hidden z-50 border">
        <div class="p-3 border-b flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800">
                Tandai semua sudah dibaca
            </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @if(empty($notifications))
            <div class="p-6 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="mt-2 text-sm">Tidak ada notifikasi</p>
            </div>
            @else
            @foreach($notifications as $notification)
            <a href="{{ $notification['url'] }}"
                class="block hover:bg-gray-50 border-b last:border-b-0 transition {{ isset($notification['read_at']) ? 'bg-white' : 'bg-blue-50' }}">
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">
                                {{ $notification['message'] }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification['created_at'] }}
                            </p>
                        </div>
                        @if(!isset($notification['read_at']))
                        <button wire:click="markAsRead('{{ $notification['id'] }}')"
                            class="text-xs text-blue-600 hover:text-blue-800 ml-2" onclick="event.preventDefault();">
                            Tandai
                        </button>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
            @endif
        </div>
    </div>
</div>