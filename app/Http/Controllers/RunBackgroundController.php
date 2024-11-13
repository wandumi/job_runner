<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\RunBackgroundJob;

class RunBackgroundController extends Controller
{
    public function runJob()
    {
        $data = ['message' => 'This is a background job!'];

        runBackgroundJob(RunBackgroundJob::class, [$data]);

        return redirect('/')->with('success', 'Job executed in the background!');

    }
}