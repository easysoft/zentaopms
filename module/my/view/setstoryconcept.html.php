<?php
/**
 * The set story concept file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: setstoryconcept.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>.form-actions{margin-top: 120px} </style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span><?php echo $lang->my->setStoryConcept;?></span>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->my->storyConcept;?></th>
          <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control chosen'");?></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      <div class='text-center form-actions'> <?php echo html::submitButton();?></div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
