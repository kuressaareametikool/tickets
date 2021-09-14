<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/rsync.php';
// Project name
set('application', 'kak-ticket');
set('remote_user', 'virt105586');
set('http_user', 'virt105586');
set('keep_releases', 2);

// Hosts
host('tickets.itmajakas.ee')
    ->setHostname('tickets.itmajakas.ee')
    ->set('http_user', 'virt105586')
    ->set('deploy_path', '~/domeenid/www.tickets.itmajakas.ee/app');
// Tasks
set('rsync_src', __DIR__);
set('rsync_dest', '{{release_path}}');

set('rsync', [
    'exclude' => [
        'deploy.php',
        '.git/',
        '.env',
        '.gitignore',
        'node_modules/',
        'storage/',
        'vendor/'
    ],
    'exclude-file' => false,
    'include'      => [],
    'include-file' => false,
    'filter'       => [],
    'filter-file'  => false,
    'filter-perdir' => false,
    'flags'        => 'rz', // Recursive, with compress
    'options'      => ['delete'],
    'timeout'      => 60,
]);


//Restart opcache
task('opcache:clear', function () {
    run('killall php80-cgi || true');
})->desc('Clear opcache');

task('deploy:prepare', [
    'deploy:info',
    'deploy:setup',
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:shared',
    'deploy:writable',
]);


task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:migrate',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'deploy:publish'
]);

after('deploy:failed', 'deploy:unlock');