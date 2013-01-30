<?php
/**
 * 禅道计划任务服务程序。
 * The crond for zentao.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      jinyong zhu <zhujinyong@cnezsoft.com>
 * @package     bin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* Set pathes and timezone. */
$zentaoPath = dirname(dirname(dirname(__FILE__))) . "/";
$cronPath   = $zentaoPath . 'bin/cron';
include $zentaoPath . 'config/config.php';
include $zentaoPath . 'lib/crontab/crontab.class.php';
date_default_timezone_set($config->timezone);

/* Parase crons. */
$crons = parseCron($cronPath);
$lastParsed = time();
printCrons($crons);

/* Start the cron demon. */
while(true)
{
    /* If need parse again, re parse the cron files. */
    if(needParseAgain($cronPath, $lastParsed))
    {
        echo "\ncron files changed, re parse them...";
        $crons = parseCron($cronPath);
        $lastParsed = time();
        printCrons($crons);
    }

    $now = new datetime('now');
    foreach($crons as $key => $cron) 
    {
        if($now > $cron['time']) 
        {
            $crons[$key]['time'] = $cron['cron']->getNextRunDate();

            $output = array();
            $log    = '';
            exec($cron['command'], $output, $return);

            $time = $now->format('G:i:s');
            foreach($output as $out) $log .= $out . "\n"; 
            $log = "$time task " .  ($key + 1) . " executed,\ncommand: $cron[command].\nreturn : $return.\noutput : $log\n";
            echo $log;
            logCron($log);
        }
    }
    sleep(40);
}

/* Parse cron file. */
function parseCron($path)
{
    chdir($path);

    $crons = array();
    $files = glob('*');
    foreach($files as $file)
    {
        $rows = file($file);
        foreach($rows as $row)
        {
            $row = preg_replace("/[ \t]+/", ' ', trim($row, " \t\n"));
            $row = preg_replace("/#.*/", '', $row);
            if($row)
            {
                preg_match_all('/(\S+\s+){5}|.*/', $row, $matchs);
                if($matchs[0])
                {
                    $cron = array();
                    $cron['schema']  = trim($matchs[0][0]);
                    $cron['command'] = trim($matchs[0][1]);
                    $cron['cron']    = CronExpression::factory($cron['schema']);
                    $cron['time']    = $cron['cron']->getNextRunDate();
                    $crons[]         = $cron;
                }
            }
        }
    }
    return $crons;
}

/* Print crons. */
function printCrons($crons)
{
    echo "\n";
    echo 'total ' . count($crons) . " tasks found.\n\n";
    foreach($crons as $id => $cron)
    {
        echo ($id + 1) . "\t$cron[schema]\t$cron[command]\n";
    }
}

/* Log cron results. */
function logCron($log)
{
    $path = dirname(dirname(dirname(__FILE__))) . '/tmp/log/';
    $file = $path . 'cron.' . date('Ymd') . '.log';

    $fp = fopen($file, "a");
    fwrite($fp, $log);
    fclose ($fp);
}

/* Need parse cron files again? */
function needParseAgain($cronPath, $lastParsed)
{
    clearstatcache();
    chdir($cronPath);

    $files = glob('*');
    foreach($files as $file) if(filemtime($file) > $lastParsed) return true;
    return false;
}
