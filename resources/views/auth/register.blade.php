<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-6">
            <x-input-label :value="__('Pilih Jenis Akaun')" />
            <div class="mt-2 space-y-3">
                <div class="flex items-center">
                    <input id="role_user" name="role" type="radio" value="user"
                           {{ (old('role', request('role', 'user')) === 'user') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <label for="role_user" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="font-semibold">Pengguna Biasa</span>
                        <span class="block text-xs text-gray-500">Cari kedai, baca ulasan, buat laporan masalah</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role_vendor" name="role" type="radio" value="vendor"
                           {{ (old('role', request('role')) === 'vendor') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <label for="role_vendor" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="font-semibold">Peniaga/Vendor</span>
                        <span class="block text-xs text-gray-500">Daftar kedai, buat promosi, urus ulasan pelanggan</span>
                    </label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Vendor Fields (Hidden by default) -->
        <div id="vendor-fields" class="mt-6 space-y-4 {{ (old('role', request('role')) === 'vendor') ? '' : 'hidden' }}">
            <div class="border-t pt-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Maklumat Perniagaan</h3>

                <!-- Business Name -->
                <div>
                    <x-input-label for="business_name" :value="__('Nama Perniagaan')" />
                    <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" autocomplete="organization" />
                    <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                </div>

                <!-- Business Type -->
                <div class="mt-4">
                    <x-input-label for="business_type" :value="__('Jenis Perniagaan')" />
                    <select id="business_type" name="business_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Pilih jenis perniagaan</option>
                        <option value="kedai makan" {{ old('business_type') === 'kedai makan' ? 'selected' : '' }}>Kedai Makan</option>
                        <option value="kedai runcit" {{ old('business_type') === 'kedai runcit' ? 'selected' : '' }}>Kedai Runcit</option>
                        <option value="perkhidmatan" {{ old('business_type') === 'perkhidmatan' ? 'selected' : '' }}>Perkhidmatan</option>
                        <option value="kesihatan & kecantikan" {{ old('business_type') === 'kesihatan & kecantikan' ? 'selected' : '' }}>Kesihatan & Kecantikan</option>
                        <option value="pendidikan" {{ old('business_type') === 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="lain-lain" {{ old('business_type') === 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                    <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('Nombor Telefon')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const vendorFields = document.getElementById('vendor-fields');

            function toggleVendorFields() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                if (selectedRole === 'vendor') {
                    vendorFields.classList.remove('hidden');
                    // Make vendor fields required when vendor is selected
                    document.getElementById('business_name').setAttribute('required', 'required');
                    document.getElementById('business_type').setAttribute('required', 'required');
                    document.getElementById('phone').setAttribute('required', 'required');
                } else {
                    vendorFields.classList.add('hidden');
                    // Remove required when user is selected
                    document.getElementById('business_name').removeAttribute('required');
                    document.getElementById('business_type').removeAttribute('required');
                    document.getElementById('phone').removeAttribute('required');
                }
            }

            roleRadios.forEach(radio => {
                radio.addEventListener('change', toggleVendorFields);
            });

            // Initial check
            toggleVendorFields();
        });
    </script>
</x-guest-layout>
