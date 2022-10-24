<?php

namespace App\Modules\Stats\Providers;

use App\Modules\Stats\Command\CommandManager;
use App\Modules\Stats\Command\ICommandManager;
use App\Modules\Stats\Command\OverallStatusCommand;
use App\Modules\Stats\Services\IStatsService;
use App\Modules\Stats\Services\StatsServiceImpl;
use App\Repositories\ILogsRepo;
use App\Repositories\LogsRepo;
use Illuminate\Support\ServiceProvider;

class StatsServiceProvider extends ServiceProvider
{

    public $bindings = [
        IStatsService::class   => StatsServiceImpl::class,
        ICommandManager::class => CommandManager::class
    ];

    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub
        $this->app->when(OverallStatusCommand::class)
            ->needs(ILogsRepo::class)
            ->give(LogsRepo::class);
    }

}
