<?php

namespace App\Http\Controllers;

use App\Services\ClientsService;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class ClientsController extends Controller
{
    protected $clientService;

    public function __construct(ClientsService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): View | Factory
    {
        $clients = $this->clientService->getAllUserClients();
        return view('clients.index', ['clients' => $clients]);
    }

    public function create(): View | Factory
    {
        return view('clients.create');
    }

    public function show($client): View | Factory
    {
        $client = $this->clientService->getClientWithBookingsById($client);
        if (!$client) {
            abort(404, 'Client not found');
        }
        return view('clients.show', ['client' => $client]);
    }
}
