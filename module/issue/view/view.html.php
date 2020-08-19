<?php
/**
 * The details view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology C
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
$browseLink = $this->createLink('issue', 'browse');
$createLink = $this->createLink('issue', 'create');
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $issue->id?></span>
      <span class="text" title="<?php echo $issue->title?>"><?php echo $issue->title?></span>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('issue', 'create')) echo html::a($createLink, "<i class='icon icon-plus'></i> {$lang->issue->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <p><strong>标题</strong> - 设计出现偏差，需求重新设计</p>
        <p><strong>标题</strong> - 设计出现偏差，需求重新设计</p>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->issue->desc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($issue->desc) ? $issue->desc : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=issue&objectID=$issue->id");?>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="tabs">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#basicInfo" data-toggle="tab"><?php echo $lang->issue->basicInfo;?></a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="basicInfo">
            <table class="table table-data">
              <tbody>
                <tr valign="middle">
                  <th class="thWidth w-80px"><?php echo $lang->issue->type;?></th>
                  <td></td>
                  
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
