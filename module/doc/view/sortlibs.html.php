<?php
/**
 * The sortlibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     doc
 * @version     $Id: sorlibs.html.php 958 2022-06-21 14:20:42Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="main">
  <div class='main-header'>
    <h2><?php echo $lang->doc->order;?></h2>
  </div>
  <div id='mainContent' class='main-content'>
    <div class='doclib-name'><?php echo $lang->doclib->name?></div>
    <?php $libIdList = ',';?>
    <form id='libs' class='load-indicator main-form' method='post' target='hiddenwin'>
      <div class='libList'>
        <?php foreach($libs as $libID => $libName):?>
        <?php $libIdList .= "$libID,";?>
        <div class='lib' data-id='<?php echo $libID;?>'>
          <span class='lib-name'><?php echo $libName;?></span><i class='icon-move'></i>
        </div>
        <?php endforeach;?>
      </div>
      <?php echo html::hidden('libIdList', trim($libIdList, ','));?>
      <div class='text-center'><?php echo html::submitButton($lang->save);?></div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
