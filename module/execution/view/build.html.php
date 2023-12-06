<?php
/**
 * The build view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: build.html.php 4262 2013-01-24 08:48:56Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<?php js::set('executionID', $executionID)?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    foreach($lang->execution->featureBar['build'] as $featureType => $label)
    {
        $label       = "<span class='text'>$label</span>";
        $activeClass = $type == $featureType ? 'btn-active-text' : '';
        if($type == $featureType) $label .= " <span class='label label-light label-badge'>{$buildsTotal}</span>";
        echo html::a(inlink('build', "executionID=$executionID&type=$featureType"), $label, '',"class='btn btn-link $activeClass' data-app={$app->tab} id='$featureType'");
    }
    ?>
    <div class="input-control space w-150px <?php echo $hidden;?>"><?php echo html::select('product', $products, $product, "onchange='changeProduct(this.value)' class='form-control chosen' data-placeholder='{$lang->productCommon}'");?></div>
  </div>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->execution->byQuery;?></a>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('execution', $execution)) common::printLink('build', 'create', "execution=$execution->id", "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <div class="cell <?php if($type == 'bysearch') echo 'show';?>" id="queryBox" data-module='executionBuild'></div>
  <?php if(empty($executionBuilds)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->build->noBuild;?></span>
      <?php if(common::canModify('execution', $execution) and common::hasPriv('build', 'create')):?>
      <?php echo html::a($this->createLink('build', 'create', "execution=$execution->id"), "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <div class='main-table' data-ride="table" data-checkable="false">
    <table class="table text-center" id='buildList'>
      <thead>
        <tr>
          <th class="c-id-sm"><?php echo $lang->build->id;?></th>
          <th class="c-name w-150px text-left <?php echo $hidden;?>"><?php echo $lang->build->product;?></th>
          <?php if($showBranch):?>
          <th class="c-name w-150px text-left <?php echo $hidden;?>"><?php echo $lang->build->branch;?></th>
          <?php endif;?>
          <th class="c-name text-left"><?php echo $lang->build->name;?></th>
          <th class="c-url w-200px text-left"><?php echo $lang->build->url;?></th>
          <th class="c-date"><?php echo $lang->build->date;?></th>
          <th class="c-user"><?php echo $lang->build->builder;?></th>
          <th class="c-actions-5"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($executionBuilds as $productID => $builds):?>
        <?php foreach($builds as $index => $build):?>
        <tr data-id="<?php echo $productID;?>">
          <td class="c-id-sm text-muted"><?php echo html::a(helper::createLink('build', 'view', "buildID=$build->id"), sprintf('%03d', $build->id));?></td>
          <td class="c-name text-left <?php echo $hidden;?>" title='<?php echo $build->productName;?>'><?php echo $build->productName;?></td>
          <?php if($showBranch):?>
          <td class="c-name text-left <?php echo $hidden;?>" title='<?php echo $build->branchName;?>'><?php echo $build->branchName;?></td>
          <?php endif;?>
          <td class="c-name" title="<?php echo $build->name;?>"><?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?></td>
          <td class="c-url text-left">
            <?php
            if($build->scmPath)
            {
                $colorStyle = strpos($build->scmPath, 'http') === 0 ? "style='color:#2463c7;'" : '';
                echo "<div><i class='icon icon-file-code' $colorStyle title='{$lang->build->scmPath}'></i> ";
                echo "<span title='{$build->scmPath}'>";
                echo $colorStyle ? html::a($build->scmPath, $build->scmPath, '_blank', $colorStyle) : $build->scmPath;
                echo '</span></div>';
            }
            if($build->filePath)
            {
                $colorStyle = strpos($build->filePath, 'http') === 0 ? "style='color:#2463c7;'" : '';
                echo "<div><i class='icon icon-download' $colorStyle title='{$lang->build->filePath}'></i> ";
                echo "<span title='{$build->filePath}'>";
                echo $colorStyle ? html::a($build->filePath, $build->filePath, '_blank', $colorStyle) : $build->filePath;
                echo '</span></div>';
            }
            ?>
          </td>
          <td class="c-date"><?php echo $build->date?></td>
          <td class="c-user em"><?php echo zget($users, $build->builder);?></td>
          <td class="c-actions"><?php echo $this->build->buildOperateMenu($build, 'browse', "executionID={$execution->id}&productID={$productID}");?></td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
