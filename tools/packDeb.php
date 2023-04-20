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

/* Create shells to make deb format packages. */
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
        $command .= "mkdir buildroot\n";
        $command .= "mkdir buildroot/opt\n";
        $command .= "mkdir buildroot/etc/apache2/sites-enabled/ -p\n";

        $command .= "cp -r ../../build/debian/DEBIAN buildroot\n";
        $command .= "sed -i '/^Version/cVersion: $version' buildroot/DEBIAN/control\n";

        $command .= "cp ../../build/debian/zentaopms.conf buildroot/etc/apache2/sites-enabled/\n";
        $command .= "cp ../../$packPrefix.$version.php{$phpVersion}.zip buildroot/opt\n";
        $command .= "cd buildroot/opt; unzip $packPrefix.$version.php{$phpVersion}.zip; mv $dirName zentao; rm $packPrefix.$version.php{$phpVersion}.zip\n";

        $command .= "sed -i 's/index.php/\/zentao\/index.php/' zentao/www/.htaccess\n";
        $command .= "cd ../../\n";
        $command .= "dpkg -b buildroot/ {$packPrefix}.{$version}.php{$phpVersion}.1.all.deb\n";
        $command .= "mv *.deb ../../\n";

        $command .= "rm -rf buildroot\n";

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
$lines .= "wait\necho 'Deb packages has done.'";
file_put_contents('deb.sh', $lines);
