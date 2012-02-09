<?php
/**
 * The export2html view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
table, th, td{font-size:12px; border:1px solid gray; border-collapse:collapse;}
</style>
<title><?php echo $fileName;?></title>
<body>
<table>
  <tr>
  <?php
  foreach($fields as $fieldLabel)
  {
      echo "<th><nobr>$fieldLabel</nobr></th>\n";
  }
  ?>
  </tr>
<?php
foreach($rows as $row)
{
    echo "<tr valign='top'>\n";
    foreach($fields as $fieldName => $fieldLabel)
    {
        $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';
        echo "<td><nobr>$fieldValue</nobr></td>\n";
    }
    echo "</tr>\n";
}
?>
</table>
</body>
</html>
