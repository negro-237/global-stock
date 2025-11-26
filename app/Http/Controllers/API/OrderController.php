<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\{ TransactionRepository, OrderRepository, CustomerRepository };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use PDF;

class OrderController extends BaseController
{
    protected $orderRepository;
    protected $customerRepository;
    protected $transactionRepository;

    public function __construct(
            OrderRepository $orderRepository,
            TransactionRepository $transactionRepository,
            CustomerRepository $customerRepository
        )
    {
        //$this->middleware(['role:admin|sondeur|operateur|client|analyste laboratoire']);
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->all(
            ['account_id' => $request->user()->account_id]
        );

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Validate that 'customer_id' and 'account_id' exist
        $validator = \Validator::make($input, [
            'customer_id' => 'required|integer|exists:customers,id',
            'products' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input['account_id'] = $request->user()->account_id;

        $order = DB::transaction(function () use ($input) {

            $order = $this->orderRepository->create($input);

            foreach($input['products'] as $product) {
                $order->transactions()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);
            }

            return $order;
        });

        return $this->sendResponse($order->toArray(), 'Order saved successfully');
    }

    public function show($id)
    {
        $order = $this->orderRepository->find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');
    }

    public function destroy($id)
    {
        $order = $this->orderRepository->find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        $this->orderRepository->delete($id);

        return $this->sendResponse($id, 'Order deleted successfully');
    }

    public function downloadFacture(Order $order)
    {
        $pdf = PDF::loadView('pdf.facture', ['order' => $order]);
        $filename = 'facture_'.$order->id.'.pdf';

        return $pdf->download($filename);
    }
}
