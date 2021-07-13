<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPostsRequest;
use App\Services\PostService;
use App\Services\QueryService;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    private $queryService;

    private $postService;

    public function __construct(QueryService $queryService,
                                PostService $postService)
    {
        $this->queryService = $queryService;
        $this->postService = $postService;
    }

    public function getPosts(GetPostsRequest $request)
    {
        // TODO: 實作查詢貼文 API
        try {
            list($page,
                $limit,
                $user_id,
                $category,
                $keyword,
                $start_date,
                $end_date) = $this->queryService->getPostsQueryParams($request);

            $builder = $this->postService->getPostsByParams(
                $user_id,
                $category,
                $keyword,
                $start_date,
                $end_date);

            $result = $this->postService->getPostsPaginateByBuilder($builder, $page, $limit);

            return response()->json([
                'status' => 200,
                'data' => Arr::get($result, 'posts'),
                'total_pages' => Arr::get($result, 'total_pages'),
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {

        }
    }
}
