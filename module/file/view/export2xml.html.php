<?php
/**
 * The export2xml view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php echo "<?xml version='1.0' encoding='utf-8'?><xml>\n";?>
<fields>
<?php
foreach($fields as $fieldName => $fieldLabel)
{
  echo "  <$fieldName>$fieldLabel</$fieldName>\n";
}
?>
</fields>
<rows>
<?php
foreach($rows as $row)
{
    echo "  <row>\n";
    foreach($fields as $fieldName => $fieldLabel)
    {
        $fieldValue = isset($row->$fieldName) ? htmlSpecialString($row->$fieldName) : '';
        echo "    <$fieldName>$fieldValue</$fieldName>\n";
    }
    echo "  </row>\n";
}
?>
</rows>
</xml>
