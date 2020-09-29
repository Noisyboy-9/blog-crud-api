<?php


use App\Models\Post;

class PostUnitTest extends TestCase
{
    /**
     * create a post and persist it to database
     *
     * @param array $attributes
     *
     * @param int   $count
     *
     * @return array
     * @note this function persists data to database
     */
    private function createPost(array $attributes = [], int $count = 1)
    {
        $posts = Post::factory()->count($count)->make($attributes);
        foreach ($posts as $post) {
            $post->save();
        }

        if ($count === 1) {
            return $posts[0];
        }

        return $posts;
    }

    /**
     * make a post array but don't persist it in database
     *
     * @param array $attributes
     *
     * @param int   $count
     *
     * @return array
     * @note this function does not persist data to database
     */
    private function makePost(array $attributes = [], int $count = 1)
    {
        $posts = Post::factory()->count($count)->make($attributes);
        // because we have a count function chained to $posts definition an array of posts will be returned so we have to check if the count is 1 to return the just the array containing a title and body

        if ($count === 1) {
            return $posts->toArray()[0];
        }

        return $posts->toArray();
    }

    /** @test * */
    public function it_knows_its_route()
    {
        $post = $this->createPost();
        $postId = $post->id;

        $this->assertEquals($post->path(), "/posts/{$postId}");
    }
}
