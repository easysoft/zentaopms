<?php
/**
 * The structure view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php echo $structures['name'] . '[' . $structures['code'] . '] ' .$lang->extension->structure . ':';?> <br /><br />
<?php unset($structures['name']); unset($structures['code']);?>
<?php foreach($structures as $structure):?>
<?php echo $structure;?> <br />
<?php endforeach;?>
