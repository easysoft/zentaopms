<?php
/**
 * The showLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $object->name?></strong>
  </div>
  <div class='panel-body row'>
    <?php foreach($libs as $libID => $libName):?>
    <div class='col-md-3'>
      <div class='lib' title='<?php echo $libName?>'>
        <?php
        if($libID == 'project')
        {
            echo html::a(inlink('allLibs', "type=project&extra=product=$object->id"), $libName);
        }
        else
        {
            echo html::a(inlink('browse', "libID=$libID"), $libName);
        }
        ?>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php js::set('type', $type);?>
<?php include '../../common/view/footer.html.php';?>
