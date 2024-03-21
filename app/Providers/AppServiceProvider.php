<?php

namespace App\Providers;

use App\Http\Controllers\ChallengesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\ProgramsParticipantsController;
use App\Http\Controllers\UsersController;
use App\Models\Challenges;
use App\Models\Companies;
use App\Models\Programs;
use App\Models\ProgramsParticipants;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
