@extends('layouts.backend.app')

@section('title', isset($landingPage) ? 'Edit Landing Page' : 'Create Landing Page')

@section('content')
@php
    // Detect if this is a variant-based product
    $isVariantProduct = !empty($product->variants);
    
    $defaultOldPrice = $product->price;
    $defaultNewPrice = $product->price;

    if (!$isVariantProduct && $product->has_active_discount) {
        if ($product->discount_type === 'percent') {
            $defaultNewPrice = $product->price - ($product->price * $product->discount_value / 100);
        } else {
            $defaultNewPrice = $product->price - $product->discount_value;
        }
        $defaultNewPrice = max(0, $defaultNewPrice);
    }
    
    // For variants, we will calculate defaults inside the loop

    $defaultNewPrice = $landingPage->new_price ?? $defaultNewPrice;
    $defaultOldPrice = $landingPage->old_price ?? $defaultOldPrice;
@endphp
<div class="clearfix mb-4">
    <h4>{{ isset($landingPage) ? 'Edit Landing Page' : 'Create Landing Page' }} for: <span class="text-primary">{{ $product->name }}</span></h4>
</div>

<div class="stat-card">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($landingPage) ? route('admin.products.landing-page.update', $product) : route('admin.products.landing-page.store', $product) }}" enctype="multipart/form-data">
        @csrf
        @if(isset($landingPage))
            @method('PUT')
        @endif

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">General Configuration</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $landingPage->meta_title ?? $product->name) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tagline (Pill text)</label>
                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $landingPage->tagline ?? ($defaultTagline ?? 'প্রিমিয়াম কালেকশন ২০২৬')) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Main Heading</label>
                        <input type="text" name="heading" class="form-control" value="{{ old('heading', $landingPage->heading ?? ($defaultHeading ?? '৫টি ভিন্ন ডিজাইন এক প্যাকেজে')) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Offer / Badge Text</label>
                        <input type="text" name="offer_text" class="form-control" value="{{ old('offer_text', $landingPage->offer_text ?? ($defaultOfferText ?? 'কম্বো অফার – ৫টি টি-শার্ট')) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (Subtitle)</label>
                    <textarea name="description" class="form-control" rows="2" required style="border-color: #a1a1a1 !important;">{{ old('description', $landingPage->description ?? ($defaultDescription ?? 'প্রিমিয়াম কম্বড কটন • প্রতিটি টি-শার্ট আলাদা স্টাইল • সীমিত সংস্করণ')) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery Note (e.g. ফ্রি এক্সপ্রেস ডেলিভারি)</label>
                        <input type="text" name="delivery_text" class="form-control" value="{{ old('delivery_text', $landingPage->delivery_text ?? 'ফ্রি এক্সপ্রেস ডেলিভারি') }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Return Note (e.g. ৩০ দিন রিটার্ন)</label>
                        <input type="text" name="return_text" class="form-control" value="{{ old('return_text', $landingPage->return_text ?? '৩০ দিন রিটার্ন') }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Pricing & Stock</h5>
            </div>
            <div class="card-body">
                @if($isVariantProduct)
                {{-- Per-Variant Pricing Table --}}
                @php
                    $activeVariants = array_filter($product->variants ?? [], fn($v) => !empty($v['active']));
                    $savedVariantPrices = $landingPage->variant_prices ?? [];
                @endphp
                <div class="alert alert-info d-flex align-items-center gap-2 mb-3" style="border-radius:10px;">
                    <i class="fas fa-layer-group"></i>
                    <div><strong>Variant Product:</strong> প্রতিটি variant-এর জন্য আলাদা দাম সেট করুন। Landing page-এ variant select করলে সেই দাম দেখাবে।</div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle" style="border-radius:10px; overflow:hidden;">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 60px;">Image</th>
                                <th>Variant (Combo)</th>
                                <th>SKU</th>
                                <th>Old Price (৳)</th>
                                <th>Sell Price (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeVariants as $idx => $variant)
                            @php
                                $sku = $variant['sku'] ?? $idx;
                                $comboLabel = implode(', ', array_map(fn($k,$v) => strtoupper($k).': '.strtoupper($v), array_keys($variant['combo'] ?? []), array_values($variant['combo'] ?? [])));
                                $savedVP = collect($savedVariantPrices)->firstWhere('sku', $sku) ?? [];
                                
                                $vPrice = (float)($variant['price'] ?? 0);
                                $vDiscount = (float)($variant['discount'] ?? 0);
                                $vDiscountType = $variant['discount_type'] ?? 'percent';
                                
                                $vSell = $vPrice;
                                if ($vDiscount > 0) {
                                    if ($vDiscountType === 'percent') {
                                        $vSell = $vPrice - ($vPrice * ($vDiscount / 100));
                                    } else {
                                        $vSell = $vPrice - $vDiscount;
                                    }
                                    $vSell = max(0, $vSell);
                                }
                                $defaultVariantOldPrice = $vPrice;
                                $defaultVariantSellPrice = $vSell;
                            @endphp
                            <tr>
                                <td>
                                    @if(!empty($variant['image']))
                                        <img src="{{ asset('storage/' . $variant['image']) }}" alt="Variant Image" class="img-thumbnail" style="width:40px; height:40px; object-fit:cover;">
                                    @else
                                        <div class="bg-light text-center border rounded d-flex align-items-center justify-content-center text-muted" style="width:40px; height:40px; font-size:10px;">No Img</div>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $comboLabel ?: $sku }}</span></td>
                                <td><strong>{{ $sku }}</strong><input type="hidden" name="variant_prices[{{ $idx }}][sku]" value="{{ $sku }}"></td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                        name="variant_prices[{{ $idx }}][old_price]"
                                        class="form-control form-control-sm"
                                        value="{{ old("variant_prices.{$idx}.old_price", $savedVP['old_price'] ?? $defaultVariantOldPrice) }}"
                                        placeholder="যেমন: 1500">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                        name="variant_prices[{{ $idx }}][price]"
                                        class="form-control form-control-sm"
                                        value="{{ old("variant_prices.{$idx}.price", $savedVP['price'] ?? $defaultVariantSellPrice) }}"
                                        placeholder="যেমন: 999">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @else
                {{-- Default Prices (Only for Simple Products) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Old Price (Regular Price)</label>
                        <input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price', $defaultOldPrice ?: '') }}" placeholder="যেমন: 1500" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Price (Sale Price)</label>
                        <input type="number" step="0.01" name="new_price" class="form-control" value="{{ old('new_price', $defaultNewPrice ?: '') }}" placeholder="যেমন: 999" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Discount Badge Text</label>
                        <input type="text" name="discount_text" class="form-control" value="{{ old('discount_text', $landingPage->discount_text ?? '') }}" placeholder="যেমন: বাঁচাচ্ছেন ৳৫০১" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inside Dhaka Delivery Charge</label>
                        <input type="number" step="0.01" name="inside_dhaka_charge" class="form-control" value="{{ old('inside_dhaka_charge', $landingPage->inside_dhaka_charge ?? 60) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Outside Dhaka Delivery Charge</label>
                        <input type="number" step="0.01" name="outside_dhaka_charge" class="form-control" value="{{ old('outside_dhaka_charge', $landingPage->outside_dhaka_charge ?? 120) }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Stock Warning Message</label>
                        <input type="text" name="stock_text" class="form-control" value="{{ old('stock_text', $landingPage->stock_text ?? 'মাত্র ২৫টি প্যাকেজ বাকি') }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">WhatsApp Connection</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp Number (with country code, e.g. 8801966789123)</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $landingPage->whatsapp_number ?? '8801966789123') }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp Message Template</label>
                        <input type="text" name="whatsapp_text" class="form-control" value="{{ old('whatsapp_text', $landingPage->whatsapp_text ?? 'I want to order the 5 Premium Tee pack') }}" required style="border-color: #a1a1a1 !important;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Media (Hero Image)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Custom Landing Page Image <small class="text-muted">(Optional, falls back to product image)</small></label>
                        <input type="file" name="image" class="form-control" accept="image/*" style="border-color: #a1a1a1 !important;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Current Preview</label>
                        @if(isset($landingPage) && $landingPage->image)
                            <img src="{{ asset('storage/' . $landingPage->image) }}" class="rounded border" style="height: 100px; object-fit: cover;">
                        @elseif($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded border" style="height: 100px; object-fit: cover;">
                            <div class="form-text text-muted">Currently using main product image</div>
                        @else
                            <div class="text-muted">No image uploaded</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Features Grid (6 Items)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @for($i = 0; $i < 6; $i++)
                        @php
                            $feature = isset($landingPage) && !empty($landingPage->features) ? ($landingPage->features[$i] ?? null) : ($defaultFeatures[$i] ?? null);
                        @endphp
                        <div class="col-md-4 mb-4 border-bottom pb-3">
                            <h6 class="fw-bold mb-2">Feature Slot #{{ $i + 1 }}</h6>
                            <div class="mb-2">
                                <label class="form-label small">FontAwesome Icon Class</label>
                                <input type="text" name="features[{{ $i }}][icon]" class="form-control form-control-sm" value="{{ old('features.'.$i.'.icon', $feature['icon'] ?? 'fas fa-tshirt') }}" required style="border-color: #a1a1a1 !important;">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Title</label>
                                <input type="text" name="features[{{ $i }}][title]" class="form-control form-control-sm" value="{{ old('features.'.$i.'.title', $feature['title'] ?? '') }}" required style="border-color: #a1a1a1 !important;">
                            </div>
                            <div>
                                <label class="form-label small">Description</label>
                                <textarea name="features[{{ $i }}][description]" class="form-control form-control-sm" rows="2" required style="border-color: #a1a1a1 !important;">{{ old('features.'.$i.'.description', $feature['description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Testimonials (3 Items)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @for($i = 0; $i < 3; $i++)
                        @php
                            $testimonial = isset($landingPage) && !empty($landingPage->testimonials) ? ($landingPage->testimonials[$i] ?? null) : ($defaultTestimonials[$i] ?? null);
                        @endphp
                        <div class="col-md-4 mb-3">
                            <h6 class="fw-bold mb-2">Testimonial #{{ $i + 1 }}</h6>
                            <div class="mb-2">
                                <label class="form-label small">Rating (Stars, e.g., 5)</label>
                                <select name="testimonials[{{ $i }}][rating]" class="form-select form-select-sm" required>
                                    @foreach(['5' => '5 Stars', '4.5' => '4.5 Stars', '4' => '4 Stars', '3.5' => '3.5 Stars', '3' => '3 Stars'] as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('testimonials.'.$i.'.rating', $testimonial['rating'] ?? '5') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Author & Location</label>
                                <input type="text" name="testimonials[{{ $i }}][author]" class="form-control form-control-sm" value="{{ old('testimonials.'.$i.'.author', $testimonial['author'] ?? '') }}" required style="border-color: #a1a1a1 !important;">
                            </div>
                            <div>
                                <label class="form-label small">Review Content</label>
                                <textarea name="testimonials[{{ $i }}][text]" class="form-control form-control-sm" rows="3" required style="border-color: #a1a1a1 !important;">{{ old('testimonials.'.$i.'.text', $testimonial['text'] ?? '') }}</textarea>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card mb-4 border border-secondary border-opacity-25">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Custom Scripts</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Header Script <small class="text-muted">(Inside &lt;head&gt; tags, useful for FB Pixel, GTM, etc.)</small></label>
                        <textarea name="header_script" class="form-control" rows="4" style="border-color: #a1a1a1 !important; font-family: monospace;">{{ old('header_script', $landingPage->header_script ?? '') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Body Script <small class="text-muted">(Inside &lt;body&gt; tags)</small></label>
                        <textarea name="body_script" class="form-control" rows="4" style="border-color: #a1a1a1 !important; font-family: monospace;">{{ old('body_script', $landingPage->body_script ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $landingPage->is_active ?? true) ? 'checked' : '' }} id="isActiveChk">
            <label class="form-check-label fw-bold" for="isActiveChk">Landing Page Active / Published</label>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($landingPage) ? 'Update' : 'Create' }} Landing Page</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
