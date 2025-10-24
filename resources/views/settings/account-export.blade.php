<x-layouts.app :title="__('Posts')">
    <div class="relative flex flex-col gap-3">
        <div class="relative mb-6 w-full hidden lg:block">



            <div class="max-w-2xl mx-auto mt-12 bg-white dark:bg-zinc-800 shadow-lg rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    Export Your Account Data
                </h2>

                <p class="text-zinc-600 dark:text-zinc-300 mb-6">
                    You can request a copy of all the information associated with your account, including
                    your profile details, posts, attachments, comments, and social interactions.
                    A downloadable <code>.zip</code> file will be generated after verification.
                </p>

                @if (session('status'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-lg mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-lg mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('account.export') }}" class="space-y-6">
                    @csrf

                    <div>
                        <flux:input
                            :label="__('Confirm with Password')"
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            viewable
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <flux:button
                            type="submit"
                            variant="primary"
                        >
                            Export My Data
                        </flux:button>

                        <a href="{{ url()->previous() }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">
                            Cancel
                        </a>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                        ⚠️ This may take a few minutes depending on the amount of your data.
                        Keep this window open until the download begins.
                    </p>
                </form>

                 <form method="POST" action="{{route('account.test-export')}}" class="flex flex-col gap-3">
                    @csrf
                    <div class="flex items-center justify-end gap-2">
                        <flux:button variant="primary" type="submit">{{ __('Test Export') }}</flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>