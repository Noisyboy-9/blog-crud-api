<?php


use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRegisterFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     *  create user and persist them to database
     *
     * @note this function saves the usres to database
     *
     *
     * @param array $attributes
     * @param int   $count
     *
     * @return array
     */
    private function createUser(array $attributes = [], int $count = 1): array
    {
        $users = User::factory()->count($count)->make($attributes);

        foreach ($users as $user) {
            $user->save();
        }

        if ($count === 1) {
            return $users->toArray()[0];
        }

        return $users->toArray();
    }


    /**
     * make users but don't persist them to database
     *
     * @note this function does not persist data to database
     *
     * @param array $attributes
     * @param int   $count
     *
     * @return array
     */
    private function makeUser(array $attributes = [], int $count = 1): array
    {
        $users = User::factory()->count($count)->make();

        if ($count === 1) {
            return $users->toArray()[0];
        }

        return $users->toArray();
    }

    /** @test * */
    public function a_user_can_be_created_and_a_access_token_will_be_stored_and_returned()
    {
        $this->withoutExceptionHandling();

        $user = [
            'username' => 'noisyboy',
            'email' => 'sina.shariati@yahoo.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $user)
            ->seeJsonStructure(['created' , 'access_token'])
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

    /** @test * */
    public function an_username_is_required_to_create_a_user()
    {
        $user = $this->makeUser(['username' => null]); // bad username

        $this->post('/register', $user)->assertResponseStatus(422);
        $this->notSeeInDatabase('users', $user);
    }

    /** @test * */
    public function an_username_must_be_unique_to_create_a_user()
    {
        $user1 = $this->createUser(['username' => 'same_username']);
        $user2 = $this->makeUser(['username' => 'same_username']);

        $this->post('/register', $user2)->assertResponseStatus(422);
        $this->notSeeInDatabase('users', $user2);
    }

    /** @test * */
    public function an_email_is_required_to_create_a_user()
    {
        $user = $this->makeUser(['email' => null]); // bad email
        $this->post('/register', $user)
            ->assertResponseStatus(422);

        $this->notSeeInDatabase('users', $user);
    }

    /** @test * */
    public function an_email_must_be_unique_to_create_user()
    {
        $user1 = $this->createUser(['email' => 'same.email@yahoo.com']);
        $user2 = $this->makeUser(['email' => 'same.email@yahoo.com']);

        $this->post('/register', $user2)->assertResponseStatus(422);
        $this->notSeeInDatabase('users', $user2);
    }

    /** @test * */
    public function a_password_is_required_to_create_a_user()
    {
        $user = $this->makeUser(['password' => null]); // bad password
        $this->post('/register', $user)
            ->assertResponseStatus(422);

        $this->notSeeInDatabase('users', $user);
    }

    /** @test * */
    public function a_password_must_be_confirmed_to_create_a_user()
    {
        $user = [
            'username' => 'noisyboy',
            'email' => 'sina.shariati@yahoo.com',
            'password' => 'password',
            'password_confirmation' => null,
        ];

        $this->post('/register', $user)->assertResponseStatus(422);

        $this->notSeeInDatabase('users', [
            'username' => 'noisyboy',
            'email' => 'sina.shariati@yahoo.com',
            'password' => 'password',
        ]);
    }
}
