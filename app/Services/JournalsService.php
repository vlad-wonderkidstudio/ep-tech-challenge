<?php

namespace App\Services;

use App\Journal;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JournalsService
{
    public function getAllJournalsForClient($clientId): array | Collection | null
    {
        return Journal::where('client_id', $clientId)->get();
    }

    public function createJournal(array $data, $clientId): Journal | null
    {
        return Journal::create([
            'client_id' => $clientId,
            'date' => Carbon::now()->toDateString(),
            'text' => $data['text'],
        ]);
    }

    public function deleteJournal($id): bool | null
    {
        if (!$id) {
            return false;
        }

        $journal = Journal::find($id);
        if ($journal) {
            try {
                $journal->delete();
            } catch (\Exception $e) {
                Log::error('An error occured -> deleteBooking: ' . $e->getMessage());
                return null;
            }
            return true;
        }
        return false;
    }

    public function checkJournalOwnership($journalId): bool
    {
        $journal = Journal::find($journalId);
        if (!$journal) {
            return false;
        }
        return $journal->client->user_id === auth()->id();
    }
}
