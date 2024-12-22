<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    protected $clientService;

    public function __construct(ClientsService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllUserClients();
        return ($clients)
            ? response()->json([
                'success' => true,
                'data' => $clients
            ])
        : response()->json([
            'success' => false,
            'message' => 'An error occurred while getting clients'
        ], 500);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required_without:phone|nullable|email|regex:' . config('validation.regex.email'),
            'phone' => 'required_without:email|nullable|regex:' . config('validation.regex.phone'),
        ], [
            'email.regex' => 'The email must be a valid email address.',
            'phone.regex' => 'The phone number can only contain digits, spaces, and a plus sign.',
        ]);

        $client = $this->clientService->createClient($request->all());
        return ($client)
            ? response()->json([
                'success' => true,
                'message' => 'Client saved',
                'data' => $client
            ], 201)
            : response()->json([
                'success' => false,
                'message' => 'An error occurred',
            ], 500);
    }

    public function destroy($id): JsonResponse
    {
        if (!$id) {
            return response()->json(['message' => 'Client ID is required'], 400);
        }
        if (!$this->clientService->checkClientOwnership($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->clientService->deleteClient($id);
        return ($deleted)
            ? response()->json([
                'success' => true,
                'message' => 'Client deleted'
            ])
            : response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
    }
}