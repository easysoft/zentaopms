<?php
/**
 * The html template file of configureScrum method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: configurescrum.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('configurescrum'), "<span class='text'>" . $lang->custom->concept . '</span>', '', "class='btn btn-link btn-active-text concept'");?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form id='ajaxForm' class='form-ajax' method='post'>
    <div class='modal-body'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->custom->scrum->setConcept;?> </th>
          <td colspan='3'><?php echo html::radio('sprintConcept', $lang->custom->sprintConceptList, zget($this->config->custom, 'sprintConcept', '0'))?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th></th>
          <td class='text-left'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
