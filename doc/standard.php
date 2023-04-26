<?php
#前言：
#  本规范的目的是让保证team成员编码的统一。
#  本规范的核心规则就是驼峰方式的命名规则。
#  此规范必要时可以打破。

## 1. 统一使用PHP8.1进行开发，强类型声明
# 为了让PHP强制使用严格类型，需要在每个编写的文件头部填写 declare(strict_types=1);
declare(strict_types=1);

# 2. 文件注释
# 版权信息修改： example 为当前模块，author修改成自己的账号，package写当前模块名；
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      XX <xx@easycorp.ltd>
 * @package     example
 * @link        http://www.zentao.net
 */

## 3. 模块目录约定 最新的目录结构
#task/
#    config/
#        form.php     #表单的配置项,用于form::use()->create();
#        table.php    #列表页面表格的配置
#    css/
#        create.ui.css
#    js/
#        create.ui.js
#    lang/
#        zh-cn.php
#    test/             #单元测试用例
#        control/
#            create.php
#        model/
#            create.php
#        task.class.php
#    ui/
#        create.html.php   #新版view页面
#    view/
#        reate.html.php   #旧版view页面，全部改版完成后删除
#    config.php
#    control.php
#    model.php
#    tao.php
#    zen.php

## 4. 使用TDD方式进行开发
#  先编写单元测试用例，然后编写功能代码
## 5. 区分public和protected方法
#  外部模块可访问的方法使用public，只有自己模块调用的方法使用protected修饰
## 6. 代码分为ui/control/zen/model/tao，共5个层次；
#   1）ui: 新版Zin代码
#   2）control/zen:
#   public方法放在control，protected放在zen；
#   control层只负责获取web请求的数据和变量，比如从$\_GET、 $\_POST、 $\_COOKIE、 $\_SESSION获取数据，其他层(zen、model、tao)禁止获取这些全局变量，只能传参使用；
#   zen层对这些数据进行加工，业务逻辑处理，调用model、tao方法（zen、tao里的方法均为protected）
#   注意：页面跳转，js输出等需要放到control层
#   3）model/tao: public方法放在model，protected放在tao；
#   model里为对外提供的方法，为public。tao的操作必须为单一的，一次查询、插入、一个表。
# 6. POST表单的数据处理统一使用form类
## 1)在module/xxx/config/form中定义

$config->example->form = new stdclass;
$config->example->form->create = array();
$config->example->form->create['name']                = array('type' => 'string', 'r e q u i r ed' => true, 'filter' => 'trim');
$config->example->form->create['PO']                    = array('type' => 'account', 'r e q u i r ed' => false, 'default' => '');
$config->example->form->create['createdDate']     = array('type' => 'date', 'r e q u i r ed' => false, 'default' => helper::now());
$config->example->form->create['createdVersion'] = array('type' => 'string', 'r e q u i r ed' => false, 'default' => $this->config->version);

## 2)调用form进行处理
$data = form::use($this->config->example->create)->create();

# -  注意代码编写时位置关系：select、insert
# 7. Control层作为入口，处理请求参数
public function edit($projectID)
{
    if(!empty($_POST))
    {
        $data = form::use($this->config->example->create)->get();
      $data = $this->projectZen->prepareEditExtras($data);
        if(!$data) return $this->projectZen->errorBeforeEdit();

        $result = $this->project->edit($projectID, $data);
        if(!$result) return $this->projectZen->errorAfterEdit();
        return $this->projectZen->responseAfterEdit($result);
    }

    // ...

    $this->zen->buildEditForm($projectID);
}

// model

public function edit(int $projectID, form $data)
{
    if(!$this->projectTao->beforeEdit()) return false;
    if(!$this->projectTao->doEdit()) return false;
    if(!$this->projectTao->afterEdit()) return false;

    return true;
}

# 在调用zen的过程中，如果业务比较复杂，推荐使用以下形式
$this->projectZen->beforeEdit();
$this->projectZen->edit();
$this->projectZen->afterEdit();

$this->projectZen->processEditResult();

## 8. zen层的方法，再次拆分后使用private修饰

protected function create()
{
    $this->story->createBranch();
}
private function createBranch()
{

}

## 9. 缩写在语言项都放在一起
#
#之前的缩写是使用下面这个形式：
$lang->bug->storyAB   = '需求';
$lang->bug->projectAB = '项目';
#现在改为：
$lang->bug->abbr  = new stdclass();
$lang->bug->abbr->story   = '需求';
$lang->bug->abbr->project = '项目';

## 10. 方法要细分，每个函数的代码行数不超过50行
#
## 11. MySQL的sql_mode使用strict模式
##    1) 对于GROUP BY聚合操作,如果在SELECT中的列,没有在GROUP BY中出现,那么这个SQL是不合法的,因为列不在GROUP BY从句中
##    2) 不允许日期和月份为零，必须使用NULL
##    3) TEXT类型不能有默认值，建议设为NULL
##    4) 不能用双引号来引用字符串,必须使用单引号

## 12. 错误和提醒的语言项，统一在一起编写
$lang->example->notice = new stdclass();
$lang->example->notice->nameRequired = '请填写名称';

$lang->example->error = new stdclass();
$lang->example->error->nameEmpty = '名称为空';

## 13. 代码注释使用中英文两种语言
# 每个模块重构的方法注释使用中英文两种语言，方法内部的注释如果使用英文表述不够贴切也需要使用中文

## 14. Model层方法命名 所有的方法必须在文件中有序的组织起来，相似功能的函数放在一起，比如
class taskModel
{
    # Get 相关
    public function getBuildsByProject() { }
    public function getReleasesByProjects() { }

    # 创建相关
    public function create() { }
    public function batchCreate() { }

    # 其他相关
    public function start() { }
    public function finish() { }
}

# 1) 获取一个对象 getXXXBy***，比如:
getBuildsByProject($projectID);
# 如果获取本模块的数据，可以缩写为getByYY，比如
getByID($projectID)

# 2) 返回一个对象数组
$this->project->getList();
$this->project->getTeamMembers($projectID);
$this->project->getListByProductAndProgram($productID, $programID);

# 3) 返回一个key/value数组，比如
getProductPairs();
#  本模块缩写为
getPairs();

#  4) 批量操作 batch***
batchCreate($projectID, $formData);

# 5) 更新操作 update***
update($projectID, $formData);

# 6) 其他操作
start($projectID);
finish($projectID);

# 15. Git 配置
# 为了从git log中获取作者的名字和邮箱，以及方便执行代码扫描和自动化操作。
# 我们应该使用姓名全拼和公司邮箱，姓名全拼不应该使用空格等特殊字符。
<<<EOT
# 查询用户名和邮箱：
git config --global --get user.name
git config --global --get user.email

# 设置用户名和邮箱：
git config --global user.name "xxx"
git config --global user.email "xxx@easycorp.ltd"
EOT;

## 使用低版本语法封装高版本内建函数

- `str_contains`
- `str-starts_with`
- `str-ends_with`

- ...
## 关键字顺序

- `public/protected/private` 关键字位于 `static` 关键字之前


## 函数规模

- 函数长度不超过 50 行

- zen 和 tao 中的函数长度不超过 30 行

## 完善注释

使用注释表达更多信息，如使用注释 `@return int[]` 解释返回值的具体类型。

## 使用更精确的类型

类型应该精确，如 `array|false` 好于 `array|bool`；`int|string` 好于 `mixed`。

## 消除重复

重复的代码进行小规模抽象。

## 同一函数别名
|    函数               |    禁用      |
|    is_int             |is_integer,is_long|
|    is_float           |is_double,is_real |
|    count              |sizeof|
|    is_bool            |is_boolean|
|    array_key_exists   |key_exists|

## 字符串表达方式优先级
# 1. 单引号  （优先使用）
# 2. 双引号  （有变量、字符串里面包含单引号）
# 3. heredoc / newdoc（有换行时）

## 移除对内建函数无意义的封装

class baseHelper
{
    public static function jsonEncode($data)
    {
       return json_encode($data);
    }
}


## 提前 return 减少嵌套

class baseHelper
{
    public static function encryptPassword(string $password): string
    {
        if(empty($password)) return '';

        global $config;
        if(empty($config->encryptPassword)) return $password;

        $secret = $config->encryptSecret;
        $iv = str_repeat("\0", 8);
        if(function_exists('openssl_encrypt'))
        {
            ...
            return openssl_encrypt($password, 'DES-CBC', substr($secret, 0, 8), OPENSSL_ZERO_PADDING, $iv);
        }
        if(function_exists('mcrypt_encrypt')) return base64_encode(mcrypt_encrypt(MCRYPT_DES, substr($secret, 0, 8), $password, MCRYPT_MODE_CBC, $iv));

        return '';
    }
}

## 合理使用空行
function isLocalIP()
{
    global $config;
    if(isset($config->islocalIP)) return $config->isLocalIP;

    $serverIP = $_SERVER['SERVER_ADDR'];
    if($serverIP == '127.0.0.1' or $serverIP == '::1') return true;
    if(str_contains((string) $serverIP, '10.70')) return false;

    return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

一、PHP命名规则
1.1 变量命名
	采用驼峰方式。首字母小写，后面的字母按照大小写间隔的方式加以区分，比如userName, serviceID
	如果有单词缩写，则采用大写形式。如：PID 。
	应该避免大写的单词在一起，因为无法直接判断单词的分割，比如IMGFile，应该写成imgFile。
	类名，类的属性命名规则与此相同。
	数据库、表、字段的命名规则与此相同。
	SQL 查询语句中关键词使用大写。 比如：SELECT * FROM userList WHERE
1.2 常量命名
	采用大写单词与下划线间隔的方式，比如IMATCH_DISPATCHER_API。
1.3 函数命名
	采用驼峰方式，动词加名词，动词小写，后面的名词用大小写间隔。比如： getAdInfo()
	如果需要，可以增加小写的前缀，这时动词则大写开始。比如： imGetAdInfo()
	类的方法命名规则与此相同，不过类的方法一般不需要增加前缀了。
1.4 目录文件命名
	目录和文件一般采用小写的格式，尽量使用两个以内的单词表达。
	不建议使用下划线间隔的方式。但如果目录或者文件名过长，无法使用少量单词表达时，应当使用下划线。
	不建议使用大写字母，但如果要表达的名称是大家约定俗称的，应尊重旧有的习惯。
二、PHP脚本文件的构成及注释
	每个文件按照以下顺序排列：功能说明部分、包含声明部分、具体的业务逻辑、自定义函数部分。
	注释按照phpdoc的标准进行，这样可以和c++程序统一起来。 http://manual.phpdoc.org/HTMLSmartyConverter/HandS/phpDocumentor/tutorial_phpDocumentor.pkg.html
2.1功能说明部分
在每一个文件的开头部分，要包含这个程序的简要说明、详细说明以及作者和最后修改时间。注释采用phpdoc的注释方式。

<?
/**
 * 监控程序（简单注释）
 *
 * 此脚本程序用来监控搜索所建索引的完整性、一致性、正确性。（详细说明，可选。）。
 * @author      wangcs <wwccss@263.net>
 * @version     $Id$
 */
?>

说明：
	详细说明部分是可选的，如果某个文件的逻辑比较复杂，可以在详细说明部分加以解释。
	其中的$Id$会被自动替换成subverion的相应信息，其中包含文件名、日期、修改者等信息。
2.2包含声明部分：
在每个文档的开头部分包含此程序所用到的包含文件。如：

include 'init.php';
include 'func/common.php';
2.3具体的业务逻辑
	注释的原则是将问题解释清楚，并不是越多越好。
	若干语句作为一个逻辑代码块，这个块的注释可以使用/* */方式。
	具体到某一个语句的注释，可以使用行尾注释：//。

/* 生成配置文件、数据文件。*/
$this->setConfig();
$this->clearCache();       // 清除缓存文件。
$this->createDataFiles(); // 生成数据文件。
2.4自定义函数部分：
	如果当前PHP脚本需要定义一个函数，则在文件尾部声明。
	凡有两个以上文件用到的函数，应将其定义在一个公共的函数库文件中。比如function.php中。
	自定义函数需包含以下几个部分：函数功能描述、函数参数说明、返回值说明。示例：

<?
/**
  * 数据库连接函数（简单说明）
  *
  * 通过这个函数链接到数据库，并返回相信的链接标识符。(详细的说明)
  * @author 					  wangcs<wwccss@263.net>
  * @global string             数据库服务器(全局变量声明,无需指定变量名，顺序对应)
  * @param string $SQL        连接成功以后执行的查询语句，默认为空。（变量声明）
  * @return array              返回数据库连接信息。（返回值说明）
  */
  function dbConnect ($SQL="")
  {
       global $mysql;
  	   xxxx;
       xxxx;
  }
?>
	如果函数功能比较简单，也可以采用一句话注释的方式，来说明此函数的作用，省略参数的说明。
<?php
/* 清除缓存文件（一句话说明，省略了参数的说明）*/
function clearCache()
{
if(!$this->clearCache) return;
Xxxxx;
}
?>

2.5类的注释方式
<?php
/**
 * 测试的基本类文件。(类的基本说明)
 *
 * @author  chunsheng.wang
 * @version $Id$
 */
/* xxx类。*/
class xxx
{
    var $binRoot;		# xxx运行的根目录。
    var $dataRoot;	# xxx数据文件所在的目录。
var $configFile; # xxx的配置文件。

/* 构造函数，初始化各个变量。*/
    function xxxx($clearCache = true)
    {
        /* 设置基本的参数。*/
        $this->binRoot  = $CFG['binRoot'];
        $this->dataRoot = $this->binRoot . 'data/';
	}
	……
}
三、PHP书写格式约定：
	大括号分成两列书写，理由是可以方便的界定大括号的范围。
	缩进采用四个空格，不使用Tab键进行缩进。
	操作符前后应该有空格，比如 $userName = 'wangcs';
	同一逻辑代码块的代码操作符应当尽量对齐，这样可以方便阅读与修改。
$userName     = 'wangcs';
$userAddress = 'hangzhou';
四、XHTML代码规范
4.1 样式表的引用
	样式表通过外部引用的方式调用，不建议在页面中新定义样式。
	页面元素中的展现形式不建议通过html代码进行定义，都统一使用样式表进行。
比如要显示红色字体，用Html代码可以这样进行定义：
<font clolor=”red”>红色字体</font>
但最好的方法是通过样式表来定义。
<span class=”redtext”>红色字段</span>
	将对网站样式的定义集中到一个样式表文件中去，如果对网站进行修改，可以很快进行。而如果分散到各个网页文件中去，改动起来就非常麻烦了。
4.2 缩进、换行约定
	网页代码的缩进使用两个空格。
因为网页嵌套标签可能比较多，所以使用四个空格进行缩进会导致最深层的代码缩进太多，因而使用两个空格进行缩进。
	如果一行中代码太长，请换行。

<tr><td><input type=”text” name=”test” value=”test” class=”MyInput” onclick=”alert(‘test’)”></td></tr>

可以改成

<tr>
<td>
<input type=”text” name=”test” value=”test” class=”MyInput” onclick=”alert(‘test’)”>
</td>
</tr>

	如果多行相似的代码出现，属性尽量对齐
<input type="hidden" name="projectID"    value="{$ProjectID}" />
<input type="hidden" name="moduleID"     value="{$ModuleID}" />
<input type="hidden" name="bugID"         value="{$BugID}" />
<input type="hidden" name="assignedTo"   value="{$AssignedTo}" />
type、name和value属性对齐以后阅读起来比较方便。

对于某种标记的多个属性，其顺序尽量保持一致。
比如table标记的定义可以按照下面的顺序来定义。

<table width="90%" align="center" border="0" cellpadding="1" cellspacing="0" class="bg-table">
4.3 书写规范
4.3.1 所有的标记都必须要有一个相应的结束标记

以前在HTML中，你可以打开许多标签，例如<p>和<li>而不一定写对应的</p>和</li>来关闭它们。但在XHTML中这是不合法的。XHTML要求有严谨的结构，所有标签必须关闭。如果是单独不成对的标签，在标签最后加一个"/"来关闭它。例如:<br /><img height="80" alt="网页设计师" src="../images/logo_w3cn_200x80.gif" width="200" />

4.3.2 所有标签的元素和属性的名字都必须使用小写

与HTML不一样，XHTML对大小写是敏感的，<title>和<TITLE>是不同的标签。XHTML要求所有的标签和属性的名字都必须使用小写。例如：<BODY>必须写成<body> 。大小写夹杂也是不被认可的，通常dreamweaver自动生成的属性名字"onMouseOver"也必须修改成"onmouseover"。

4.3.3 所有的标记都必须合理嵌套

4.3.4 所有的属性必须用引号""括起来

在HTML中，你可以不需要给属性值加引号，但是在XHTML中，它们必须被加引号。

4.3.5 给所有属性赋一个值

XHTML规定所有属性都必须有一个值，没有值的就重复本身。例如：

<td nowrap> <input type="checkbox" name="shirt" value="medium" checked>

必须修改为：

<td nowrap="nowrap">
<input type="checkbox" name="shirt" value="medium" checked="checked">
</td>
4.4 表单变量命名约定
表单中的变量命名采用PHP的命名方式，使用驼峰方式命名。比如：
<form name=”loginForm”>
  <input type=”text”      name=”userName”  value=”” />
  <input type=”password” name=”password”   value=”” />
</form>
五、JavaScript 代码规范
5.1 变量命名约定
由于JavaScript区分大小写，所以对变量进行命名的时候需要谨慎。同时为了与php程序保持统一，JavaScript的变量命名也采用驼峰的形式。
5.2 函数命名、注释约定
5.2.1 函数命名采用动词+名词的形式，第一项首字母小写。也可以增加前缀。
比如：function checkUserName()
增加前缀的命名可以为：function sysCheckUserName()

5.2.2 函数的注释沿用phpDoc的标准。
比如：
/**
 * Displays an confirmation box beforme to submit a "DROP/DELETE/ALTER" query.
 * This function is called while clicking links
 *
 * @author                         wangcs<wangcs@okooo.net>
 * @param   object   link        the link
 * @param   object   sqlQuery   the sql query to submit
 * @return  boolean              whether to run the query or not
 */
function confirmLink(link, sqlQuery)
{
}
5.3 代码书写规范
	缩进约定：缩进采用四个空格进行缩进。
	每一行都以”;”结束。
	循环、逻辑判断中大括号都单独占一行。
	相邻几行代码中相似的部分尽量对齐。
比如：
/**
 * 动态显示表格。
 *
 * @author                    王春生 <wwccss@263.net>
 * @param  int id            表格编号。
 * @param  int totalCount  表格总数。
 */
function showTable(id,totalCount)
{
    for (i = 1; i <= totalCount; i++)
    {
        if (I == ID)
        {
        }
        else
        {
        }
}
}
六、Subverion操作约定：
	php脚本开始部分应当加上$Id$标签，这样svn会自动将其替换为最后的修改时间、版本和修改者。
在提交svn的时候，需要通过下面的语句设置文件的Id属性：
svn propset svn:keywords Id
	commit的时候一定要写注释，注释的内容必须是此版本的修改信息。注释信息请按照下面的说明进行：
+ 表示新增功能
* 表示修改功能
- 表示删除的功能；
	+ - * 前后都有一个空格。
七、禅道相关约定
7.1 html
模板文件的文件名一律采用小写。
缩进使用两个空格。
元素的name和id命名采用驼峰方式。
7.2 js
文件名使用小写。
缩进使用四个空格。
大括号换行。
类名、方法名、参数采用驼峰方式。
7.3 css
文件名采用小写。
定义尽量在一行中完成。
id采用驼峰方式。
class使用减号连接小写单词。
7.4 images
文件名采用减号连接，都使用小写单词。
7.5 control和model
相关的方法排列在一起。
7.6 语言文件的定义顺序
字段列表
方法列表
各个字段取值列表
页面标签、交互提示
Placeholder列表
