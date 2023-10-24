<?php
/* Get pms version and lite version. */
$pmsVersion = $argv[1];
if(empty($pmsVersion)) die("Please give me pms version.\n");
$liteVersion = isset($argv[2]) ? $argv[2] : '';

/* File pathes. */
$basePath    = dirname(dirname(__FILE__));
$releasePath = getenv('ZENTAO_RELEASE_PATH');
$releasePath = !empty($releasePath) ? $releasePath : $basePath;
if(!file_exists($releasePath . '/zentaopms.zip')) die("Please give me encrypted packages.\n");

/* Get the encrypted packages. */
`cp $releasePath/zentaopms.zip $basePath`;
`unzip zentaopms.zip; rm zentaopms.zip`;

/* Encrypted packages list. */
$fileList   = array();
$fileList[] = "{$basePath}/zentaobiz.php5.4_5.6.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.0.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.1.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.2_7.4.zip";
$fileList[] = "{$basePath}/zentaobiz.php8.1.zip";

$fileList[] = "{$basePath}/zentaomax.php5.4_5.6.zip";
$fileList[] = "{$basePath}/zentaomax.php7.0.zip";
$fileList[] = "{$basePath}/zentaomax.php7.1.zip";
$fileList[] = "{$basePath}/zentaomax.php7.2_7.4.zip";
$fileList[] = "{$basePath}/zentaomax.php8.1.zip";

foreach($fileList as $file) if(!file_exists($file)) echo basename($file) . " is not exists\n";

`unzip {$basePath}/zentaobiz.php5.4_5.6.zip; mv biz zentaobiz.php5.4_5.6`;
`unzip {$basePath}/zentaobiz.php7.0.zip;     mv biz zentaobiz.php7.0`;
`unzip {$basePath}/zentaobiz.php7.1.zip;     mv biz zentaobiz.php7.1`;
`unzip {$basePath}/zentaobiz.php7.2_7.4.zip; mv biz zentaobiz.php7.2_7.4`;
`unzip {$basePath}/zentaobiz.php8.1.zip;     mv biz zentaobiz.php8.1`;
`unzip {$basePath}/zentaomax.php5.4_5.6.zip; mv max zentaomax.php5.4_5.6`;
`unzip {$basePath}/zentaomax.php7.0.zip;     mv max zentaomax.php7.0`;
`unzip {$basePath}/zentaomax.php7.1.zip;     mv max zentaomax.php7.1`;
`unzip {$basePath}/zentaomax.php7.2_7.4.zip; mv max zentaomax.php7.2_7.4`;
`unzip {$basePath}/zentaomax.php8.1.zip;     mv max zentaomax.php8.1`;

/* Create shells to make zip format packages. */
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
        $shellName = $workDir . "make.sh";
        $encryptedPhpVersion = $phpVersion == '8.0' ? '7.2_7.4' : $phpVersion;
        echo "Creating $shellName\n";

        mkdir($workDir, 0777, true);

        /* The commands of the shell. */
        $command  = "cd $workDir\n";
        $command .= "unzip ../../$packPrefix.{$version}.zip\n";
        $command .= "cp -rf ../../zentaobiz.php$encryptedPhpVersion/* $dirName/\n";
        $command .= "cp -rf ../../zentaomax.php$encryptedPhpVersion/* $dirName/\n";
        $command .= "zip -r ../../$packPrefix.{$version}.php{$phpVersion}.zip $dirName\n";

        $command .= "rm -rf $dirName/\n";

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
$lines .= "wait\necho 'Zip packages has done.'";
file_put_contents('zip.sh', $lines);
