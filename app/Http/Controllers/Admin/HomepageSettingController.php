<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;

class HomepageSettingController extends Controller
{
    // Section definitions: key => max images
    private array $sections = [
        'hero_banners' => 3,
        'best_selling_banners' => 3,
        'discounted_products_banner' => 1,
        'delivery_charges' => 1, // 1 entry with inside and outside charges
    ];

    public function index()
    {
        $settings = [];
        foreach (array_keys($this->sections) as $key) {
            $settings[$key] = HomepageSetting::get($key, []);
        }

        return view('backend.settings.homepage', compact('settings'));
    }

    public function update(Request $request, string $section)
    {
        if (! array_key_exists($section, $this->sections)) {
            abort(404);
        }

        if ($section === 'delivery_charges') {
            $data = $request->validate([
                'inside_dhaka' => 'required|numeric|min:0',
                'outside_dhaka' => 'required|numeric|min:0',
            ]);
            HomepageSetting::set($section, $data);
            return redirect()
                ->route('admin.settings.homepage', ['tab' => $section])
                ->with('success', 'Delivery charges updated successfully.');
        }

        if ($section === 'hero_banners') {
            $banners = [];

            if ($request->has('banners')) {
                foreach ($request->banners as $index => $bannerData) {
                    if (isset($bannerData['delete'])) {
                        if (! empty($bannerData['existing_image'])) {
                            $fullPath = storage_path('app/public/'.$bannerData['existing_image']);
                            if (file_exists($fullPath)) {
                                @unlink($fullPath);
                            }
                        }
                        continue;
                    }

                    $imagePath = $bannerData['existing_image'] ?? null;

                    if ($request->hasFile("banners.{$index}.image")) {
                        if ($imagePath) {
                            $fullPath = storage_path('app/public/'.$imagePath);
                            if (file_exists($fullPath)) {
                                @unlink($fullPath);
                            }
                        }
                        $file = $request->file("banners.{$index}.image");
                        $imagePath = $file->store('homepage', 'public');
                    }

                    if ($imagePath) {
                        $banners[] = [
                            'image' => $imagePath,
                            'label' => $bannerData['label'] ?? null,
                            'title' => $bannerData['title'] ?? null,
                            'desc' => $bannerData['desc'] ?? null,
                            'link' => $bannerData['link'] ?? null,
                        ];
                    }
                }
            }

            HomepageSetting::set($section, $banners);

            return redirect()
                ->route('admin.settings.homepage', ['tab' => $section])
                ->with('success', 'Hero banners updated successfully.');
        }

        $maxImages = $this->sections[$section];
        $existing = HomepageSetting::get($section, []);
        $images = is_array($existing) ? $existing : [];

        // Handle deletions first
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $path) {
                $fullPath = storage_path('app/public/'.$path);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
                $images = array_values(array_filter($images, fn ($i) => $i !== $path));
            }
        }

        // Handle new uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (count($images) >= $maxImages) {
                    break; // enforce max
                }
                $path = $file->store('homepage', 'public');
                $images[] = $path;
            }
        }

        HomepageSetting::set($section, $images);

        return redirect()
            ->route('admin.settings.homepage', ['tab' => $section])
            ->with('success', 'Section updated successfully.');
    }
}
