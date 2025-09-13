<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_driver_account_enrollment(): void
    {
        for($i = 0; $i < 50; $i++){
            $user = User::factory()->count(1)->create();
            $response = $this->post('/register', $user[$i]);

            $driver = Driver::factory()->count(1)->create();

            $driver->user_id = $user[$i]->id;

            $response = $this->post('/driver/enroll/', $driver);

            $response->assertOk();
        }
    }
}
