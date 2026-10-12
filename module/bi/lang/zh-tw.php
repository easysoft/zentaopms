<?php
$lang->bi->binNotExists        = 'DuckDB 二进制文件不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目录没有权限, 需要修改目录 "%s" 的权限。<br />命令为：<br />chmod 755 -R %s。';

$lang->bi->errorIllegalFilter = array();
$lang->bi->errorIllegalFilter['field']    = '過濾條件的字段名不合法，請求已被拒絕。';
$lang->bi->errorIllegalFilter['operator'] = '過濾條件的操作符不合法，請求已被拒絕。';
$lang->bi->errorIllegalFilter['value']    = '過濾條件的值不合法，只允許數字或引號包裹的字符串，請求已被拒絕。';
$lang->bi->errorIllegalSql                = 'SQL 語句包含危險語法（寫文件/讀文件），請求已被拒絕。';
