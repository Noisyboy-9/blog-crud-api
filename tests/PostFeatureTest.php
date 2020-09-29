<?php


use App\Models\Post;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PostFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * create a post and persist it to database
     *
     * @param array $attributes
     *
     * @note this function persists data to database
     * @return array
     */
    private function createPost(array $attributes = [])
    {
        $post = Post::factory()->make($attributes);
        $post->save();

        return $post->toArray();
    }

    /**
     * make a post array but don't persist it in database
     *
     * @param array $attributes
     *
     * @note this function does not persist data to database
     * @return array
     */
    private function makePost(array $attributes = [])
    {
        return Post::factory()->make($attributes)->toArray();
    }


    /** @test * */
    public function a_post_can_be_created()
    {
        $post = $this->makePost();

        $this->post('/posts', $post)
            ->seeJson(['created' => true])
            ->seeJson(['data' => $post])
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

}
