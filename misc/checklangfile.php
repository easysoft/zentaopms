#!/usr/bin/env php
<?php
define('DS', DIRECTORY_SEPARATOR);

start();
function start()
{
    /* Check the address of the language item, there can be multiple. */
    /* Example: $sourceRoots[] = 'repo/zentaopms'; */
    $sourceRoots   = array();
    $sourceRoots[] = '';

    foreach($sourceRoots as $sourceRoot)
    {
        exec("find $sourceRoot -name 'en.php'", $enFiles);
        foreach($enFiles as $enFile)
        {
            $langPath = dirname($enFile);
            $deFile   = $langPath . DS . 'de.php';
            $frFile   = $langPath . DS . 'fr.php';
            if(file_exists($deFile)) replaceLangFile($deFile, $enFile);
            if(file_exists($frFile)) replaceLangFile($frFile, $enFile);
            if(!file_exists($deFile) and is_file($enFile)) exec("cp $enFile $deFile");
            if(!file_exists($frFile) and is_file($enFile)) exec("cp $enFile $frFile");
        }

        exec("find $sourceRoot -name 'en'", $enPaths);
        foreach($enPaths as $enPath)
        {
            $langPath = dirname($enPath);
            $dePath   = $langPath . DS . 'de';
            $frPath   = $langPath . DS . 'fr';

            if(!is_dir($dePath)) mkdir($dePath);
            if(!is_dir($frPath)) mkdir($frPath);

            $files = array();
            exec("ls $enPath", $files);
            foreach($files as $file)
            {
                $enFile = $enPath . DS . $file;
                $deFile = $dePath . DS . $file;
                $frFile = $frPath . DS . $file;
                if(file_exists($deFile)) replaceLangFile($deFile, $enFile);
                if(file_exists($frFile)) replaceLangFile($frFile, $enFile);
                if(!file_exists($deFile) and is_file($enFile)) exec("cp $enFile $deFile");
                if(!file_exists($frFile) and is_file($enFile)) exec("cp $enFile $frFile");
            }
        }
    }
}
function replaceLangFile($replaceFile, $enFile)
{
    $enContent      = fopen($enFile, "r");
    $replaceContent = file_get_contents($replaceFile, "r");
    $replace        = '';
    while(!feof($enContent))
    {
        $row = fgets($enContent);//读取一行
        preg_match('/\$lang->([^\s()]* ) *=(.*);$/', $row, $matches);
        if(isset($matches[1]) and isset($matches[2]))
        {
            $matches[1] = str_replace(array('[', ']', '/'), array('\[', '\]', '\/'), $matches[1]);
            preg_match('/\$lang->' . "$matches[1]\s*=(.*);\n/", $replaceContent, $replaceMatches);
            if(isset($replaceMatches[1])) $row = str_replace($matches[2], $replaceMatches[1], $row);
        }
        $replace .= $row;
    }
    fclose($enContent);
    file_put_contents($replaceFile, $replace);
}
