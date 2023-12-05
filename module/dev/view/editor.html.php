<?php
/**
 * The editor view file of dev module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dev
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='text-center'>
    <?php
    echo $lang->dev->noteEditor;
    if(common::hasPriv('editor', 'turnon')) echo html::a($this->createLink('editor', 'turnon', 'status=1'), $lang->dev->switchList[1], '', "class='btn btn-primary'");
    ?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
