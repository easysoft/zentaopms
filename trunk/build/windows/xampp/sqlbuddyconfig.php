<?php
$sbconfig['DefaultAdapter']    = "mysql";
$sbconfig['DefaultHost']       = "localhost:" . getPortOfMySQL();
$sbconfig['DefaultUser']       = "root";
$sbconfig['EnableUpdateCheck'] = true;
$sbconfig['RowsPerPage']       = 100;
$sbconfig['EnableGzip']        = true;

function getPortOfMySQL()
{
    $mysqlConfig = file_get_contents('../../mysql/bin/my.ini');
    if(strpos($mysqlConfig, '3308')) return '3308';
    return '3306';
}
