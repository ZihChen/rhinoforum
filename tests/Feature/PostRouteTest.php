<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\FakePostsDataTrait;
use Tests\TestCase;


class PostRouteTest extends TestCase
{
    use DatabaseMigrations, FakePostsDataTrait;

    // TODO: 針對 API route 撰寫測試

    /**
     * Test Case：測試取得Post分頁
     * 輸入：建立100個Post並輸入各種分頁參數
     * 預期：每頁筆數與當前頁數符合邏輯
     *
     * @group api
     * @group api.posts
     * @group api.posts.get
     * @group api.posts.get.paginate
     * @return void
     * @throws \Throwable
     */
    public function testGetPostsPaginate()
    {
        Post::factory()->count(100)->create();

        $params = [
            [
                'page' => 1,
                'limit' => 10,
            ],
            [
                'page' => 2,
                'limit' => 15,
            ],
            [
                'page' => 3,
                'limit' => 30,
            ]
        ];

        foreach ($params as $param) {
            $response = $this->get(route('api.posts.read', $param));

            $total_pages = $response->decodeResponseJson()['total_pages'];
            $current_page = $response->decodeResponseJson()['current_page'];

            $expect_total_pages = ceil(100 / Arr::get($param, 'limit'));

            $response->assertJson(['status' => 200]);
            $this->assertEquals($expect_total_pages, $total_pages);
            $this->assertEquals(Arr::get($param, 'page'), $current_page);
            $response->assertJsonStructure([
                'status',
                'data' => [
                    [
                        'user_id',
                        'content',
                        'category',
                        'published_at',
                    ]
                ],
                'total_pages',
                'current_page',
            ]);
        }
    }

    /**
     * Test Case：測試取得Post(keyword + date query)
     * 輸入：建立5個不同屬性的Post，輸入keyword和隔一日的日期
     * 預期：有兩筆包含Tesla的關鍵字，其中只有published_at是隔一日的日期的Post才會被搜到
     *
     * @group api
     * @group api.posts
     * @group api.posts.get
     * @group api.posts.get.keyword_and_date
     * @return void
     * @throws \Throwable
     */
    public function testGetPostsByKeywordAndDuringDate()
    {
        Post::factory()->suspended($this->generatePost_a())->create();  //keyword:Tesla + date:today
        Post::factory()->suspended($this->generatePost_b())->create();
        Post::factory()->suspended($this->generatePost_c())->create();  //keyword:Tesla + date:after 1 day
        Post::factory()->suspended($this->generatePost_d())->create();
        Post::factory()->suspended($this->generatePost_e())->create();

        $params = [
            'keyword' => 'Tesla',
            'start_date' => today()->addDay()->format('Y-m-d H:i:s'),
            'end_date' => today()->addDay()->format('Y-m-d H:i:s'),
            'page' => 1,
            'limit' => 10,
        ];

        $response = $this->get(route('api.posts.read', $params));

        $data = Arr::get($response->decodeResponseJson(), 'data');

        $response->assertJson(['status' => 200]);
        $this->assertEquals(1, count($data));
        $this->assertEquals(3, Arr::get(Arr::first($data), 'id'));
        $response->assertJsonStructure([
            'status',
            'data' => [
                [
                    'user_id',
                    'content',
                    'category',
                    'published_at',
                ]
            ],
            'total_pages',
            'current_page',
        ]);
    }

    /**
     * Test Case：測試取得Post(user_id + category)
     * 輸入：建立5個不同屬性的Post，輸入user_id和Financial的Category
     * 預期：有兩筆user_id:4的資料，其中只有category:Financial的Post才會被搜到
     *
     * @group api
     * @group api.posts
     * @group api.posts.get
     * @group api.posts.get.user_id_and_category
     * @return void
     * @throws \Throwable
     */
    public function testGetPostsByUserIdAndCategory()
    {
        Post::factory()->suspended($this->generatePost_a())->create();
        Post::factory()->suspended($this->generatePost_b())->create();
        Post::factory()->suspended($this->generatePost_c())->create();
        Post::factory()->suspended($this->generatePost_d())->create();  //user_id:4 + category:Financial
        Post::factory()->suspended($this->generatePost_e())->create();  //user_id:4 + category:Design

        $params = [
            'user_id' => 4,
            'category' => 'Financial',
            'page' => 1,
            'limit' => 10,
        ];

        $response = $this->get(route('api.posts.read', $params));

        $data = Arr::get($response->decodeResponseJson(), 'data');

        $response->assertJson(['status' => 200]);
        $this->assertEquals(1, count($data));
        $this->assertEquals(4, Arr::get(Arr::first($data), 'id'));
        $response->assertJsonStructure([
            'status',
            'data' => [
                [
                    'user_id',
                    'content',
                    'category',
                    'published_at',
                ]
            ],
            'total_pages',
            'current_page',
        ]);
    }
}
