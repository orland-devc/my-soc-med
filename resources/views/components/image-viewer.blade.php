<div id="imageViewerModal" class="fixed inset-0 bg-black/80 bg-opacity-75 hidden z-50 flex items-center justify-center" style="display: none;">
    <div class="relative w-full h-full flex items-center justify-center">
        <!-- Close button -->
        <button onclick="closeImageViewer()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">
            &times;
        </button>

        <!-- Previous button -->
        <button onclick="previousImage()" class="absolute left-4 text-white text-3xl hover:text-gray-300 z-10">
            &#10094;
        </button>

        <!-- Image -->
        <img id="viewerImage" src="" alt="Viewer" class="max-w-4xl max-h-96 object-contain" />

        <!-- Next button -->
        <button onclick="nextImage()" class="absolute right-4 text-white text-3xl hover:text-gray-300 z-10">
            &#10095;
        </button>
    </div>
</div>

<script>
let currentImageIndex = 0;
let allImages = [];

function openImageViewer(img) {
    // Get all images with the viewer-image class
    allImages = Array.from(document.querySelectorAll('.viewer-image'));
    currentImageIndex = allImages.indexOf(img);
    
    document.getElementById('viewerImage').src = img.src;
    document.getElementById('imageViewerModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImageViewer() {
    document.getElementById('imageViewerModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function nextImage() {
    if (allImages.length === 0) return;
    currentImageIndex = (currentImageIndex + 1) % allImages.length;
    document.getElementById('viewerImage').src = allImages[currentImageIndex].src;
}

function previousImage() {
    if (allImages.length === 0) return;
    currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
    document.getElementById('viewerImage').src = allImages[currentImageIndex].src;
}

// Close on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeImageViewer();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') previousImage();
});

// Close on background click
document.getElementById('imageViewerModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'imageViewerModal') closeImageViewer();
});
</script>

<style>
.viewer-image {
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.viewer-image:hover {
    opacity: 0.8;
}
</style>