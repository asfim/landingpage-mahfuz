@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="category-page py-5" style="background: #f8f9fa; min-height: 70vh;">
    <div class="wrap container">
        <!-- Breadcrumb / Header -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Shop</li>
            </ol>
        </nav>

        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h1 class="fw-bold mb-1 text-dark" style="font-size: 2.2rem; letter-spacing: -0.5px;">
                    Shop All Products
                </h1>
                <p class="text-muted mb-0">Explore our complete collection of premium products</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center gap-3">
                <span class="badge bg-dark px-3 py-2 fs-6 rounded-pill">{{ $products->total() }} Products</span>
                <form id="sortForm" action="{{ route('shop') }}" method="GET" class="d-inline-block">
                    <!-- Preserve existing filter query params -->
                    @if(request()->has('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request()->has('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request()->has('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                    
                    <select name="sort" class="form-select form-select-sm border-secondary shadow-sm" onchange="document.getElementById('sortForm').submit();" style="border-radius: 8px;">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-5 mb-lg-0">
                <div class="sticky-top" style="top: 120px; z-index: 10;">
                    <form action="{{ route('shop') }}" method="GET" id="filterForm">
                        <!-- Preserve sorting -->
                        @if(request()->has('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h5 class="fw-bold mb-4 border-bottom pb-3">Filters</h5>
                            
                            <!-- Category Filter -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 text-uppercase text-muted small">Categories</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" id="shop_filter_cat_all" value="" {{ empty(request('category')) ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                    <label class="form-check-label" for="shop_filter_cat_all">All Categories</label>
                                </div>
                                @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" id="shop_filter_cat_{{ $category->id }}" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                    <label class="form-check-label" for="shop_filter_cat_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <!-- Price Filter -->
                            <div>
                                <h6 class="fw-semibold mb-3 text-uppercase text-muted small">Price Range</h6>
                                
                                <div class="price-slider-wrapper mb-3" style="position: relative; height: 30px;">
                                    <input type="range" id="priceMinRange" class="form-range" min="0" max="200000" step="50" value="{{ request('min_price', 0) }}" style="position: absolute; width: 100%; z-index: 2; opacity: 0; cursor: pointer;">
                                    <input type="range" id="priceMaxRange" class="form-range" min="0" max="200000" step="50" value="{{ request('max_price', 200000) }}" style="position: absolute; width: 100%; z-index: 2; opacity: 0; cursor: pointer;">
                                    
                                    <div class="slider-track" style="width: 100%; height: 5px; background: #e9ecef; position: absolute; top: 12px; border-radius: 5px; z-index: 1;">
                                        <div id="sliderProgress" style="height: 100%; background: #b33e0f; border-radius: 5px; position: absolute;"></div>
                                    </div>
                                    <div id="minThumb" style="width: 15px; height: 15px; background: #b33e0f; border-radius: 50%; position: absolute; top: 7px; z-index: 3; pointer-events: none;"></div>
                                    <div id="maxThumb" style="width: 15px; height: 15px; background: #b33e0f; border-radius: 50%; position: absolute; top: 7px; z-index: 3; pointer-events: none;"></div>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <input type="number" name="min_price" id="minPriceInput" class="form-control form-control-sm text-center fw-bold" placeholder="Min ৳" value="{{ request('min_price', 0) }}" readonly>
                                    <span class="text-muted">-</span>
                                    <input type="number" name="max_price" id="maxPriceInput" class="form-control form-control-sm text-center fw-bold" placeholder="Max ৳" value="{{ request('max_price', 200000) }}" readonly>
                                </div>
                                <button type="submit" class="btn btn-outline-dark btn-sm w-100 rounded-pill fw-semibold">Apply Price</button>
                            </div>
                        </div>
                        
                        @if(request()->has('category') || request()->has('min_price') || request()->has('max_price'))
                            <a href="{{ route('shop') }}" class="btn btn-link text-danger text-decoration-none w-100"><i class="bi bi-x-circle me-1"></i> Clear All Filters</a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products Column -->
            <div class="col-lg-9">

        @if($products->isEmpty())
            <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                <i class="bi bi-box2 text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                <h4 class="text-muted fw-bold">No Products Found</h4>
                <p class="text-muted">We couldn't find any products in our store at the moment. Please check back later!</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 mt-2 rounded-pill fw-semibold">Back to Home</a>
            </div>
        @else
            <!-- Products Grid -->
            <div class="row g-3">
                @foreach($products as $product)
                    @include('frontend.partials.product_card', ['product' => $product])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const minRange = document.getElementById('priceMinRange');
    const maxRange = document.getElementById('priceMaxRange');
    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    const progress = document.getElementById('sliderProgress');
    const minThumb = document.getElementById('minThumb');
    const maxThumb = document.getElementById('maxThumb');

    function updateSlider() {
        let minVal = parseInt(minRange.value);
        let maxVal = parseInt(maxRange.value);

        if (minVal > maxVal) {
            let tmp = minVal;
            minVal = maxVal;
            maxVal = tmp;
        }

        minInput.value = minVal;
        maxInput.value = maxVal;

        let maxTotal = parseInt(minRange.max);
        let minPercent = (minVal / maxTotal) * 100;
        let maxPercent = (maxVal / maxTotal) * 100;

        progress.style.left = minPercent + '%';
        progress.style.width = (maxPercent - minPercent) + '%';
        
        // Thumb position adjustment (thumb width is 15px)
        minThumb.style.left = `calc(${minPercent}% - 7.5px)`;
        maxThumb.style.left = `calc(${maxPercent}% - 7.5px)`;
    }

    minRange.addEventListener('input', updateSlider);
    maxRange.addEventListener('input', updateSlider);

    // Init
    updateSlider();
});
</script>
@endsection
