<?php
include 'scm.class.php';

function subversionTest()
{
    $repo->SCM = 'Subversion';
    $repo->client = '/usr/bin/svn';
    $repo->account = 'aaa';
    $repo->password = 'aaaaaa';

    $scm = new scm($repo);
    print_r($scm->cat("http://svn.aaa.5upm.cn/bb/aaa"));
}

subversionTest();
