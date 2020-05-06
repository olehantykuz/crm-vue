<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTransactionRequest;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\Category;
use App\Models\User;
use App\Services\Bill\BillServiceFactory;
use App\Services\TransactionService;
use Carbon\Carbon;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $month = (int) $request->get('month') ?: Carbon::now()->month;
        $year = (int) $request->get('year') ?: Carbon::now()->year;
        /** @var User $user */
        $user = Auth::user();
        $billService = BillServiceFactory::getInstance();
        $transactions = $this->transactionService
            ->getUserTransactionsByDate($user, $month, $year);

        return new JsonResponse([
            'transactions' => TransactionResource::collection($transactions),
            'totals' => $billService->getTotalAmountInCurrencies($user->id, $month, $year, true),
        ]);
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
        $billService = BillServiceFactory::getInstance();
        $date = Carbon::now();

        return new JsonResponse([
            'transaction' => new TransactionResource($transaction),
            'totals' => $billService->getTotalAmountInCurrencies($user->id, $date->month, $date->year, true),
        ], 201);
    }

}
