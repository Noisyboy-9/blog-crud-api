<?php


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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

    /** @test * */
    public function a_created_user_has_hashed_password()
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


        $encryptedPassword = DB::table('users')->first()->password;
        $decryptedPassword = Crypt::decrypt($encryptedPassword);

        $this->assertEquals($decryptedPassword, $user['password']);
    }
}
