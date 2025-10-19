<x-layouts.app :title="__('New Flex')">
    <div class="relative">
        <div class="relative mb-6 w-full hidden md:block">
            <div class="flex justify-between">
                <div class="">
                    <flux:heading size="xl" level="1">{{ __('Create New Post') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Flex your ideas to the world!') }}</flux:subheading>
                </div>
            </div>

            <flux:separator variant="subtle" />
            
        </div>


        <div class="relative">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4">
                <div class="md:hidden flex items-center justify-between">
                    <flux:heading size="lg" level="2" class="text-center font-bold">
                        {{ __('Create New Post') }}
                    </flux:heading>
                    <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="" class="w-7 h-7 rounded-full inline border">
                </div>

                <form action="{{ route('posts.store')}}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf

                    <flux:input
                        :label="__('Caption')"
                        type="text"
                        name="caption"
                        :placeholder="__('Flex Caption')"
                    />

                    <flux:textarea
                        :label="__('Description')"
                        name="description"
                        :placeholder="__('Write anything...')"
                        class="resize-none"
                    ></flux:textarea>

                    <div>
                        <div id="filePreview" class="mt-4"></div>
                        <div class="flex items-center justify-center bg-gray-100 dark:bg-zinc-700 rounded-lg py-4">
                            <label for="attachments" class="flex flex-col items-center justify-center cursor-pointer">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-500 text-white rounded-full mb-2 transition-colors duration-200 hover:bg-blue-600">
                                    <i class="fas fa-plus text-2xl"></i>
                                </div>
                                <span class="text-sm text-gray-700 dark:text-zinc-100">Add photos</span>
                            </label>
                            <input type="file" id="attachments" name="attachments[]" class="hidden" multiple onchange="previewFiles(this.files)" accept="image/*">
                        </div>
                        @error('attachments')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <flux:button type="submit" variant="primary">
                        {{ __('Flex it!') }}
                    </flux:button>
                </form>
                
            </div>
        </div>

        
    </div>
</x-layouts.app>

<script>
    let selectedFiles = [];

    function previewFiles(files) {
        const filePreview = document.getElementById('filePreview');
        filePreview.innerHTML = '';

        selectedFiles = [...selectedFiles, ...files];

        if (selectedFiles.length === 0) {
            return;
        }

        selectedFiles.forEach((file) => {
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                const imgPreview = document.createElement('div');
                imgPreview.classList.add('m-2', 'relative', 'inline-block');

                const img = document.createElement('img');
                img.src = reader.result;
                img.classList.add('max-h-32', 'object-contain', 'rounded-lg', 'border', 'border-blue-800');

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('transform', 'translate-x-2', '-translate-y-2', 'absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center', 'focus:outline-none', 'hover:bg-red-600', 'transition-colors', 'duration-200');
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', () => {
                    imgPreview.remove();
                    selectedFiles = selectedFiles.filter(f => f !== file);
                });

                imgPreview.appendChild(img);
                imgPreview.appendChild(removeBtn);
                filePreview.appendChild(imgPreview);
            });

            reader.readAsDataURL(file);
        });
    }

</script>