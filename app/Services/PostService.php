<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPostsByParams(
        $user_id,
        $category,
        $keyword,
        $start_date,
        $end_date
    )
    {
        $query_builder = $this->post->query();

        if (!empty($user_id)
            || !empty($category)
            || !empty($keyword)
            || (!empty($start_date) && !empty($end_date))) {

            if (!empty($user_id)) {
                $query_builder = $this->post->where('user_id', $user_id);
            }

            if (!empty($category)) {
                $query_builder = empty($query_builder)
                    ? $this->post->where('category', $category)
                    : $query_builder->where('category', $category);
            }

            if (!empty($keyword)) {
                $query_builder = empty($query_builder)
                    ? $this->post->where('content', 'like', '%' . $keyword . '%')
                    : $query_builder->where('content', 'like', '%' . $keyword . '%');
            }

            if (!empty($start_date) && !empty($end_date)) {
                $query_builder = empty($query_builder)
                    ? $this->post->where(function ($query) use ($start_date, $end_date) {
                        $query->where('published_at', '>=', $start_date)
                            ->where('published_at', '<=', $end_date);
                    })
                    : $query_builder->where(function ($query) use ($start_date, $end_date) {
                        $query->where('published_at', '>=', $start_date)
                            ->where('published_at', '<=', $end_date);
                    });
            }
        }

        return $query_builder;
    }

    public function getPostsPaginateByBuilder($builder, $page, $limit)
    {
        $posts_count = $builder->count();

        $total_pages = ceil($posts_count / $limit);

        $posts = $builder->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();

        return [
            'posts' => $posts,
            'total_pages' => $total_pages,
        ];
    }
}
