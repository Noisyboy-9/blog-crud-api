<?php


use Laravel\Lumen\Testing\DatabaseTransactions;

class PostFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /** @test * */
    public function a_post_can_be_created()
    {
        $this->withoutExceptionHandling();

        $post = [
            'title' => 'post title',
            'body' => 'post body'
        ];

        $this->post('/posts', $post)->assertResponseStatus(201);

        $this->seeInDatabase('posts', $post);
    }
}
