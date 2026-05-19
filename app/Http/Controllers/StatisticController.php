<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StatisticController
{
    public function index(){
        return Inertia::render('pages/statistic/index');
    }
}
