<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Http\Controllers\Controller;
use App\Services\JournalsService;
use App\Services\ClientsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\Json;

class JournalsController extends Controller
{
    protected $journalsService;
    protected $clientService;

    public function __construct(JournalsService $journalsService, ClientsService $clientService)
    {
        $this->journalsService = $journalsService;
        $this->clientService = $clientService;
    }

    public function index($clientId): JsonResponse
    {
        if (!$clientId) {
            return response()->json(['message' => 'Client ID is required'], 400);
        }
        if (! $this->clientService->checkClientOwnership($clientId)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $journals = $this->journalsService->getAllJournalsForClient($clientId);
        return ($journals)
            ? response()->json([
                'success' => true,
                'data' => $journals
            ])
            : response()->json([
                'success' => false,
                'message' => 'An error occurred while getting journals'
            ], 500);
    }

    public function store(Request $request, $clientId): JsonResponse
    {
        if (!$clientId) {
            return response()->json(['message' => 'Client ID is required'], 400);
        }
        if (! $this->clientService->checkClientOwnership($clientId)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'text' => 'required|string',
        ]);

        $journal = $this->journalsService->createJournal($request->all(), $clientId);
        return ($journal)
            ? response()->json([
                'success' => true,
                'message' => 'Journal saved',
                'data' => $journal
            ], 201)
            : response()->json([
                'success' => false,
                'message' => 'An error occurred',
            ], 500);
    }

    public function destroy($clientId, $id): JsonResponse
    {
        if (!$id) {
            return response()->json(['message' => 'Journal ID is required'], 400);
        }
        if (!$this->clientService->checkClientOwnership($clientId) || !$this->journalsService->checkJournalOwnership($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->journalsService->deleteJournal($id);
        return $deleted
            ? response()->json([
                'success' => true,
                'message' => 'Journal deleted'
            ])
            : response()->json(['message' => 'An error occured'], 500);
    }
}
