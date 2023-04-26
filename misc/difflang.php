#!/usr/bin/env php
<?php
$preVersion = $argv[1];
$nowVersion = $argv[2];

$base = dirname(dirname(__FILE__));
chdir($base);
`unzip $base/ZenTaoPMS.{$preVersion}.zip -d $base/prev`;
$diff['en']    = '';
$diff['zh-cn'] = '';
$diff['zh-tw'] = '';
foreach(glob("$base/module/*") as $module)
{
    $moduleName = basename($module);
    foreach(array_keys($diff) as $lang)
    {
        if(file_exists($module . "/lang/{$lang}.php"))
        {
            $oldFile = "$base/prev/zentaopms/module/$moduleName/lang/{$lang}.php";
            if(!file_exists($oldFile))
            {
                $oldDir = dirname($oldFile);
                if(!is_dir($oldDir)) `mkdir -p $oldDir`;
                file_put_contents($oldFile, "<?php\n");
            }
            $diffCmd = "diff $oldFile $module/lang/{$lang}.php";
            $diff[$lang] .= "$diffCmd\n";
            $diff[$lang] .= `$diffCmd` . "\n";
        }
        if(is_dir($module . "/ext/lang/{$lang}"))
        {
            foreach(glob($module . "/ext/lang/{$lang}/*.php") as $langFile)
            {
                $fileName = basename($langFile);
                $oldFile  = "$base/prev/zentaopms/module/$moduleName/ext/lang/{$lang}/{$fileName}";
                if(!file_exists($oldFile))
                {
                    $oldDir = dirname($oldFile);
                    if(!is_dir($oldDir)) `mkdir -p $oldDir`;
                    file_put_contents($oldFile, "<?php\n");
                }
                $diffCmd  = "diff $oldFile $langFile";
                $diff[$lang] .= "$diffCmd\n";
                $diff[$lang] .= `$diffCmd` . "\n";
            }
        }
    }
}

$preVersion = str_replace('.stable', '', $preVersion);
$nowVersion = str_replace('.stable', '', $nowVersion);
if(!is_dir("$base/module/translate/diff")) mkdir("$base/module/translate/diff", 0755, true);
foreach($diff as $lang => $content) file_put_contents("$base/module/translate/diff/{$lang}_{$preVersion}_{$nowVersion}", $content);
`rm -rf $base/prev`;
