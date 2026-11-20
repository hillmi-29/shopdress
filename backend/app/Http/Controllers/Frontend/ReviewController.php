<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan produk dari pelanggan yang sudah membeli.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:20480'],
        ], [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.between' => 'Rating harus antara 1 sampai 5 bintang.',
            'comment.max' => 'Komentar maksimal 1.000 karakter.',
            'photos.max' => 'Maksimal 4 foto per ulasan.',
            'photos.*.image' => 'File foto harus berupa gambar.',
            'photos.*.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 4 MB.',
            'video.mimetypes' => 'Format video harus MP4, MOV, atau WEBM.',
            'video.max' => 'Ukuran video maksimal 20 MB.',
        ]);

        $user = $request->user();

        if (! Review::hasVerifiedPurchase($user, $product->id)) {
            return back()
                ->withErrors(['review' => 'Ulasan hanya dapat dikirim untuk produk yang sudah Anda beli dan bayar.'])
                ->withInput();
        }

        $review = Review::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $existingPhotos = $review->photos ?? [];
        $newPhotos = $request->file('photos', []);
        $newPhotos = is_array($newPhotos) ? $newPhotos : [$newPhotos];

        if (count($existingPhotos) + count($newPhotos) > 4) {
            return back()
                ->withErrors(['photos' => 'Total foto per ulasan maksimal 4. Pilih lebih sedikit foto.'])
                ->withInput();
        }

        $photos = $existingPhotos;

        foreach ($newPhotos as $photo) {
            $photos[] = $photo->store('reviews/photos', 'public');
        }

        $isNew = ! $review->exists;
        $oldVideo = $review->video;

        $review->fill([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'photos' => array_values($photos),
        ]);

        if ($request->hasFile('video')) {
            $review->video = $request->file('video')->store('reviews/videos', 'public');
        }

        $review->save();

        if ($request->hasFile('video') && $oldVideo && $oldVideo !== $review->video) {
            Storage::disk('public')->delete($oldVideo);
        }

        return redirect()
            ->to(route('products.show', $product->slug).'#ulasan')
            ->with('success', $isNew
                ? 'Ulasan berhasil dikirim. Terima kasih sudah berbagi pengalaman.'
                : 'Ulasan berhasil diperbarui.')
            ->with('review_submitted', true);
    }
}
