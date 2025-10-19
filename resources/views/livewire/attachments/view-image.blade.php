<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
};
?>

<div id="imageTab" class="fixed inset-0 z-50 overflow-auto bg-gray-900 bg-opacity-90 flex items-center justify-center py-8" style="display: none; backdrop-filter: blur(5px);">
    <div class="absolute text-3xl grid grid-cols-2 gap-6" style="top: 20px; right:20px">
        <a href="#" onclick="downloadImage()" class="flex items-center justify-center font-bold text-gray-300 hover:text-white hover:bg-gray-600 p-3 rounded-full w-14 h-14 transition-all duration-200">
            <i class="fa-solid fa-download text-gray-300 hover:text-white"></i>
        </a>
        <a href="#" onclick="hideimageTab()" class="flex items-center justify-center font-bold text-gray-300 hover:text-white hover:bg-gray-600 p-3 rounded-full w-14 h-14 transition-all duration-200">
            <i class="fa-regular fa-circle-xmark text-gray-300 hover:text-white"></i>
        </a>
    </div>
    @if ($post->attachments->count() > 1)
    <div>
        <a href="#" onclick="prevImage()" class="text-6xl text-gray-400 hover:text-white font-bold absolute" style="top: 45%; left: 20px">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <a href="#" onclick="nextImage()" class="text-6xl text-gray-400 hover:text-white font-bold absolute" style="top: 45%; right: 20px">
            <ion-icon name="chevron-forward-outline"></ion-icon>
        </a>
    </div>
    @endif
    <img id="imagePreview" src="" alt="" class="h-[80vh] hidden">
    <div id="otherFile" class="hidden text-white text-2xl flex-col items-center gap-8"></div>       
</div>