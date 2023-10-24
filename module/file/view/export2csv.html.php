<?php
/**
 * The export2csv view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
echo '"'. implode('","', $fields) . '"' . "\n";
if($rows)
{
    foreach($rows as $row)
    {
        echo '"';
        foreach($fields as $fieldName => $fieldLabel)
        {
            isset($row->$fieldName) ? print(str_replace(array('"', '&nbsp;'), array('“', ' '), htmlspecialchars_decode(strip_tags($row->$fieldName, '<img>')))) : print('');
            echo '","';
        }
        echo '"' . "\n";
    }
}
if($this->post->kind == 'task' && $config->vision != 'lite') echo $this->lang->file->childTaskTips;
