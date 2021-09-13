<?php

namespace App\Imports;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     *
     * @return null
     */
    public function collection(Collection $collection)
    {
        $collection->each(function($row){
            if (Storage::exists('unzipped/'.$row['opilase_isikukood'].'.jpg')) {
                if (Ticket::where('code', $row['opilase_isikukood'])->exists()) {
                    Ticket::where('code', $row['opilase_isikukood'])->update([
                        'name' => $row['opilase_nimi'],
                        'picture' => Storage::get('unzipped/'.$row['opilase_isikukood'].'.jpg'),
                    ]);

                    return 0;
                }

                Ticket::create([
                    'name' => $row['opilase_nimi'],
                    'code' => $row['opilase_isikukood'],
                    'picture' => Storage::get('unzipped/'.$row['opilase_isikukood'].'.jpg'),
                    'ticket_nr' => $this->getLatestTiceketNr() + 1,
                    'code_3' => $this->generateNewCode(),
                    'status' => 'I'
                ]);
            }
            return 0;
        });

        return 0;
    }

    protected function getLatestTiceketNr()
    {
        return intval(Ticket::latest('ticket_nr')->pluck('ticket_nr')->first());
    }

    protected function generateNewCode()
    {
        return intval('92332021000'. $this->getLatestTiceketNr() + 1 .'8');    
    }
}
