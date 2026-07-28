<?php
declare(strict_types = 1);

/**
 * Prepare the GitFox entry and config for codescan model tests.
 *
 * @param  object|null $test
 * @access public
 * @return void
 */
function initCodescanGitFox(object $test = null): void
{
    zenData('entry')->loadYaml('entry', false, 2)->gen(1);

    global $config;
    if(!isset($config->devops) || !is_object($config->devops)) $config->devops = new stdclass();
    if(!isset($config->devops->gitfoxURL))  $config->devops->gitfoxURL  = 'http://localhost';
    if(!isset($config->devops->gitfoxPort)) $config->devops->gitfoxPort = 3000;

    if($test === null || !isset($test->instance) || !is_object($test->instance)) return;

    if(!isset($test->instance->config->devops) || !is_object($test->instance->config->devops)) $test->instance->config->devops = new stdclass();
    $test->instance->config->devops->gitfoxURL  = $config->devops->gitfoxURL;
    $test->instance->config->devops->gitfoxPort = $config->devops->gitfoxPort;
}
