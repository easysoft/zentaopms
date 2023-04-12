<?php
$sinceVersion = $argv[1];
$untilVersion = $argv[2];

if(empty($sinceVersion) or empty($untilVersion)) die("Please enter the since and until version. Usage: php privchangelog 16.5 18.3.");

include '../module/misc/lang/zh-cn.php';

$releaseDates = array();
foreach($lang->misc->releaseDate as $releaseVersion => $releaseDate)
{
    if(version_compare($releaseVersion, $sinceVersion, ">=") and version_compare($releaseVersion, $untilVersion, '<=')) $releaseDates[$releaseVersion] = $releaseDate;
}
if(empty($releaseDates)) exit;

asort($releaseDates);

$changelogFile = '../module/group/lang/changelog.php';
$resourceFile  = '../module/group/lang/resource.php';

$oldDate    = current($releaseDates);
$oldVersion = key($releaseDates);
file_put_contents($changelogFile, "\n", FILE_APPEND);
foreach($releaseDates as $version => $releaseDate)
{
    if($version == $oldVersion) continue;

    $oldVersionCommit = trim(shell_exec("git log --oneline --until='$oldDate' --pretty=format:'%h'  $resourceFile | head -n 1"), "\n");
    $newVersionCommit = trim(shell_exec("git log --oneline --until='$releaseDate' --pretty=format:'%h'  $resourceFile | head -n 1"), "\n");
    if($oldVersionCommit == $newVersionCommit)
    {
        $oldDate    = $releaseDate;
        $oldVersion = $version;
        continue;
    }

    $addPrivileges = trim(shell_exec("git diff --ignore-space-change {$oldVersionCommit} {$newVersionCommit} -- $resourceFile | grep '+\$lang->resource' | grep -v 'stdclass'"), "\n");
    $addPrivileges = str_replace('+$lang', '$lang', $addPrivileges);
    if(empty($addPrivileges))
    {
        $oldDate    = $releaseDate;
        $oldVersion = $version;
        continue;
    }

    $privList = explode("\n", $addPrivileges);
    foreach($privList as $priv)
    {
        preg_match("/(\\\$lang\->resource)\->(.*)\->(.*)= '(.*)';/", $priv, $matches);

        $module     = $matches[2];
        $methodLang = $matches[4];

        $content = "\$lang->changelog['$version'][] = '$module-$methodLang';";

        file_put_contents($changelogFile, $content . "\n", FILE_APPEND);
    }

    $oldDate    = $releaseDate;
    $oldVersion = $version;

    file_put_contents($changelogFile, "\n", FILE_APPEND);
}
exec("sed '\$d' $changelogFile");
