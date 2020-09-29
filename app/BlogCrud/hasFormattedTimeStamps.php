<?php

namespace App\BlogCrud;

use Carbon\Carbon;

trait hasFormattedTimeStamps
{

    public function getCreatedAtAttribute($date)
    {

        $test = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $date)->format('d-m-y');
        dd($test);

    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }
}
