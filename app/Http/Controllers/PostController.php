<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPostsRequest;
use App\Services\PostService;
use App\Services\QueryService;

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

        list($posts, $total_pages) = $this->postService->getPostsPaginateByBuilder($builder, $page, $limit);

        return response()->json([
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => $total_pages,

        ]);
    }
}
