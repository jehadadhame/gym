<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Purchase;
use Google\Client;
use Google\Service\Sheets;

class SyncDataToGoogleSheet extends Command
{
    protected $signature = 'sync:google-sheet';
    protected $description = 'Sync purchases (product_id = 17) from MySQL to Google Sheets';

    public function handle()
    {
        $this->info('Starting sync...');

        // Step 1: Fetch data using the Purchase model
        $rows = Purchase::where('product_id', 17)
            ->select('id', 'member_id', 'product_id', 'invoice_id', 'created_at', 'updated_at', 'created_by', 'updated_by')
            ->get();

        if ($rows->isEmpty()) {
            $this->info('No purchases found for product_id = 17.');
            return;
        }

        // Step 2: Prepare Google Sheets API client
        $client = new Client();
        $client->setApplicationName('Laravel Google Sheets Sync');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $service = new Sheets($client);

        // Your Google Sheet info
        $spreadsheetId = '1bS7LHd5pS3WMJAQzSY8fEJtcblgdBjvHcku5MdUg0wY'; // Replace this
        $range = 'Sheet1!A1'; // Starting cell

        // Step 3: Prepare header + data
        $values = [];

        // Add header row (column names)
        $values[] = ['id', 'member_id', 'product_id', 'invoice_id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

        // Add data rows
        foreach ($rows as $row) {
            $values[] = [
                $row->id,
                $row->member_id,
                $row->product_id,
                $row->invoice_id,
                $row->created_at,
                $row->updated_at,
                $row->created_by,
                $row->updated_by,
            ];
        }

        // Step 4: Send data to Google Sheets
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];

        $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

        $this->info('Data synced successfully to Google Sheets!');
    }
}
