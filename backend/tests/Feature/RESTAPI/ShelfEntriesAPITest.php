<?php

namespace Tests\Feature\RESTAPI;

use App\Models\GlobalUnit;
use App\Models\ShelfEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\ShelfEntriesTestHelper;
use Tests\TestCase;

class ShelfEntriesAPITest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const ENDPOINT = '/api/shelf';

    protected User $user1;
    protected User $user2;
    protected $unitId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->unitId = GlobalUnit::first()->id;
    }

    protected function createEntry(User $user, $data = [])
    {
        if($data === [])
            $data = ShelfEntriesTestHelper::generateShelfEntryData($this->faker, $this->unitId);
        return $this->actingAs($user)->postJson(self::ENDPOINT, $data);
    }

    public function test_user_can_create_shelf_entry()
    {
        $data = ShelfEntriesTestHelper::generateShelfEntryData($this->faker, $this->unitId);
        $response = $this->createEntry($this->user1, $data);
        $response->assertStatus(201);
        $this->assertDatabaseCount('shelf_entries', 1);
    }

    public function test_user_can_list_own_entries()
    {
        $this->createEntry($this->user1);
        $response = $this->actingAs($this->user1)->getJson(self::ENDPOINT);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_user_can_update_entry()
    {
        $entry = $this->createEntry($this->user1)->json('data');
        $newData = ShelfEntriesTestHelper::generateShelfEntryData($this->faker, $this->unitId);
        $response = $this->actingAs($this->user1)->putJson(self::ENDPOINT.'/'.$entry['id'], $newData);
        $response->assertStatus(200);
    }

    public function test_user_can_delete_entry()
    {
        $entry = $this->createEntry($this->user1)->json('data');
        $response = $this->actingAs($this->user1)->deleteJson(self::ENDPOINT.'/'.$entry['id']);
        $response->assertStatus(200);
        $this->assertDatabaseCount('shelf_entries', 0);
    }

    public function test_user_cannot_access_other_users_entry()
    {
        $entry = $this->createEntry($this->user1)->json('data');
        $response = $this->actingAs($this->user2)->getJson(self::ENDPOINT.'/'.$entry['id']);
        $response->assertStatus(403);
    }
}
