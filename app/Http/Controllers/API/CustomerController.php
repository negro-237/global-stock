<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\{ CustomerRepository, ProductRepository, OrderRepository };
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    protected $customerRepository;
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
            CustomerRepository $customerRepository,
            ProductRepository $productRepository,
            OrderRepository $orderRepository
        )
    {
        //$this->middleware(['role:admin|sondeur|operateur|client|analyste laboratoire']);
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository->all(
            ['account_id' => $request->user()->account_id]
        )->sortBy('name')->values();

        $products = $this->productRepository->all(
            ['account_id' => $request->user()->account_id]
        )->sortBy('name')->values();

        $orders = $this->orderRepository->all(
            ['account_id' => $request->user()->account_id]
        )->sortBy('name')->values();

        $data = [
            'customers' => $customers,
            'products' => $products,
            'orders' => $orders
        ];

        return $this->sendResponse($data, 'Customers retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Validate that 'name' and 'account_id' exist
        $validator = \Validator::make($input, [
            'name' => 'required|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Check if customer already exists for the given account_id
        $existingCustomer = $this->customerRepository->all([
            'name' => $input['name'],
            'account_id' => $request->user()->account_id,
        ])->first();

        if ($existingCustomer) {
            return $this->sendError('Customer already exists for this account.', [], 409);
        }

        $input['account_id'] = $request->user()->account_id;

        $customer = $this->customerRepository->create($input);

        return $this->sendResponse($customer->toArray(), 'Customer saved successfully');
    }

    public function show($id)
    {
        $customer = $this->customerRepository->find($id);

        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }

        return $this->sendResponse($customer->toArray(), 'Customer retrieved successfully');
    }

    public function destroy($id)
    {
        $customer = $this->customerRepository->find($id);

        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }

        $this->customerRepository->delete($id);

        return $this->sendResponse([], 'Customer deleted successfully');
    }

}
