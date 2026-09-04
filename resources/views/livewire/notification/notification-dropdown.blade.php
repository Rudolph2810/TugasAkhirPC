<div class="relative" x-data="{ open: false }" @click.away="open = false" wire:poll.5s="loadNotifications">
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

    <div x-show="open" @click.away="open = false"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl overflow-hidden z-50 border border-gray-200">
        <div class="p-3 border-b flex justify-between items-center bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-800">
                🔔 Notifikasi
                @if($unreadCount > 0)
                <span class="ml-1 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $unreadCount }}</span>
                @endif
            </h3>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800">
                Tandai semua dibaca
            </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
            @if(empty($notifications))
            <div class="p-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="mt-2 text-sm">Belum ada notifikasi</p>
            </div>
            @else
            @foreach($notifications as $notification)
            <a href="{{ $notification['url'] ?? '#' }}"
                class="block hover:bg-gray-50 transition-all {{ isset($notification['read_at']) ? 'bg-white' : 'bg-blue-50' }}"
                wire:click="markAsRead('{{ $notification['id'] }}')">
                <div class="p-4">
                    <p class="text-sm text-gray-800 whitespace-pre-line">
                        {{ $notification['message'] }}
                    </p>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-500">{{ $notification['project_code'] ?? '' }}</span>
                        <span class="text-xs text-gray-400">{{ $notification['created_at'] }}</span>
                    </div>
                </div>
            </a>
            @endforeach
            @endif
        </div>
    </div>
</div>