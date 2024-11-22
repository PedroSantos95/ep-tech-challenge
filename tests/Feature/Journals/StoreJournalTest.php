<?php

namespace Tests\Feature\Journals;

use App\User;
use App\Client;
use App\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreJournalTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_cannot_add_journal_entry()
    {
        $client = Client::factory()->create();

        $this->post(route('journals.store', $client), [
            'text' => 'This is a journal entry',
            'date' => now()->format('Y-m-d'),
        ])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('journals', 0);
    }

    /** @test */
    public function user_can_only_add_journal_to_own_clients()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $this->actingAs($user)
            ->post(route('journals.store', $client), [
                'text' => 'This is a journal entry',
                'date' => now()->format('Y-m-d'),
            ])
            ->assertStatus(404);
    }


    public function authenticated_user_can_create_journal_entry_for_own_client()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->assertEmpty(Journal::all());

        $this->actingAs($user)
            ->post(route('journals.store', $client), [
                'text' => 'This is a journal entry',
                'date' => now()->format('Y-m-d'),
            ])
            ->assertCreated();

        $this->assertCount(1, Journal::all());
        tap(Journal::first(), function ($journal) use ($client) {
            $this->assertEquals('This is a journal entry', $journal->text);
            $this->assertEquals(now()->format('Y-m-d'), $journal->date->format('Y-m-d'));
            $this->assertEquals($client->id, $journal->client_id);
        });
    }
}
