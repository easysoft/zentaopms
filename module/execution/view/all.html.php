<?php
/**
 * The html template file of all method of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     execution
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php
js::import($jsRoot . 'dtable/min.js');
css::import($jsRoot . 'dtable/min.css');

$cols       = $this->execution->generateCol($orderBy);
$executions = $this->execution->generateRow($executionStats, $users, $productID);

$sortLink = $this->createLink('execution', 'all', "status=$status&orderBy={orderBy}&productID=$productID&param=$param&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID");

js::set('sortLink', $sortLink);
js::set('cols', json_encode($cols));
js::set('data', json_encode($executions));

js::set('orderBy', $orderBy);
js::set('status', $status);
js::set('from', $from);

js::set('isCNLang', !$this->loadModel('common')->checkNotCN());
?>

<?php $canBatchEdit = common::hasPriv('execution', 'batchEdit');?>
<?php $canBatchChangeStatus = common::hasPriv('execution', 'batchChangeStatus');?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolBar pull-left'>
    <?php if($from == 'project'):?>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList, $productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&orderby=$orderBy"), $lang->product->allProduct) . "</li>";
          foreach($productList as $key => $product)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&orderby=$orderBy&productID=$key"), $product) . "</li>";
          }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=$key&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab' data-app='$from'");?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->execution->byQuery;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=$from", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create'), "<i class='icon icon-sm icon-plus'></i> " . ($from == 'execution' ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-primary create-execution-btn' data-app='execution' onclick='$(this).removeAttr(\"data-toggle\")'");?>
  </div>
</div>

<div id='mainContent' class="main-row fade">
  <div class="cell<?php if($status == 'bySearch') echo ' show';?>" id="queryBox" data-module='execution'></div>
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution;?></span>
      <?php if(empty($allExecutionsNum)):?>
        <?php if(common::hasPriv('execution', 'create')):?>
        <?php echo html::a($this->createLink('execution', 'create'), "<i class='icon icon-plus'></i> " . ($from == 'execution' ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-info' data-app='execution'");?>
        <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='executionForm' method='post'>
    <div class="table-header fixed-right">
      <nav class="btn-toolbar pull-right setting"></nav>
    </div>
    <div id="myTable"></div>
    <div class='table-footer'>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('project', 'batchEdit');
            $misc       = "id='batchEditBtn'";
            echo html::commonButton($lang->edit, $misc);
        }
        if($canBatchChangeStatus)
        {
            $changeStatusHtml  = "<div class='btn-group dropup'>";
            $changeStatusHtml .= "<button data-toggle='dropdown' type='button' class='btn'>{$this->lang->statusAB} <span class='caret'></span></button>";
            $changeStatusHtml .= "<div class='dropdown-menu search-list'><div class='list-group'>";
            foreach($this->lang->execution->statusList as $status => $statusText)
            {
                $actionLink        = $this->createLink('execution', 'batchChangeStatus', "status=$status");
                $changeStatusHtml .= html::a('#', $statusText, '', "class='statusLink' data-link='$actionLink'  onmouseover=\"setBadgeStyle(this, true);\" onmouseout=\"setBadgeStyle(this, false)\"");
            }
            $changeStatusHtml .= "</div></div></div>";

            echo $changeStatusHtml;
        }
        ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <script>
  cols = JSON.parse(cols);
  data = JSON.parse(data);
  const options =
  {
      striped: true,
      plugins: ['nested', 'checkable'],
      checkOnClickRow: true,
      sortLink: createSortLink,
      cols: cols,
      data: data,
      footer: false,
      responsive: true,
      onCheckChange: toggleActions,
      height: function(height)
      {
          return Math.min($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - $('.table-footer').outerHeight() - 30, height);
      },
      onRenderCell: function(result, info)
      {
          if(info.col.name === 'burn' && Array.isArray(info.row.data.burns) && info.row.data.burns.length)
          {
              tryRenderSparkline();
              return [{html: '<span class="sparkline pending text-left no-padding" values="' + info.row.data.burns.join(',') + '"></span>'}];
          }
          return result;
      }
  };

  function renderSparkline()
  {
      $('#myTable .dtable-rows .sparkline.pending').removeClass('pending').sparkline();
  }

  function tryRenderSparkline()
  {
      if(window.renderingSparkline) clearTimeout(window.renderingSparkline);
      window.renderingSparkline = setTimeout(renderSparkline, 200);
  }

  function createSortLink(col)
  {
      var sort = col.name + '_asc';
      if(sort == orderBy) sort = col.name + '_desc';
      return sortLink.replace('{orderBy}', sort);
  }

  function toggleActions(changes)
  {
      checkItems = this.getChecks();

      if(checkItems.length > 0)
      {
          $('.table-footer .table-actions').show();
      }
      else
      {
          $('.table-footer .table-actions').hide();
      }
  }

  $(".export").modalTrigger({width:650, type:'iframe', shown: setCookie});

  function setCookie()
  {
      $.cookie('checkedItem', checkItems.join(','), {expires: config.cookieLife, path: config.webRoot});
  }

  $('#myTable').dtable(options);

  $('#batchEditBtn').click(function()
  {
      buildForm(createLink('execution', 'batchEdit'));
  })

  $('.statusLink').click(function()
  {
      buildForm($(this).data('link'), 'hiddenwin');
  })
  </script>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
