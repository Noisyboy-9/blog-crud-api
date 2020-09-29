<?php


use Laravel\Lumen\Testing\DatabaseTransactions;

class UserFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /** @test * */
    public function a_user_can_be_created()
    {
        $this->withoutExceptionHandling();

        $user = [
            'username' => 'noisyboy',
            'email' => 'sina.shariati@yahoo.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $user)
            ->assertResponseStatus(201);

        $this->seeInDatabase('users', [
            'username' => 'noisyboy',
            'email' => 'sina.shariati@yahoo.com',
        ]);
    }
}
