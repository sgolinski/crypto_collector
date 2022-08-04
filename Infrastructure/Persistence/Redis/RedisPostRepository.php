<?php

namespace App\Infrastructure\Persistence\Redis;

class RedisPostRepository implements PostRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function save(Post $aPost)
    {
        $this->client->hset(
            'posts',
            (string)$aPost->id(),
            serialize($aPost)
        );
    }

    public function remove(Post $aPost)
    {
        $this->client->hdel('posts', (string)$aPost->id());
    }

    public function postOfId(PostId $anId)
    {
        if ($data = $this->client->hget('posts', (string)$anId)) {
            return unserialize($data);
        }

        return null;
    }

    public function latestPosts(\DateTimeImmutable $sinceADate)
    {
        $latest = $this->filterPosts(
            function (Post $post) use ($sinceADate) {
                return $post->createdAt() > $sinceADate;
            }
        );

        $this->sortByCreatedAt($latest);

        return array_values($latest);
    }

    private function filterPosts(callable $fn)
    {
        return array_filter(array_map(
            function ($data) {
            return unserialize($data);
        },
            $this->client->hgetall('posts')
        ), $fn);
    }

    private function sortByCreatedAt(&$posts)
    {
        usort($posts, function (Post $a, Post $b) {
            if ($a->createdAt() == $b->createdAt()) {
                return 0;
            }
            return ($a->createdAt() < $b->createdAt()) ? -1 : 1;
        });
    }

    public function nextIdentity()
    {
        return new PostId();
    }
}
