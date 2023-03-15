<?php
$sideLibs = array();
foreach($lang->doclib->tabList as $libType => $typeName) $sideLibs[$libType] = $this->doc->getLimitLibs($libType);
$allModules = $this->loadModel('tree')->getDocStructure();

$sideSubLibs = array();
$sideSubLibs['product']   = $this->doc->getSubLibGroups('product', array_keys($sideLibs['product']));
$sideSubLibs['execution'] = $this->doc->getSubLibGroups('execution', array_keys($sideLibs['execution']));
if($this->methodName != 'browse')
{
    $browseType = '';
    $moduleID   = '';
}
if(empty($type)) $type = 'product';
$sideWidth = common::checkNotCN() ? '270' : '238';
?>
<div class="side-col" style="width:<?php echo $sideWidth;?>px" data-min-width="<?php echo $sideWidth;?>">
  <div class="cell" id="<?php echo $type;?>">
    <div class="file-tree" data-type="<?php echo $type?>"></div>
  </div>

  <style>
  .float-r {float: right;}
  /* css for tree */
  .file-tree {overflow-x: auto;}
  .file-tree  a {height: 30px;}
  .flex-center {display: flex; align-items: center; justify-content: space-between;}
  .h-full {height: 100%;}
  .w-full {width: 100%;}
  .tree li>.list-toggle {top: 4px;}
  </style>

  <script>
  $(function()
  {
      var treeData = [
          {title: '产品主库', isCatalogue: false, children:[
              {title: '一级目录', children: [
                  {title: '二级目录', children: [
                      {title: '三级目录', children: [
                        {title: '四级目录'}
                      ]}
                  ]},
              ]},
          ]},
          {title: '产品自定义库',isCatalogue: false, children:[
              {title: '一级目录'},
          ]},
      ];

      $('.file-tree').tree(
      {
          data: treeData,
          itemCreator: function($li, item)
          {
              var $item = '<a href=# ' +
                          'data-has-children="' + (item.children ? !!item.children.length : false) + '"'  +
                          'title="' + item.title +
                          '">' +
                          '<div class="text h-full w-full flex-center">' + item.title +
                              '<i class="icon icon-drop icon-ellipsis-v float-r hidden"' +
                              'data-isCatalogue="' + (item.isCatalogue === false ? false : true) + '"' +
                              '></i>' +
                          '</div>' +
                          '</a>';
              $li.append($item);
              if (item.active) $li.addClass('active open in');
          }
      });
      $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");

      $('.file-tree').on('mousemove', 'a', function(e)
      {
          $(this).find('.icon').removeClass('hidden');
      }).on('mouseout', 'a', function(e)
      {
          $(this).find('.icon').addClass('hidden');
      })

      function renderDropdown(option)
      {
          var $liList = (option.id == 'dropDownLibrary') ?
                         ('<li data-method="addCatalogue"><a><i class="icon icon-controls"></i>添加目录</a></li>' +
                         '<li data-method="editLib"><a><i class="icon icon-edit"></i>编辑库</a></li>' +
                         '<li data-method="deleteLib"><a><i class="icon icon-trash"></i>删除库</a></li>')
                         :
                         ('<li data-method="addCatalogue"><a><i class="icon icon-controls"></i>添加同级目录</a></li>' +
                         '<li data-method="editLib"><a><i class="icon icon-edit"></i>添加子目录</a></li>' +
                         '<li data-method="editLib"><a><i class="icon icon-edit"></i>编辑目录</a></li>' +
                         '<li data-method="deleteLib"><a><i class="icon icon-trash"></i>删除目录</a></li>')

          debugger;
          var dropdown = '<ul class="dropdown-menu dropdown-in-tree" ' +
                         'id="dropDownLibrary" style="display: unset; ' +
                         'left:' + option.left + 'px; ' +
                         'top:' + option.top + 'px;' +
                          '">' + $liList +
                         '</ul>';
          return dropdown;
      };

      function refreshDropdown(option)
      {
          $('#' + option.id).css({
          'display': 'unset',
          'left': option.left,
          'top': option.top
          });
      };

      $('.file-tree').on('click', '.icon-drop', function(e)
      {
          var isCatalogue = $(this).attr('data-isCatalogue') === 'false' ? false : true;
          var dropDownID  = isCatalogue ? 'dropDownLibrary' : 'dropDownCatalogue';
          var option = {
              left: e.pageX,
              top: e.pageY,
              id: dropDownID
          };
          if (!$('#' + dropDownID).length)
          {
              var dropDown = renderDropdown(option);
              $("body").append(dropDown);
          }
          else
          {
              refreshDropdown(option)
          }
          e.stopPropagation();
      });

      $('body').on('click', function(e)
      {
          if(!$.contains(e.target, $('.dropdown-in-tree')))
          {
              $('.dropdown-in-tree').css('display', 'none');
          }
      });
  });
  </script>
</div>
