<?php
/**
 * The kanban view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     project
 * @version     $Id: kanban.html.php $
 */
?>
<?php if($_POST) die(include 'preview.html.php')?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->printKanban->common;?></h2>
    </div>
    <form target='_blank' method='post'>
      <table class='table'>
        <tr>
          <td class='text-center text-middle'>
            <?php echo $lang->printKanban->content . ' ： ' . html::radio('content', $lang->printKanban->typeList, 'all')?>
            <?php echo html::submitButton($lang->printKanban->print)?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
