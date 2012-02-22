<?php
/**
 * The html template file of index method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: step5.html.php 2568 2012-02-18 15:53:35Z zhujinyong@cnezsoft.com$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table align='center' class='table-6'>
  <caption><?php echo $lang->install->success;?></caption>
  <tr>
	<td>
	  <?php echo nl2br(sprintf($lang->install->joinZentao, $config->version, $this->createLink('admin', 'register'), $this->createLink('admin', 'bind'), inlink('step6')));?>
	</td>
  </tr>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
