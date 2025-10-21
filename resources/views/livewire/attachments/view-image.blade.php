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
    
    <script>
        let currentIndex = 0;
        const attachments = @json($ticket->attachments->pluck('file_location'));
        const fileNames = @json($ticket->attachments->pluck('file_name'));

        function viewImage(imageUrl, fileName, index, fileExtension) {
            const img = document.getElementById('imagePreview');
            const otherFile = document.getElementById('otherFile');
            const titleName = document.getElementById('fileName');
            if (fileName.endsWith('.jpg') || fileName.endsWith('.jpeg') || fileName.endsWith('.png')) {
                otherFile.classList.add('hidden');
                img.classList.remove('hidden');
                titleName.innerHTML = `<p>${fileName}</p>`;
                img.src = imageUrl;
                img.dataset.fileName = fileName;
                const imageTab = document.getElementById('imageTab');
                imageTab.style.display = "flex";
            }
            else {
                titleName.innerHTML = `<p>${fileName}</p>`;
                img.classList.add('hidden');
                otherFile.classList.remove('hidden');
                otherFile.classList.add('flex');
                const imageTab = document.getElementById('imageTab');
                imageTab.style.display = "flex";
                otherFile.innerHTML = `<p>Can't view this file. Please download it.</p><a href="${imageUrl}" class="px-4 py-2 bg-white rounded-xl text-gray-600 hover:text-blue-600" download>Download ${fileName} <i class="fa-solid fa-download ml-4"></i></a>`;
            }
            
            currentIndex = index;
        }
        function hideimageTab() {
            const img = document.getElementById('imageTab');
            img.style.display = "none";
        }

        function downloadImage() {
            const imageUrl = document.getElementById('imagePreview').src;
            const fileName = document.getElementById('imagePreview').dataset.fileName;
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function prevImage() {
            currentIndex = (currentIndex === 0) ? attachments.length - 1 : currentIndex - 1;
            const imageUrl = "{{ asset('/') }}" + attachments[currentIndex];
            const fileName = fileNames[currentIndex];
            document.getElementById('imagePreview').src = imageUrl;
            document.getElementById('imagePreview').dataset.fileName = fileName;
        }

        function nextImage() {
            currentIndex = (currentIndex === attachments.length - 1) ? 0 : currentIndex + 1;
            const imageUrl = "{{ asset('/') }}" + attachments[currentIndex];
            const fileName = fileNames[currentIndex];
            document.getElementById('imagePreview').src = imageUrl;
            document.getElementById('imagePreview').dataset.fileName = fileName;
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft') {
                prevImage();
            } else if (event.key === 'ArrowRight') {
                nextImage();
            }
        });

    </script>
</div>