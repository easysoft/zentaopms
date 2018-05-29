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
<main class='main'>
  <div class='container'>
    <div id='mainContent' class='main-content'>
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $lang->printKanban->common;?></h2>
        </div>
        <form target='_blank' method='post'>
          <table class='table table-form'>
            <tr>
              <td class='text-right w-100px'><?php echo $lang->printKanban->content?>:</td>
              <td class='text-left text-middle'>
                <?php echo html::radio('content', $lang->printKanban->typeList, 'all')?>
                &nbsp; &nbsp; <?php echo html::submitButton($lang->printKanban->print, '', 'btn btn-primary btn-wide')?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
