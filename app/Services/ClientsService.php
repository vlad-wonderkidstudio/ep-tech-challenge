<?php

namespace App\Services;

use App\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class ClientsService
{
    public function getAllUserClients(): array | Collection | null
    {
        $clients = Client::where('user_id', auth()->id())->get();

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }
        return $clients;
    }

    public function getClientWithBookingsById($id): Client | null
    {
        if (!$id) {
            return null;
        }
        $client = Client::where('id', $id)->first();
        if (!$client) {
            return null;
        }

        $client->append('bookings_sorted');
        $client->append('journals_sorted');

        return $client;
    }

    public function createClient(array $inputFields): Client | null
    {
        $client = new Client;
        $client->name = $inputFields['name'];
        $client->email = $inputFields['email'];
        $client->phone = $inputFields['phone'];
        $client->address = $inputFields['address'];
        $client->city = $inputFields['city'];
        $client->postcode = $inputFields['postcode'];
        $client->user_id = auth()->id();
        try {
            $client->save();
        } catch (\Exception $e) {
            Log::error('An error occured -> createClient: ' . $e->getMessage());
            return null;
        }

        return $client;
    }

    public function deleteClient($id): bool | null
    {
        if (!$id) {
            return false;
        }
        $client = Client::find($id);
        if ($client) {
            try {
                $client->bookings()->delete();
                $client->delete();
            } catch (\Exception $e) {
                Log::error('An error occured -> deleteClient: ' . $e->getMessage());
                return null;
            }
            return true;
        }
        return false;
    }

    public function checkClientOwnership($clientId): bool
    {
        return Client::where('id', $clientId)
            ->where('user_id', auth()->id())
            ->exists();
    }
}
