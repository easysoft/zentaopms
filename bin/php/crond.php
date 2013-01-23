<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/crontab/crontab.class.php';
$cronPath = dirname(dirname(dirname(__FILE__))) . '/bin/cron';

$crons = parseCron($cronPath);

/* run as daemon. */
while(1)
{
    $now = new DateTime('now');
    foreach($crons as $key => $cron) 
    {
        if($now > $cron['time']) 
        {
            $crons[$key]['time'] = $cron['cron']->getNextRunDate();

            $output = array();
            $log    = '';
            exec($cron['command'], $output, $return);

            $time = $now->format('Y-m-d H:i:s');
            foreach($output as $out) $log .= $out . "\n"; 
            $log = $time . ' ' . $key . ' return ' . $return . ' : ' . $log . "\n";
            logCron($log);
        }
    }
    sleep(40);
}

/* Log cron results. */
function logCron($log)
{
    $path = dirname(dirname(dirname(__FILE__))) . '/tmp/cron';
    $file = $path . '/' . date('Ymd');

    if(!file_exists($path)) mkdir($path, 0777);

    $fp = fopen($file, "a");
    fwrite($fp, $log);
    fclose ($fp);
}

/* Parse cron file. */
function parseCron($path)
{
    $crons = array();
    chdir($path);
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
