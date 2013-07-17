<?php
/**
 * The html template file of index method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<table align='center' class='table-5'>
  <caption><?php echo $lang->convert->common;?></caption>
  <tr><td><?php echo nl2br($lang->convert->desc);?></td></tr>
  <tr><td><h3 class='a-center'><?php echo html::a($this->createLink('convert', 'selectsource'), $lang->convert->start);?></h3></td></tr>
</table>
<?php include '../../common/view/footer.html.php';?>
