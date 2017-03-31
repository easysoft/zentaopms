<?php
/**
 * The view lib file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='CASELIB'> <strong><?php echo $lib->id;?></strong></span>
    <strong><?php echo $lib->name;?></strong>
    <?php if($lib->deleted):?>
    <span class='label label-danger'><?php echo $lang->testsuite->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink = $this->session->caseList ? $this->session->caseList : $this->createLink('testsuite', 'library', "libID=$lib->id");
    $actionLinks = '';
    if(!$lib->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('testsuite', 'edit',     "libID=$lib->id");
        common::printIcon('testsuite', 'delete',   "libID=$lib->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>
<div class='main'>
  <fieldset>
    <legend><?php echo $lang->testsuite->legendDesc;?></legend>
    <div class='article-content'><?php echo $lib->desc;?></div>
  </fieldset>
  <?php include '../../common/view/action.html.php';?>
  <div class='actions'><?php echo $actionLinks;?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
