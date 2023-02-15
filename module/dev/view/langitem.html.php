<?php
/**
 *  * The editor view file of dev module of ZenTaoPMS.
 *   *
 *    * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 *     * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *      * @author      Yidong Wang <yidong@cnezsoft.com>
 *       * @package     dev
 *        * @version     $Id$
 *         * @link        http://www.zentao.net
 *          */
?>
<?php include 'header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->dev->featureBar['langItem'] as $key => $label):?>
    <?php $active = $type == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('langItem', "type=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div id='mainContent' class='main-content flex'>
  <div class="side-left">
    <div class="title">默认值</div>
    <div class="label-list">
      <div labelId="123456Input" class="input-label h-32 my-12">项目集</div>
      <div labelId="223456Input" class="input-label h-32 my-12">产品</div>
    </div>
  </div>
  <div class="side-right">
    <div class="title">修改值</div>
    <div class="input-list">
     <div class="input-control h-32 my-12">
       <input id="123456Input" class="form-control shadow-primary-hover" placeholder="项目集"></input>
       <i iconId="123456Input" class="icon icon-angle-right text-primary hidden"></i>
     </div>
     <div class="input-control h-32 my-12">
       <input id="223456Input" class="form-control shadow-primary-hover" placeholder="产品"></input>
       <i iconId="223456Input" class="icon icon-angle-right text-primary hidden"></i>
     </div>
    </div>
  </div>
  <div class="side-main">
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

