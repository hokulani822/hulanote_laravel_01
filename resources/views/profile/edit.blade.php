<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
        .bg-plumeria {
            background-image: url('{{ asset('images/plumeria_background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .text-soft-brown { color: #8B7355; }
        .border-soft-brown { border-color: #8B7355; }
        .bg-soft-brown { background-color: #D2B48C; }
        .bg-lavender { background-color: rgba(230, 230, 250, 0.85); }
        body, h2, button, input { font-family: 'Noto Sans JP', sans-serif; }
        .title-font { font-family: 'Dancing Script', cursive; }
    </style>
    <div class="min-h-screen bg-plumeria flex justify-center items-center py-12 px-4">
        <div class="w-full max-w-4xl">
            <div class="bg-lavender rounded-lg shadow-lg p-8 mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        {{ __('Profile') }}
                    </h1>
                </div>

                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown mb-6">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-4">{{ __('Profile Information') }}</h2>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown mb-6">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-4">{{ __('Update Password') }}</h2>
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-4">{{ __('Delete Account') }}</h2>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>