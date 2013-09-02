<?php
/**
 * encodings: 提交日志的编码，比如GBK，可以用逗号连接起来的多个。
 * client: Git客户端执行文件的路径，windows下面是git.exe的路径，linux下面比如/usr/bin/git
 * repos可以是多个，需要设定某一个库的访问路径。
 *
 * encodeings: the encoding of the comment，can be a list.
 * client: the git client binary path. Unser windows, find the path of git.exe. Under linux, try /usr/bin/git
 * Can set multi repos, ervery one should set the path.
 *
 * 例子：
 * $config->git->client = '/usr/bin/git';                       // c:\git\git.exe
 * $config->git->repos['pms']['path'] = '/home/user/repo/pms';  // c:\repo\pms
 *
 */
$config->git = new stdClass();
$config->git->encodings = 'utf-8, gbk';  
$config->git->client    = '';

$i = 1;
$config->git->repos[$i]['path'] = '';

/*
$i ++;
$config->git->repos[$i]['path'] = '';
*/
