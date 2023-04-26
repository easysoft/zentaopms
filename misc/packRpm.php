<?php
/* Get pms version and lite version. */
$pmsVersion = $argv[1];
if(empty($pmsVersion)) die("Please give me pms version.\n");

/* Zip file pathes. */
$basePath   = dirname(dirname(__FILE__));
$fileList   = array();
$fileList[] = "{$basePath}/ZenTaoPMS.$pmsVersion.php5.4_5.6.zip";
$fileList[] = "{$basePath}/ZenTaoPMS.$pmsVersion.php7.0.zip";
$fileList[] = "{$basePath}/ZenTaoPMS.$pmsVersion.php7.1.zip";
$fileList[] = "{$basePath}/ZenTaoPMS.$pmsVersion.php7.2_7.4.zip";
$fileList[] = "{$basePath}/ZenTaoPMS.$pmsVersion.php8.0.zip";

$fileList[] = "{$basePath}/ZenTaoALM.$pmsVersion.int.php5.4_5.6.zip";
$fileList[] = "{$basePath}/ZenTaoALM.$pmsVersion.int.php7.0.zip";
$fileList[] = "{$basePath}/ZenTaoALM.$pmsVersion.int.php7.1.zip";
$fileList[] = "{$basePath}/ZenTaoALM.$pmsVersion.int.php7.2_7.4.zip";
$fileList[] = "{$basePath}/ZenTaoALM.$pmsVersion.int.php8.0.zip";

foreach($fileList as $file) if(!file_exists($file)) echo basename($file) . " is not exists\n";

/* Create shells to make rpm format packages. */
$shellList = array();
foreach(array('zh-cn', 'en') as $langType)
{
    /* Init vars. */
    $packPrefix = $langType == 'zh-cn' ? 'ZenTaoPMS' : 'ZenTaoALM';
    $version    = $langType == 'zh-cn' ? $pmsVersion : $pmsVersion . '.int';
    $dirName    = $langType == 'zh-cn' ? 'zentaopms' : 'zentaoalm';

    /* Cycle the php versions. */
    foreach(array('5.4_5.6', '7.0', '7.1', '7.2_7.4', '8.0') as $phpVersion)
    {
        /* File name. */
        $workDir   = "tmp/$packPrefix.{$version}.{$phpVersion}/";
        $shellName = $workDir . 'make.sh';
        echo "Creating $shellName\n";

        mkdir($workDir, 0777, true);

        /* The commands of the shell. */
        $command  = "cd $workDir\n";
        $command .= "mkdir rpmbuild/SPECS -p\n";
        $command .= "mkdir rpmbuild/SOURCES\n";
        $command .= "mkdir rpmbuild/SOURCES/etc/httpd/conf.d/ -p\n";
        $command .= "mkdir rpmbuild/SOURCES/opt/ -p\n";

        $command .= "cp ../../build/rpm/zentaopms.spec rpmbuild/SPECS\n";
        $command .= "sed -i '/^Version/cVersion:$version' rpmbuild/SPECS/zentaopms.spec\n";
        if($langType == 'en') $command .= "sed -i '/^Name:/cName:zentaoalm' rpmbuild/SPECS/zentaopms.spec\n";

        $command .= "cp ../../$packPrefix.$version.php{$phpVersion}.zip rpmbuild/SOURCES\n";
        if($langType == 'zh-cn') $command .= "cp ../../build/debian/zentaopms.conf rpmbuild/SOURCES/etc/httpd/conf.d/\n";
        if($langType == 'en') $command .= "cp ../../build/debian/zentaopms.conf rpmbuild/SOURCES/etc/httpd/conf.d/zentaoalm.conf\n";

        $command .= "cd rpmbuild/SOURCES; unzip $packPrefix.$version.php{$phpVersion}.zip; mv $dirName opt/zentao;\n";
        $command .= "sed -i 's/index.php/\/zentao\/index.php/' opt/zentao/www/.htaccess\n";
        $command .= "tar -czvf $dirName-$version.tar.gz etc opt; rm -rf $packPrefix.$version.php{$phpVersion}.zip etc opt;\n";

        $command .= "cd ../../\n";
        $command .= "rpmbuild --define \"_topdir \${PWD}/rpmbuild\" -ba rpmbuild/SPECS/zentaopms.spec\n";
        $command .= "cp rpmbuild/RPMS/noarch/$dirName-$version-1.noarch.rpm ../../{$packPrefix}.{$version}.php{$phpVersion}.1.noarch.rpm\n";

        $command .= "rm -rf rpmbuild\n";

        file_put_contents($shellName, $command);

        $shellList[] = $shellName;
    }
}

/* Execute the shells. */
$lines = '';
foreach($shellList as $shellName)
{
    echo $shellName . "\n";
    $lines .= "sh $shellName &\n";
}
$lines .= "wait\necho 'Rpm packages has done.'";
file_put_contents('rpm.sh', $lines);
