<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\{ProductRepository};

class ProductController extends BaseController
{
    protected $productRepository;
    protected $accountRepository;

    public function __construct(
            ProductRepository $productRepository,
        )
    {
        //$this->middleware(['role:admin|sondeur|operateur|client|analyste laboratoire']);
        $this->productRepository = $productRepository;
        //$this->accountRepository = $productRepository;

    }

    public function index(Request $request)
    {

        $data = [
            'products' => $request->user()->account->products->toArray(),
            'supplies' => $request->user()->account->supplies()->get(),
            'categories' => $request->user()->account->categories->toArray(),
        ];

        return $this->sendResponse($data, 'Categories retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Validate that 'name' and 'account_id' exist
        $validator = \Validator::make($input, [
            'name' => 'required|string|max:255',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'sometimes|required|regex:/^\d+(\.\d{1,2})?$/',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Check if category already exists for the given account_id
        $existingProduct = $this->productRepository->all([
            'name' => $input['name'],
            'category_id' => $input['category_id'],
        ])->first();

        if ($existingProduct) {
            return $this->sendError('Product already exists for this account.', [], 409);
        }

        $product = $this->productRepository->create($input);

        $supply = $product->supplies()->create([
            'quantity' => $request->quantity
        ]);

        $data = [
            'product' => $product->toArray(),
            'supply' => $supply->toArray(),
        ];

        return $this->sendResponse($data, 'Product saved successfully');
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $data = [
            'product' => $product->toArray(),
            'supplies' => $product->supplies()->get()->toArray(),
        ];

        return $this->sendResponse($data, 'Product retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $validator = \Validator::make($input, [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|regex:/^\d+(\.\d{1,2})?$/',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $product = $this->productRepository->update($input, $id);

        return $this->sendResponse($product->toArray(), 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $this->productRepository->delete($id);

        return $this->sendResponse([], 'Product deleted successfully');
    }

    public function supply($id, Request $request) {

        $validator = \Validator::make($request->all(), [
            'quantity' => 'sometimes|required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $supply = $product->supplies()->create([
            'quantity' => $request->quantity
        ]);

        return $this->sendResponse($supply->toArray(), 'Supply record created successfully');
    }
}
