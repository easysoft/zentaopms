<?php
include("../config/my.php");
$demoSql = file("../db/demo.sql");
$output  = '';
foreach($demoSql as $sql)
{
    if(strpos($sql, 'INSERT') !== false and
       strpos($sql, $config->db->prefix . 'config')  === false and
       strpos($sql, $config->db->prefix . 'company') === false and
       strpos($sql, $config->db->prefix . 'group')   === false
       ) $output .= $sql;
}
file_put_contents("../db/demo.sql", $output);

