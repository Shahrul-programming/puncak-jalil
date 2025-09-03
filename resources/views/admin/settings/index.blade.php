                <!-- Upload Tab -->
                <div id="upload-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Upload Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="max_upload_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Upload Size (KB)</label>
                            <input type="number" id="max_upload_size" name="max_upload_size" value="{{ old('max_upload_size', $setting->max_upload_size) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="allowed_file_types" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allowed File Types (comma separated)</label>
                            <input type="text" id="allowed_file_types" name="allowed_file_types" value="{{ old('allowed_file_types', $setting->allowed_file_types) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
                <!-- Security Tab -->
                <div id="security-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Security Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Login Attempts</label>
                            <input type="number" id="max_login_attempts" name="max_login_attempts" value="{{ old('max_login_attempts', $setting->max_login_attempts) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="session_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Session Timeout (minit)</label>
                            <input type="number" id="session_timeout" name="session_timeout" value="{{ old('session_timeout', $setting->session_timeout) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
                <!-- Social Media Tab -->
                <div id="social-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Social Media Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook URL</label>
                            <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram URL</label>
                            <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
                <!-- Maintenance Tab -->
                <div id="maintenance-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Maintenance Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="maintenance_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Mode</label>
                            <select id="maintenance_mode" name="maintenance_mode" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                                <option value="0" {{ old('maintenance_mode', $setting->maintenance_mode) ? '' : 'selected' }}>Off</option>
                                <option value="1" {{ old('maintenance_mode', $setting->maintenance_mode) ? 'selected' : '' }}>On</option>
                            </select>
                        </div>
                        <div>
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Message</label>
                            <input type="text" id="maintenance_message" name="maintenance_message" value="{{ old('maintenance_message', $setting->maintenance_message) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-cogs text-blue-600 mr-3"></i>
                    System Settings
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Configure your website settings and preferences</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="POST" action="{{ route('admin.settings.reset') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to reset all settings to default values?')"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-undo mr-2"></i>Reset to Default
                    </button>
                </form>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Settings Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="showTab('general')" 
                            class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-home mr-2"></i>General
                    </button>
                    <button type="button" onclick="showTab('email')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </button>
                    <button type="button" onclick="showTab('upload')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                    <button type="button" onclick="showTab('security')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-shield-alt mr-2"></i>Security
                    </button>
                    <button type="button" onclick="showTab('social')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-share-alt mr-2"></i>Social Media
                    </button>
                    <button type="button" onclick="showTab('maintenance')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-tools mr-2"></i>Maintenance
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- General Tab -->
                <div id="general-tab" class="tab-content">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">General Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Laman</label>
                            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $setting->site_name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Laman</label>
                            <input type="text" id="site_description" name="site_description" value="{{ old('site_description', $setting->site_description) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon</label>
                            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
                <!-- Email Tab -->
                <div id="email-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Email Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Host</label>
                            <input type="text" id="smtp_host" name="smtp_host" value="{{ old('smtp_host', $setting->smtp_host) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Port</label>
                            <input type="text" id="smtp_port" name="smtp_port" value="{{ old('smtp_port', $setting->smtp_port) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Username</label>
                            <input type="text" id="smtp_username" name="smtp_username" value="{{ old('smtp_username', $setting->smtp_username) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Password</label>
                            <input type="password" id="smtp_password" name="smtp_password" value="{{ old('smtp_password', $setting->smtp_password) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Encryption</label>
                            <input type="text" id="smtp_encryption" name="smtp_encryption" value="{{ old('smtp_encryption', $setting->smtp_encryption) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Address</label>
                            <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $setting->mail_from_address) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Name</label>
                            <input type="text" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $setting->mail_from_name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        </div>
                    </div>
                </div>
                <!-- Tab lain boleh ditambah ikut column yang ada... -->
            </div>

            <!-- Submit Button -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex items-center justify-end space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection
