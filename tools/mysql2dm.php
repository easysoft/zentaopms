#!/usr/bin/env php
<?php
include '../lib/dbh/dbh.class.php';

$config = new stdclass();

$config->db      = new stdclass();
$config->default = new stdclass();
function getWebRoot() {}
include '../config/my.php';

if(!file_exists('dmconfig.php'))
{
    print("Please create the config file named 'dmconfig.php' in this directory. Config example:");
    print <<<EOT


<?php
\$dmConfig = new stdclass();
\$dmConfig->driver   = 'dm';
\$dmConfig->host     = '10.0.7.242';
\$dmConfig->port     = '5236';
\$dmConfig->name     = 'lybiz85';
\$dmConfig->user     = 'SYSDBA';
\$dmConfig->encoding = 'UTF8';
\$dmConfig->prefix   = 'zt_';
\$dmConfig->password = 'SYSDBA001';


EOT;
    exit;
}

echo "开始迁移MySQL数据到达梦数据库\n\n";

include 'dmconfig.php';

function formatSQL($sql)
{
    $fieldsBegin = stripos($sql, 'select');
    $fieldsEnd   = stripos($sql, 'from');
    $fields      = substr($sql, $fieldsBegin+6, $fieldsEnd-$fieldsBegin-6);
    $fieldList   = preg_split("/,(?![^(]+\))/", $fields);
    foreach($fieldList as $key => $field)
    {
        $aliasPos = stripos($field, ' AS ');
        $subField = substr($field, 0, $aliasPos);
        if(stripos($field, 'SUM(') === 0) $subField = substr($subField, 4, -1);

        $fieldParts = preg_split("/\+(?![^(]+\))/", $subField);
        foreach($fieldParts as $pkey => $fieldPart)
        {
            $originField = trim($fieldPart);
            if(stripos($originField, 'if(') === false) continue;
            $fieldParts[$pkey] = formatDmIfFunction($originField);
        }
        $fieldList[$key] = str_replace($subField, implode(' + ', $fieldParts), $field);
    }
    $fields = implode(',', $fieldList);
    return substr($sql, 0, $fieldsBegin+6) . $fields . substr($sql, $fieldsEnd);
}

function formatDmIfFunction($field)
{
    preg_match('/if\(.+\)+/i', $field, $matches);

    $if = $matches[0];
    if(substr_count($if, '(') == 1)
    {
        $pos = strpos($if, ')');
        $if  = substr($if, 0, $pos+1);
    }

    /* fix sum(if(..., 1, 0)) , count(if(..., 1, 0)) */
    if(substr($if, strlen($if)-2) == '))' and (stripos($field, 'sum(') == 0 or stripos($field, 'count(') == 0)) $if = substr($if, 0, strlen($if)-1);

    $parts = explode(',', substr($if, 3, strlen($if)-4)); // remove 'if(' and ')'
    $case  = 'CASE WHEN ' . implode(',', array_slice($parts, 0, count($parts)-2)) . ' THEN ' . $parts[count($parts)-2] . ' ELSE ' . $parts[count($parts)-1] . ' END';
    $field = str_ireplace($if, $case, $field);

    return $field;
}

$mysql = new dbh($config->db);

$stmt = $mysql->query('SHOW TABLES');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tables  = array();
$views   = array();

$indexes = array(); //普通索引
$tablePrimary   = array(); //表的主键

foreach($data as $table)
{
    $table = current($table);

    /* Table. */
    if(strpos($table, 'zt_') === 0)
    {
        $stmt  = $mysql->prepare('DESC ' . $table);
        $stmt->execute();
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Table. */
        $tables[$table] = array();
        foreach($fields as $field)
        {
            $type = strtolower(explode('(', $field['Type'])[0]);
            $type = trim(str_replace('unsigned', '', $type));
            switch($type)
            {
                case 'int':
                case 'mediumint':
                case 'smallint':
                case 'tinyint':
                    $type = 'integer';
                    break;
                case 'enum':
                case 'varchar':
                case 'char':
                    $type = 'varchar(255)';
                    break;
                case 'mediumtext':
                case 'longtext':
                    $type = 'text';
                    break;
            }

            $tables[$table][$field['Field']] = array(
                'type'          => $type,
                'name'          => $field['Field'],
                'null'          => $field['Null'] == 'YES',
                'default'       => $field['Default'],
                //'isPrimary'     => $field['Key'] == 'PRI', // 不需要从DESC读取索引
                'autoIncrement' => $field['Extra'] == 'auto_increment',
            );
        }

        /* Index. */
        $stmt  = $mysql->prepare('SHOW INDEX FROM ' . $table);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result)
        {
            foreach($result as $item)
            {
                $keyName = $item['Key_name'];

                /* 主键索引在创建表的时候一起创建。 */
                if($keyName == 'PRIMARY')
                {
                    if(!isset($tablePrimary[$table])) $tablePrimary[$table] = array();
                    $tablePrimary[$table][(int)$item['Seq_in_index']-1] = $item['Column_name'];
                    continue;
                }

                /* 其他索引单独创建。 */
                if(!isset($indexes[$table . $keyName])) $indexes[$table . $keyName] = array('table' => $table, 'name' => $keyName, 'type' => $item['Index_type'], 'isUnique' => !$item['Non_unique'], 'cols' => array());

                /* 达梦的全文索引只支持一个字段。 */
                if($item['Index_type'] == 'FULLTEXT' && $item['Column_name'] != 'title') continue;

                $indexes[$table . $keyName]['cols'][(int)$item['Seq_in_index']-1] = $item['Column_name'];
            }
        }
    }
    /* View. */
    elseif(strpos($table, 'ztv_') === 0)
    {
        $stmt  = $mysql->prepare('SHOW CREATE VIEW ' . $table);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pos = stripos($result[0]['Create View'], ' as ');
        $sql = substr($result[0]['Create View'], $pos + 4);
        $sql = str_replace('`', '"', $sql);
        $views[$table] = array(
            'sql' => formatSQL($sql),
        );
    }
}

echo "初始化表空间和用户\n";
$dm = new dbh($dmConfig, false);

/* Delete. */
$tableSpace = strtoupper($dmConfig->name);
$res = $dm->query("SELECT * FROM dba_users WHERE username = '{$dmConfig->name}'")->fetchAll();
if(!empty($res))
{
    $dropUser = "DROP USER \"{$dmConfig->name}\" CASCADE";
    $dm->rawQuery($dropUser);
}

$tableSpace = strtoupper($dmConfig->name);
$res = $dm->query("SELECT * FROM dba_data_files WHERE TABLESPACE_NAME = '$tableSpace'")->fetchAll();
if(!empty($res))
{
    $dropTableSpace = "DROP TABLESPACE \"$tableSpace\"";

    $dm->rawQuery($dropTableSpace);
}

/* Create. */
$createTableSpace = "CREATE TABLESPACE \"$tableSpace\" DATAFILE '{$dmConfig->name}.DBF' size 150 AUTOEXTEND ON";
$createUser       = "CREATE USER \"{$dmConfig->name}\" IDENTIFIED by {$dmConfig->password} DEFAULT TABLESPACE \"{$dmConfig->name}\" DEFAULT INDEX TABLESPACE \"{$dmConfig->name}\"";

$dm->query($createTableSpace);
$dm->query($createUser);

$createSchema = "CREATE SCHEMA \"{$dmConfig->name}\" AUTHORIZATION \"{$dmConfig->name}\"";
$dm->query($createSchema);

echo "生成达梦数据表结构\n";
$dm = new dbh($dmConfig);

$identityTables = array(); //标记有自增字段的表，插入数据的时候会判断

/* 插入表结构。 */
foreach($tables as $table => $fields)
{
    $sql = "CREATE TABLE IF NOT EXISTS \"$table\" (\n";

    foreach(array_values($fields) as $key => $field)
    {
        $sql .= '"' . $field['name'] . '" ' . $field['type'] . ($field['null'] ? ' NULL ' : ' NOT NULL ');
        if($field['default'] !== NULL && $field['default'] != 'CURRENT_TIMESTAMP') $sql .= " DEFAULT '{$field['default']}' ";

        if($field['autoIncrement'])
        {
            $sql .= " IDENTITY(1, 1) ";
            $identityTables[$table] = $table;
        }

        $sql .= $key == count(array_values($fields))-1 && !isset($tablePrimary[$table]) ? "\n" : ",\n";
    }

    if(isset($tablePrimary[$table]))
    {
        $primary = array();
        ksort($tablePrimary[$table]);
        foreach($tablePrimary[$table] as $col) $primary[] = '"' . $col . '"';

        $sql .= "NOT CLUSTER PRIMARY KEY (" . implode(',', $primary) . ")"; // 只有NOT CLUSTER可以删除
    }

    $sql .= "\n)\n";

    $dm->rawQuery($sql);
}

echo "生成达梦表索引\n";
foreach($indexes as $index)
{
    $name = strtolower($index['table']) . '_' . $index['name'];
    foreach($index['cols'] as $col)
    {
        $fields = array();
        ksort($index['cols']);
        foreach($index['cols'] as $col)
        {
            $fields[] = '"' . $col . '"';
        }
    }

    $sql = 'CREATE ';
    if($index['isUnique']) // 唯一索引
    {
        $sql .= ' UNIQUE ';
    }
    elseif($index['type'] == 'FULLTEXT') // 全文索引
    {
        $sql .= ' CONTEXT ';
    }

    $sql .= ' INDEX "' . $name . '" ON `' . $index['table'] . '` (' . implode(',', $fields) . ')';

    $dm->rawQuery($sql);
}

echo "生成达梦视图\n";
/* 插入视图结构。 */
foreach($views as $view => $info)
{
    $sql = str_replace('convert(', '(', $info['sql']);
    $sql = str_replace('using utf8mb3', '', $sql);
    $sql = str_replace('using utf8_general_ci', '', $sql);
    $dm->rawQuery("CREATE OR REPLACE VIEW \"$view\" AS " . $sql);
}

echo "开始插入数据，请耐心等待...";
foreach($tables as $table => $fields)
{
    $dm->rawQuery("DELETE FROM `$table`");
    $rows = $mysql->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    if(empty($rows)) continue;

    $fs = array();
    foreach($fields as $field) $fs[] = '"' . $field['name'] . '"';
    $fs = implode(',', $fs);

    $template = "INSERT INTO \"$table\"($fs) VALUES";

    $step  = 500;
    $begin = 0;
    $data  = array();
    for($key = $begin; $key < count($rows); $key++)
    {
        $row    = $rows[$key];
        $values = array();
        foreach($fields as $field)
        {
            $value = str_replace("'", "''''", $row[$field['name']]);
            $value = "'{$value}'";
            if($value == "'0000-00-00 00:00:00'" || $value == "'0000-00-00'")
            {
                $null = $tables[$table][$field['name']]['null'];
                $value = $null ? 'NULL': '1970-01-01';
            }
            $values[] = $value;
        }
        $values = '(' . implode(',', $values) . ')';
        $data[] = $values;
    }

    $sql = $template . implode(',', $data) . ';';

    if(isset($identityTables[$table])) $sql = "SET IDENTITY_INSERT $table ON;" . $sql;

    $dm->rawQuery($sql);
}

echo "\n迁移成功，修改my.php测试一下吧\n\n";
