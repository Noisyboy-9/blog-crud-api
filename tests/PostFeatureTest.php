<?php


use App\Models\Post;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PostFeatureTest extends TestCase
{
    use  DatabaseTransactions;

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
            return $posts->toArray()[0];
        }

        return $posts->toArray();
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
    public function all_posts_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        $posts = $this->createPost([], 10);

        $this->get('/posts')
            ->seeJsonContains(['data' => $posts])
            ->assertResponseStatus(200);
    }

    /** @test * */
    public function a_post_can_be_created()
    {
        $post = $this->makePost();

        $this->post('/posts', $post)
            ->seeJson(['created' => true])
            ->assertResponseStatus(201);

        $this->seeInDatabase('posts', $post);
    }

    /** @test * */
    public function a_post_must_have_a_title()
    {
        $post = $this->makePost(['title' => null]);

        $this->post('/posts', $post)->assertResponseStatus(422);


        $this->notSeeInDatabase('posts', $post);
    }

    /** @test * */
    public function a_post_must_have_a_body()
    {
        $post = $this->makePost(['body' => null]);

        $this->post('/posts', $post)->assertResponseStatus(422);

        $this->notSeeInDatabase('posts', $post);
    }

    /** @test * */
    public function a_post_title_must_be_unique()
    {
        $post1 = $this->createPost(['title' => 'same title']);
        $post2 = $this->makePost(['title' => 'same title']); // creating 2 posts with same titles

        $this->post('/posts', $post2)->assertResponseStatus(422);
        $this->notSeeInDatabase('posts', $post2);
    }

    /** @test * */
    public function a_post_can_be_fetched()
    {
        $post = $this->createPost();


        $this->get("/posts/{$post['id']}")
            ->seeJson(['data' => $post])
            ->assertResponseOk();
    }

    /** @test * */
    public function a_post_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $oldPost = $this->createPost();

        $newPost = [
            'title' => 'new post title',
            'body' => 'new post body',
        ];

        $this->put("/posts/{$oldPost['id']}", $newPost)
            ->seeJson(['updated' => true])
            ->assertResponseStatus(200);


        $this->seeInDatabase('posts', $newPost);
        $this->notSeeInDatabase('posts', $oldPost);
    }

    /** @test * */
    public function a_title_is_required_for_updating_a_post()
    {
        $oldPost = $this->createPost();

        $newPost = $this->makePost([
            'title' => null // bad title
        ]);

        $this->put("/posts/{$oldPost['id']}", $newPost)->assertResponseStatus(422);

        $this->seeInDatabase('posts', $oldPost);
        $this->notSeeInDatabase('posts', $newPost);
    }

    /** @test * */
    public function a_body_is_required_for_updating_a_post()
    {
        $oldPost = $this->createPost();

        $newPost = $this->makePost([
            'body' => null // bad body
        ]);

        $this->put("/posts/{$oldPost['id']}", $newPost)->assertResponseStatus(422);

        $this->seeInDatabase('posts', $oldPost);
        $this->notSeeInDatabase('posts', $newPost);
    }

    /** @test * */
    public function a_post_title_must_be_unique_to_update()
    {
        $oldPost = $this->createPost(['title' => 'same title']);
        $newPost = $this->makePost(['title' => 'same title']);
        $this->put("/posts/{$oldPost['id']}", $newPost)->assertResponseStatus(422);

        $this->seeInDatabase('posts', $oldPost);
        $this->notSeeInDatabase('posts', $newPost);
    }

    /** @test * */
    public function a_post_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $post = $this->createPost();

        $this->delete("/posts/{$post['id']}")
            ->assertResponseStatus(200);

        $this->notSeeInDatabase('posts', $post);
    }
}
