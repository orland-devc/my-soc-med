<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>

    <div 
        id="toastNotice" 
        class="hidden animate-showup fixed top-3 right-3 p-3 text-white rounded-lg bg-red-600 z-9999"
        style="box-shadow: 0 4px 10px rgba(0,0,0,0.3)">
        <div class="flex items-center">
            <span class="mr-2 text-3xl">
                😅
            </span>
            <span>
                Sorry, this is not yet available.
            </span>
            <button onclick="hideToast()" class="ml-2 text-3xl h-8 w-8 flex justify-center hover:bg-red-700 rounded-lg cursor-pointer transition-all">
                &times;
            </button>
        </div>
    </div>
</x-layouts.app.sidebar>

<style>
    button {
        cursor: pointer;
    }
    .animate-showUp {
        animation: showUp 0.2s ease-in-out;
    }

    .animate-close {
        animation: vanish 0.3s ease-in-out;
    }

    @keyframes showUp {
        from { opacity: 0; transform: translateX(10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes vanish {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(10px); }
    }
</style>

<script>
    const toastNotice = document.getElementById('toastNotice');

    function showToast() {
        setTimeout(() => {
            toastNotice.classList.remove('hidden', 'animate-close');
            toastNotice.classList.add('animate-showUp');
        }, 300);
        setTimeout(() => hideToast(), 8000);
    }

    function hideToast() {
        toastNotice.classList.remove('animate-showUp');
        toastNotice.classList.add('animate-close');
        setTimeout(() => toastNotice.classList.add('hidden'), 280);
    }
</script>