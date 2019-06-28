<?php
/**
 * The select task file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun <chunsheng@cnezsoft.com>
 * @package     testreport 
 * @version     $Id: resolve.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testreport->selectTask;?></h2>
    </div>
    <form method='post'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->testtask->common;?></th>
          <td><?php echo html::select('testtask', $taskPairs, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th></th>
          <td colspan='2'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
