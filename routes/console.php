<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('report:daily-sales')
    ->daily()
    ->emailOutputTo(config('reports.daily_sales_recipients'));
