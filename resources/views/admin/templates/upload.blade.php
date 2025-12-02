@extends('layouts.admin')

@section('title', 'Upload Template dari ZIP')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Template dari ZIP</h1>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload Template</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.templates.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="zip_file" class="form-label">
                                <strong>File ZIP Template</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control @error('zip_file') is-invalid @enderror" 
                                   id="zip_file" name="zip_file" required accept=".zip">
                            <div class="form-text">
                                Upload file ZIP yang berisi template website (harus berisi file index.html)
                            </div>
                            @error('zip_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Template Info -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="name" class="form-label">
                                    <strong>Nama Template</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Contoh: Business Pro Template">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="price" class="form-label">
                                    <strong>Harga (IDR)</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required>
                                <div class="form-text">0 untuk template gratis</div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label">
                                <strong>Kategori</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <strong>Deskripsi</strong>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Deskripsi template...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <strong>Featured Template</strong>
                                    </label>
                                    <div class="form-text">
                                        Tandai sebagai template unggulan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="alert alert-info">
                            <h5 class="alert-heading mb-3"><i class="fas fa-info-circle me-2"></i>Struktur File ZIP yang Diperlukan:</h5>
                            <ul class="mb-0">
                                <li>File utama harus bernama <strong>index.html</strong></li>
                                <li>Folder <strong>css/</strong> untuk file stylesheet</li>
                                <li>Folder <strong>js/</strong> untuk file JavaScript</li>
                                <li>Folder <strong>images/</strong> untuk gambar (opsional)</li>
                                <li>Maksimum ukuran file: <strong>20MB</strong></li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const priceInput = document.getElementById('price');
        
        // Preview file name
        const zipFileInput = document.getElementById('zip_file');
        zipFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                const baseName = fileName.replace('.zip', '').replace('.ZIP', '');
                
                // Auto-fill template name if empty
                if (!nameInput.value) {
                    nameInput.value = baseName
                        .split(/[-_]/)
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                        .join(' ');
                }
            }
        });
    });
</script>
@endpush