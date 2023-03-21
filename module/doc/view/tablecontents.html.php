<?php
/**
 * The table contents view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('moduleTree', $moduleTree);?>
<?php js::set('treeData', $libTree);?>
<?php js::set('linkParams', "type=$type&objectID=$objectID");?>
<?php js::set('docLang', $lang->doc);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
  $exportMethod = $type . '2export';
  if(common::hasPriv('doc', $exportMethod))
  {
      echo html::a($this->createLink('doc', $exportMethod, "libID=$libID&docID=0", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='$exportMethod'");
  }

  if(common::hasPriv('doc', 'createLib'))
  {
      echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe"');
  }

  if(common::hasPriv('doc', 'create'))
  {
      if($libID)
      {
          $html  = "<div class='dropdown btn-group createDropdown'>";
          $html .= html::a($this->createLink('doc', 'createBasicInfo', "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html", '', true), "<i class='icon icon-plus'></i> {$lang->doc->create}", '', "class='btn btn-primary iframe'");
          $html .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
          $html .= "<ul class='dropdown-menu pull-right'>";

          foreach($this->lang->doc->createList as $typeKey => $typeName)
          {
              if($config->edition != 'max' and $typeKey == 'template') continue;
              $class  = (strpos($this->config->doc->officeTypes, $typeKey) !== false or strpos($this->config->doc->textTypes, $typeKey) !== false) ? 'iframe' : '';
              $module = $typeKey == 'api' ? 'api' : 'doc';
              $method = strpos($this->config->doc->textTypes, $typeKey) !== false ? 'createBasicInfo' : 'create';

              $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=$typeKey";
              if($typeKey == 'api') $params = "libID=$libID&moduleID=$moduleID";
              if($typeKey == 'template') $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html&fromGlobal=&from=template";

              $html .= "<li>";
              $html .= html::a(helper::createLink($module, $method, $params, '', $class ? true : false), $typeName, '', "class='$class' data-app='{$this->app->tab}'");
              $html .= "</li>";
              if($typeKey == 'template') $html .= '<li class="divider"></li>';
              if($config->edition != 'max' and $typeKey == 'api') $html .= '<li class="divider"></li>';
          }

          $html .= '</ul></div>';
          echo $html;
      }
  }
  ?>
  </div>
</div>
<div id='mainContent'class="fade flex split-row">
  <div id='side-bar' class="panel side side-col col overflow-x-auto flex-none" data-min-width="150">
    <div id="fileTree" class="file-tree"></div>
  </div>
  <div id="spliter" class="spliter col-spliter"></div>
  <div class="main-col flex-full col overflow-x-auto flex-auto" data-min-width="500">
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" style="min-width: 400px" id="queryBox" data-module=<?php echo $type . 'Doc';?>></div>
    <?php if(empty($docs)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
        <?php
        if(common::hasPriv('doc', 'create'))
        {
            if($libID)
            {
                $html  = "<div class='dropdown btn-group createDropdown'>";
                $html .= html::a($this->createLink('doc', 'createBasicInfo', "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html", '', true), "<i class='icon icon-plus'></i> {$lang->doc->create}", '', "class='btn btn-info iframe'");
                $html .= "<button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                $html .= "<ul class='dropdown-menu pull-right'>";

                foreach($this->lang->doc->createList as $typeKey => $typeName)
                {
                    if($config->edition != 'max' and $typeKey == 'template') continue;
                    $class  = (strpos($this->config->doc->officeTypes, $typeKey) !== false or strpos($this->config->doc->textTypes, $typeKey) !== false) ? 'iframe' : '';
                    $module = $typeKey == 'api' ? 'api' : 'doc';
                    $method = strpos($this->config->doc->textTypes, $typeKey) !== false ? 'createBasicInfo' : 'create';

                    $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=$typeKey";
                    if($typeKey == 'api') $params = "libID=$libID&moduleID=$moduleID";
                    if($typeKey == 'template') $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html&fromGlobal=&from=template";

                    $html .= "<li>";
                    $html .= html::a(helper::createLink($module, $method, $params, '', $class ? true : false), $typeName, '', "class='$class' data-app='{$this->app->tab}'");
                    $html .= "</li>";
                    if($typeKey == 'template') $html .= '<li class="divider"></li>';
                    if($config->edition != 'max' and $typeKey == 'api') $html .= '<li class="divider"></li>';
                }

                $html .= '</ul></div>';
                echo $html;
            }
        }
        ?>
      </p>
    </div>
    <?php else:?>
    <div class="panel">
      <table class="table table-borderless table-hover table-files table-fixed no-margin">
        <thead>
          <tr>
            <th class="c-id"><?php echo $lang->doc->id;?></th>
            <th class="c-name"><?php echo $lang->doc->title;?></th>
            <th class="c-user"><?php echo $lang->doc->addedByAB;?></th>
            <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
            <th class="c-datetime"><?php echo $lang->doc->editedBy;?></th>
            <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
            <th class="w-90px text-center"><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($docs as $doc):?>
          <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
          <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
          <tr>
          <?php $objectID = isset($doc->{$type}) ? $doc->{$type} : 0;?>
            <td class="c-id" title='<?php echo $doc->id?>'>
            <?php
            if(common::hasPriv('doc', 'objectLibs'))
            {
                echo html::a($this->createLink('doc', 'objectLibs', "type=$type&objectID=$objectID&libID=$doc->lib&docID=$doc->id"), $doc->id, '', "title='{$doc->id}' data-app='{$this->app->tab}'");
            }
            else
            {
                echo "<i class='icon icon-file-text text-muted'></i> {$doc->id}";
            }
            ?>
            </td>
            <td class="c-name" title='<?php echo $doc->title;?>'>
            <?php
            if(common::hasPriv('doc', 'objectLibs'))
            {
                echo html::a($this->createLink('doc', 'objectLibs', "type=$type&objectID=$objectID&libID=$doc->lib&docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title='{$doc->title}' data-app='{$this->app->tab}' class='text-primary'");
            }
            else
            {
                echo "<i class='icon icon-file-text text-muted'></i> {$doc->title}";
            }
            ?>
            <?php if(common::canBeChanged('doc', $doc) and common::hasPriv('doc', 'collect')):?>
              <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
            <?php endif;?>
            </td>
            <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
            <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'y-m-d');?></td>
            <td class="c-user"><?php echo zget($users, $doc->editedBy);?></td>
            <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'y-m-d');?></td>
            <td class="c-actions">
              <?php if(common::canBeChanged('doc', $doc)):?>
              <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'", true, false)?>
              <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!empty($docs)):?>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
