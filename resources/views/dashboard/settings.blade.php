@extends('dashboard.layouts.app')

@section('title', 'Pengaturan & Akun - Temploka')

@section('content')
    <div class="p-4 sm:p-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Photo Profile Section -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-6 shadow-card">
                <div class="mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-black mb-2">Foto Profil</h2>
                    <p class="text-sm sm:text-base text-black">Unggah foto profil untuk akun Anda</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                    <!-- Profile Photo -->
                    <div class="relative">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl overflow-hidden profile-photo">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Profile Photo" class="w-full h-full object-cover" id="profileImage">
                            @else
                                <div id="profileInitials" class="w-full h-full flex items-center justify-center bg-primary text-white text-xl font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Upload Progress (Hidden by default) -->
                        <div id="uploadProgress" class="hidden absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center">
                            <div class="text-white text-sm">
                                <i class="fas fa-spinner fa-spin"></i>
                                <span class="hidden sm:inline">Uploading...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="w-full sm:w-auto">
                        <!-- Hidden File Input -->
                        <input type="file" id="profilePhotoInput" accept="image/jpeg,image/png,image/gif" class="hidden" />
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="button" onclick="document.getElementById('profilePhotoInput').click()" 
                                    class="w-full sm:w-auto border border-primary text-primary px-4 sm:px-5 py-3 rounded-2xl font-medium text-base sm:text-lg transition duration-150 hover:bg-primary-50">
                                <i class="fas fa-camera mr-2"></i>Ubah Foto
                            </button>
                            
                            @if(Auth::user()->avatar)
                            <button type="button" onclick="removeProfilePhoto()" 
                                    class="w-full sm:w-auto border border-red-500 text-red-500 px-4 sm:px-5 py-3 rounded-2xl font-medium text-base sm:text-lg transition duration-150 hover:bg-red-50">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mt-2 text-center sm:text-left">JPG, PNG atau GIF. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-6 shadow-card">
                <div class="mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-black mb-2">Informasi Pribadi</h2>
                    <p class="text-sm sm:text-base text-black">Perbarui informasi pribadi Anda</p>
                </div>

                <!-- PERBAIKI ACTION FORM -->
                <form class="space-y-4 sm:space-y-6" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-700">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="text-red-700">Terjadi kesalahan. Silakan periksa form.</span>
                            </div>
                        </div>
                    @endif

                    <!-- Name and Email Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm sm:text-base font-bold text-black mb-2">Nama lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                   class="w-full bg-gray-50 rounded-2xl px-4 sm:px-5 py-3 sm:py-4 focus:outline-none focus:ring-2 focus:ring-primary @error('name') border border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm sm:text-base font-bold text-black mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                   class="w-full bg-gray-50 rounded-2xl px-4 sm:px-5 py-3 sm:py-4 focus:outline-none focus:ring-2 focus:ring-primary @error('email') border border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone and Company Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-sm sm:text-base font-bold text-black mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                   class="w-full bg-gray-50 rounded-2xl px-4 sm:px-5 py-3 sm:py-4 focus:outline-none focus:ring-2 focus:ring-primary @error('phone') border border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Perusahaan -->
                        <div>
                            <label class="block text-sm sm:text-base font-bold text-black mb-2">Nama Perusahaan</label>
                            <input type="text" name="company" value="{{ old('company', Auth::user()->company ?? '') }}" 
                                   class="w-full bg-gray-50 rounded-2xl px-4 sm:px-5 py-3 sm:py-4 focus:outline-none focus:ring-2 focus:ring-primary @error('company') border border-red-500 @enderror">
                            @error('company')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-sm sm:text-base font-bold text-black mb-2">Bio</label>
                        <textarea name="bio" rows="6" 
                                  class="w-full bg-gray-50 rounded-2xl px-4 sm:px-5 py-3 sm:py-4 min-h-[120px] sm:min-h-[180px] focus:outline-none focus:ring-2 focus:ring-primary @error('bio') border border-red-500 @enderror">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Profile Photo (hidden) -->
                    <input type="hidden" name="avatar" id="avatarInput" value="{{ Auth::user()->avatar }}">

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-primary border border-primary text-white px-4 sm:px-5 py-3 sm:py-4 rounded-2xl font-medium text-base sm:text-lg transition duration-150 hover:bg-teal-700">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const avatarInput = document.getElementById('avatarInput');
    const uploadProgress = document.getElementById('uploadProgress');

    // Handle profile photo upload
    profilePhotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
            console.log('No file selected');
            return;
        }

        console.log('File selected:', file.name, file.type, file.size);

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showToast('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.', 'error');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file maksimal 2MB.', 'error');
            return;
        }

        // Show upload progress
        uploadProgress.classList.remove('hidden');
        console.log('Upload progress shown');

        // Create FormData for AJAX upload
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');

        console.log('FormData created, sending request...');

        // Upload via AJAX
        fetch('{{ route("profile.upload-avatar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response received:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update avatar input value
                avatarInput.value = data.avatar_path;
                
                // Update profile image display
                const profileContainer = document.querySelector('.profile-photo');
                profileContainer.innerHTML = `
                    <img src="${data.avatar_url}?t=${new Date().getTime()}" 
                         alt="Profile Photo" 
                         class="w-full h-full object-cover" 
                         id="profileImage">
                `;
                
                // Show success message
                showToast('Foto profil berhasil diupload!', 'success');
                console.log('Avatar updated successfully');
            } else {
                throw new Error(data.message || 'Upload gagal');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showToast('Gagal mengupload foto: ' + error.message, 'error');
        })
        .finally(() => {
            // Hide upload progress
            uploadProgress.classList.add('hidden');
            // Reset file input
            profilePhotoInput.value = '';
            console.log('Upload process completed');
        });
    });
});

// Remove profile photo
function removeProfilePhoto() {
    if (!confirm('Yakin ingin menghapus foto profil?')) return;

    console.log('Removing profile photo...');

    fetch('{{ route("profile.remove-avatar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Remove response:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Remove response data:', data);
        if (data.success) {
            // Clear avatar input
            document.getElementById('avatarInput').value = '';
            
            // Reset to initials
            const profileContainer = document.querySelector('.profile-photo');
            const userName = '{{ Auth::user()->name }}';
            const nameParts = userName.split(' ');
            const initials = (nameParts[0]?.charAt(0) || '') + (nameParts[1]?.charAt(0) || '');
            
            profileContainer.innerHTML = `
                <div id="profileInitials" class="w-full h-full flex items-center justify-center bg-primary text-white text-xl font-bold">
                    ${initials || 'U'}
                </div>
            `;
            
            showToast('Foto profil berhasil dihapus!', 'success');
            console.log('Avatar removed successfully');
        } else {
            throw new Error(data.message || 'Hapus gagal');
        }
    })
    .catch(error => {
        console.error('Remove error:', error);
        showToast('Gagal menghapus foto: ' + error.message, 'error');
    });
}

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('[class*="fixed top-4 right-4"]').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-3 sm:p-4 rounded-xl text-white font-medium transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle mr-2"></i>
            <span class="text-sm sm:text-base">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Debug info
console.log('Profile settings loaded');
console.log('CSRF Token:', '{{ csrf_token() }}');
console.log('Upload URL:', '{{ route("profile.upload-avatar") }}');
console.log('Remove URL:', '{{ route("profile.remove-avatar") }}');
console.log('Current avatar:', '{{ Auth::user()->avatar }}');
</script>
@endpush

@push('styles')
<style>
    .profile-photo {
        transition: all 0.3s ease;
    }
    
    .profile-photo:hover {
        transform: scale(1.05);
    }
    
    input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 150, 137, 0.2);
    }
    
    /* Custom scrollbar for textarea */
    textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 640px) {
        .grid-cols-1.sm\:grid-cols-2 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
@endpush