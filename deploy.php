<?php

namespace Deployer;

use Robo\Robo;

require 'contrib/wordpress.php';
require 'contrib/cachetool.php';
require 'contrib/rsync.php';
require 'contrib/deploy/rollback.php';

$robo = Robo::createConfiguration(['robo.yml'])->export();

set('scaffold_machine_name', $robo['machine_name']);
set('scaffold_env_file', __DIR__ . '/.env.example');
set('theme_dir', $robo['theme_path']);
set('keep_releases', 5);
set('branch', null);
set('default_stage', 'production');
set('ssh_multiplexing', true);

set('shared_files', ['.env']);
set('shared_dirs', ['web/app/uploads']);
set('writable_dirs', array_merge(get('shared_dirs'), ['{{theme_dir}}/storage', 'web/app/cache']));
set('writable_mode', 'chmod');
set('writable_use_sudo', false);
set('writable_chmod_mode', 'ug+w');

set('bin/robo', './vendor/bin/robo');
set('bin/wp', './vendor/bin/wp');
set('bin/npm', function () {
    return run('which npm');
});

/**
 * Deploy configuration
 */
set('rsync_src', '{{build_artifact_dir}}');
set('rsync_dest', '{{release_path}}');
set('rsync', [
    'exclude'       => [],
    'include'       => [],
    'filter'        => [],
    'exclude-file'  => false,
    'include-file'  => false,
    'filter-file'   => false,
    'filter-perdir' => false,
    'flags'         => 'rv',
    'options'       => ['delete', 'links', 'chmod=u+w'],
    'timeout'       => 3600,
]);

/**
 * Build configuration
 */
set('build_repository', __DIR__); // @todo github
set('build_shared_dirs', []);
set('build_copy_dirs', ['{{theme_dir}}/vendor', 'vendor', '{{theme_dir}}/node_modules']);
set('build_path', '/tmp/dep-' . basename(__DIR__));
set('build_artifact_dir', '{{build_path}}/artifact');
set('build_artifact_exclude', [
    '.git',
    'node_modules',
    '*.sql',
    '/.*',
    '/*.md',
    '/config/*.yml',
    '/config/patches',
    '/composer.json',
    '/composer.lock',
    '/*.php',
    '/*.xml',
    '/*.yml',
    '/Vagrantfile*',
]);

require 'vendor/generoi/deployer-genero/common.php';
require 'vendor/generoi/deployer-genero/build.php';
require 'vendor/generoi/deployer-genero/setup.php';
require 'vendor/generoi/deployer-genero/wordpress.php';

/**
 * Hosts
 */
if (!empty($prod = $robo['env']['@production'])) {
    host('production')
        ->alias($prod['host'])
        ->port($prod['port'] ?? 22)
        ->remote_user($prod['user'])
        ->set('url', $prod['url'])
        ->set('deploy_path', dirname($prod['path']))
        ->set('bin/wp', '{{ release_path }}/vendor/bin/wp');
        // ->set('http_user', 'apache')
        // ->set('bin/wp', '/usr/local/bin/wp')
        // ->set('cachetool', '127.0.0.1:11000')
}

if (!empty($staging = $robo['env']['@staging'])) {
    host('staging')
        ->alias($staging['host'])
        ->port($staging['port'] ?? 22)
        ->remote_user($staging['user'])
        ->set('url', $staging['url'])
        ->set('deploy_path', dirname($staging['path']))
        ->set('bin/wp', '{{ release_path }}/vendor/bin/wp');
}

/**
 * Deploy
 */
task('cache:clear:kinsta', function () {
    run('curl {{ url }}/kinsta-clear-cache-all/');
});

desc('Clear caches');
task('cache:clear', [
    'cache:clear:kinsta',
    // 'cache:clear:wp:wpsc',
    // 'cachetool:clear:opcache',
    // 'cache:clear:wp:objectcache',
    // 'cache:clear:wp:acorn',
    // 'cache:wp:acorn',
]);

task('build:assets', function () {
    run('cd {{release_path}}/{{theme_dir}} && {{bin/composer}} {{composer_options}}');
    if (!get('use_quick')) {
        run('cd {{release_path}}/{{theme_dir}} && {{bin/npm}} install --no-audit', ['timeout' => 1000]);
    }
    run('cd {{release_path}}/{{theme_dir}} && {{bin/npm}} run lint');
    run('cd {{release_path}} && {{bin/robo}} build:production');
    run('ls {{release_path}}/{{theme_dir}}/dist');
});

// Make all files except the ones listed as writable, read-only.
task('deploy:readonly', function () {
    $dirs = join(' ', get('writable_dirs'));

    cd('{{release_path}}');
    run('chmod -R a-w .');
    run("chmod -R {{writable_chmod_mode}} $dirs");
});

// Make to releases which are to be removed writable again. The next task that
// runs, `cleanup`, will delete them.
task('cleanup:writable', function () {
    $releases = get('releases_list');
    $keep = get('keep_releases');

    if ($keep === -1) {
        // Keep unlimited releases.
        return;
    }

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("chmod -R ug+w {{deploy_path}}/releases/$release");
    }
});

desc('Deploy release');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',

    'build',

    'rsync:warmup',
    'rsync',

    'deploy:shared',
    'deploy:writable',
    'deploy:readonly',
    'deploy:symlink',

    'cache:clear',

    'deploy:unlock',
    'cleanup:writable',
    'cleanup',
    'deploy:success',
]);

after('rollback', 'cache:clear');
after('deploy:failed', 'deploy:unlock');
