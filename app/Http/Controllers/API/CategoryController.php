<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\CategoryRepository;

class CategoryController extends BaseController
{
    protected $categoryRepository;

     public function __construct(
            CategoryRepository $categoryRepository,
        )
    {
            //$this->middleware(['role:admin|sondeur|operateur|client|analyste laboratoire']);
            $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {

        $categories = $this->categoryRepository->all(
            ['account_id' => $request->user()->account_id]
        );

        return $this->sendResponse($categories->toArray(), 'Categories retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Validate that 'name' and 'account_id' exist
        $validator = \Validator::make($input, [
            'name' => 'required|string|max:255',
            //'account_id' => 'required|integer|exists:accounts,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Check if category already exists for the given account_id
        $existingCategory = $this->categoryRepository->all([
            'name' => $input['name'],
            'account_id' => $request->user()->account_id,
        ])->first();

        if ($existingCategory) {
            return $this->sendError('Category already exists for this account.', [], 409);
        }

        $input['account_id'] = $request->user()->account_id;

        $category = $this->categoryRepository->create($input);

        return $this->sendResponse($category->toArray(), 'Category saved successfully');
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        // Validate that 'name' exists
        $validator = \Validator::make($input, [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        // Check if another category with the same name exists for the given account_id
        $existingCategory = $this->categoryRepository->all([
            'name' => $input['name'],
            'account_id' => $category->account_id,
        ])->first();

        if ($existingCategory && $existingCategory->id != $id) {
            return $this->sendError('Another category with this name already exists for this account.', [], 409);
        }

        $category = $this->categoryRepository->update($input, $id);

        return $this->sendResponse($category->toArray(), 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        $this->categoryRepository->delete($id);

        return $this->sendResponse([], 'Category deleted successfully');
    }
}
