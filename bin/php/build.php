<?php
/**
1. 修改zentaophp中的version number，打tag。
2. 修改ZenTaoPMS中的version
    config.php中的version.
    install中的version。
3. 修改升级程序。(版本列表。)
4. 打包ZenTaoPMS。
5. 合并目录。
6. 修改www/index.php中的包含路径。
7. 导出新的数据库。 grep -v '\-\-' /mnt/c/zentao.sql  |grep -v ^$ |sed "s/DROP/\-\- DROP/" >zentao.sql
8. zip包。
9. windows包。
10. 上传文件。
11. 撰写升级声明。
 */

$phpURL = 'http://zentaophp.googlecode.com/svn/tags/';
$pmsURL = 'http://ZenTaoPMS.googlecode.com/svn/tags/';

$phpTag = getLatestTag($phpURL);
$pmsTag = getLatestTag($pmsURL);

$phpTagURL = $phpURL . $phpTag;
$pmsTagURL = $pmsURL . $pmsTag;

echo $phpTag . "'\t" . $pmsTag . "\n";

chdir('../../release/');
echo `svn export $phpTagURL`;
echo `svn export $pmsTagURL`;

/* Get the latest tag under a url. */
function getLatestTag($url)
{
    $lines = file($url);
    $latestTag = '';
    foreach($lines as $line)
    {
        if(strpos($line, '<li>') !== false) $latestTag = $line;
    }
    $latestTag = explode('"', $latestTag);
    $latestTag = $latestTag[1];
    return $latestTag;
}
