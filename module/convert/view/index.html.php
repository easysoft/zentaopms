<?php
/**
 * The html template file of index method of convert module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-5'>
    <caption><?php echo $lang->convert->common;?></caption>
    <tr><td><?php echo nl2br($lang->convert->desc);?></td></tr>
    <tr><td><h3 class='a-center'><?php echo html::a($this->createLink('convert', 'selectsource'), $lang->convert->start);?></h3></td></tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
