var mindmapMgr = undefined;

$(document).ready(function()
{
    mindmapMgr = new ztmindmap.Wraper({
        product: productID,
        branch: branch,
        typeKeys:{
            module: userConfig_module,
            scene: userConfig_scene,
            testcase: userConfig_case,
            stepGroup: userConfig_group,
            pri: userConfig_pri
        }
    });

    $.get($.createLink('testcase', 'getXmindImport'),function(res)
    {
        var data = JSON.parse(res);
        if(ztmindmap.isArray(data))
        {
            var rootTopic = data[0].rootTopic;
            mindmapMgr.initFromJSON(rootTopic);
        }
        else
        {
            var sheet = data["xmap-content"]["sheet"];
            if(ztmindmap.isArray(sheet)) sheet = sheet[0];
            mindmapMgr.initFromSheet(sheet);
        }

        setTimeout(() => mindmapMgr.render(), 500)
    });

    $("#xmindmapSave").on("click",function(e)
    {
        var data = mindmapMgr.toJson();
        if(data.testcaseList.length == 0)
        {
            alert(jsLng.caseNotExist);
            return;
        }

        var sceneList    = JSON.stringify(data.sceneList);
        var testcaseList = JSON.stringify(data.testcaseList);

        var form = new FormData();
        form.append("sceneList", sceneList);
        form.append("testcaseList", testcaseList);

        postAndLoadPage($.createLink('testcase', 'saveXmindImport'), form);
    })
});

function setStories(){
    //do nothing
}

if(window.ztmindmap == undefined) window.ztmindmap = {};

ztmindmap.ModuleManager = function(wraper){ this.wraper = wraper;}
ztmindmap.ModuleManager.prototype.clearNodeDisplay = function($node, data){ $node.find(".suffix").hide();};

ztmindmap.ModuleManager.prototype.getContextMenuList = function(data)
{
    if(data.$.typeBy.import == true) return [];

    //如果上一个节点时模型，当前节点可以在 ”场景“ 和 ”测试用例间切换“
    var parentNode = this.wraper.findParentNode(data);
    if(parentNode.$.type == "module")
    {
        var menus = [];

        if(data.$.type == "testcase" || data.$.type == undefined) menus.push({action:"addScene", label:jsLng.set2Scene});
        if(data.$.type == "scene" || data.$.type == undefined) menus.push({action:"addTestcase", label:jsLng.set2Testcase});
        if(data.$.type != undefined) menus.push({action:"delType", label:jsLng.clearSetting});

        return menus;
    }

    return [];
};

ztmindmap.ModuleManager.prototype.delType = function($node,data)
{
    this.wraper.changeNodeType($node,data,undefined);
    this.wraper.clearTypeAfter($node,data);
    //this.refreshDisplay();
}

ztmindmap.ModuleManager.prototype.addScene = function($node,data)
{
    var moduleData = this.wraper.findParentData(data);
    data.$.moduleID = moduleData.$.moduleID;
    this.wraper.changeNodeType($node,data,"scene");
    this.wraper.clearTypeAfter($node,data);
    //this.refreshDisplay();
}

ztmindmap.ModuleManager.prototype.addTestcase = function($node,data)
{
    this.wraper.changeNodeType($node,data,"testcase");
    this.wraper.clearTypeAfter($node,data);
    //this.refreshDisplay();
}

ztmindmap.ModuleManager.prototype.refreshNodeDisplay = function($node, data)
{
    $node.find(".suffix").hide();

    if(data.$.moduleID != undefined)
    {
        $node.find(".suffix").show();
        $node.find(".suffix").find(".content").html(this.wraper.disKeys['module'] + ":" + data.$.moduleID);
    }
}

ztmindmap.ModuleManager.prototype.refreshDisplay = function(){ this.wraper.refreshDisplay();};


if(window.ztmindmap == undefined) window.ztmindmap = {};

ztmindmap.SceneManager = function(wraper)
{
    this.wraper = wraper;
    this.proprMgr = new ztmindmap.SceneProperyManager(this);
    this.proprMgr.init();
};

ztmindmap.SceneManager.prototype.clearNodeDisplay = function($node, data)
{
    $node.find(".suffix").hide();
    $node.find(".scene-indicator").hide();

    $node.removeAttr("title");
    data.tooltip = undefined;
    data.$.moduleID = undefined;
    $node.tooltip('destroy');
};

ztmindmap.SceneManager.prototype.refreshNodeDisplay = function($node, data)
{
    $node.find(".scene-indicator").show();

    $node.find(".scene-indicator").removeClass("scene-indicator-yes");

    if(data.$.moduleID != undefined)
    {
        $node.attr("title",data.tooltip);
        $node.tooltip();

        $node.find(".scene-indicator").addClass("scene-indicator-yes");
    }

    if(data.$.sceneID != undefined)
    {
        $node.find(".suffix").show();
        $node.find(".suffix").find(".content").html(this.wraper.disKeys['scene'] + ":" + data.$.sceneID);
    }
};

ztmindmap.SceneManager.prototype.getContextMenuList = function(data)
{
    var parentNode = this.wraper.findParentNode(data);
    if(parentNode.$.type == "scene" && (data.$.type != "testcase" && data.$.type != "scene"))
        return [{action:"addTestcase", label:jsLng.set2Testcase}];

    if(data.$.typeBy.import == true || data.$.type != "scene") return [];

    //如果有父场景(包括父父节点)，当前节点禁止设置所属模块，只能最前面的场景节点设置
    var parents = this.wraper.findParentNodes(data);
    for(var i=0;i<parents.length;i++)
    {
        var item = parents[i];
        if(item.$.type == "scene") return [];
    }

    return [{action:"configModule", label:jsLng.setModule}];
};

ztmindmap.SceneManager.prototype.addTestcase = function($node,data)
{
    this.wraper.changeNodeType($node,data,"testcase");
    this.wraper.clearTypeAfter($node,data);
    //this.refreshDisplay();
}

ztmindmap.SceneManager.prototype.configModule = function($node,data){ this.proprMgr.show($node,data);};

ztmindmap.SceneManager.prototype.refreshDisplay = function(){ this.wraper.refreshDisplay();}

ztmindmap.SceneProperyManager = function(mgr)
{
    this.$modal = undefined;
    this.$node  = undefined;
    this.data   = undefined;
    this.mgr    = mgr;
};

ztmindmap.SceneProperyManager.prototype.init = function()
{
    var that = this;

    this.$modal = new zui.ModalBase('#moduleSelector', {show: false});
    this.$modal.$element.find("#sceneProperySave").on("click",function(){ that.save();});
    this.$modal.$element.find("#moduleSelectorCancel").on("click",function(){ that.$modal.hide();});
};

ztmindmap.SceneProperyManager.prototype.show = function($node,data)
{
    this.$node = $node;
    this.data  = data;

    if(data.$.type != "scene") return;

    var initScene = data.$.sceneID || 0;
    this.$modal.$element.find(".picker").zui('picker').$.setValue(initScene);
    this.$modal.show();
};

ztmindmap.SceneProperyManager.prototype.save = function()
{
    var moduleID = this.$modal.$element.find("[name='module']").val();

    if(moduleID == undefined || moduleID == 0)
    {
        alert(jsLng.pickModule);
        return;
    }

    var moduleName = this.$modal.$element.find(".picker-single-selection").html();

    this.data.$.moduleID = moduleID;
    this.data.tooltip = moduleName;
    this.mgr.refreshNodeDisplay(this.$node,this.data);

    this.mgr.wraper.autoSetSceneModuleAfter(this.$node,this.data);
    this.mgr.refreshDisplay();

    this.$modal.hide();
};


if(window.ztmindmap == undefined) window.ztmindmap = {};

ztmindmap.StepGroupManager = function(wraper){ this.wraper = wraper;};

ztmindmap.StepGroupManager.prototype.clearNodeDisplay = function($node, data){ $node.find(".suffix").hide();};

ztmindmap.StepGroupManager.prototype.refreshNodeDisplay = function($node, data)
{
    $node.find(".suffix").hide();
    $node.find(".suffix").show();
    $node.find(".suffix").find(".content").html(this.wraper.disKeys['stepGroup']);
};


if(window.ztmindmap == undefined) window.ztmindmap = {};

/**
 * 设为场景：节点后面的自动推断,  按照最多4级来判断：测试用例 -> 步骤分组 ->步骤 -> 期望结果
 *
 * 清除前面场景：不做其他推断
 * 清除后面场景：直接子节点设置为测试用例
 * 清除当前场景：仅在只有一个场景的情况下有这个菜单，同时删除所有用例
 *
 * 设为废弃：当前节点及其后续节点全部丢掉
 * 重新启用：当前节点及其后续节点全部启用
 *
 * 清除场景： 操作不够明确，无法判断用户意图，这里不提供
 */

ztmindmap.nodeTemplate =
    "<div  data-toggle='tooltip' data-placement='bottom' id='node-{id}' class='mindmap-node' data-type='{type}' data-id='{id}' data-parent='{parent}'>" +
    "   <div class='scene-indicator' style='display:none;'><i class='icon icon-flag'></i></div>" +
    "   <a class='pri-level' style='display:none;'></a>" +
    "   <div class='wrapper'>" +
    "       <div class='text'>{text}</div>" +
    "       <div class='caption'>{caption}</div>" +
    "       <div class='btn-toggle'></div>" +
    "   </div>" +
    "   <div class='suffix'><span>[</span><span class='content'>M:10000</span><span>]</span></div>" +
    "</div>";

ztmindmap.defaultTypeKeys = {
    module: "M",
    scene: "S",
    testcase: "C",
    stepGroup:"G",
    pri: "P"
}


ztmindmap.Wraper = function(params)
{
    this.data      = undefined;
    this.productID = params.product;
    this.branch    = params.branch;
    this.typeKeys  = $.extend({},ztmindmap.defaultTypeKeys,params.typeKeys);
    this.disKeys   = $.extend({},ztmindmap.defaultTypeKeys,params.typeKeys);

    for(var key in this.typeKeys) this.typeKeys[key] = this.typeKeys[key].toLowerCase();

    this.nodeMgrs = {
        scene: new ztmindmap.SceneManager(this),
        testcase: new ztmindmap.TestcaseManager(this),
        module: new ztmindmap.ModuleManager(this),
        stepGroup: new ztmindmap.StepGroupManager(this)
    }
};

ztmindmap.Wraper.prototype.initFromJSON = function(root)
{
    var rootNode = this.paserXmindNode(root,0);

    this.xmindJson2zendao(root,rootNode,1);

    this.data = rootNode;

    this.inheritImportProps();
}

ztmindmap.Wraper.prototype.initFromSheet = function(sheet)
{
    var title = ztmindmap.isObject(sheet.title) ? sheet.title.text : sheet.title;

    var root = {id:sheet.id, text:title,children:[],type:"tmpTop"};

    this.xmindXml2zendao(sheet.topic, root, 0);

    this.data = root.children[0];

    this.inheritImportProps();
};

ztmindmap.Wraper.prototype.render = function()
{
    var that = this;

    that.instance = createMindmap({
        hSpace:80,
        showToggleButton: true,
        nodeTeamplate: ztmindmap.nodeTemplate,
        data: that.data,
        enableDrag: false,
        subLineWidth: 2,
        afterNodeLoad: function(event)
        {
            var data = event.data;
            var $node = event.$node;

            that.refreshNodeDisplay($node,data);
            that.register($node,data);

            $node.on("contextmenu",function(e)
            {
                var contextMenuList = that.getContextMenuList(data);
                if(contextMenuList.length == 0) return;

                e.preventDefault();

                mindmap$.zui.ContextMenu.show(contextMenuList,{event:e,onClickItem:function(item)
                {
                    if(item.type == undefined)
                        that[item.action] && that[item.action]($node,data);
                    else
                        that.nodeMgrs[item.type][item.action] && that.nodeMgrs[item.type][item.action]($node, data);
                }});
            });
        }
    });

    for(var key in this.nodeMgrs)
    {
        this.nodeMgrs[key]["init"] && this.nodeMgrs[key]["init"]();
    }
};

ztmindmap.Wraper.prototype.refreshDisplay = function(){ this.instance.showNode();};

ztmindmap.Wraper.prototype.getContextMenuList = function(data)
{
    var contextMenuList = [];

    if(data.$.typeBy.import == false)
    {
        if(data.$.type != "scene")
        {
            var parents = this.findParentNodes(data);
            var canChangeToScene = true;
            for(var i=parents.length-1;i>=0;i--)
            {
                var item = parents[i];
                if(item.$.type == "testcase" || item.$.type == "stepGroup")
                {
                    canChangeToScene = false;
                    break;
                }
            }

            if(canChangeToScene == true) contextMenuList.push({ action: "setAsScene", label: jsLng.set2Scene});
        }

        if(data.$.type == "scene")
        {
            var removeBeforeScene = this.canRemoveSceneBefore(data);
            var removeAfterScene  = this.canRemoveSceneAfter(data);

            if(removeBeforeScene == true) contextMenuList.push({ action: "clearBeforeScene", label: jsLng.clearBefore });

            if(removeAfterScene == true) contextMenuList.push({ action: "clearAfterScene", label: jsLng.clearAfter });

            if(removeBeforeScene == false && removeAfterScene == false && data.$.typeBy.import == false) contextMenuList.push({ action: "clearCurrentScene", label: jsLng.clearCurrent });
        }
    }

    for(var key in this.nodeMgrs)
    {
        var mgr = this.nodeMgrs[key];
        var menuList = mgr.getContextMenuList != undefined ? mgr.getContextMenuList(data) : [];

        if(menuList.length > 0 && contextMenuList.length >0) contextMenuList.push({type:"divider"});

        for(var i=0; i<menuList.length; i++)
        {
            var menuItem = menuList[i];
            menuItem.type = key;
            contextMenuList.push(menuItem);
        }
    }

    return contextMenuList;
}

ztmindmap.Wraper.prototype.setAsScene = function($node, data)
{

    this.changeNodeType($node,data, "scene");
    this.autoReasoningAfter($node,data);

    //如果父节点也是场景
    let parent = this.findParentNode(data);
    if(parent.$.type == "scene" && parent.$.moduleID != undefined)
    {
        let $parent = mindmap$("#node-" + parent.id);
        this.copyScenePropertyAfter($parent,parent);
    }

    this.refreshDisplay();
}

ztmindmap.Wraper.prototype.copyScenePropertyAfter = function($node, data)
{
    var children = data.children || [];
    for(var childData of children)
    {
        if(childData.$.type != "scene") continue;
        if(childData.$.typeBy.import == true) continue;

        childData.$.moduleID = data.$.moduleID;
        var $childNode = mindmap$("#node-" + childData.id);
        this.refreshNodeDisplay($childNode,childData);

        this.copyScenePropertyAfter($childNode,childData);
    }
}

ztmindmap.Wraper.prototype.clearTypeAfter = function($node, data)
{
    var that = this;

    var children = data.children || [];
    for(var child of children)
    {
        var $child = mindmap$("#node-" + child.id);
        clearTypeImpl($child, child);
    }

    function clearTypeImpl($curNode, curData)
    {
        that.changeNodeType($curNode,curData,undefined);
        var curChildren = curData.children || [];
        for(var curChild of curChildren)
        {
            var $curChild = mindmap$("#node-" + curChild.id);
            clearTypeImpl($curChild, curChild);
        }
    }

    this.refreshDisplay();
}

ztmindmap.Wraper.prototype.clearBeforeScene = function($node, data)
{
    var beforeScenes = this.findParentNodes(data);
    for(var sceneData of beforeScenes)
    {
        if(sceneData.$.type != "scene" || sceneData.$.typeBy.import == true) continue;

        var $sceneNode = mindmap$("#node-" + sceneData.id);
        this.changeNodeType($sceneNode,sceneData,undefined);
    }

    this.refreshDisplay();
}

ztmindmap.Wraper.prototype.clearAfterScene = function($node,data)
{
    this.autoRemoveSceneAfer($node,data);

    var children = data.children || [];
    for(var child of children)
    {
        var $child = mindmap$("#node-" + child.id);
        this.changeNodeType($child,child,"testcase");
    }

    this.refreshDisplay();
}

ztmindmap.Wraper.prototype.clearCurrentScene = function($node, data)
{
    if(data.$.typeBy.import == true) return;

    this.changeNodeType($node,data,undefined);
    this.autoRemoveSceneAfer($node,data);

    var parentData = this.findParentData(data);
    if(parentData.$.type == "scene")
    {
        this.changeNodeType($node,data,"testcase");
    }

    this.refreshDisplay();
}

/**
 * 从给定节点开始(给定节点是场景节点)自动推动后续场景节点
 * @param {*} $node 给定的场景节点$dom 元素
 * @param {*} data  给定的场景节点数据
 */
ztmindmap.Wraper.prototype.autoReasoningAfter = function($node,data)
{
    var children = data.children || [];
    for(var i=0; i<children.length; i++)
    {
        var childData = children[i];
        var $childNode = mindmap$("#node-" + childData.id);

        if(childData.$.typeBy.import == true) continue;

        if(childData.$.type == "scene") continue;

        //获取给定子节点的子元素长度(包括节点本身)
        var length = ztmindmap.getLengthOfNode(childData);
        //测试用例 -> 测试步骤 -> 预期
        //测试用例 -> 测试步骤分组 -> 测试步骤 -> 预期
        if(length > 4)
        {
            //本身 + 子元素长度 大于 4 的肯定不是测试用例，这里自动推断为场景
            childData.$.moduleID = data.$.moduleID;
            childData.tooltip = data.tooltip;
            this.changeNodeType($childNode,childData,"scene");

            this.autoReasoningAfter($childNode,childData);
        }
        else
        {
            //本身 + 子元素长度 小于等于 4 的可能是场景，也可能是测试用例，这里默认推断为测试用例
            //这里需要用户通过界面手动指定
            this.changeNodeType($childNode,childData,"testcase");
        }
    }
}

/**
 * 移除给点节点下面的所有场景 和 测试用例
 * @param {Object} $node 给定节点$Dom
 * @param {Object} data  给定节点数据
 */
ztmindmap.Wraper.prototype.autoRemoveSceneAfer = function($node,data)
{
    var children = data.children || [];
    for(var i=0; i<children.length; i++)
    {
        var childData  = children[i];
        var $childNode = mindmap$("#node-" + childData.id);

        if(childData.$.typeBy.import == true) continue;

        //所有后续节点都先设置成 undefined
        this.changeNodeType($childNode,childData,undefined);

        this.autoRemoveSceneAfer($childNode,childData);
    }
}

/**
 * 自动设置后续所有的场景模块为指定节点的模块
 * @param {Object} $node 指定节点
 * @param {Object} data 指定节点的数据
 */
ztmindmap.Wraper.prototype.autoSetSceneModuleAfter = function($node, data)
{
    var children = data.children || [];
    for(var child of children)
    {
        if(child.$.type == "scene")
        {
            child.$.moduleID = data.$.moduleID;
            child.tooltip = data.tooltip;

            $child = mindmap$("#node-" + child.id);
            this.refreshNodeDisplay($child,child);

            this.autoSetSceneModuleAfter($child,child)
        }
    }

    this.refreshDisplay();
}

/**
 * 判断是否能删除给定场景节点前面的场景
 * 必须满足：
 * @param {Object} data
 */
ztmindmap.Wraper.prototype.canRemoveSceneBefore = function(data)
{
    var scenes = this.findParentNodes(data);
    for(var scene of scenes)
    {
        if(scene.$.type == "scene") return scene.$.typeBy.import == false;
    }

    return false;
}

ztmindmap.Wraper.prototype.canRemoveSceneAfter = function(data)
{
    var children = data.children || [];
    for(var child of children)
    {
        if(child.$.type == "scene")
        {
            return child.$.typeBy.import == false;
        }
        else
        {
            if(this.canRemoveSceneAfter(child) == true) return true;
        }
    }

    return false;
}

/**
 * 获取所有的叶子节点数据
 */
ztmindmap.Wraper.prototype.getLeafDatas = function()
{
    var leafs = [];

    function findLeafs(data)
    {
        if(data.children == undefined || data.children.length == 0)
            leafs.push(data);
        else
        {
            var children = data.children || [];
            for(var child of children)
            {
                findLeafs(child);
            }
        }
    }

    findLeafs(this.data);

    return leafs;
}

ztmindmap.Wraper.prototype.findParentData = function(purData)
{
    var parentList = this.findParentDatas(purData);

    return parentList.length >=2 ? parentList[parentList.length-2] : undefined;
}

/**
 * 查找给数据的父节点，父父数据的集合(包括当前叶子节点)
 * @param {Object} purData
 */
ztmindmap.Wraper.prototype.findParentDatas = function(purData)
{
    var results = [];

    function findParentDataPath(data)
    {
        if(data == purData) return true;

        var children = data.children || [];
        for(var child of children)
        {
            if(findParentDataPath(child) == true)
            {
                results.unshift(child);
                return true;
            }
        }

        return false;
    }

    var flag = findParentDataPath(this.data);

    if(flag == true) results.unshift(this.data);

    return results;
}

/**
 * 查找给数据的父节点，父父节点的集合
 * 倒序，使用的时候注意坑
 * @param {Object} data
 */
ztmindmap.Wraper.prototype.findParentNodes = function(data)
{

    var results = [];

    function findParentPath(iData)
    {
        if(iData.id == data.parent)
        {
            results.push(iData);
            return true;
        }

        var children = iData.children || [];
        for(var child of children)
        {
            if(findParentPath(child) == true)
            {
                results.push(child);
                return true;
            }
        }

        return false;
    }

    findParentPath(this.instance.data);

    return results;
}

/**
 * 查找给定数据的父节点
 * @param {Object} data 给定数据
 */
ztmindmap.Wraper.prototype.findParentNode = function(data)
{
    function findParentImpl(iData)
    {
        if(iData.id == data.parent) return iData;

        var children = iData.children || [];
        for(var child of children)
        {
            var result = findParentImpl(child);
            if(result != undefined) return result;
        }

        return undefined;
    }

    return findParentImpl(this.instance.data);
}

ztmindmap.Wraper.prototype.refreshNodeDisplay = function($node,data)
{
    var mgr = this.nodeMgrs[data.$.type];
    mgr && mgr.refreshNodeDisplay && mgr.refreshNodeDisplay($node,data);
}

ztmindmap.Wraper.prototype.register = function($node, data)
{
    var mgr = this.nodeMgrs[data.$.type];
    mgr && mgr.register && mgr.register($node,data);
}

ztmindmap.Wraper.prototype.changeNodeType = function($node,data,newType)
{
    if(data.$.type == newType) return;

    var oldMgr = this.nodeMgrs[data.$.type];
    var newMgr = this.nodeMgrs[newType];

    data.$.type = newType;

    if(oldMgr != undefined)
    {
        oldMgr["clearNodeDisplay"] && oldMgr["clearNodeDisplay"]($node,data);
        oldMgr["unRegister"] && oldMgr["unRegister"]($node,data);
    }

    if(newMgr != undefined)
    {
        newMgr["refreshNodeDisplay"] && newMgr["refreshNodeDisplay"]($node,data);
        newMgr["register"] && newMgr["register"]($node,data);
    }
};

ztmindmap.Wraper.prototype.xmindXml2zendao = function(topic, parent,level)
{
    var obj = this.paserXmindNode(topic,level);
    parent.children.push(obj);

    var joinChildren = [];

    if(topic.children != undefined)
    {
        var children = topic.children;
        if(ztmindmap.isObject(children)) children = [children];

        for(child of children)
        {
            var topicsList = child.topics;
            if(ztmindmap.isObject(topicsList)) topicsList = [topicsList];

            for(var topics of topicsList)
            {
                var topic = topics.topic;
                if(ztmindmap.isObject(topic)) topic = [topic];
                for(var t of topic)
                {
                    joinChildren.push(t);
                }
            }
        }
    }

    for(var i=0; i<joinChildren.length; i++)
        this.xmindXml2zendao(joinChildren[i],obj,level+1);
};

ztmindmap.Wraper.prototype.xmindJson2zendao = function(parentJson, parentNode,level)
{
    if(parentJson.children == undefined) return;
    if(parentJson.children.attached == undefined) return;

    var children = parentJson.children;

    var attached = children.attached;
    if(ztmindmap.isArray(attached) == false)
        attached = [attached];

    for(var att of attached)
    {
        var obj = this.paserXmindNode(att,level);
        parentNode.children.push(obj);
        this.xmindJson2zendao(att,obj,level+1);
    }
};

/**
 * 导出的数据再次导入时，自动继承设置属性
 * 1）模块后面的所有场景继承模块的Id
 * 2）场景后面的所有子场景继承场景的模块
 * 3）场景之前的所有节点，前置设置为场景，并不容许删除(import=true, create=auto)
 */
ztmindmap.Wraper.prototype.inheritImportProps = function()
{
    if(this.data.$.typeBy.import == false || this.data.$.productID == undefined) return;

    var leafs = this.getLeafDatas();
    for(var leaf of leafs)
    {
        var strandList = this.findParentDatas(leaf);
        //两个场景之间的其他节点统一设置为场景节点
        checkOtherBetweenScene(strandList);
        //两个 import 之间的场景强制设置为 import
        checkImportBetweenImportScene(strandList);

        //module 或者 scene 节点后的所有场景节点模块都设置成 module or scene 节点对应的ID
        checkSceneAfterModuleNode(strandList);

        //最后一个场景的下一个节点默认识别为测试用例
        checkLastSceneAfter(strandList);
    }

    function checkLastSceneAfter(dataList)
    {
        var lastIndex = -1;
        for(var i=dataList.length-1;i>=0;i--)
        {
            if(dataList[i].$.type == "scene")
            {
                lastIndex = i;
                break;
            }
        }

        if(lastIndex < 0 || lastIndex == dataList.length-1) return;

        var nextData = dataList[lastIndex+1];
        nextData.$.type = "testcase";
    }

    function checkOtherBetweenScene(dataList)
    {
        var firstSceneIndex = -1;
        var lastSceneIndex = -1;

        for(var i=0;i<dataList.length;i++)
        {
            if(dataList[i].$.type == "scene")
            {
                firstSceneIndex = i;
                break;
            }
        }

        for(var i=dataList.length-1;i>=0;i--)
        {
            if(dataList[i].$.type == "scene")
            {
                lastSceneIndex = i;
                break;
            }
        }

        if(firstSceneIndex == lastSceneIndex)
            return;

        for(var i=firstSceneIndex;i<=lastSceneIndex;i++)
        {
            dataList[i].$.type = "scene";
        }
    }

    function checkSceneAfterModuleNode(dataList)
    {
        var referIndex = -1;
        for(var i=0;i<dataList.length;i++)
        {
            if(dataList[i].$.type == "module" || dataList[i].$.type == "scene")
            {
                referIndex = i;
                break;
            }
        }

        if(referIndex < 0) return;

        var moduleID = dataList[referIndex].$.moduleID;
        for(var i=referIndex + 1; i<dataList.length;i++)
        {
            if(dataList[i].$.type == "scene") dataList[i].$.moduleID = moduleID;
        }
    }

    function checkImportBetweenImportScene(dataList)
    {
        var firstSceneIndex = -1;
        var lastSceneIndex = -1;

        for(var i=0;i<dataList.length;i++)
        {
            if(dataList[i].$.type == "scene" && dataList[i].$.typeBy.import == true)
            {
                firstSceneIndex = i;
                break;
            }
        }

        for(var i=dataList.length-1;i>=0;i--)
        {
            if(dataList[i].$.type == "scene" && dataList[i].$.typeBy.import == true)
            {
                lastSceneIndex = i;
                break;
            }
        }

        if(firstSceneIndex == lastSceneIndex) return;

        for(var i=firstSceneIndex;i<=lastSceneIndex;i++)
        {
            dataList[i].$.typeBy.import = true;
            dataList[i].$.typeBy.create = "auto";
        }
    }
}

ztmindmap.Wraper.prototype.paserXmindNode = function(nodeData, level)
{
    var titleInfo = ztmindmap.splitText(getNodeText(nodeData.title));

    var obj = { id:nodeData.id,
                text: titleInfo.text,
                type: level == 0 ? "root": (level == 1 ? "sub" : "node"),
                children:[],
                subSide:"right",
                $:{
                    type: undefined,
                    typeBy: { import:false, create: "auto" }
                }};

    var props = ztmindmap.kv2Obj(titleInfo.suffix);
    if(obj.type == "root" && titleInfo.suffix != undefined)
        this['setPropsInProduct'] && this['setPropsInProduct'](obj, titleInfo.suffix);
    else if(props[this.typeKeys.module] != undefined)
        this['setPropsInModule'] && this['setPropsInModule'](obj, props);
    else if(props[this.typeKeys.scene] != undefined)
        this['setPropsInScene'] && this['setPropsInScene'](obj, props);
    else if(props[this.typeKeys.testcase] != undefined)
        this['setPropsInTestcase'] && this['setPropsInTestcase'](obj, props);
    else if(props[this.typeKeys.stepGroup] != undefined)
    {
        obj.$.type = "stepGroup";
        obj.$.typeBy.import = true;
    }

    return obj;

    function getNodeText(v)
    {
        return ztmindmap.isObject(v) ? v.text : v;
    }
};

ztmindmap.Wraper.prototype.setPropsInProduct = function(obj, suffix)
{
    if(ztmindmap.isID(suffix) == false) return;

    obj.readonly = true;
    obj.$.productID = suffix * 1;
    obj.$.type = "product";
    obj.$.typeBy.import = true;
    obj.$.typeBy.create = "auto";
}

ztmindmap.Wraper.prototype.setPropsInModule = function(obj, props)
{
    var moduleID = props[this.typeKeys.module];

    obj.$.type = "module";
    obj.$.typeBy.create = "auto";

    if(ztmindmap.isID(moduleID) == true)
    {
        obj.readonly = true;
        obj.$.moduleID = moduleID;
        obj.$.typeBy.import = true;
    }
}

ztmindmap.Wraper.prototype.setPropsInScene = function(obj, props)
{
    var sceneID = props[this.typeKeys.scene];

    obj.$.type = "scene";
    obj.$.typeBy.create = "auto";

    if(ztmindmap.isID(sceneID))
    {
        obj.$.sceneID = sceneID;
        obj.$.typeBy.import = true;
    }
}

ztmindmap.Wraper.prototype.setPropsInTestcase = function(obj, props)
{

    var testcaseID = props[this.typeKeys.testcase];

    obj.$.type = "testcase";
    obj.$.pri = props[this.typeKeys['pri']] || 3;
    obj.$.typeBy.create = "auto";

    if(ztmindmap.isID(testcaseID))
    {
        obj.$.testcaseID = testcaseID;
        obj.$.typeBy.import = true;
    }
}

ztmindmap.Wraper.prototype.toJson = function()
{
    var that = this;

    var sceneList = [];
    var testcaseList = [];

    var rootData = this.instance.data;

    function sceneToJson(data)
    {
        if(data.$.type == "scene") sceneList.push(that.singleSceneToJson(data));
        if(data.$.type == "testcase") return;

        var children = data.children || [];
        for(var child of children) sceneToJson(child);
    }

    function testcaseToJson(data)
    {
        if(data.$.type == "testcase")
            testcaseList.push(that.singleTestCaseToJson(data));
        else {
            var children = data.children || [];
            for(var child of children)
                testcaseToJson(child);
        }
    }

    sceneToJson(rootData);
    testcaseToJson(rootData);

    return {sceneList:sceneList, testcaseList:testcaseList};
};

ztmindmap.Wraper.prototype.singleSceneToJson = function(data)
{
    var obj = {
        name: data.text,
        module: ztmindmap.toNum(data.$.moduleID,undefined),
        product: this.productID * 1,
        branch: this.branch * 1,
        tmpId: data.id,
        tmpPId: data.parent,
    }

    if(data.$.sceneID != undefined && data.$.sceneID != "") obj.id = data.$.sceneID * 1;

    return obj;
};

ztmindmap.Wraper.prototype.singleTestCaseToJson = function(data)
{
    var parentNode = this.findParentNode(data);

    //获取测试用例数据
    var obj = {
        pri: ztmindmap.toNum(data.$.pri,3),
        name: data.text,
        //module: ztmindmap.toNum(scene.$.moduleID,0),
        product: this.productID * 1,
        branch: this.branch * 1,
        tmpId: data.id,
        tmpPId: data.parent,
    }

    if(parentNode.$.type == "scene")
        obj.module = ztmindmap.toNum(parentNode.$.moduleID,0);
    else if(parentNode.$.type == "module")
        obj.module = ztmindmap.toNum(parentNode.$.moduleID,0);
    else
        obj.module = 0;

    //设置测试用例的id
    if(data.$.testcaseID != undefined && data.$.testcaseID != "")
        obj.id = data.$.testcaseID * 1;

    //获取测试用例的步骤
    obj.stepList = [];
    var children = data.children || [];
    for(var child of children)
    {
        var step = {
            type: "step",
            desc: child.text,
            tmpId: child.id,
            tmpPId: child.parent,
        }

        obj.stepList.push(step);

        var maxLng = ztmindmap.getLengthOfNode(child);
        var nextChilds = child.children || [];

        //child的子元素：
        //1) 有且仅只有一个，这个子元素作为期望结果来处理
        if(maxLng == 2 && child.$.type != "stepGroup")
        {
            step.expect = nextChilds[0].text;
            step.type = "step";
            continue;
        }

        //2) 有多个子元素， 当前元素是步骤分组， 这些子元素作为子步骤
        if(maxLng > 2 || child.$.type == "stepGroup")
        {
            step.type = "group";
            for(var nextChild of nextChilds)
            {
                var subStep = {
                    type: "item",
                    desc: nextChild.text,
                    tmpId: nextChild.id,
                    tmpPId: nextChild.parent
                };

                //如果后续只有一个子元素，作为期望的结果来处理，超过一个子元素，丢掉后面的子元素
                var nextNextChild = nextChild.children || [];
                if(nextNextChild.length == 1)
                    subStep.expect = nextNextChild[0].text;

                obj.stepList.push(subStep);
            }
        }
    }

    return obj;
};


if(window.ztmindmap == undefined) window.ztmindmap = {};

ztmindmap.TestcaseManager = function(wraper)
{
    this.wraper = wraper;
    this.priSelector = undefined;
}

ztmindmap.TestcaseManager.prototype.init = function()
{
    this.priSelector = new ztmindmap.PriSelector(this);
}

ztmindmap.TestcaseManager.prototype.register = function($node, data)
{
    let that = this;

    $node.find(".pri-level").on("click",function(e)
    {
        that.priSelector.show($node,data);
    });
};

ztmindmap.TestcaseManager.prototype.unRegister = function($node, data)
{
    $node.find(".pri-level").unbind();
};

ztmindmap.TestcaseManager.prototype.getContextMenuList = function(data)
{
    var parent = this.wraper.findParentNode(data);
    if(parent == undefined || parent.$.type != "testcase")
        return [];

    if(data.$.type == "stepGroup")
        return [{action:"removeStepGroup", label:jsLng.removeGroup}];
    else
        return [{action:"addStepGroup", label:jsLng.set2Group}];
};

ztmindmap.TestcaseManager.prototype.removeStepGroup = function($node,data)
{
    this.wraper.changeNodeType($node,data,undefined);
    this.refreshDisplay();
}

ztmindmap.TestcaseManager.prototype.addStepGroup = function($node,data)
{
    this.wraper.changeNodeType($node,data,"stepGroup");
    this.refreshDisplay();
}

ztmindmap.TestcaseManager.prototype.clearNodeDisplay = function($node, data)
{
    $node.find(".suffix").hide();
    $node.find(".pri-level").hide();
};

ztmindmap.TestcaseManager.prototype.refreshNodeDisplay = function($node, data)
{
    $node.find(".pri-level").show();

    $node.find(".pri-level").removeClass("pri-1");
    $node.find(".pri-level").removeClass("pri-2");
    $node.find(".pri-level").removeClass("pri-3");
    $node.find(".pri-level").removeClass("pri-4");

    if(data.$.pri == undefined)
    {
        $node.find(".pri-level").addClass("pri-empty");
        $node.find(".pri-level").html("");
    }
    else
    {
        $node.find(".pri-level").removeClass("pri-empty");
        $node.find(".pri-level").html(data.$.pri);
        $node.find(".pri-level").addClass("pri-" + data.$.pri);
    }

    if(data.$.testcaseID != undefined)
    {
        $node.find(".suffix").show();
        $node.find(".suffix").find(".content").html(this.wraper.disKeys['testcase'] + ":" + data.$.testcaseID);
    }
};

ztmindmap.TestcaseManager.prototype.refreshDisplay = function()
{
    this.wraper.refreshDisplay();
}

ztmindmap.PriSelector = function(mgr)
{
    this.$gradeDom = undefined;
    this.$node     = undefined;
    this.data      = undefined;
    this.mgr       = mgr;

    this.init();
};

ztmindmap.PriSelector.prototype.init = function()
{
    var that = this;

    var htmlStr = "";
    htmlStr += "<div class='testcase-pri-root effect'>";
    htmlStr += "    <a pri='1' class='pri-1' href='javascript:void(0);'>1</a>";
    htmlStr += "    <a pri='2' class='pri-2' href='javascript:void(0);'>2</a>";
    htmlStr += "    <a pri='3' class='pri-3' href='javascript:void(0);'>3</a>";
    htmlStr += "    <a pri='4' class='pri-4' href='javascript:void(0);'>4</a>";
    htmlStr += "</div>";

    var $priDom = mindmap$(htmlStr);

    mindmap$(".mindmap-container").append($priDom);

    $priDom.find("a").on("click",function(e)
    {
        var pri = mindmap$(e.currentTarget).attr("pri");
        that.data.$.pri = pri;
        that.mgr.refreshNodeDisplay(that.$node,that.data);
        that.mgr.refreshDisplay();
    });

    mindmap$(document).on("click",function(e)
    {
        that.hide();
    })

    $priDom.hide();
    that.$priDom = $priDom;
};

ztmindmap.PriSelector.prototype.show = function($node, data)
{
    this.$node     = $node;
    this.data      = data;

    var nodeLeft   = $node.position().left - 15;
    var nodeTop    = $node.position().top;
    var nodeHeight = $node.height();

    this.$priDom.css({left:nodeLeft, top:nodeTop + nodeHeight + 10});

    this.$priDom.show();
};

ztmindmap.PriSelector.prototype.hide = function($node, data)
{
    this.$node = undefined;
    this.data = undefined;

    this.$priDom && this.$priDom.hide();
};


if(window.ztmindmap == undefined) window.ztmindmap = {};

ztmindmap.isArray = function(obj)
{
    return Object.prototype.toString.call(obj) === '[object Array]';
}

ztmindmap.isObject = function(obj)
{
    return Object.prototype.toString.call(obj) === '[object Object]';
};

ztmindmap.getLengthOfNode = function(data)
{
    var maxLength = 0;

    var children = data.children || [];
    for(var i=0; i<children.length; i++)
    {
        var tmpLength = ztmindmap.getLengthOfNode(children[0]);
        maxLength = Math.max(maxLength, tmpLength);
    }

    return maxLength + 1;
}

ztmindmap.toNum = function(v, defaultValue = undefined)
{
    if(v == undefined || v == "")
        return defaultValue ? defaultValue * 1 : defaultValue;

    return v * 1;
}

ztmindmap.trim = function(str)
{
    if(str == undefined) return undefined;

    return str.replace(/(^\s*)|(\s*$)/g, "");
}

ztmindmap.splitText = function(str)
{
    str = ztmindmap.trim(str);

    if(str == undefined || str == "") return {text:undefined, suffix:undefined};

    var last = str.substr(str.length-1,1);

    if(last != "]" && last != "】") return {text:str, suffix:undefined};

    var sIndex1 = str.lastIndexOf("[");
    var sIndex2 = str.lastIndexOf("【");
    var sIndex = Math.max(sIndex1,sIndex2);

    if(sIndex < 0) return {text:str, suffix:undefined};

    var text = str.substring(0, sIndex);
    var suffix = str.substring(sIndex+1, str.length-1);

    return {text:ztmindmap.trim(text), suffix:ztmindmap.trim(suffix)};
}

ztmindmap.kv2Obj = function(suffix)
{
    if(suffix == undefined) return {};

    suffix = suffix.replace("，",",");
    suffix = suffix.replace("：",":");
    suffix = suffix.toLowerCase();

    var obj = {};

    var kvList = suffix.split(",");
    for(var kvStr of kvList)
    {
        var tmpKvStr = ztmindmap.trim(kvStr);
        if(tmpKvStr == undefined || tmpKvStr == "") continue;

        var kvSplit = tmpKvStr.split(":");

        var key = undefined;
        var value = undefined;

        if(kvSplit.length == 1)
        {
            key = ztmindmap.trim(kvSplit[0]);
            value = key;
        }
        else
        {
            key = ztmindmap.trim(kvSplit[0]);
            value = ztmindmap.trim(kvSplit[1]);
        }

        obj[key] = value;
    }

    return obj;
}

ztmindmap.isID = function(str)
{
    if(str == undefined || str == "") return false;

    return /(^[1-9]\d*$)/.test(str);
}
