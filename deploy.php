<?php namespace Deployer;

require 'recipe/laravel.php';

set('ssh_type', 'ext-ssh2');
set('default_stage', 'staging');

// Set configurations
set('repository', 'git@github.com:ApparelMedia/pear-nonprofit-api.git');
set('writable_dirs', ['storage']);
set('writable_use_sudo', false);
set('shared_files', ['.env']);
set('shared_dirs', [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

task('copy:dotenv', function () {
    $sourceDotEnv = '/opt/pear-nonprofit-api/shared/.env.' . env('stage_name');
    $targetDotEnv = env('deploy_path') .'/shared/.env';
    run("cp $sourceDotEnv $targetDotEnv");
})->desc('Copying .env file from file published by CI WebOps');

after('deploy:symlink', 'copy:dotenv');

/**
 * Main task (Overwritting Default Laravel Task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
    'artisan:cache:clear',
])->desc('Deploy your project');

// Production Server
server('prod1', 'prod1.nonprofit')
    ->configFile('/home/vagrant/.ssh/config')
    ->env('deploy_path', '/opt/pear-nonprofit-api')
    ->env('stage_name', 'production')
    ->stage('production');

server('prod2', 'prod2.nonprofit')
    ->configFile('/home/vagrant/.ssh/config')
    ->env('deploy_path', '/opt/pear-nonprofit-api')
    ->env('stage_name', 'production')
    ->stage('production');

// Staging Server
server('stage1', 'stage1.nonprofit')
    ->configFile('/home/vagrant/.ssh/config')
    ->env('deploy_path', '/opt/pear-nonprofit-api')
    ->env('stage_name', 'staging')
    ->stage('staging');

