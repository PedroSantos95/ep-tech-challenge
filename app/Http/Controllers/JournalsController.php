<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJournalRequest;
use App\Client;
use App\Journal;
use App\Http\Requests\ShowJournalRequest;
use Illuminate\View\View;

class JournalsController extends Controller
{
    public function index(): View
    {
        $clients = auth()->user()->clients;

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create(int $client): View
    {
        $client = auth()->user()->clients()->findOrFail($client);

        return view('journals.create', ['client' => $client]);
    }

    public function store(StoreJournalRequest $request, int $client_id): Journal
    {
        $client = auth()->user()->clients()->findOrFail($client_id);

        $journal = new Journal;
        $journal->date = $request->get('date');
        $journal->text = $request->get('text');
        $journal->client_id = $client->id;
        $journal->save();

        return $journal;
    }

    public function show(Request $request, int $client): View
    {
        $client = auth()->user()->clients()
            ->with([
                'journals' => function ($query) {
                    $query->orderByDesc('date');
                }
            ])
            ->findOrFail($client);

        $this->authorize('view', $client);

        return view('clients.show', ['client' => $client]);
    }

    public function destroy(Client $client, Journal $journal): JsonResponse
    {
        $this->authorize('delete', $journal);

        $journal->delete();

        return response()->json([
            'message' => 'Journal deleted successfully.'
        ], 200);
    }
}
