<?php
/* This file is used to crete a tag of subverion. */

$config->zentaophp->svnRoot  = 'https://zentaophp.googlecode.com/svn/';
$config->zentaophp->svnTrunk = 'trunk/';
$config->zentaophp->svnTags  = 'tags/';

$config->zentaopms->svnRoot  = 'https://zentaoms.googlecode.com/svn/';
$config->zentaopms->svnTrunk = 'trunk/';
$config->zentaopms->svnTags  = 'tags/';

if(count($argv) != 4) die(__FILE__ . " repo version releasetype:beta|alpa|stable\n");

$repo    = $argv[1];
$version = $argv[2];
$release = $argv[3];

$sourceURL = $config->$repo->svnRoot . $config->$repo->svnTrunk;
$targetURL = $config->$repo->svnRoot . $config->$repo->svnTags . $repo . '_' . $version . '_' . $release . '_' . date('Ymd');

$svnCMD = "svn rm $targetURL -m 'remove it'; svn cp $sourceURL $targetURL -m 'tag $version of $repo'";
system($svnCMD);
