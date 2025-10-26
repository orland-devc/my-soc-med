<x-layouts.app :title="__('New Flex')">
    <div class="relative -m-4">

        <!-- Main Content -->
        <div class="px-4 py-4 md:py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Mobile Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl flex items-center font-bold text-zinc-900 dark:text-white">
                        {{ __('Create Post') }}
                    </h1>
                    <livewire:auth.user-options/>
                </div>

                <form action="{{ route('posts.store')}}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                    @csrf

                    <!-- Form Card -->
                    <div class="rounded-2xl md:shadow-lg md:border border-zinc-200 dark:border-zinc-700 md:p-8">
                        
                        <!-- Caption Input -->
                        <div class="mb-6">
                            <flux:input
                                :label="__('Caption')"
                                type="text"
                                name="caption"
                                :placeholder="__('What\'s on your mind?')"
                                class="text-lg"
                            />
                        </div>

                        <!-- Description Textarea -->
                        <div class="mb-6">
                            <flux:textarea
                                :label="__('Description')"
                                name="description"
                                :placeholder="__('Tell your story... Share your thoughts, experiences, or anything on your mind. The more details, the better!')"
                                class="resize-none h-32 md:h-40 text-base"
                            ></flux:textarea>
                        </div>

                        <!-- Privacy Options -->
                        <div class="mb-8 hidden">
                            <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">
                                {{ __('Who can see this?') }}
                            </label>
                            
                            <div class="grid grid-cols-1 gap-3">
                                <!-- Public Option -->
                                <label class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-400 dark:hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 group">
                                    <input 
                                        type="radio" 
                                        name="privacy" 
                                        value="0" 
                                        class="mt-1 w-5 h-5 text-blue-600 border-zinc-300 dark:border-zinc-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-zinc-800" 
                                        checked
                                    >
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-globe text-blue-600 dark:text-blue-400"></i>
                                            <span class="font-semibold text-zinc-900 dark:text-white">{{ __('Public') }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ __('Anyone on or off the platform can see this post') }}
                                        </p>
                                    </div>
                                </label>

                                <!-- Friends Option -->
                                <label class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-400 dark:hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 group">
                                    <input 
                                        type="radio" 
                                        name="privacy" 
                                        value="1" 
                                        class="mt-1 w-5 h-5 text-blue-600 border-zinc-300 dark:border-zinc-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-zinc-800"
                                    >
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user-group text-green-600 dark:text-green-400"></i>
                                            <span class="font-semibold text-zinc-900 dark:text-white">{{ __('Friends') }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ __('Only your friends can see this post') }}
                                        </p>
                                    </div>
                                </label>

                                <!-- Only Me Option -->
                                <label class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-400 dark:hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 group">
                                    <input 
                                        type="radio" 
                                        name="privacy" 
                                        value="2" 
                                        class="mt-1 w-5 h-5 text-blue-600 border-zinc-300 dark:border-zinc-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-zinc-800"
                                    >
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-lock text-orange-600 dark:text-orange-400"></i>
                                            <span class="font-semibold text-zinc-900 dark:text-white">{{ __('Only Me') }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ __('Only you can see this post') }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload Area -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">
                                {{ __('Add Media') }}
                            </label>
                            
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="attachments" 
                                    name="attachments[]" 
                                    class="hidden" 
                                    multiple 
                                    onchange="previewFiles(this.files)" 
                                    accept="image/*"
                                >
                                
                                <label 
                                    for="attachments" 
                                    class="flex flex-col items-center justify-center cursor-pointer p-8 border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-zinc-800/50 transition-all duration-200 group"
                                >
                                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-image text-3xl text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="text-base font-semibold text-zinc-900 dark:text-white text-center">
                                        {{ __('Drop images here or click to browse') }}
                                    </span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                        {{ __('PNG, JPG, GIF up to 10MB') }}
                                    </span>
                                </label>
                            </div>

                            @error('attachments')
                                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-red-600 dark:text-red-400 text-sm flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        <!-- File Preview Grid -->
                        <div id="filePreview" class="mb-6"></div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary" class="flex-1 flex items-center">
                            {{ __('Flex it!') }}
                            <i class="fas fa-meteor text-xl mr-2"></i>
                        </flux:button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    let selectedFiles = [];

    // Drag and drop functionality
    const fileInput = document.getElementById('attachments');
    const uploadArea = fileInput.parentElement.parentElement;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-zinc-800/50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-zinc-800/50');
        });
    });

    uploadArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        previewFiles(files);
    });

    function previewFiles(files) {
        const filePreview = document.getElementById('filePreview');

        selectedFiles = [...selectedFiles, ...Array.from(files)];

        if (selectedFiles.length === 0) {
            filePreview.innerHTML = '';
            return;
        }

        filePreview.innerHTML = '';

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-2 md:grid-cols-3 gap-4';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative group';

                const img = document.createElement('img');
                img.src = reader.result;
                img.className = 'w-full h-32 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700';

                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black/40 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'bg-red-500 hover:bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center transition-all transform hover:scale-110';
                removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                removeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    imgContainer.remove();
                    selectedFiles = selectedFiles.filter((f, i) => i !== index);
                    if (selectedFiles.length === 0) {
                        grid.remove();
                    }
                });

                overlay.appendChild(removeBtn);
                imgContainer.appendChild(img);
                imgContainer.appendChild(overlay);
                grid.appendChild(imgContainer);
            });

            reader.readAsDataURL(file);
        });

        filePreview.appendChild(grid);
    }
</script>