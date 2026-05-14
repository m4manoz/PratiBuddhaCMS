<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:seed {--fresh : Rebuild the database before seeding}', function () {
    $this->info('Seeding PRATIBUDDHA prototype data...');

    $seederClass = 'Database\\Seeders\\DatabaseSeeder';

    if ($this->option('fresh')) {
        $this->info('Running migrate:fresh...');
        $freshExitCode = $this->call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
            '--seeder' => $seederClass,
        ]);

        if ($freshExitCode !== Command::SUCCESS) {
            $this->error('migrate:fresh failed.');
            return Command::FAILURE;
        }

        $this->info('Database refreshed and seeded for prototype.');
        return Command::SUCCESS;
    }

    $seedExitCode = $this->call('db:seed', [
        '--force' => true,
        '--class' => $seederClass,
    ]);

    if ($seedExitCode !== Command::SUCCESS) {
        $this->error('db:seed failed.');
        return Command::FAILURE;
    }

    $this->info('Prototype seed data loaded.');
    return Command::SUCCESS;
})->purpose('Seed PRATIBUDDHA prototype data with a single command');
