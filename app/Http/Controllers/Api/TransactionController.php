<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTransactionRequest;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\Category;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /** @var TransactionService  */
    protected $transactionService;

    /**
     * TransactionController constructor.
     */
    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Category $category, Request $request)
    {
        $validator = Validator::make($request->all(), with(new CreateTransactionRequest())->rules());
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->toArray());
        }

        /** @var User $user */
        $user = Auth::user();
        $transaction = $this->transactionService->create($user, $category, $request->all());

        return new JsonResponse(['transaction' => new TransactionResource($transaction)], 201);
    }
}
