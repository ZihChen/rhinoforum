<?php

namespace Tests;

trait FakePostsDataTrait
{
    public function generatePost_a()
    {
        return [
            'user_id' => 1,
            'category' => 'Tech',
            'content' => 'Launch A Tesla Roadster To Mars On The Falcon Heavy Rocket ',
            'published_at' => today(),
        ];
    }

    public function generatePost_b()
    {
        return [
            'user_id' => 2,
            'category' => 'Financial',
            'content' => 'Bank of England eyes power of Amazon, Microsoft and Google in finance',
            'published_at' => today()->addDays(1),
        ];
    }

    public function generatePost_c()
    {
        return [
            'user_id' => 3,
            'category' => 'Tech',
            'content' => 'Tesla’s Self-driving technology will capture signals, hazards & listen sirens, alarms',
            'published_at' => today()->addDays(1),
        ];
    }

    public function generatePost_d()
    {
        return [
            'user_id' => 4,
            'category' => 'Financial',
            'content' => 'Business Nasdaq, S&P 500 scale new peaks as focus turns to earnings, economic data',
            'published_at' => today()->addDays(2),
        ];
    }

    public function generatePost_e()
    {
        return [
            'user_id' => 4,
            'category' => 'Design',
            'content' => 'Accessible School Design Standards and Guidelines',
            'published_at' => today()->addDays(3),
        ];
    }
}
