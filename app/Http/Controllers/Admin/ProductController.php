<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // LIST PRODUCTS
    public function index(Request $request)
    {
        $query = Product::query();

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);

        return view('admin.products', compact('products'));
    }

    // STORE PRODUCT
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'token_cost' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if (empty($validated['image_url'])) {
            $validated['image_url'] = $this->fetchProductImageFromPexels($validated['name']);
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    // SHOW PRODUCT
    public function show(Product $product)
    {
        return view('admin.products-show', compact('product'));
    }

    // EDIT FORM
    public function edit(Product $product)
    {
        return view('admin.products-edit', compact('product'));
    }

    // UPDATE PRODUCT
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'token_cost' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if (empty($validated['image_url'])) {
            $validated['image_url'] = $this->fetchProductImageFromPexels($validated['name']) ?? $product->image_url;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // DELETE PRODUCT
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function fetchProductImageFromPexels(string $query): ?string
    {
        $apiKey = config('services.pexels.api_key');

        if (! $apiKey || trim($query) === '') {
            return null;
        }

        $searchQueries = $this->buildSearchQueries($query);
        $bestPhoto = null;
        $bestScore = -999;

        foreach ($searchQueries as $searchQuery) {
            try {
                $response = Http::timeout(8)
                    ->withHeaders([
                        'Authorization' => $apiKey,
                    ])
                    ->get('https://api.pexels.com/v1/search', [
                        'query' => $searchQuery,
                        'per_page' => 10,
                        'orientation' => 'landscape',
                    ]);

                if (! $response->ok()) {
                    continue;
                }

                $photos = data_get($response->json(), 'photos', []);

                foreach ($photos as $photo) {
                    $score = $this->scorePexelsPhoto($query, (array) $photo);
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestPhoto = $photo;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        if (! $bestPhoto || $bestScore < 2) {
            return null;
        }

        return data_get($bestPhoto, 'src.medium')
            ?? data_get($bestPhoto, 'src.large')
            ?? data_get($bestPhoto, 'src.original');
    }

    private function buildSearchQueries(string $query): array
    {
        $q = trim($query);
        $normalized = Str::lower($q);
        $queries = [$q, "{$q} product"];

        if (Str::contains($normalized, ['ipad', 'iphone', 'macbook', 'airpods', 'apple watch'])) {
            $queries[] = "apple {$q} device";
            $queries[] = "{$q} tablet";
        }

        if (Str::contains($normalized, ['ps5', 'playstation', 'xbox', 'nintendo', 'switch'])) {
            $queries[] = "{$q} gaming console";
        }

        if (Str::contains($normalized, ['nike', 'adidas', 'puma', 'new balance', 'reebok'])) {
            $queries[] = "{$q} shoes";
            $queries[] = "{$q} sneaker";
        }

        return array_values(array_unique($queries));
    }

    private function scorePexelsPhoto(string $query, array $photo): int
    {
        $haystack = Str::lower(trim(
            (string) ($photo['alt'] ?? '').' '.(string) ($photo['url'] ?? '')
        ));

        $tokens = collect(preg_split('/\s+/', Str::lower($query)) ?: [])
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values();

        $strongTokens = $tokens->filter(function ($token) {
            return in_array($token, [
                'ipad', 'iphone', 'macbook', 'airpods', 'apple', 'samsung',
                'nike', 'adidas', 'xbox', 'playstation', 'ps5', 'nintendo',
            ], true);
        });

        $generalTokens = $tokens->reject(function ($token) use ($strongTokens) {
            return $strongTokens->contains($token) || in_array($token, ['mini', 'new', 'the', 'pro', 'max'], true);
        });

        $score = 0;

        foreach ($strongTokens as $token) {
            if (Str::contains($haystack, $token)) {
                $score += 4;
            } else {
                $score -= 3;
            }
        }

        foreach ($generalTokens as $token) {
            if (mb_strlen($token) >= 3 && Str::contains($haystack, $token)) {
                $score += 1;
            }
        }

        $isLikelyElectronics = $tokens->contains(fn ($t) => in_array($t, ['ipad', 'iphone', 'macbook', 'tablet', 'laptop'], true));
        if ($isLikelyElectronics && Str::contains($haystack, ['car', 'vehicle', 'automobile', 'mini cooper'])) {
            $score -= 6;
        }

        return $score;
    }
}
