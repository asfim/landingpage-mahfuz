<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $landingPage->meta_title ?? $product->name }}</title>
  <!-- Bootstrap 5 + Icons + Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    html, body {
      font-family: 'Inter', sans-serif;
      background: #f6f8fb;
      color: #1a2535;
      scroll-behavior: smooth;
      overflow-x: hidden;
      width: 100%;
      max-width: 100%;
      line-height: 1.65;
    }
    section {
      overflow-x: hidden;
    }
    :root {
      --accent: #b33e0f;
      --accent-light: #e95f2b;
      --dark-bg: #0a1a2b;
      --card-border: 1px solid rgba(0,0,0,0.06);
      --shadow-soft: 0 12px 30px -10px rgba(0,0,0,0.08);
      --section-bg-alt: #f0f4fa;
      --primary-color: #b33e0f;
    }
    /* ===== SECTION HEADER STYLE ===== */
    .section-title {
      font-size: 2rem;
      font-weight: 800;
      color: #1a2535;
      letter-spacing: -0.02em;
      margin-bottom: 0.5rem;
    }
    .section-title span { color: var(--accent); }
    .section-subtitle {
      color: #64748b;
      font-size: 1.05rem;
      max-width: 500px;
      margin: 0 auto;
    }
    .section-pill {
      display: inline-block;
      background: #fff2ee;
      color: var(--accent);
      border: 1px solid #f5cbb7;
      border-radius: 100px;
      padding: 4px 16px;
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 12px;
    }
    /* ===== TEXT ACCENT ===== */
    .text-accent { color: var(--accent) !important; }
    .btn-accent {
      background: linear-gradient(145deg, #b33e0f, #942f08);
      border: none;
      padding: 14px 36px;
      border-radius: 60px;
      font-weight: 700;
      color: #fff;
      box-shadow: 0 10px 22px -8px rgba(179,62,15,0.4);
      transition: all 0.25s ease;
    }
    .btn-accent:hover {
      transform: scale(1.03) translateY(-3px);
      box-shadow: 0 18px 30px -10px rgba(179,62,15,0.6);
      color: #fff;
    }
    .btn-outline-premium {
      background: transparent;
      border: 2px solid rgba(255,255,255,0.3);
      backdrop-filter: blur(4px);
      padding: 12px 28px;
      border-radius: 60px;
      font-weight: 600;
      color: #fff;
      transition: 0.25s;
    }
    .btn-outline-premium:hover {
      background: rgba(255,255,255,0.08);
      border-color: #fff;
      color: #fff;
      transform: translateY(-2px);
    }
    .premium-card {
      background: #ffffff;
      border-radius: 40px;
      border: var(--card-border);
      box-shadow: var(--shadow-soft);
      transition: all 0.3s cubic-bezier(0.2,0.9,0.4,1.1);
      overflow: hidden;
    }
    .premium-card:hover {
      transform: translateY(-8px);
      border-color: rgba(179,62,15,0.25);
      box-shadow: 0 24px 48px -16px rgba(0,0,0,0.15);
    }
    .hero-glow {
      background: radial-gradient(circle at 80% 20%, rgba(255,215,140,0.08) 0%, transparent 50%),
                  radial-gradient(circle at 20% 80%, rgba(179,62,15,0.15), transparent 60%),
                  linear-gradient(135deg, #082b1a 0%, #0f462b 45%, #1c5e3a 100%);
      border-bottom: 4px solid #b33e0f;
    }
    .floating-soft {
      animation: floatSoft 5s ease-in-out infinite;
    }
    @keyframes floatSoft {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    .timer-section-wrap {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      border-radius: 24px;
      padding: 1.4rem 2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      border: 1px solid rgba(255,255,255,0.08);
    }
    .time-block {
      background: rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 0.8rem 1.2rem;
      min-width: 80px;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.12);
      backdrop-filter: blur(4px);
    }
    .time-number {
      font-weight: 800;
      font-size: 2.4rem;
      background: linear-gradient(135deg, #fff 40%, #f59e0b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: 2px;
      line-height: 1;
    }
    .time-label {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.55);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 4px;
    }
    .time-sep {
      font-size: 2rem;
      font-weight: 800;
      color: #f59e0b;
      align-self: flex-start;
      padding-top: 0.6rem;
      line-height: 1;
    }
    .timer-badge {
      background: linear-gradient(135deg, #b33e0f, #e05a2b);
      border-radius: 100px;
      padding: 4px 14px;
      font-size: 0.75rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.5px;
      margin-bottom: 10px;
      display: inline-block;
    }
    .price-show {
      background: #fff;
      border-radius: 48px;
      padding: 2rem 1.8rem;
      border: 1px solid #eef2f8;
      box-shadow: 0 20px 40px -14px rgba(0,0,0,0.06);
      position: relative;
      overflow: hidden;
    }
    .price-show::after {
      content: "";
      position: absolute;
      top: 0; left: 0; width: 6px; height: 100%;
      background: linear-gradient(180deg, #b33e0f, #f59e0b);
    }
    .shine-slide {
      position: absolute;
      top: -30%; left: -40%;
      width: 60%;
      height: 200%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
      transform: rotate(25deg);
      animation: shineSlide 7s infinite;
      pointer-events: none;
    }
    @keyframes shineSlide {
      0% { left: -60%; }
      25% { left: 120%; }
      100% { left: 120%; }
    }
    .form-premium {
      background: #fff;
      border-radius: 40px;
      border: 1px solid #eef2f8;
      padding: 2rem;
      box-shadow: 0 16px 32px -12px rgba(0,0,0,0.04);
    }
    .form-control-premium {
      border-radius: 60px;
      padding: 14px 22px;
      border: 1.5px solid #e2e8f0;
      background: #fff;
      transition: 0.2s;
    }
    .form-control-premium:focus {
      border-color: #b33e0f;
      box-shadow: 0 0 0 5px rgba(179,62,15,0.12);
      transform: translateX(6px);
    }
    .trust-pill {
      background: #fff;
      border: 1px solid #eaeef4;
      border-radius: 100px;
      padding: 6px 18px;
      font-weight: 500;
      font-size: 0.8rem;
      transition: 0.2s;
    }
    .trust-pill:hover {
      border-color: #b33e0f;
      background: #fef6f0;
      transform: translateY(-3px);
    }
    .wa-sticky {
      position: fixed;
      bottom: 24px; right: 24px;
      z-index: 1050;
      background: #25D366;
      border-radius: 60px;
      padding: 10px 22px;
      border: 1px solid rgba(255,255,255,0.3);
      box-shadow: 0 12px 28px rgba(0,0,0,0.18);
      animation: waBounce 2.4s infinite;
      transition: 0.2s;
    }
    .wa-sticky:hover { transform: scale(1.06); animation: none; }
    @keyframes waBounce {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .img-soft-rounded {
      border-radius: 32px;
      border: 1px solid rgba(255,255,255,0.15);
      box-shadow: 0 20px 35px -14px rgba(0,0,0,0.3);
      transition: 0.4s ease;
    }
    .img-soft-rounded:hover { transform: scale(1.02); }
    .bulk-quantity-selector {
      background: #f2f5f9;
      border-radius: 60px;
      padding: 0.4rem 1.2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
      border: 1px solid #e2e8f0;
    }
    .bulk-quantity-selector button {
      background: transparent;
      border: none;
      font-size: 1.6rem;
      font-weight: 300;
      color: #0b1a2a;
      padding: 0 8px;
      transition: 0.15s;
    }
    .bulk-quantity-selector button:hover { color: #b33e0f; transform: scale(1.2); }
    .bulk-quantity-selector span { font-weight: 700; font-size: 1.5rem; min-width: 40px; text-align: center; }
    .delivery-charge-badge {
      background: #eef2f8;
      border-radius: 60px;
      padding: 4px 18px;
      font-weight: 500;
      font-size: 0.9rem;
    }
    @media (max-width: 768px) {
      .hero-glow { text-align: center; }
      .hero-glow h1 { font-size: 2.2rem !important; line-height: 1.25; }
      .hero-glow p.lead { font-size: 1.05rem !important; }
      .hero-glow .d-flex.flex-wrap { justify-content: center; }
      .hero-glow .mt-4.d-flex { justify-content: center; flex-wrap: wrap; gap: 10px; }
      .hero-glow img { max-width: 100% !important; height: auto !important; max-height: 320px !important; margin-top: 20px; }
      
      .time-number { font-size: 1.4rem; }
      .time-block { min-width: 62px; padding: 0.2rem 0.6rem; }
      .timer-glow { padding: 0.6rem 1rem; gap: 0.6rem; }

      .price-show { padding: 1.5rem 1rem !important; border-radius: 32px !important; }
      .new-price { font-size: 2.6rem !important; }
      .old-price { font-size: 1.4rem !important; }

      h2.text-center { font-size: 1.8rem !important; }
      .premium-card { border-radius: 28px !important; padding: 1.5rem 1rem !important; }
      
      .form-premium { padding: 1.2rem 1rem !important; border-radius: 28px !important; }
      .form-premium h3 { font-size: 1.5rem !important; }
      .form-control-premium { padding: 10px 18px !important; border-radius: 30px; }
      
      .wa-sticky { 
        bottom: 16px !important; 
        right: 16px !important; 
        width: 56px !important; 
        height: 56px !important; 
        padding: 0 !important; 
        border-radius: 50% !important; 
        display: flex !important; 
        align-items: center !important; 
        justify-content: center !important; 
      }
      .wa-sticky a { justify-content: center !important; margin-left: 2px; }
      
      .variant-img-wrapper { height: 200px !important; }
    }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,60% { transform: translateX(-6px); }
      40%,80% { transform: translateX(6px); }
    }
    .variant-btn.btn-dark {
      background-color: #b33e0f !important;
      border-color: #b33e0f !important;
      color: #fff !important;
    }
    .variant-btn:hover {
      border-color: #b33e0f;
      color: #b33e0f;
    }

    /* ===== PREMIUM CARD DESIGN ===== */
    .card,
    .variant-card,
    .premium-card {
      border: 1px solid rgba(0,0,0,0.07) !important;
      border-radius: 20px !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04) !important;
      transition: transform 0.3s ease, box-shadow 0.3s ease !important;
      background: #fff;
    }
    .card:hover,
    .variant-card:hover {
      transform: translateY(-6px) !important;
      box-shadow: 0 16px 40px rgba(0,0,0,0.13), 0 4px 12px rgba(179,62,15,0.08) !important;
    }
    .variant-card .variant-img-wrapper {
      background: linear-gradient(145deg, #fdf6f2, #f9f5f0) !important;
      border-bottom: 1px solid #f0ebe6;
    }
    .variant-card .card-body {
      padding: 1.2rem 1rem !important;
    }
    .variant-card .card-title {
      font-size: 0.95rem;
    }
    @media (max-width: 768px) {
      .variant-card .card-body { padding: 0.8rem 0.6rem !important; }
      .variant-card .card-title { font-size: 0.82rem; }
      .variant-card .price-section span { font-size: 0.85rem !important; }
    }
    /* Testimonial & feature cards */
    .premium-card {
      background: #fff !important;
      border-radius: 24px !important;
      box-shadow: 0 6px 28px rgba(0,0,0,0.07), 0 1px 6px rgba(0,0,0,0.04) !important;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .premium-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 18px 44px rgba(0,0,0,0.12), 0 4px 14px rgba(179,62,15,0.07) !important;
    }
  </style>
  {!! $landingPage->header_script ?? '' !!}
</head>
<body>
{!! $landingPage->body_script ?? '' !!}

@php
  // Price — prefer landing page price; fallback to product price (with discount applied) or first variant price
  $firstVariantPriceFallback = 0;
  $firstVariantOldPriceFallback = 0;
  if (!empty($product->variants)) {
      $now = now();
      foreach ($product->variants as $v) {
          if (!empty($v['active']) && isset($v['price']) && (float)$v['price'] > 0) {
              $vP = (float)$v['price'];
              $vDiscount = (float)($v['discount'] ?? 0);
              $vDiscountType = $v['discount_type'] ?? 'percent';
              $isActive = true;
              $startDate = !empty($v['discount_start']) ? \Carbon\Carbon::parse($v['discount_start']) : null;
              $endDate = !empty($v['discount_end']) ? \Carbon\Carbon::parse($v['discount_end']) : null;
              if ($startDate && $startDate->gt($now)) $isActive = false;
              if ($endDate && $endDate->lt($now)) $isActive = false;

              $firstVariantOldPriceFallback = $vP;
              if ($vDiscount > 0 && $isActive) {
                  if ($vDiscountType === 'percent') {
                      $firstVariantPriceFallback = $vP - ($vP * $vDiscount / 100);
                  } else {
                      $firstVariantPriceFallback = $vP - $vDiscount;
                  }
                  $firstVariantPriceFallback = max(0, $firstVariantPriceFallback);
              } else {
                  $firstVariantPriceFallback = $vP;
              }
              break;
          }
      }
  }

  // For simple products, apply discount to get effective sell price
  $effectiveOldPrice = $product->price > 0 ? $product->price : $firstVariantOldPriceFallback;
  $effectiveProductPrice = $product->price > 0 ? $product->price : $firstVariantPriceFallback;
  if ($product->price > 0 && $product->has_active_discount) {
      if ($product->discount_type === 'percent') {
          $effectiveProductPrice = $product->price - ($product->price * $product->discount_value / 100);
      } else {
          $effectiveProductPrice = $product->price - $product->discount_value;
      }
      $effectiveProductPrice = max(0, $effectiveProductPrice);
  }

  $oldPrice = $landingPage->old_price > 0 ? $landingPage->old_price : $effectiveOldPrice;
  $newPrice = $landingPage->new_price > 0 ? $landingPage->new_price : $effectiveProductPrice;
  $insideCharge = $landingPage->inside_dhaka_charge ?? 60;
  $outsideCharge = $landingPage->outside_dhaka_charge ?? 120;

  // Parse product variants (new combo structure)
  $productVariants = [];
  $variantAttributes = []; // e.g. ['size' => ['m','xl'], 'color' => ['red','blue']]
  if (!empty($product->variants)) {
      $lpVariantPrices = $landingPage->variant_prices ?? [];
      $now = now();
      foreach ($product->variants as $variant) {
          if (empty($variant['active'])) continue;
          if (!empty($variant['combo']) && is_array($variant['combo'])) {
              foreach ($variant['combo'] as $attrKey => $attrVal) {
                  if (!in_array($attrVal, $variantAttributes[$attrKey] ?? [])) {
                      $variantAttributes[$attrKey][] = $attrVal;
                  }
              }
          }
          // Inject custom variant price from Landing Page if available
          // Otherwise use discounted product price
          $sku = $variant['sku'] ?? null;
          if ($sku && !empty($lpVariantPrices)) {
              $customPricing = collect($lpVariantPrices)->firstWhere('sku', $sku);
              if ($customPricing && !empty($customPricing['price'])) {
                  $variant['price'] = (float) $customPricing['price'];
              } elseif (!empty($variant['price'])) {
                  // Apply product variant discount as fallback
                  $vP = (float)$variant['price'];
                  $vDiscount = (float)($variant['discount'] ?? 0);
                  $vDiscountType = $variant['discount_type'] ?? 'percent';
                  $isActive = true;
                  $startDate = !empty($variant['discount_start']) ? \Carbon\Carbon::parse($variant['discount_start']) : null;
                  $endDate = !empty($variant['discount_end']) ? \Carbon\Carbon::parse($variant['discount_end']) : null;
                  if ($startDate && $startDate->gt($now)) $isActive = false;
                  if ($endDate && $endDate->lt($now)) $isActive = false;
                  if ($vDiscount > 0 && $isActive) {
                      if ($vDiscountType === 'percent') {
                          $variant['price'] = max(0, $vP - ($vP * $vDiscount / 100));
                      } else {
                          $variant['price'] = max(0, $vP - $vDiscount);
                      }
                  }
              }
              if ($customPricing && !empty($customPricing['old_price'])) {
                  $variant['old_price'] = (float) $customPricing['old_price'];
              }
              if ($customPricing && isset($customPricing['inside_dhaka_charge'])) {
                  $variant['inside_dhaka_charge'] = (float) $customPricing['inside_dhaka_charge'];
              }
              if ($customPricing && isset($customPricing['outside_dhaka_charge'])) {
                  $variant['outside_dhaka_charge'] = (float) $customPricing['outside_dhaka_charge'];
              }
          } elseif (!empty($variant['price'])) {
              // No landing page custom pricing — apply variant discount as sell price
              $vP = (float)$variant['price'];
              $vDiscount = (float)($variant['discount'] ?? 0);
              $vDiscountType = $variant['discount_type'] ?? 'percent';
              $isActive = true;
              $startDate = !empty($variant['discount_start']) ? \Carbon\Carbon::parse($variant['discount_start']) : null;
              $endDate = !empty($variant['discount_end']) ? \Carbon\Carbon::parse($variant['discount_end']) : null;
              if ($startDate && $startDate->gt($now)) $isActive = false;
              if ($endDate && $endDate->lt($now)) $isActive = false;
              $variant['old_price'] = $variant['old_price'] ?? $vP;
              if ($vDiscount > 0 && $isActive) {
                  if ($vDiscountType === 'percent') {
                      $variant['price'] = max(0, $vP - ($vP * $vDiscount / 100));
                  } else {
                      $variant['price'] = max(0, $vP - $vDiscount);
                  }
              }
          }

          $productVariants[] = $variant;
      }
  }
  $hasVariants = !empty($productVariants) && !empty($variantAttributes);
@endphp

<!-- ======== HERO 1 ======== -->
<section class="hero-glow py-5 text-white">
  <div class="container py-3">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
        <div class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background:rgba(0,0,0,0.25); backdrop-filter:blur(4px); border:1px solid rgba(255,215,170,0.4);">
          <span class="small fw-semibold"><i class="fas fa-crown me-1 text-warning"></i> {{ $landingPage->tagline ?? 'প্রিমিয়াম কালেকশন ২০২৬' }}</span>
        </div>
        <h1 class="display-4 fw-bold" style="letter-spacing:-0.02em; text-shadow:0 2px 6px rgba(0,0,0,0.2);">
          {{ $landingPage->heading ?? '৫টি ভিন্ন ডিজাইন এক প্যাকেজে' }}
        </h1>
        <p class="lead mt-3" style="color:rgba(255,255,245,0.85);">{{ $landingPage->description ?? 'প্রিমিয়াম কম্বড কটন • প্রতিটি টি-শার্ট আলাদা স্টাইল • সীমিত সংস্করণ' }}</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="#order" class="btn btn-accent px-5 py-3"><i class="fas fa-shopping-bag me-2"></i> পুরো প্যাকেজ অর্ডার</a>
          <a href="#features" class="btn btn-outline-premium px-4 py-3"><i class="fas fa-info-circle me-2"></i> ডিটেইলস</a>
        </div>
        <div class="mt-4 d-flex gap-4 small" style="color:rgba(255,255,240,0.8);">
          <span><i class="fas fa-check-circle text-success"></i> {{ $landingPage->delivery_text ?? 'ফ্রি এক্সপ্রেস ডেলিভারি' }}</span>
          <span><i class="fas fa-undo-alt text-warning"></i> {{ $landingPage->return_text ?? '৩০ দিন রিটার্ন' }}</span>
        </div>
      </div>
      <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="150">
        @php
          $heroImage = 'https://placehold.co/400x400/eee/aaa?text=No+Img';
          if (!empty($landingPage->image)) {
              $heroImage = asset('storage/' . $landingPage->image);
          } elseif ($hasVariants && !empty($productVariants[0]['image'])) {
              $heroImage = asset('storage/' . $productVariants[0]['image']);
          } elseif (!empty($product->image)) {
              $heroImage = asset('storage/' . $product->image);
          }
        @endphp
        <img loading="lazy" src="{{ $heroImage }}" alt="{{ $product->name }}" class="img-fluid img-soft-rounded floating-soft" style="max-height:420px; width:auto; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- ======== TIMER + TRUST BADGES ======== -->
<section class="py-4">
  <div class="container text-center">
    <div data-aos="zoom-in" data-aos-duration="700" style="display:flex; flex-direction:column; align-items:center; gap:10px;">
      <div class="timer-badge">🔥 অফার শেষ হওয়ার আগেই অর্ডার করুন!</div>
      <div class="timer-section-wrap">
        <div class="time-block">
          <div class="time-number" id="hours">00</div>
          <div class="time-label">ঘণ্টা</div>
        </div>
        <div class="time-sep">:</div>
        <div class="time-block">
          <div class="time-number" id="minutes">00</div>
          <div class="time-label">মিনিট</div>
        </div>
        <div class="time-sep">:</div>
        <div class="time-block">
          <div class="time-number" id="seconds">00</div>
          <div class="time-label">সেকেন্ড</div>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center gap-3 flex-wrap mt-5" data-aos="fade-up" data-aos-delay="250">
      <span class="trust-pill"><i class="fas fa-shield-alt text-primary me-1"></i> ক্রেতা সুরক্ষা</span>
      <span class="trust-pill"><i class="fas fa-truck-fast text-success me-1"></i> ক্যাশ অন ডেলিভারি</span>
      <span class="trust-pill"><i class="fas fa-credit-card me-1"></i> নিরাপদ পেমেন্ট</span>
      <span class="trust-pill"><i class="fas fa-star text-warning me-1"></i> ৫★ রেটিং</span>
    </div>
  </div>
</section>

@if($hasVariants)
<!-- ======== VARIANT SHOWCASE ======== -->
<section id="variants" class="py-5" style="background: linear-gradient(180deg, #f6f8fb 0%, #eef2f9 100%);">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-pill">🛒 ভ্যারিয়েন্ট</div>
      <h2 class="section-title">আপনার পছন্দের <span>ভ্যারিয়েন্ট</span> বেছে নিন</h2>
      <p class="section-subtitle">স্টক ফুরিয়ে যাওয়ার আগেই অর্ডার করুন!</p>
    </div>
    
    <div class="row g-4 justify-content-center">
      @foreach($productVariants as $idx => $variant)
        @php
          $sku = $variant['sku'] ?? 'v-'.$idx;
          $comboLabel = implode(', ', array_map(fn($k,$v) => strtoupper($k).': '.strtoupper($v), array_keys($variant['combo'] ?? []), array_values($variant['combo'] ?? [])));
          $vPrice = !empty($variant['price']) && $variant['price'] > 0 ? $variant['price'] : $effectiveProductPrice;
          $vOldPrice = !empty($variant['old_price']) && $variant['old_price'] > 0 ? $variant['old_price'] : ($vPrice * 1.5);
          $vImage = !empty($variant['image']) ? asset('storage/'.$variant['image']) : 'https://placehold.co/400x400/eee/aaa?text=No+Img';
        @endphp
        <div class="col-6 col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $idx * 50 }}">
          <div class="card h-100 variant-card" style="border-radius:18px; overflow:hidden;">
            <div class="text-center bg-white variant-img-wrapper" style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
              <img loading="lazy" src="{{ $vImage }}" alt="{{ $comboLabel }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div class="card-body text-center p-4">
              <h5 class="card-title fw-bold mb-2">{{ $comboLabel }}</h5>
              <div class="price-section mb-3">
                <span class="text-muted text-decoration-line-through small me-2">৳{{ number_format($vOldPrice, 0, '.', '') }}</span>
                <span class="fw-bold fs-5 text-accent">৳{{ number_format($vPrice, 0, '.', '') }}</span>
              </div>
              <button type="button" class="btn btn-accent w-100 py-2 fw-semibold order-variant-btn" data-sku="{{ $sku }}" onclick="selectVariantAndScroll('{{ $sku }}')">
                <i class="fas fa-shopping-cart"></i> <span class="d-none d-sm-inline ms-1">অর্ডার করুন</span>
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@else
<!-- ======== PRICE SHOWCASE with BULK QUANTITY ======== -->
<section class="py-4">
  <div class="container">
    <div class="price-show" data-aos="flip-up" data-aos-duration="1000">
      <div class="shine-slide"></div>
      <div class="row align-items-center">
        <!-- Left Side: Product Image -->
        <div class="col-md-5 text-center mb-4 mb-md-0">
          @php
            $showcaseImage = $landingPage->image ? asset('storage/' . $landingPage->image) : ($product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/eee/aaa?text=No+Img');
          @endphp
          <img loading="lazy" src="{{ $showcaseImage }}" alt="{{ $product->name }}" class="img-fluid rounded-4" style="max-height: 350px; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1); border: 2px solid #e2e8f0;">
        </div>
        
        <!-- Right Side: Price Details -->
        <div class="col-md-7 text-center text-md-start">
          <div class="d-inline-block mb-3 px-4 py-1 rounded-pill" style="background:#b33e0f; color:#fff; font-weight:600;">
            <i class="fas fa-bolt me-1"></i> {{ $landingPage->offer_text ?? 'কম্বো অফার – ৫টি টি-শার্ট' }}
          </div>
          
          <!-- QUANTITY SELECTOR (বাল্ক) -->
          <div class="d-flex justify-content-center justify-content-md-start align-items-center gap-3 flex-wrap my-3">
            <span class="fw-semibold">প্যাকেজ সংখ্যা :</span>
            <div class="bulk-quantity-selector">
              <button id="qtyDown" aria-label="কমান">−</button>
              <span id="qtyDisplay">১</span>
              <button id="qtyUp" aria-label="বাড়ান">+</button>
            </div>
            <span class="text-muted small">(সর্বোচ্চ ৫ প্যাকেজ)</span>
          </div>

          <!-- PRICE & DISCOUNT DYNAMIC -->
          <div class="d-flex justify-content-center justify-content-md-start align-items-baseline gap-3 flex-wrap">
            <span class="old-price" id="oldPriceDisplay" style="font-size:1.8rem; font-weight:500; color:#94a3b8; text-decoration:line-through;">৳{{ number_format($oldPrice, 0, '.', '') }}</span>
            <span class="new-price" id="newPriceDisplay" style="font-size:4rem; font-weight:800; background:linear-gradient(135deg,#b33e0f,#ea580c); background-clip:text; -webkit-background-clip:text; color:transparent;">৳{{ number_format($newPrice, 0, '.', '') }}</span>
            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25" id="savedAmount">{{ $landingPage->discount_text ?? 'বাঁচাচ্ছেন ৳' . number_format($oldPrice - $newPrice, 0, '.', '') }}</span>
          </div>

          <p class="text-muted mt-3 justify-content-center justify-content-md-start"><i class="fas fa-box-open me-1"></i> <span id="stockMsg">{{ $landingPage->stock_text ?? 'মাত্র ২৫টি প্যাকেজ বাকি' }}</span></p>
          <a href="#order" class="btn btn-accent btn-lg px-5 mt-2"><i class="fas fa-lock me-2"></i> <span id="ctaPrice">৳{{ number_format($newPrice, 0, '.', '') }}</span> এ অর্ডার করুন</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

<!-- ======== 5 PRODUCT FEATURES (GRID) ======== -->
@if(!empty($landingPage->features))
<section id="features" class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-pill">✨ বৈশিষ্ট্য</div>
      <h2 class="section-title">কেন এই পণ্য <span>বিশেষ?</span></h2>
      <p class="section-subtitle">আমাদের পণ্যের অনন্য বৈশিষ্ট্যগুলো জানুন</p>
    </div>
    <div class="row g-4">
      @foreach($landingPage->features as $feature)
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="premium-card p-4 text-center h-100 d-flex flex-column align-items-center" style="border-radius:20px;">
          <div class="mb-3" style="width:72px;height:72px;display:flex;align-items:center;justify-content:center;border-radius:18px;background:linear-gradient(135deg,#fff2ee,#ffe4d4);border:1.5px solid #f5cbb7;">
            <i class="{{ $feature['icon'] ?? 'fas fa-tshirt' }} fa-2x" style="color:#b33e0f;"></i>
          </div>
          <h5 class="fw-bold mb-2">{{ $feature['title'] ?? '' }}</h5>
          <p class="small text-muted mb-0">{{ $feature['description'] ?? '' }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ======== ORDER FORM ======== -->
<section id="order" class="py-5" style="background: linear-gradient(180deg, #f6f8fb 0%, #fff 100%);">
  <div class="container">
    <div class="text-center mb-4" data-aos="fade-up">
      <div class="section-pill">📦 অর্ডার করুন</div>
      <h2 class="section-title">এখনই <span>অর্ডার</span> করুন</h2>
      <p class="section-subtitle">নিচে তথ্য দিন, আমরা দ্রুত ডেলিভারি দেব</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="form-premium" data-aos="fade-up" data-aos-duration="1000" style="border-radius:28px; box-shadow:0 20px 60px -16px rgba(0,0,0,0.1);">
          <h3 class="fw-bold text-center mb-4"><i class="fas fa-pen-fancy me-2" style="color:#b33e0f;"></i> অর্ডার ফর্ম পূরণ করুন</h3>
          
          <form id="orderForm">
            <div class="row g-4">
              <!-- Left Column: User Details -->
              <div class="col-md-7">
                <div class="mb-3">
                  <label class="form-label fw-semibold">আপনার নাম *</label>
                  <input type="text" id="fullName" class="form-control form-control-premium" placeholder="আপনার পুরো নাম লিখুন" required>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">ফোন নম্বর *</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white text-muted fw-semibold" style="border-radius: 60px 0 0 60px; border: 1.5px solid #e2e8f0; border-right: none; padding-left: 22px;">+88</span>
                    <input type="tel" id="phone" class="form-control form-control-premium" placeholder="017xxxxxxxx" required style="border-radius: 0 60px 60px 0; border-left: none; padding-left: 8px;">
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">পূর্ণ ঠিকানা (বাড়ি, রোড, থানা, জেলা) *</label>
                  <textarea id="address" class="form-control form-control-premium" rows="3" placeholder="বিস্তারিত ঠিকানা লিখুন" required style="border-radius:20px;"></textarea>
                </div>
              </div>

              <!-- Right Column: Order Summary -->
              <div class="col-md-5">
                <div class="card border-0" style="border-radius:20px; background:linear-gradient(145deg,#f8fafe,#f0f4fb); border:1px solid #e2e8f0 !important;">
                  <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 pb-2" style="border-bottom:2px solid #f1f5f9;">📋 অর্ডার সামারি</h5>

                    @if($hasVariants)
                    {{-- Selected Variant Display / Dropdown --}}
                    <div class="mb-4">
                      <label class="form-label fw-semibold">আপনার পছন্দের ভ্যারিয়েন্ট <span class="text-danger">*</span></label>
                      <input type="hidden" name="variant_sku" id="selectedVariantSku" value="">
                      <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary w-100 text-start d-flex align-items-center justify-content-between p-2" type="button" id="variantDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" style="border-radius:12px; border-color:#cbd5e1; background:#fff;">
                          <div class="d-flex align-items-center gap-3" id="variantDropdownSelected">
                            <div style="width:40px; height:40px; background:#f1f5f9; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                              <i class="fas fa-box-open text-muted"></i>
                            </div>
                            <span class="text-muted fw-semibold">ভ্যারিয়েন্ট নির্বাচন করুন</span>
                          </div>
                          <i class="fas fa-chevron-down text-muted"></i>
                        </button>
                        <ul class="dropdown-menu w-100 shadow-lg border-0 mt-1 p-2" aria-labelledby="variantDropdownBtn" style="border-radius:16px; max-height:300px; overflow-y:auto;">
                          @foreach($productVariants as $idx => $variant)
                            @php
                              $sku = $variant['sku'] ?? 'v-'.$idx;
                              $comboLabel = implode(', ', array_map(fn($k,$v) => strtoupper($k).': '.strtoupper($v), array_keys($variant['combo'] ?? []), array_values($variant['combo'] ?? [])));
                              $vPrice = !empty($variant['price']) && $variant['price'] > 0 ? $variant['price'] : $effectiveProductPrice;
                              $vImage = !empty($variant['image']) ? asset('storage/'.$variant['image']) : 'https://placehold.co/400x400/eee/aaa?text=No+Img';
                              $vInsideCharge = $variant['inside_dhaka_charge'] ?? $insideCharge;
                              $vOutsideCharge = $variant['outside_dhaka_charge'] ?? $outsideCharge;
                            @endphp
                            <li>
                              <a class="dropdown-item d-flex align-items-center gap-3 p-2 rounded variant-dropdown-item" href="#" data-sku="{{ $sku }}" data-price="{{ $vPrice }}" data-img="{{ $vImage }}" data-label="{{ $comboLabel }}" data-inside-charge="{{ $vInsideCharge }}" data-outside-charge="{{ $vOutsideCharge }}" style="transition:background 0.2s;">
                                <div class="form-check m-0 pointer-events-none">
                                  <input class="form-check-input variant-checkbox" type="checkbox" value="{{ $sku }}" style="pointer-events:none; border: 2px solid #94a3b8; width: 1.2rem; height: 1.2rem;">
                                </div>
                                <img loading="lazy" src="{{ $vImage }}" alt="{{ $comboLabel }}" style="width:40px; height:40px; object-fit:cover; border-radius:8px;">
                                <div class="flex-grow-1">
                                  <div class="fw-bold">{{ $comboLabel }}</div>
                                  <div class="text-accent fw-semibold small">৳{{ number_format($vPrice, 0, '.', '') }}</div>
                                </div>
                              </a>
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    @endif

                    <div class="mb-3">
                      <label class="form-label fw-semibold">পরিমাণ (Quantity)</label>
                      <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary px-3" id="btnMinusQty">-</button>
                        <input type="number" id="orderQtyInput" class="form-control text-center fw-bold" value="1" min="1" max="10" readonly>
                        <button type="button" class="btn btn-outline-secondary px-3" id="btnPlusQty">+</button>
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label fw-semibold">ডেলিভারি এরিয়া</label>
                      <div class="form-check mb-2">
                        <input class="form-check-input delivery-radio" type="radio" name="delivery_area" id="insideDhaka" value="inside" checked>
                        <label class="form-check-label" for="insideDhaka">ঢাকা সিটি (৳{{ $insideCharge }})</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input delivery-radio" type="radio" name="delivery_area" id="outsideDhaka" value="outside">
                        <label class="form-check-label" for="outsideDhaka">ঢাকার বাইরে (৳{{ $outsideCharge }})</label>
                      </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                      <span class="text-muted">মূল্য:</span>
                      <span class="fw-semibold">৳<span id="summarySubtotal">{{ $newPrice }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span class="text-muted">ডেলিভারি চার্জ:</span>
                      <span class="fw-semibold">৳<span id="summaryShipping">{{ $insideCharge }}</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 fs-5">
                      <span class="fw-bold">সর্বমোট:</span>
                      <span class="fw-bold text-danger">৳<span id="summaryTotal">{{ $newPrice + $insideCharge }}</span></span>
                    </div>

                    <button type="submit" class="btn btn-accent w-100 py-3" style="border-radius: 60px;"><i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন</button>
                  </div>
                </div>
              </div>
            </div>

            <div id="orderSuccessMsg" class="alert alert-success mt-4 d-none text-center" style="border-radius: 16px;">
              <i class="fas fa-check-circle fa-2x me-2 mb-2 d-block"></i> <strong>অর্ডার সফল!</strong> আমাদের টিম দ্রুত কনফার্মেশনের জন্য কল করবে।
            </div>
          </form>

          <p class="text-muted text-center mt-4 small"><i class="fas fa-lock me-1"></i> আপনার তথ্য সম্পূর্ণ নিরাপদ এবং শুধুমাত্র ডেলিভারির জন্য ব্যবহৃত হবে।</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======== TESTIMONIALS ======== -->
@if(!empty($landingPage->testimonials))
<section class="py-5" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); overflow:hidden;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-pill" style="background:rgba(255,255,255,0.1);color:#fff;border-color:rgba(255,255,255,0.2);">⭐ রিভিউ</div>
      <h2 class="section-title" style="color:#fff;">ক্রেতাদের <span style="color:#f59e0b;">মতামত</span></h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.6);">হাজার হাজার সন্তুষ্ট ক্রেতা আমাদের বিশ্বাস করেন</p>
    </div>
  </div>

  {{-- Infinite marquee carousel --}}
  <div class="testimonial-marquee-wrapper" style="position:relative; overflow:hidden;">
    <div class="testimonial-marquee-track" id="testimonialTrack">
      @php $testimonials = $landingPage->testimonials; @endphp
      {{-- Render twice for seamless loop --}}
      @foreach([...$testimonials, ...$testimonials] as $t)
      @php
        $stars = (float)($t['rating'] ?? 5);
        $fullStars = floor($stars);
        $halfStar = $stars - $fullStars >= 0.5;
      @endphp
      <div class="testimonial-marquee-card">
        <div style="margin-bottom:10px;">
          @for($s = 0; $s < $fullStars; $s++)
            <i class="fas fa-star text-warning" style="font-size:0.85rem;"></i>
          @endfor
          @if($halfStar)
            <i class="fas fa-star-half-alt text-warning" style="font-size:0.85rem;"></i>
          @endif
        </div>
        <p style="color:rgba(255,255,255,0.88); font-style:italic; font-size:0.92rem; line-height:1.6; flex-grow:1;">&ldquo;{{ $t['text'] ?? '' }}&rdquo;</p>
        <div style="display:flex; align-items:center; gap:10px; margin-top:16px;">
          <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#b33e0f,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0;">{{ mb_substr($t['author'] ?? 'ক', 0, 1) }}</div>
          <div>
            <div style="color:#fff; font-weight:600; font-size:0.88rem;">{{ $t['author'] ?? '' }}</div>
            <div style="color:rgba(255,255,255,0.45); font-size:0.75rem;">যাচাইকৃত ক্রেতা ✓</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <style>
    .testimonial-marquee-wrapper { width: 100%; }
    .testimonial-marquee-track {
      display: flex;
      gap: 20px;
      padding: 10px 20px 20px;
      width: max-content;
      animation: marqueeScroll {{ max(20, count($testimonials) * 6) }}s linear infinite;
    }
    .testimonial-marquee-track:hover { animation-play-state: paused; }
    .testimonial-marquee-card {
      width: 300px;
      min-height: 180px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      padding: 24px 22px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 20px;
      backdrop-filter: blur(8px);
      transition: transform 0.3s, background 0.3s;
    }
    .testimonial-marquee-card:hover {
      background: rgba(255,255,255,0.12);
      transform: translateY(-4px);
    }
    @keyframes marqueeScroll {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @media (max-width: 768px) {
      .testimonial-marquee-card { width: 260px; padding: 18px 16px; }
    }
  </style>
</section>
@endif

<!-- ======== STICKY WHATSAPP ======== -->
@if($landingPage->whatsapp_number)
<div class="wa-sticky">
  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingPage->whatsapp_number) }}?text={{ urlencode($landingPage->whatsapp_text ?? '') }}" target="_blank" class="text-white text-decoration-none d-flex align-items-center gap-2">
    <i class="fab fa-whatsapp fa-xl"></i> <span class="d-none d-sm-inline">WhatsApp Order</span>
  </a>
</div>
@endif

<!-- ======== FOOTER ======== -->
<footer class="py-4 text-center text-muted" style="background-color: #f8f9fa; font-size: 0.9rem; border-top: 1px solid #e2e8f0;">
  <div class="container">
    <p class="mb-0">Developed by <a href="https://crownsit.com" target="_blank" style="color: #b33e0f; font-weight: bold; text-decoration: none;">Crowns IT</a></p>
  </div>
</footer>

{{-- Variant data for JavaScript --}}
@if($hasVariants)
<script id="variantData" type="application/json">
  @json($productVariants)
</script>
@endif

<!-- ======== SCRIPTS ======== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 900, easing: 'ease-out-quad' });

  // ----- FORM & PRICING LOGIC -----
  (function(){
    let basePrice = {{ $newPrice }};
    let baseOldPrice = {{ $oldPrice }};
    const insideCharge = {{ $insideCharge }};
    const outsideCharge = {{ $outsideCharge }};
    const maxQty = 10;
    let currentQty = 1;
    let currentInsideCharge = insideCharge;
    let currentOutsideCharge = outsideCharge;
    let shippingCost = currentInsideCharge; // Default inside dhaka

    // Elements
    const qtyDisplay = document.getElementById('qtyDisplay');
    const oldPriceSpan = document.getElementById('oldPriceDisplay');
    const newPriceSpan = document.getElementById('newPriceDisplay');
    const savedSpan = document.getElementById('savedAmount');
    const ctaPrice = document.getElementById('ctaPrice');
    const stockMsg = document.getElementById('stockMsg');
    
    // Form Summary Elements
    const orderQtyInput = document.getElementById('orderQtyInput');
    const btnMinusQty = document.getElementById('btnMinusQty');
    const btnPlusQty = document.getElementById('btnPlusQty');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryShipping = document.getElementById('summaryShipping');
    const summaryTotal = document.getElementById('summaryTotal');
    const deliveryRadios = document.querySelectorAll('.delivery-radio');

    function totalPriceFormat(num) {
      const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
      return String(num).replace(/[0-9]/g, function(w){ return banglaDigits[w] || w; });
    }

    function updateUI() {
      const totalPrice = basePrice * currentQty;
      const totalOld = baseOldPrice * currentQty;
      const saved = totalOld - totalPrice;
      const finalTotal = totalPrice + shippingCost;

      // Update Hero Section (if exists)
      if(qtyDisplay) qtyDisplay.innerText = currentQty;
      if(oldPriceSpan) oldPriceSpan.innerText = '৳' + totalPriceFormat(totalOld);
      if(newPriceSpan) newPriceSpan.innerText = '৳' + totalPriceFormat(totalPrice);
      if(savedSpan) savedSpan.innerText = 'বাঁচাচ্ছেন ৳' + totalPriceFormat(saved);
      if(ctaPrice) ctaPrice.innerText = '৳' + totalPriceFormat(totalPrice);
      
      if(stockMsg) {
        const remaining = 25 - (currentQty - 1) * 5;
        if (remaining > 0) {
          stockMsg.innerText = `মাত্র ${totalPriceFormat(remaining)}টি প্যাকেজ বাকি (${totalPriceFormat(remaining*5)}টি আইটেম)`;
        } else {
          stockMsg.innerText = 'শেষ মুহূর্ত! দ্রুত অর্ডার করুন';
        }
      }

      // Update Form Summary
      if(orderQtyInput) orderQtyInput.value = currentQty;
      if(summarySubtotal) summarySubtotal.innerText = totalPriceFormat(totalPrice);
      if(summaryShipping) summaryShipping.innerText = totalPriceFormat(shippingCost);
      if(summaryTotal) summaryTotal.innerText = totalPriceFormat(finalTotal);
    }

    // Event Listeners for Hero Qty (if exists)
    const heroQtyUp = document.getElementById('qtyUp');
    const heroQtyDown = document.getElementById('qtyDown');
    if(heroQtyUp) {
      heroQtyUp.addEventListener('click', function() {
        if (currentQty < maxQty) { currentQty++; updateUI(); }
      });
    }
    if(heroQtyDown) {
      heroQtyDown.addEventListener('click', function() {
        if (currentQty > 1) { currentQty--; updateUI(); }
      });
    }

    // Event Listeners for Form Qty
    if(btnPlusQty) {
      btnPlusQty.addEventListener('click', function() {
        if (currentQty < maxQty) { currentQty++; updateUI(); }
      });
    }
    if(btnMinusQty) {
      btnMinusQty.addEventListener('click', function() {
        if (currentQty > 1) { currentQty--; updateUI(); }
      });
    }
    
    // Event Listeners for Delivery Radio
    deliveryRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        if(this.value === 'inside') {
          shippingCost = currentInsideCharge;
        } else {
          shippingCost = currentOutsideCharge;
        }
        updateUI();
      });
    });

    // Initialize UI
    updateUI();

    // ===== MULTI VARIANT LOGIC =====
    const variantDataEl = document.getElementById('variantData');
    const allVariants = variantDataEl ? JSON.parse(variantDataEl.textContent) : [];
    let selectedSkus = [];

    function findMatchingVariant(sku) {
      if (!allVariants.length) return null;
      return allVariants.find(v => v.sku === sku) || null;
    }

    function updateMultipleVariantState() {
      const dropdownSelected = document.getElementById('variantDropdownSelected');
      
      if (selectedSkus.length === 0) {
        basePrice = {{ $newPrice }};
        baseOldPrice = {{ $oldPrice }};
        currentInsideCharge = insideCharge;
        currentOutsideCharge = outsideCharge;
        
        if (dropdownSelected) {
          dropdownSelected.innerHTML = `
           <div style="width:40px; height:40px; background:#f1f5f9; border-radius:8px; display:flex; align-items:center; justify-content:center;">
             <i class="fas fa-box-open text-muted"></i>
           </div>
           <span class="text-muted fw-semibold">ভ্যারিয়েন্ট নির্বাচন করুন</span>
          `;
        }
      } else {
        basePrice = 0;
        baseOldPrice = 0;
        let maxInside = 0;
        let maxOutside = 0;
        
        selectedSkus.forEach(sku => {
          const v = findMatchingVariant(sku);
          if (v) {
            let p = (v.price != null && v.price > 0) ? parseFloat(v.price) : {{ $newPrice }};
            basePrice += p;
            baseOldPrice += (v.old_price != null && v.old_price > 0) ? parseFloat(v.old_price) : (p * 1.5);
            
            let ic = (v.inside_dhaka_charge != null) ? parseFloat(v.inside_dhaka_charge) : insideCharge;
            let oc = (v.outside_dhaka_charge != null) ? parseFloat(v.outside_dhaka_charge) : outsideCharge;
            if (ic > maxInside) maxInside = ic;
            if (oc > maxOutside) maxOutside = oc;
          }
        });
        
        currentInsideCharge = maxInside || insideCharge;
        currentOutsideCharge = maxOutside || outsideCharge;

        if (dropdownSelected) {
          if (selectedSkus.length === 1) {
            const vItem = document.querySelector(`.variant-dropdown-item[data-sku="${selectedSkus[0]}"]`);
            if (vItem) {
              dropdownSelected.innerHTML = `
                <img loading=" lazy\ src="${vItem.dataset.img}" style="width:40px; height:40px; object-fit:cover; border-radius:8px;">
                <div>
                  <div class="fw-bold" style="color:#0f172a; line-height:1.2;">${vItem.dataset.label}</div>
                  <div class="text-accent small fw-semibold" style="line-height:1.2;">৳${totalPriceFormat(vItem.dataset.price)}</div>
                </div>
              `;
            }
          } else {
            dropdownSelected.innerHTML = `
              <div style="width:40px; height:40px; background:#fdf2ee; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#b33e0f; font-weight:bold; border:1px solid #f5cbb7;">
                ${selectedSkus.length}
              </div>
              <div>
                <div class="fw-bold" style="color:#0f172a; line-height:1.2;">${selectedSkus.length}টি নির্বাচিত</div>
                <div class="text-accent small fw-semibold" style="line-height:1.2;">মোট: ৳${totalPriceFormat(basePrice)}</div>
              </div>
            `;
          }
        }
      }

      // Update Delivery Labels
      const insideLabel = document.querySelector('label[for="insideDhaka"]');
      const outsideLabel = document.querySelector('label[for="outsideDhaka"]');
      if (insideLabel) insideLabel.textContent = 'ঢাকা সিটি (৳' + currentInsideCharge + ')';
      if (outsideLabel) outsideLabel.textContent = 'ঢাকার বাইরে (৳' + currentOutsideCharge + ')';
      
      const selectedRadio = document.querySelector('input[name="delivery_area"]:checked');
      if (selectedRadio && selectedRadio.value === 'inside') {
        shippingCost = currentInsideCharge;
      } else {
        shippingCost = currentOutsideCharge;
      }
      
      updateUI();
    }

    // Dropdown Checkbox Selection
    document.querySelectorAll('.variant-dropdown-item').forEach(function(item) {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        const sku = this.dataset.sku;
        const checkbox = this.querySelector('.variant-checkbox');
        
        if (selectedSkus.includes(sku)) {
            selectedSkus = selectedSkus.filter(s => s !== sku);
            if (checkbox) checkbox.checked = false;
        } else {
            selectedSkus.push(sku);
            if (checkbox) checkbox.checked = true;
        }
        
        document.getElementById('selectedVariantSku').value = selectedSkus.join(',');
        updateMultipleVariantState();
      });
    });

    // Selecting from Showcase grid
    window.selectVariantAndScroll = function(sku) {
      const dropdownItem = document.querySelector(`.variant-dropdown-item[data-sku="${sku}"]`);
      if (dropdownItem) {
        const checkbox = dropdownItem.querySelector('.variant-checkbox');
        if (checkbox && !checkbox.checked) {
          dropdownItem.click();
        }
      }
      document.getElementById('order').scrollIntoView({ behavior: 'smooth' });
    };

    // ORDER SUBMIT
    document.getElementById("orderForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const name = document.getElementById("fullName").value.trim();
      let phone = document.getElementById("phone").value.trim();
      const address = document.getElementById("address").value.trim();
      const area = document.querySelector('input[name="delivery_area"]:checked').value;
      const qty = currentQty;
      
      // prepend +88 if not already there
      if (!phone.startsWith('+88')) {
        if (phone.startsWith('880')) {
          phone = '+' + phone;
        } else {
          phone = '+88' + phone;
        }
      }
      
      // Build variants from selectedVariantSku
      let variants = {};
      if (allVariants.length) {
        const sku = document.getElementById('selectedVariantSku').value;
        if (!sku) {
          alert('অনুগ্রহ করে আপনার পছন্দের ভ্যারিয়েন্ট বেছে নিন।');
          
          // Highlight dropdown
          const dropdownBtn = document.getElementById('variantDropdownBtn');
          if (dropdownBtn) {
            dropdownBtn.style.animation = 'shake 0.4s ease';
            dropdownBtn.style.borderColor = 'red';
            setTimeout(() => {
              dropdownBtn.style.animation = '';
              dropdownBtn.style.borderColor = '#cbd5e1';
            }, 1000);
          }
          
          return;
        }
        
        if (activeVariant && activeVariant.sku) {
          variants = Object.assign({}, activeVariant.combo);
          variants._sku = activeVariant.sku;
        }
      }

      if (!name || !phone || !address) {
        alert("দয়া করে নাম, ফোন এবং ঠিকানা দিন।");
        return;
      }
      if (phone.length < 9) {
        alert("সঠিক ফোন নম্বর দিন (কমপক্ষে ৯ ডিজিট)।");
        return;
      }

      const shippingMethod = area === 'inside' ? 'inside_dhaka' : 'outside_dhaka';
      const subtotal = basePrice * qty;
      const total = subtotal + shippingCost;

      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> অর্ডার সম্পন্ন হচ্ছে...';

      fetch("{{ route('order.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          customer_name: name,
          customer_phone: phone,
          customer_address: address + ' (' + (area === 'inside' ? 'ঢাকা সিটি' : 'ঢাকার বাইরে') + ')',
          shipping_method: shippingMethod,
          shipping_cost: shippingCost,
          payment_method: 'cod',
          subtotal: subtotal,
          tax: 0,
          total: total,
          items: [{
            product_id: {{ $product->id }},
            product_name: "{{ $product->name }}",
            product_image: "{{ $product->image }}",
            price: basePrice,
            quantity: qty,
            variants: variants
          }]
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const successDiv = document.getElementById("orderSuccessMsg");
          successDiv.classList.remove("d-none");
          successDiv.scrollIntoView({ behavior: "smooth", block: "center" });
          
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 1500);
        } else {
          alert(data.message || 'অর্ডার করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন';
        }
      })
      .catch(error => {
        console.error('Order error:', error);
        alert('অর্ডার করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন';
      });
    });

  })();

  // ----- TIMER (9 hours) -----
  (function(){
    const KEY = "lp_offer_end_{{ $landingPage->id ?? 0 }}";
    let expiry = localStorage.getItem(KEY);
    const now = Date.now();
    // If no expiry, or it's already expired — reset to 9 hours from now
    if (!expiry || parseInt(expiry) <= now) {
      expiry = now + (9 * 60 * 60 * 1000);
      localStorage.setItem(KEY, expiry);
    } else {
      expiry = parseInt(expiry);
    }
    function update() {
      let rem = Math.max(0, expiry - Date.now());
      if(document.getElementById("hours"))   document.getElementById("hours").innerText   = String(Math.floor(rem / 3600000)).padStart(2,'0');
      if(document.getElementById("minutes")) document.getElementById("minutes").innerText = String(Math.floor((rem % 3600000) / 60000)).padStart(2,'0');
      if(document.getElementById("seconds")) document.getElementById("seconds").innerText = String(Math.floor((rem % 60000) / 1000)).padStart(2,'0');
    }
    update();
    setInterval(update, 1000);
  })();

  // SMOOTH SCROLL
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return; // Ignore empty anchors
      const target = document.querySelector(href);
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  });
</script>
</body>
</html>
