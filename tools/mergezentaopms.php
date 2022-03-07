<?php
$pmsVersion = $argv[1];
$basePath   = dirname(dirname(__FILE__));

if(empty($pmsVersion)) die("Please give me pms version.\n");

$releasePath = exec('echo $ZENTAO_RELEASE_PATH;');
$releasePath = !empty($releasePath) ? $releasePath : $basePath;

if(!file_exists($releasePath . '/zentaopms.zip')) die("Please give me encrypted packages.\n");

`cp $releasePath/zentaopms.zip $basePath`;
`unzip zentaopms.zip; rm zentaopms.zip`;

$fileList   = array();
$fileList[] = "{$basePath}/zentaobiz.php5.3_5.6.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.0.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.1.zip";
$fileList[] = "{$basePath}/zentaobiz.php7.2_7.4.zip";

$fileList[] = "{$basePath}/zentaomax.php5.3_5.6.zip";
$fileList[] = "{$basePath}/zentaomax.php7.0.zip";
$fileList[] = "{$basePath}/zentaomax.php7.1.zip";
$fileList[] = "{$basePath}/zentaomax.php7.2_7.4.zip";

checkExistsFiles($fileList);

// zip
foreach(array('zh-cn', 'en') as $langType)
{
    $packPrefix = $langType == 'zh-cn' ? 'ZenTaoPMS' : 'ZenTaoALM';
    $version    = $langType == 'zh-cn' ? $pmsVersion : $pmsVersion . '.int';
    $dirName    = $langType == 'zh-cn' ? 'zentaopms' : 'zentaoalm';

    foreach(array('5.3_5.6', '7.0', '7.1', '7.2_7.4') as $phpVersion)
    {
        `unzip $packPrefix.{$version}.zip`;
        `unzip zentaobiz.php{$phpVersion}.zip`;
        `unzip zentaomax.php{$phpVersion}.zip`;
        `cp -rf biz/* $dirName/`;
        `cp -rf max/* $dirName/`;
        `zip -r $packPrefix.{$version}.php{$phpVersion}.zip $dirName`;
        `rm -rf biz/ max/ $dirName/`;
    }
}
// deb
//foreach(array('zh-cn', 'en') as $langType)
//{
//    $packPrefix = $langType == 'zh-cn' ? 'ZenTaoPMS' : 'ZenTaoALM';
//    $version    = $langType == 'zh-cn' ? $pmsVersion : $pmsVersion . '.int';
//    $dirName    = $langType == 'zh-cn' ? 'zentaopms' : 'zentaoalm';
//
//    foreach(array('5.3_5.6', '7.0', '7.1', '7.2_7.4') as $phpVersion)
//    {
//	    `mkdir buildroot`;
//	    `cp -r build/debian/DEBIAN buildroot`;
//	    `sed -i '/^Version/cVersion: $version' buildroot/DEBIAN/control`;
//	    `mkdir buildroot/opt`;
//	    `mkdir buildroot/etc/apache2/sites-enabled/ -p`;
//	    `cp build/debian/zentaopms.conf buildroot/etc/apache2/sites-enabled/`;
//	    `cp $packPrefix.$version.php{$phpVersion}.zip buildroot/opt`;
//	    `cd buildroot/opt; unzip $packPrefix.$version.php{$phpVersion}.zip; mv $dirName zentao; rm $packPrefix.$version.php{$phpVersion}.zip`;
//	    `sed -i 's/index.php/\/zentao\/index.php/' buildroot/opt/zentao/www/.htaccess`;
//	    `sudo dpkg -b buildroot/ {$packPrefix}_{$version}_{$phpVersion}_1_all.deb`;
//	    `rm -rf buildroot`;
//    }
//}
//// rpm
//foreach(array('zh-cn', 'en') as $langType)
//{
//    $packPrefix = $langType == 'zh-cn' ? 'ZenTaoPMS' : 'ZenTaoALM';
//    $version    = $langType == 'zh-cn' ? $pmsVersion : $pmsVersion . '.int';
//    $dirName    = $langType == 'zh-cn' ? 'zentaopms' : 'zentaoalm';
//
//    foreach(array('5.3_5.6', '7.0', '7.1', '7.2_7.4') as $phpVersion)
//    {
//	    `mkdir ~/rpmbuild/SPECS -p`;
//	    `mkdir ~/rpmbuild/SOURCES`;
//	    `mkdir ~/rpmbuild/SOURCES/etc/httpd/conf.d/ -p`;
//	    `mkdir ~/rpmbuild/SOURCES/opt/ -p`;
//
//	    `cp build/rpm/zentaopms.spec ~/rpmbuild/SPECS`;
//	    `sed -i '/^Version/cVersion:$version' ~/rpmbuild/SPECS/zentaopms.spec`;
//        if($langType == 'en') `sed -i '/^Name:/cName:zentaoalm' ~/rpmbuild/SPECS/zentaopms.spec`;
//
//	    `cp $packPrefix.$version.php{$phpVersion}.zip ~/rpmbuild/SOURCES`;
//        if($langType == 'zh-cn') `cp build/debian/zentaopms.conf ~/rpmbuild/SOURCES/etc/httpd/conf.d/`;
//        if($langType == 'en') `cp build/debian/zentaopms.conf ~/rpmbuild/SOURCES/etc/httpd/conf.d/zentaoalm.conf`;
//
//	    `cd ~/rpmbuild/SOURCES; unzip $packPrefix.$version.php{$phpVersion}.zip; mv $dirName opt/zentao;`;
//	    `sed -i 's/index.php/\/zentao\/index.php/' ~/rpmbuild/SOURCES/opt/zentao/www/.htaccess`;
//	    `cd ~/rpmbuild/SOURCES; tar -czvf $dirName-$version.tar.gz etc opt; rm -rf $packPrefix.$version.php{$phpVersion}.zip etc opt;`;
//
//	    `rpmbuild -ba ~/rpmbuild/SPECS/zentaopms.spec`;
//	    `cp ~/rpmbuild/RPMS/noarch/$dirName-$version-1.noarch.rpm ./{$dirName}-{$version}-php{$phpVersion}-1.noarch.rpm`;
//	    `rm -rf ~/rpmbuild`;
//    }
//}

function checkExistsFiles($fileList)
{
    $files = ''; 
    foreach($fileList as $file)
    {   
        if(!file_exists($file)) echo basename($file) . " is not exists\n";
    }   
}
