<?php
/* Get the yaml file and parse it. */
if(count($argv) != 2) die("please set the yaml file.\n");
$filename = $argv[1];
if(!is_file($filename)) die("the yaml file doesn't exit\n");
include '../lib/spyc/spyc.class.php';
$extension = Spyc::YAMLLoadString(file_get_contents($filename));

/* Basic info checking. */
if(empty($extension['name'])) die("name field must be set\n");
if(empty($extension['code'])) die("code field must be set\n");
if(!preg_match('/^[a-zA-Z0-9_]{1}[a-zA-Z0-9_]{1,}[a-zA-Z0-9_]{1}$/', $extension['code'])) die("code shoulde be letter, nubmer and _\n");
if(!preg_match('/^(extension|patch|theme)$/', $extension['type'])) die("type shoulde be extension, patch or theme\n");
if(empty($extension['abstract']))     die("abstract field must be set\n");

/* desc and install fields checking. */
if(is_array($extension['desc']))      die("desc should be a string, please check your yaml synatax\n");
if(is_array($extension['install']))   die("install should be a string, please check your yaml synatax\n");

/* release checking. */
if(!is_array($extension['releases'])) die("releases should be set.\n");
foreach($extension['releases'] as $version => $release)
{
    if(empty($release['zentaoversion'])) die("version $version should set the compatible zentao versions\n");
    if(!preg_match('/^(free|share|opensource|business)$/', $release['charge'])) die("version $version's charge field shoulde be free, share, opensource or business\n");
    if(empty($release['license'])) die("version $version 's license should be set\n");
    if(empty($release['date'])   ) die("version $version 's date field should be set\n");
}
