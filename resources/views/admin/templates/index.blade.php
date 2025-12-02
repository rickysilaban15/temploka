@extends('layouts.admin')

@section('title', 'Manajemen Template')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Template</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Template Baru
            </a>
            <a href="{{ route('admin.templates.upload.form') }}" class="btn btn-success">
                <i class="fas fa-upload me-2"></i>Upload ZIP
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Template</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTemplates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Template Gratis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $freeTemplates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Template Premium</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $premiumTemplates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crown fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Template Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeTemplates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="templatesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr>
                            <td>
                                @if($template->thumbnail)
                                    <img src="{{ asset($template->thumbnail) }}" alt="{{ $template->name }}" 
                                         class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center py-2" style="width: 80px; height: 60px; line-height: 36px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $template->name }}</strong><br>
                                <small class="text-muted">{{ $template->slug }}</small>
                            </td>
                            <td>{{ $template->category->name ?? '-' }}</td>
                            <td>
                                @if($template->price == 0)
                                    <span class="badge bg-success">Gratis</span>
                                @else
                                    <span class="badge bg-warning">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @if($template->is_featured)
                                    <span class="badge bg-info">Featured</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $template->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('templates.show', $template->id) }}" 
                                       class="btn btn-sm btn-info" title="Preview" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.templates.edit', $template->id) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.templates.destroy', $template->id) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus template ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $templates->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img-thumbnail {
        object-fit: cover;
    }
</style>
@endpush