<?php
/**
 * The export2html view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
table th,table td{padding:5px;}
</style>
<title><?php echo $fileName;?></title>
<body>
<?php if($this->post->kind == 'task') echo "<font color='red'>" . $this->lang->file->childTaskTips . '</font>';?>
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
$rowspans = $this->post->rowspan ? $this->post->rowspan : array();
$colspans = $this->post->colspan ? $this->post->colspan : array();
$i = 0;
foreach($rows as $row)
{
    echo "<tr valign='top'>\n";
    $col        = 0;
    $endColspan = 0;
    foreach($fields as $fieldName => $fieldLabel)
    {
        $col ++;
        if(!empty($endColspan) and $col < $endColspan) continue;
        if(isset($endRowspan[$fieldName]) and $i < $endRowspan[$fieldName]) continue;
        $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';
        $rowspan = '';
        if(isset($rowspans[$i]) and isset($rowspans[$i]['rows'][$fieldName]))
        {
            $rowspan = "rowspan='{$rowspans[$i]['rows'][$fieldName]}'";
            $endRowspan[$fieldName] = $i + $rowspans[$i]['rows'][$fieldName];
        }
        $colspan = '';
        if(isset($colspans[$i]) and isset($colspans[$i]['cols'][$fieldName]))
        {
            $colspan = "colspan='{$colspans[$i]['cols'][$fieldName]}'";
            $endColspan = $col + $colspans[$i]['cols'][$fieldName];
        }
        echo "<td $rowspan $colspan><nobr>$fieldValue</nobr></td>\n";

    }
    echo "</tr>\n";
    $i++;
}
?>
</table>
</body>
</html>
