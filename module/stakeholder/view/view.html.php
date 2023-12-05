<?php
/**
 * The view view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(QingDao Nature Easy Soft Network Technology C
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
$browseLink = $this->createLink('stakeholder', 'browse', "projectID=$user->objectID");
$createLink = $this->createLink('stakeholder', 'create', "projectID=$user->objectID");
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $user->id?></span>
      <span class="text" title="<?php echo $user->name?>"><?php echo $user->name?></span>
      <?php if($user->deleted):?>
      <span class='label label-danger'><?php echo $lang->stakeholder->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('stakeholder', 'create')) echo html::a($createLink, "<i class='icon icon-plus'></i> {$lang->stakeholder->create}", '', "class='btn btn-primary' data-app=$app->tab");?>
  </div>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->stakeholder->nature;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($user->nature) ? $user->nature : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->stakeholder->analysis;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($user->analysis) ? $user->analysis : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->stakeholder->strategy;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($user->strategy) ? $user->strategy : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <?php foreach($expects as $expect):?>
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->stakeholder->expect . "($expect->createdDate)";?></div>
        <div class="detail-content article-content">
          <?php echo !empty($expect->expect) ? $expect->expect : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->stakeholder->progress . "($expect->createdDate)";?></div>
        <div class="detail-content article-content">
          <?php echo !empty($expect->progress) ? $expect->progress : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=stakeholder&objectID=$user->id");?>
    <div class="cell" id="communicate"><?php include '../../common/view/action.html.php';?></div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="tabs">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#basicInfo" data-toggle="tab"><?php echo $lang->stakeholder->basicInfo;?></a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="basicInfo">
            <table class="table table-data">
              <tbody>
                <tr valign="middle">
                  <th class="thWidth w-80px"><?php echo $lang->stakeholder->name;?></th>
                  <td><?php echo $user->name?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->type;?></th>
                  <td><?php echo zget($lang->stakeholder->typeList, $user->type, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->company;?></th>
                  <td><?php echo $user->companyName;?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->phone;?></th>
                  <td><?php echo $user->phone;?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->qq;?></th>
                  <td><?php echo $user->qq;?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->weixin;?></th>
                  <td><?php echo $user->weixin;?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->email;?></th>
                  <td><?php echo $user->email;?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->isKey;?></th>
                  <td><?php echo zget($lang->stakeholder->keyList, $user->key, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $this->lang->stakeholder->from;?></th>
                  <td><?php echo zget($lang->stakeholder->fromList, $user->from, '');?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
