<?php
/**
 * The create bug view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<tr>
  <th><?php echo $lang->issue->resolution;?></th>
  <td>
    <?php echo html::select('resolution', $lang->issue->resolveMethods, $resolution, 'class="form-control chosen" onchange="getSolutions()"');?>
  </td>
</tr>
<tr>
  <th class='w-110px'><?php echo $lang->bug->product;?></th>
  <td>
    <div class='input-group'>
      <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen control-product'");?>
      <?php if($this->session->currentProductType != 'normal' and isset($products[$productID])):?>
      <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control chosen control-branch'");?>
      <?php endif;?>
    </div>
  </td>
  <td>
    <div class='input-group' id='moduleIdBox'>
    <span class="input-group-addon"><?php echo $lang->bug->module?></span>
      <?php
      echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
      if(count($moduleOptionMenu) == 1)
      {
          echo "<span class='input-group-addon'>";
          echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
          echo '&nbsp; ';
          echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
          echo '</span>';
      }
      ?>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->bug->type;?></th>
  <?php $showOS      = strpos(",$showFields,", ',os,')      !== false;?>
  <?php $showBrowser = strpos(",$showFields,", ',browser,') !== false;?>
  <td>
    <div class='input-group' id='bugTypeInputGroup'>
      <?php echo html::select('type', $lang->bug->typeList, '', "class='form-control'");?>
      <?php if($showOS):?>
      <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
      <?php echo html::select('os', $lang->bug->osList, '', "class='form-control w-120px'");?>
      <?php endif;?>
      <?php if($showBrowser):?>
      <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
      <?php echo html::select('browser', $lang->bug->browserList, '', "class='form-control w-100px'");?>
      <?php endif;?>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->bug->project;?></th>
  <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProjectRelated(this.value)'");?></span></td>
  <td>
    <div class='input-group required' id='buildBox'>
      <span class="input-group-addon"><?php echo $lang->bug->openedBuild?></span>
      <?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?>
      <span class='input-group-addon fix-border' id='buildBoxActions'></span>
      <div class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn' id='all' data-toggle='tooltip' onclick='loadAllBuilds()'")?></div>
    </div>
  </td>
</tr>
<tr>
  <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
  <td>
    <div class='input-group'>
      <?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?>
      <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allUsers, "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip'");?></span>
    </div>
  </td>
<?php $showDeadline = strpos(",$showFields,", ',deadline,') !== false;?>
<?php if($showDeadline):?>
  <td id='deadlineTd'>
    <div class='input-group'>
      <span class='input-group-addon'><?php echo $lang->bug->deadline?></span>
      <span><?php echo html::input('deadline', $issue->deadline, "class='form-control form-date'");?></span>
    </div>
  </td>
</tr>
<?php endif;?>
<tr>
  <th><?php echo $lang->bug->title;?></th>
  <td colspan='2'>
    <div class="input-group title-group required">
      <div class="input-control has-icon-right">
        <?php echo html::input('title', $issue->title, "class='form-control'");?>
      </div>
      <span class="input-group-addon fix-border br-0"><?php echo $lang->bug->pri;?></span>
      <div class="input-group-btn pri-selector" data-type="pri">
        <?php echo html::select('pri', $lang->bug->priList, $issue->pri, "class='form-control'");?>
      </div>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->bug->steps;?></th>
  <td colspan='2'>
    <?php echo html::textarea('steps', $issue->desc, "rows='10' class='form-control'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedBy;?></th>
  <td>
    <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedDate;?></th>
  <td>
     <div class='input-group has-icon-right'>
       <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
       <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
     </div>
  </td>
</tr>
<tr>
  <td></td>
  <td>
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
<?php
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('createRelease', $lang->release->create);
js::set('createBuild', $lang->build->create);
js::set('refresh', $lang->refresh);
?>
<script>
$(function()
{
    var page = window.page || '';
    var flow = window.flow;

    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');
    if(page == 'create')
    {
        var productID  = $('#product').val();
        var moduleID   = $('#module').val();
        var assignedto = $('#assignedTo').val();
        changeProductConfirmed = true;
        oldStoryID             = $('#story').val() || 0;
        oldProjectID           = 0;
        oldOpenedBuild         = '';
        oldTaskID              = $('#oldTaskID').val() || 0;
        notice();
    }

    if(page == 'create' || page == 'edit' || page == 'assignedto' || page == 'confirmbug')
    {
        oldProductID = $('#product').val();
        $("#story, #task, #mailto").chosen();
    }

    if(window.flow != 'full')
    {
        $('.querybox-toggle').click(function()
        {
            $(this).parent().toggleClass('active');
        });
    }
});

/**
 * Load all fields.
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadAll(productID)
{
    if(page == 'create')
    {
        loadProjectTeamMembers(productID);
    }

    if(!changeProductConfirmed)
    {
        firstChoice = confirm(confirmChangeProduct);
        changeProductConfirmed = true;    // Only notice the user one time.

        if(!firstChoice)
        {
            $('#product').val(oldProductID);//Revert old product id if confirm is no.
            $('#product').trigger("chosen:updated");
            $('#product').chosen();
            return true;
        }

        loadAll(productID);
    }
    else
    {
        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        $('#task').chosen();
        loadProductBranches(productID)
        loadProductModules(productID);
        loadProductProjects(productID);
        loadProductBuilds(productID);
        loadProductplans(productID);
        loadProductStories(productID);
    }
}

/**
 * Load by branch.
 *
 * @access public
 * @return void
 */
function loadBranch()
{
    $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
    $('#task').chosen();
    productID = $('#product').val();
    loadProductModules(productID);
    loadProductProjects(productID);
    loadProductBuilds(productID);
    loadProductplans(productID);
    loadProductStories(productID);
}

/**
  *Load all builds of one project or product.
  *
  * @access public
  * @return void
  */
function loadAllBuilds(that)
{
    if(page == 'resolve')
    {
        oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&type=all');
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
    else
    {
        productID = $('#product').val();
        projectID = $('#project').val();
        if(page == 'edit') buildBox = $(that).closest('.input-group').attr('id');

        if(projectID)
        {
            loadAllProjectBuilds(projectID, productID);
        }
        else
        {
            loadAllProductBuilds(productID);
        }
    }
}

/**
  * Load all builds of the project.
  *
  * @param  int    $projectID
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadAllProjectBuilds(projectID, productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create')
    {
        oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    if(page == 'edit')
    {
        if(buildBox == 'openedBuildBox')
        {
            link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
            $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
        if(buildBox == 'resolvedBuildBox')
        {
            link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&needCreate=true&type=all');
            $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
    }
}

/**
  * Load all builds of the product.
  *
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadAllProductBuilds(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=all');
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    if(page == 'edit')
    {
        if(buildBox == 'openedBuildBox')
        {
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=all');
            $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
        if(buildBox == 'resolvedBuildBox')
        {
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&type=all');
            $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
    }
}

/**
 * Load product's modules.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductModules(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen()
        if(typeof(bugModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon' style='border-left-width: 1px;'>" + bugModule + "</span>");
    });
}

/**
 * Load product stories
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductStories(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load projects of product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductProjects(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID + '&branch=' + branch);
    $('#projectIdBox').load(link, function(){$(this).find('select').chosen()});
}

/**
 * Load product plans.
 *
 * @param  productID $productID
 * @access public
 * @return void
 */
function loadProductplans(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $('#planIdBox').load(link, function(){$(this).find('select').chosen()});
}

/**
 * Load product builds.
 *
 * @param  productID $productID
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);

    if(page == 'create')
    {
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    else
    {
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
}

/**
 * Load project related bugs and tasks.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadProjectRelated(projectID)
{
    if(projectID)
    {
        loadProjectTasks(projectID);
        loadProjectStories(projectID);
        loadProjectBuilds(projectID);
        loadAssignedTo(projectID);
    }
    else
    {
        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        loadProductStories($('#product').val());
        loadProductBuilds($('#product').val());
    }
}

/**
 * Load project tasks.
 *
 * @param  projectID $projectID
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID + '&taskID=' + oldTaskID);
    $.post(link, function(data)
    {
        if(!data) data = '<select id="task" name="task" class="form-control"></select>';
        $('#task').replaceWith(data);
        $('#task_chosen').remove();
        $("#task").chosen();
    })
}

/**
 * Load project stories.
 *
 * @param  projectID $projectID
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load builds of a project.
 *
 * @param  int      $projectID
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    productID = $('#product').val();
    oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&index=0&needCreate=true");
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    else
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});

        oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
}

/**
 * Set story field.
 *
 * @param  moduleID $moduleID
 * @param  productID $productID
 * @access public
 * @return void
 */
function setStories(moduleID, productID)
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story_chosen').remove();
        $("#story").chosen();
    });
}

/**
 * Load product branches.
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadProductBranches(productID)
{
    $('#branch').remove();
    $('#branch_chosen').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', page == 'create' ? '120px' : '65px');
            $('#branch').chosen();
        }
    })
}

/**
 * Load team members of the project as assignedTo list.
 *
 * @param  int     $projectID
 * @access public
 * @return void
 */
function loadAssignedTo(projectID)
{
    link = createLink('bug', 'ajaxLoadAssignedTo', 'projectID=' + projectID + '&selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        $('#assignedTo_chosen').remove();
        $('#assignedTo').replaceWith(data);
        $('#assignedTo').chosen();
    });
}

/**
 * notice for create build.
 *
 * @access public
 * @return void
 */
function notice()
{
    $('#buildBoxActions').empty().hide();
    if($('#openedBuild').find('option').length <= 1)
    {
        var html = '';
        if($('#project').length == 0 || $('#project').val() == '')
        {
            var branch = $('#branch').val();
            if(typeof(branch) == 'undefined') branch = 0;
            var link = createLink('release', 'create', 'productID=' + $('#product').val() + '&branch=' + branch); 
            html += '<a href="' + link + '" target="_blank" style="padding-right:5px">' + createBuild + '</a> ';
            html += '<a href="javascript:loadProductBuilds(' + $('#product').val() + ')">' + refresh + '</a>';
        }
        else
        {
            projectID = $('#project').val();
            html += '<a href="' + createLink('build', 'create','projectID=' + projectID) + '" target="_blank" style="padding-right:5px">' + createBuild + '</a> ';
            html += '<a href="javascript:loadProjectBuilds(' + projectID + ')">' + refresh + '</a>';
        }
        var $bba = $('#buildBoxActions');
        if($bba.length)
        {
            $bba.html(html);
            $bba.show();
        }
        else
        {
            if($('#buildBox').closest('tr').find('td').size() > 1)
            {
                $('#buildBox').closest('td').next().attr('id', 'buildBoxActions');
                $('#buildBox').closest('td').next().html(html);
            }
            else
            {
                html = "<td id='buildBoxActions'>" + html + '</td>';
                $('#buildBox').closest('td').after(html);
            }
        }
    }
}

/**
  * Load all users as assignedTo list.
  *
  * @access public
  * @return void
  */
function loadAllUsers()
{
    var link = createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        if(data)
        {
            var moduleID  = $('#module').val();
            var productID = $('#product').val();
            setAssignedTo(moduleID, productID);
            $('#assignedTo').empty().append($(data).find('option')).trigger('chosen:updated').trigger('chosen:activate');
        }
    });
}

/**
  * Load team members of the latest project of a product as assignedTo list.
  *
  * @param  $productID
  * @access public
  * @return void
  */
function loadProjectTeamMembers(productID)
{
    var link = createLink('bug', 'ajaxLoadProjectTeamMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link, function(){$('#assignedTo').chosen();});
}

/**
 * load assignedTo and stories of module.
 *
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    var moduleID  = $('#module').val();
    var productID = $('#product').val();
    setAssignedTo(moduleID, productID);
    setStories(moduleID, productID);
}

/**
 * Set the assignedTo field.
 *
 * @access public
 * @return void
 */
function setAssignedTo(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    var link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
        $("#assignedTo").trigger("chosen:updated");
    });
}

$(function()
{
    if($('#project').val()) loadProjectRelated($('#project').val());

    $('[data-toggle=tooltip]').tooltip();

    // adjust size of bug type input group
    var adjustBugTypeGroup = function()
    {
        var $group = $('#bugTypeInputGroup');
        var width = ($group.parent().width()), addonWidth = 0;
        var $controls = $group.find('.chosen-single');
        $group.children('.input-group-addon').each(function()
        {
            addonWidth += $(this).outerWidth();
        });
        var bestWidth = Math.floor((width - addonWidth)/$controls.length);
        $controls.css('width', bestWidth);
        var lastWidth = width - addonWidth - bestWidth * ($controls.length - 1);
        $controls.last().css('width', lastWidth);
    };
    adjustBugTypeGroup();
    $(window).on('resize', adjustBugTypeGroup);

    // init pri and severity selector
    $('#severity, #pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html($selector.data('type') === 'severity' ? '<span class="label-severity" data-severity="' + value + '" title="' + value + '"></span>' : '<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
    loadAll(<?php echo $productID;?>);
});
</script>
