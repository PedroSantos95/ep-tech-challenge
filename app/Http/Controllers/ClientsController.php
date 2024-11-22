<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientsController extends Controller
{
    public function index(): View
    {
        $clients = auth()->user()->clients;

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function show(int $client): View
    {
        $client = Client::with([
            'bookings' => function ($query) {
                return $query->select('id', 'start', 'end', 'notes', 'client_id')->orderByDesc('start');
            },
            'journals' => function ($query) {
                $query->orderByDesc('date');
            }
        ])
            ->findOrFail($client);

        $this->authorize('view', $client);

        return view('clients.show', ['client' => $client]);
    }

    public function store(StoreClientRequest $request): Client
    {
        $client = new Client;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->city = $request->get('city');
        $client->postcode = $request->get('postcode');
        $client->user_id = auth()->id();
        $client->save();

        return $client;
    }

    public function destroy(int $client): JsonResponse
    {
        $client = Client::findOrFail($client);

        $this->authorize('delete', $client);

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully.'
        ], 200);
    }
}
