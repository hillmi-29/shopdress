# Rencana Implementasi AI Sederhana untuk ShopDress

**Versi:** 1.0  
**Tanggal:** 2024  
**Approach:** Pure PHP, Zero Dependency, Jalan di Laravel Existing  
**Estimasi Waktu:** 2-3 Jam Coding  
**Biaya Tambahan:** Rp 0

---

## Daftar Fitur (3 Fitur Inti)

| # | Fitur | Jenis | Manfaat Bisnis | Kompleksitas |
|---|-------|-------|----------------|--------------|
| 1 | **Size Recommender Rule-Based** | Service PHP | Turunkan retur ukuran 15-20% | ⭐ Sangat Mudah |
| 2 | **Product Description Template Generator** | Service PHP | Hemat waktu admin 80%, konsisten SEO | ⭐ Sangat Mudah |
| 3 | **Simple "Customers Also Bought"** | SQL Query | Cross-sell otomatis, naikkan AOV | ⭐ Sangat Mudah |

---

## Arsitektur (Tidak Ada Perubahan Infrastructure)

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Application                      │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  App\Services\                                        │  │
│  │  ├─ SizeRecommender.php      (rule-based logic)       │  │
│  │  ├─ DescriptionGenerator.php (template-based)         │  │
│  │  └─ SimpleRecommender.php    (SQL collaborative)      │  │
│  └───────────────────────────────────────────────────────┘  │
│                          │                                  │
│                    Dependency Injection                     │
│                          ▼                                  │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  Controllers & Blade Views                            │  │
│  │  ├─ ProductController@show → size guide + also bought │  │
│  │  ├─ Filament ProductResource → generate description   │  │
│  │  └─ Blade Components → size guide form                │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## Detail Implementasi per Fitur

---

### FITUR 1: Size Recommender Rule-Based

**File:** `app/Services/SizeRecommender.php`

**Logika:**
- Size chart standar Indonesia (XS-XXL) dalam cm
- Input: user chest/waist/hip + fit preference (slim/regular/loose)
- Output: recommended size + confidence % + alternatives
- Perhitungan: Euclidean distance sederhana dengan ease factor

**Interface:**
```php
interface SizeRecommenderInterface
{
    public function recommend(float $chest, float $waist, float $hip, string $fit = 'regular'): array;
    public function getSizeChart(): array;
}
```

**Return Format:**
```php
[
    'size' => 'M',
    'confidence' => 87,
    'alternatives' => ['S' => 72, 'L' => 65, 'M' => 87],
    'size_chart' => [...],
    'fit_used' => 'regular'
]
```

**Integrasi Points:**
1. **Product Detail Page** → Blade component form input ukuran user
2. **Variant Picker Modal** → Auto-suggest size saat pilih varian
3. **Cart/Checkout** → Tampilkan rekomendasi sebelum bayar
4. **API** → `/api/size-recommend` untuk AJAX

---

### FITUR 2: Product Description Template Generator

**File:** `app/Services/DescriptionGenerator.php`

**Logika:**
- Template per kategori: dress, gamis, abaya, tops, bottoms, outerwear
- Placeholder: {name}, {fabric}, {fit}, {detail}, {occasion}
- Random opening paragraph + 5 bullet points fitur
- Bahasa Indonesia natural, SEO-friendly

**Kategori Template:**
| Kategori | Opening Style | Focus Fitur |
|----------|---------------|-------------|
| dress | Fashion-forward, versatile | Bahan, potongan, detail, warna, finishing |
| gamis | Muslimah-friendly, ibadah+santai | Adem, kancing, lengan, rok A-line, bordir |
| abaya | Elegan, flowy | Bahan premium, jahitan, cutting, warna, layering |
| tops | Mix & match | Bahan, fit, detail leher/lengan, layering |
| bottoms | Nyaman, flattering | Bahan, pinggang elastik, potongan, pocket |
| outerwear | Layering piece | Bahan, cutting, saku, kancing/resleting |

**Integrasi Points:**
1. **Filament Product Resource** → Action "Generate Deskripsi AI" di toolbar
2. **Product Create/Edit Form** → Button di sebelah field description
3. **Bulk Action** → Generate untuk multiple produk sekaligus

---

### FITUR 3: Simple "Customers Also Bought" (Collaborative Filtering SQL)

**File:** `app/Services/SimpleRecommender.php`

**Query Utama (Also Bought):**
```sql
SELECT p.*, COUNT(*) as frequency
FROM order_items oi1
JOIN order_items oi2 ON oi1.order_id = oi2.order_id
JOIN products p ON oi2.product_id = p.id
WHERE oi1.product_id = ?
  AND oi2.product_id != ?
  AND p.is_active = true
GROUP BY p.id
ORDER BY frequency DESC
LIMIT 4
```

**Fallback (Same Category):**
```sql
SELECT * FROM products 
WHERE category_id = ? AND id != ? AND is_active = true
ORDER BY RAND() LIMIT 4
```

**Method:**
```php
public function getAlsoBought(int $productId, int $limit = 4): Collection
public function getSimilarByCategory(int $productId, int $categoryId, int $limit = 4): Collection
public function getTrending(int $limit = 8): Collection  // bestseller 30 hari
```

**Integrasi Points:**
1. **Product Detail** → Section "Produk Serupa" / "Pembeli Juga Membeli"
2. **Cart Page** → "Lengkapi Look Anda"
3. **Checkout Success** → "Kamu Mungkin Suka"
4. **Homepage** → "Trending Minggu Ini"

---

## File Structure yang Akan Dibuat

```
app/
├── Services/
│   ├── SizeRecommender.php
│   ├── DescriptionGenerator.php
│   └── SimpleRecommender.php
├── Http/
│   ├── Controllers/
│   │   └── Frontend/
│   │       └── Api/
│   │           └── SizeRecommendController.php  (optional AJAX)
│   └── Requests/
│       └── SizeRecommendRequest.php
└── View/
    └── Components/
        └── frontend/
            └── size-guide.blade.php

resources/
├── views/
│   └── frontend/
│       ├── components/
│       │   └── size-guide.blade.php
│       └── products/
│           └── partials/
│               └── also-bought.blade.php
└── filament/
    └── Resources/
        └── ProductResource/
            └── Actions/
                └── GenerateDescription.php

config/
└── ai.php  (optional: konfigurasi size chart, template, dll)
```

---

## Integrasi ke Kode Existing

### 1. AppServiceProvider.php
```php
public function register()
{
    $this->app->singleton(\App\Services\SizeRecommender::class);
    $this->app->singleton(\App\Services\DescriptionGenerator::class);
    $this->app->singleton(\App\Services\SimpleRecommender::class);
}
```

### 2. ProductController@show (Update)
```php
public function show(string $slug)
{
    $product = Product::with(['category', 'primaryImage', 'variants', 'reviews.user'])
        ->where('slug', $slug)->where('is_active', true)->firstOrFail();

    // Size recommender (jika user submit form)
    $sizeRec = null;
    if (request()->filled(['chest', 'waist', 'hip'])) {
        $sizeRec = app(\App\Services\SizeRecommender::class)->recommend(
            (float) request('chest'),
            (float) request('waist'),
            (float) request('hip'),
            request('fit', 'regular')
        );
    }

    // Also bought
    $alsoBought = app(\App\Services\SimpleRecommender::class)
        ->getAlsoBought($product->id);

    // Related products (fallback same category)
    $relatedProducts = $alsoBought->isEmpty()
        ? app(\App\Services\SimpleRecommender::class)
            ->getSimilarByCategory($product->id, $product->category_id)
        : collect();

    return view('frontend.products.show', compact(
        'product', 'sizeRec', 'alsoBought', 'relatedProducts'
    ));
}
```

### 3. Blade View: products/show.blade.php (Tambahan)
```blade
<!-- Size Guide Component -->
@if ($product->variants->count() > 1)
    <x-frontend.size-guide :product="$product" :recommendation="$sizeRec" />
@endif

<!-- Also Bought Section -->
@if ($alsoBought->isNotEmpty())
    @include('frontend.products.partials.also-bought', ['products' => $alsoBought])
@elseif ($relatedProducts->isNotEmpty())
    @include('frontend.products.partials.also-bought', ['products' => $relatedProducts, 'title' => 'Produk Serupa'])
@endif
```

### 4. Filament Action: GenerateDescription.php
```php
<?php
namespace App\Filament\Resources\ProductResource\Actions;

use Filament\Tables\Actions\Action;
use App\Services\DescriptionGenerator;

class GenerateDescription extends Action
{
    protected function setUp(): void
    {
        $this->label('Generate Deskripsi AI')
            ->icon('heroicon-o-sparkles')
            ->color('success')
            ->form([
                \Filament\Forms\Components\TextInput::make('fabric')->label('Bahan'),
                \Filament\Forms\Components\TextInput::make('fit')->label('Fit (slim/regular/loose)'),
                \Filament\Forms\Components\TextInput::make('detail')->label('Detail Khusus'),
                \Filament\Forms\Components\TextInput::make('occasion')->label('Okasi')->default('acara santai maupun formal'),
            ])
            ->action(function (array $data, $record) {
                $generator = app(DescriptionGenerator::class);
                $description = $generator->generate(
                    name: $record->name,
                    category: $record->category->name ?? 'dress',
                    fabric: $data['fabric'] ?? '',
                    fit: $data['fit'] ?? '',
                    detail: $data['detail'] ?? '',
                    occasion: $data['occasion'] ?? 'acara santai maupun formal'
                );
                $record->update(['description' => $description]);
                \Filament\Notifications\Notification::make()
                    ->title('Deskripsi berhasil digenerate')
                    ->success()
                    ->send();
            });
    }
}
```

---

## Konfigurasi (Optional: config/ai.php)

```php
<?php
return [
    'size_chart' => [
        'XS' => ['chest' => 76, 'waist' => 60, 'hip' => 84],
        'S'  => ['chest' => 82, 'waist' => 66, 'hip' => 90],
        'M'  => ['chest' => 88, 'waist' => 72, 'hip' => 96],
        'L'  => ['chest' => 94, 'waist' => 78, 'hip' => 102],
        'XL' => ['chest' => 100, 'waist' => 84, 'hip' => 108],
        'XXL'=> ['chest' => 106, 'waist' => 90, 'hip' => 114],
    ],
    'fit_ease' => [
        'slim' => 2,
        'regular' => 6,
        'loose' => 10,
    ],
    'description_templates' => [
        'dress' => [ /* ... */ ],
        'gamis' => [ /* ... */ ],
        'abaya' => [ /* ... */ ],
        'default' => [ /* ... */ ],
    ],
    'recommender' => [
        'also_bought_limit' => 4,
        'fallback_to_category' => true,
        'min_orders_for_also_bought' => 3,
    ],
];
```

---

## Testing Checklist

### Size Recommender
- [ ] Input valid → return size + confidence
- [ ] Input extreme (sangat kecil/besar) → return size terdekat + confidence rendah
- [ ] Fit slim/regular/loose → hasil berbeda
- [ ] Integration di product detail → form submit → tampil rekomendasi
- [ ] Mobile responsive

### Description Generator
- [ ] Semua kategori punya template
- [ ] Placeholder ter-ganti semua
- [ ] Output tidak ada placeholder tersisa
- [ ] Filament action → update description field
- [ ] Bulk action (jika diimplementasikan)

### Simple Recommender
- [ ] Also bought → return produk + frequency
- [ ] Fallback category → return produk random aktif
- [ ] Empty state → tidak error
- [ ] Performance: query < 100ms
- [ ] Cache 1 jam (optional)

---

## Rollout Plan

| Tahap | Aktivitas | Waktu | PIC |
|-------|-----------|-------|-----|
| **1. Setup** | Buat 3 Service class + register di AppServiceProvider | 30 menit | Dev |
| **2. Size Guide** | Buat Blade component + integrasi ke ProductController + test | 45 menit | Dev |
| **3. Description** | Buat Filament Action + test generate di admin | 30 menit | Dev |
| **4. Also Bought** | Integrasi ke product detail + partial blade + test | 30 menit | Dev |
| **5. Polish** | Cache, error handling, responsive, copywriting | 30 menit | Dev |
| **6. Deploy** | Push ke staging → test → production | 15 menit | Dev |

**Total: ~3 jam**

---

## Maintenance & Monitoring

| Metric | Cara Cek | Frequency |
|--------|----------|-----------|
| Size guide usage | Log request ke `size_recommend` | Mingguan |
| Description generated | Filament activity log / custom log | Mingguan |
| Also bought click-through | Event tracking (GA4/Matomo) | Mingguan |
| Query performance | Laravel Telescope / DB slow query log | Bulanan |

---

## Future Enhancement (Jika Butuh)

| Enhancement | Effort | Value |
|-------------|--------|-------|
| Cache also bought 1 jam | 10 menit | Performance |
| Size chart per kategori (bukan global) | 30 menit | Akurasi |
| A/B test template description | 1 jam | Konversi |
| Track size guide → purchase conversion | 2 jam | Insight |
| ML model replace rule-based (nanti) | 1-2 minggu | Akurasi lebih tinggi |

---

## Catatan Penting

1. **Tidak ada migration database** — semua pakai tabel existing (`orders`, `order_items`, `products`)
2. **Tidak ada queue/job** — semua synchronous, cepat (< 50ms)
3. **Tidak ada external dependency** — pure PHP + Laravel Eloquent/Query Builder
4. **Backward compatible** — tidak merusak fitur existing
5. **Feature flag ready** — bisa dimatikan via config `ai.enabled = false`

---

## Approval Checklist

- [ ] Product Owner review scope
- [ ] Tech Lead review code structure
- [ ] QA test scenario disepakati
- [ ] Deploy window dijadwalkan

---

**Dibuat oleh:** AI Assistant  
**Status:** Siap Implementasi  
**Prioritas:** Tinggi (Quick Win, Zero Cost, High Impact)