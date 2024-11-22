<?php

namespace Tests\Feature\Journals;

use App\User;
use App\Client;
use App\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteJournalTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function authenticated_user_can_delete_their_own_journals()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $journal = Journal::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)
            ->delete(route('journals.destroy', [$client, $journal]));

        $response->assertOk();
        $this->assertDeleted($journal);
    }

    /** @test */
    public function unauthenticated_user_cannot_delete_journal()
    {
        $client = Client::factory()->create();
        $journal = Journal::factory()->create(['client_id' => $client->id]);

        $response = $this->delete(route('journals.destroy', [$client, $journal]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('journals', ['id' => $journal->id]);
    }

    /** @test */
    public function user_can_only_delete_journals_of_own_clients()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $journal = Journal::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)
            ->delete(route('journals.destroy', [$client, $journal]));

        $response->assertForbidden();
        $this->assertDatabaseHas('journals', ['id' => $journal->id]);
    }
}
