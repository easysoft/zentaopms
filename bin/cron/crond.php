<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/crontab/crontab.class.php';
include 'config.php';

/* all tasks to run. */
$tasks = array();
foreach($crontab as $key => $cron)
{
    $tasks[$key]->cron = CronExpression::factory($cron['schema']);
    $tasks[$key]->time = $tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i');
}

/* run as daemon. */
while(1)
{
    foreach($crontab as $key => $cron) 
    {
        if($tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i') != $tasks[$key]->time)
        {
            $time    = date('Y-m-d H:i:s');
            $output  = system('php ' . $cron['script'], $retval);
            $content = $time . ' ' . $key . ' return ' . $retval . ' : ' . $output . "\n";
            logCron($content);
            $tasks[$key]->time = $tasks[$key]->cron->getNextRunDate()->format('Y-m-d H:i');
        }
    }
    sleep(60);
}

/* log cron results. */
function logCron($content)
{
    $file = dirname(dirname(dirname(__FILE__))) . '/tmp/cron.log'; 
    $fp   = fopen($file, "a");
    fwrite($fp, $content);
    fclose ($fp);
}
