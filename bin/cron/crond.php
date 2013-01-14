<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/crontab/crontab.class.php';
$crons = parseCron();
$tasks = array();
foreach($crons as $key => $cron)
{
    $tasks[$key]       = new stdClass();
    $tasks[$key]->cron = CronExpression::factory($cron['schema']);
    $tasks[$key]->time = $tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i');
}

/* run as daemon. */
while(1)
{
    foreach($crons as $key => $cron) 
    {
        if($tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i') != $tasks[$key]->time)
        {
            $time    = date('Y-m-d H:i:s');
            $output  = system($cron['command'], $retval);
            $content = $time . ' ' . $key . ' return ' . $retval . ' : ' . $output . "\n";
            logCron($content);
            $tasks[$key]->time = $tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i');
        }
    }
    sleep(60);
}

/* Log cron results. */
function logCron($content)
{
    $file = dirname(dirname(dirname(__FILE__))) . '/tmp/cron.log'; 
    $fp   = fopen($file, "a");
    fwrite($fp, $content);
    fclose ($fp);
}

/* Parse cron file. */
function parseCron($path = 'tasks')
{
    $crons = array();
    chdir($path);
    $files = glob('*');
    foreach($files as $file)
    {
        $handle  = fopen($file, 'r');
        $content = fread($handle, filesize($file));
        fclose($handle);

        $rows = explode("\n", $content);
        foreach($rows as $row)
        {
            $row = preg_replace("/[ \t]+/", ' ', trim($row, " \t"));
            $row = preg_replace("/#.*/", '', $row);
            if($row)
            {
                preg_match_all('/(\S+\s+){5}|.*/', $row, $matchs);
                if($matchs[0])
                {
                    $cron = array();
                    $cron['schema']  = trim($matchs[0][0]);
                    $cron['command'] = trim($matchs[0][1]);
                    $crons[]         = $cron;
                }
            }
        }
    }
    return $crons;
}
