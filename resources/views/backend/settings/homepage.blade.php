@extends('layouts.backend.app')

@section('title', 'Homepage Settings')

@section('content')
<div class="clearfix mb-4">
  <h4>Homepage Settings</h4>
</div>

@php
  $tab = request('tab', 'hero_banners');
  $tabs = [
    'hero_banners'           => [
      'label' => 'Hero Section Banner',
      'max' => 3,
      'icon' => 'bi-image',
      'recommendation' => 'Recommended size: 1200 x 480 px (Max: 2 MB)'
    ],
    // 'best_selling_banners'   => [
    //   'label' => 'Best Selling Banner',
    //   'max' => 3,
    //   'icon' => 'bi-stars',
    //   'recommendation' => 'Recommended size: 394 x 220 px (Aspect ratio ~ 16:9)'
    // ],
    // 'discounted_products_banner' => [
    //   'label' => 'Discounted Products',
    //   'max' => 1,
    //   'icon' => 'bi-lightning',
    //   'recommendation' => 'Recommended size: 285 x 200 px (Aspect ratio ~ 4:3 / 3:2)'
    // ],
    'delivery_charges' => [
      'label' => 'Global Delivery Charges',
      'max' => 1,
      'icon' => 'bi-truck',
      'recommendation' => 'Set default delivery charges for checkout.'
    ],
  ];
@endphp

<div class="row g-4">

  {{-- Left Tabs --}}
  <div class="col-md-3">
    <div class="stat-card p-0" style="overflow:hidden;">
      <div class="list-group list-group-flush rounded-3">
        @foreach($tabs as $key => $info)
          <a href="{{ route('admin.settings.homepage', ['tab' => $key]) }}"
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 px-3 {{ $tab === $key ? 'active' : '' }}">
            <i class="bi {{ $info['icon'] }}"></i>
            <span class="small fw-semibold">{{ $info['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Right Content --}}
  <div class="col-md-9">
    @foreach($tabs as $key => $info)
      @if($tab === $key)
        <div class="stat-card">
          <h5 class="fw-bold mb-1"><i class="bi {{ $info['icon'] }} me-2 text-primary"></i>{{ $info['label'] }}</h5>
          <p class="text-muted small mb-4">Maximum {{ $info['max'] }} {{ $info['max'] > 1 ? 'images' : 'image' }} allowed for this section.</p>

          @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
          @endif

          {{-- Current Settings / Dynamic Banner Slots --}}
          @php $current = $settings[$key] ?? []; @endphp

          @if($key === 'delivery_charges')
            <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}">
              @csrf
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label">Inside Dhaka Delivery Charge</label>
                  <input type="number" step="0.01" name="inside_dhaka" class="form-control" value="{{ $current['inside_dhaka'] ?? 60 }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Outside Dhaka Delivery Charge</label>
                  <input type="number" step="0.01" name="outside_dhaka" class="form-control" value="{{ $current['outside_dhaka'] ?? 120 }}" required>
                </div>
              </div>
              <button class="btn btn-primary" type="submit">Save Delivery Charges</button>
            </form>
          @elseif($key === 'hero_banners')
            <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}" enctype="multipart/form-data">
              @csrf
              @for ($i = 0; $i < 3; $i++)
                @php
                  $banner = $current[$i] ?? null;
                  $bannerImage = is_array($banner) ? ($banner['image'] ?? null) : $banner;
                  $bannerLabel = is_array($banner) ? ($banner['label'] ?? null) : null;
                  $bannerTitle = is_array($banner) ? ($banner['title'] ?? null) : null;
                  $bannerDesc = is_array($banner) ? ($banner['desc'] ?? null) : null;
                  $bannerLink = is_array($banner) ? ($banner['link'] ?? null) : null;
                @endphp
                <div class="card mb-4 p-3 border shadow-sm rounded" style="background: #fafafa;">
                  <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                    <span>Banner Slot {{ $i + 1 }}</span>
                    @if($bannerImage)
                      <span class="badge bg-success small text-white" style="background-color: #198754 !important;">Active</span>
                    @else
                      <span class="badge bg-secondary small text-white" style="background-color: #6c757d !important;">Empty</span>
                    @endif
                  </h6>
                  <div class="row g-3">
                    <div class="col-md-4 text-center border-end">
                      @if($bannerImage)
                        <img src="{{ asset('storage/' . $bannerImage) }}" alt="Banner {{ $i + 1 }}"
                             style="max-height:120px; width:100%; object-fit:cover; border-radius:8px; border:1px solid #ddd;" class="mb-2">
                        <input type="hidden" name="banners[{{ $i }}][existing_image]" value="{{ $bannerImage }}">
                        <div class="form-check justify-content-center d-flex mt-2">
                          <input class="form-check-input" type="checkbox" name="banners[{{ $i }}][delete]" id="delete_{{ $i }}">
                          <label class="form-check-label ms-2 text-danger small fw-semibold" for="delete_{{ $i }}">Remove Banner Slot</label>
                        </div>
                      @else
                        <div class="border rounded p-4 text-muted small bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                          <i class="bi bi-image me-2"></i> No Image Uploaded
                        </div>
                      @endif
                      <div class="mt-3 text-start">
                        <label class="form-label small fw-semibold d-block text-start">Upload / Replace Image</label>
                        <input type="file" name="banners[{{ $i }}][image]" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text small text-muted mt-1" style="font-size: 11px;">Recommended: 1200 x 480 px (Max: 2 MB)</div>
                      </div>
                    </div>
                    <div class="col-md-8 text-start">
                      <div class="row g-2">
                        <div class="col-md-6 mb-2">
                          <label class="form-label small fw-semibold">Label (e.g. NEW ARRIVAL)</label>
                          <input type="text" name="banners[{{ $i }}][label]" class="form-control form-control-sm" value="{{ $bannerLabel }}">
                        </div>
                        <div class="col-md-6 mb-2">
                          <label class="form-label small fw-semibold">Button URL Link (e.g. #products-grid)</label>
                          <input type="text" name="banners[{{ $i }}][link]" class="form-control form-control-sm" value="{{ $bannerLink }}">
                        </div>
                      </div>
                      <div class="mb-2">
                        <label class="form-label small fw-semibold">Heading Title (e.g. Modern Fashion Collection 2026)</label>
                        <input type="text" name="banners[{{ $i }}][title]" class="form-control form-control-sm" value="{{ $bannerTitle }}">
                      </div>
                      <div class="mb-0">
                        <label class="form-label small fw-semibold">Short Description</label>
                        <textarea name="banners[{{ $i }}][desc]" class="form-control form-control-sm" rows="2">{{ $bannerDesc }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              @endfor
              <button type="submit" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-save me-1"></i> Save Hero Banners
              </button>
            </form>
          @else
            {{-- Original form for other pages --}}
            @if(count($current) > 0)
              <div class="mb-4">
                <label class="form-label fw-semibold small">Current Images</label>
                <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}" id="delete-form-{{ $key }}">
                  @csrf
                  <div class="d-flex flex-wrap gap-3">
                    @foreach($current as $img)
                      <div class="position-relative">
                        <img src="{{ asset('storage/' . $img) }}" alt="Banner"
                             style="height:100px;width:160px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                        <button type="submit" name="delete_images[]" value="{{ $img }}"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-0"
                                style="width:22px;height:22px;font-size:11px;line-height:1;"
                                onclick="return confirm('Remove this image?')">
                          <i class="bi bi-x"></i>
                        </button>
                      </div>
                    @endforeach
                  </div>
                </form>
              </div>
            @endif

            @if(count($current) < $info['max'])
              <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}"
                    enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    Upload {{ $info['max'] > 1 ? 'Images' : 'Image' }}
                    <span class="text-muted fw-normal small">({{ $info['max'] - count($current) }} slot(s) remaining)</span>
                  </label>
                  <input type="file" name="images[]" class="form-control"
                         accept="image/*"
                         {{ $info['max'] - count($current) > 1 ? 'multiple' : '' }}
                         required style="border-color: #a1a1a1 !important;">
                  <div class="form-text d-flex align-items-center gap-1 mt-2 text-secondary">
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    <span>Accepted: JPG, PNG, WebP. <strong>{{ $info['recommendation'] }}</strong></span>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="bi bi-upload me-1"></i> Save Images
                </button>
              </form>
            @else
              <div class="alert alert-info py-2 small">
                <i class="bi bi-info-circle me-1"></i>
                Maximum images reached. Remove an existing image to upload a new one.
              </div>
            @endif
          @endif
        </div>
      @endif
    @endforeach
  </div>

</div>
@endsection

@push('styles')
<style>
  .list-group-item.active {
    background-color: #1a73e8 !important;
    border-color: #1a73e8 !important;
    color: #fff !important;
  }
  .list-group-item {
    border-left: none;
    border-right: none;
    transition: background .15s;
  }
  .list-group-item:first-child { border-top: none; }
</style>
@endpush
