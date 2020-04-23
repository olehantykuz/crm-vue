<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateCategoryRequest;
use App\Http\Resources\Category as CategoryResource;
use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /** @var CategoryService  */
    protected $categoryService;

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $categories = $this->categoryService->getAllByUser($user);

        return new JsonResponse(['categories' => CategoryResource::collection($categories)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), with(new CreateCategoryRequest())->rules());
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->toArray());
        }

        /** @var User $user */
        $user = Auth::user();
        $category = $this->categoryService->create($request->all(), $user);

        return new JsonResponse(['category' => new CategoryResource($category)], 201);
    }
}
