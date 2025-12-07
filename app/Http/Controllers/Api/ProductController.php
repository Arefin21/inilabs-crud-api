<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'id'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'per_page' => $request->get('per_page', 15),
        ];

        $products = $this->productService->getAllProducts($filters);

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $product = $this->productService->getProductById((int) $id);

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product),
        ]);
    }

    public function update(ProductRequest $request, string $id): JsonResponse
    {
        $product = $this->productService->updateProduct((int) $id, $request->validated());

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->productService->deleteProduct((int) $id);

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
