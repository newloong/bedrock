<?php

/**
 * Configuration overrides for WP_ENV === 'staging'
 */

use Roots\WPConfig\Config;

/**
 * You should try to keep staging as close to production as possible. However,
 * should you need to, you can always override production configuration values
 * with `Config::define`.
 *
 * Example: `Config::define('WP_DEBUG', true);`
 * Example: `Config::define('DISALLOW_FILE_MODS', false);`
 */

Config::define('SHC_SHOW_ENV_STAGING', 'staging');
Config::define('WP_HOME', 'https://staging-gdsbedrock-staging.kinsta.cloud');
Config::define('WP_SITEURL', Config::get('WP_HOME') . '/wp');
Config::define('DISALLOW_FILE_EDIT', false);
Config::define('DISALLOW_FILE_MODS', false);
