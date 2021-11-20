<?php
declare(strict_types=1);
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    public const FAKE_USER_ID = 42;

    /**
     * Pull the first api token from the database and inject it into the headers of the test HTTP request.
     *
     * Usage in a test:
     * ```
     * $response = $this->authenticated()->json('/api/something', ['data' => 'here']);
     * // assert something about the response
     * ```
     *
     * FIXME: there is probably a better way than disabling all middleware. Ideally we would inject a user + token into
     * the test database, and inject the Bearer header?
     *
     * @return self
     */
    public function withFakeAuthentication(): self
    {
        return $this->withoutMiddleware()
            ->actingAs(User::factory()->make(['id' => self::FAKE_USER_ID]));
    }
}
