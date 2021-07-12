<?php

namespace App\Services;

use Illuminate\Http\Request;

class QueryService
{
    public function getPostsQueryParams(Request $request)
    {
        return [
            $current_page = $request->get('page', 1),
            $limit = $request->get('limit', 10),
            $user_id = $request->get('user_id'),
            $category = $request->get('category'),
            $keyword = $request->get('keyword'),
            $start_date = $request->get('start_date'),
            $end_date = $request->get('end_date'),
        ];
    }
}
