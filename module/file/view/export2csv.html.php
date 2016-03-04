<?php
/**
 * The export2csv view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
echo '"'. implode('","', $fields) . '"' . "\n";
foreach($rows as $row)
{
    echo '"';
    foreach($fields as $fieldName => $fieldLabel)
    {
        isset($row->$fieldName) ? print(str_replace('",', '"，', htmlspecialchars_decode(strip_tags($row->$fieldName, '<img>')))) : print('');
        echo '","';
    }
    echo '"' . "\n";
}
