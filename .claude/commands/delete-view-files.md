## 角色定义

你是一个软件开发工程师，禅道项目管理软件的核心开发者。禅道项目管理软件经过一个大的重构，现在你将删除重构后不再需要保留的文件。

## 处理流程

1.编写一个php脚本处理这些工作。
2.module目录下的每个一级目录代表一个模块。
3.不处理ai、common、file、search和transfer模块。
4.检查每个模块下是否同时存在view目录和ui目录，如果没有同时存在则不处理。
5.检查每个模块下的view目录里的文件是否在当前模块下的ui目录中存在同名文件，如果存在则删除view目录下的文件。注意：sendmail.html.php文件除外。
6.如果view目录里的文件被全部删除则删除view目录。
7.解析模块名和方法名的中文名称：
- 目录名代表模块名，文件名中除去.html.php外的部分代表方法名。
- 读取common目录下的lang目录中的zh-cn.php文件的内容。
- 读取每一个目录下的lang目录中的zh-cn.php文件的内容。
- 读取文件时可能遇到一些未知的PHP变量，下面是一部分参考：
  - $lang->ERCommon = '业务需求'
  - $lang->URCommon = '用户需求'
  - $lang->SRCommon = '软件需求'
  - $lang->productCommon = '产品'
  - $lang->projectCommon = '项目'
  - $lang->executionCommon = '执行'
  - $lang->execution->common = '执行'
  - $lang->mr->common = '合并请求'
  - $lang->common->story = '需求'
- 模块名的中文名称在语言文件中的定义为'$lang->模块名->common'。
- 方法名的中文名称在语言文件中的定义为'$lang->模块名->方法名'。
- 把读取到的语言项定义转换为小写后和文件名比对，两者一致时语言项定义即为方法名的中文名称。
- 以task模块为例：
  - 模块名为'task'，在common/lang/zh-cn.php中存在定义"$lang->task->common         = '任务';"，则模块名的中文名称为'任务'。
  - view目录下的文件名为'create.html.php'，则方法名为'create'，在task/lang/zh-cn.php中存在定义'$lang->task->create              = "建任务";'，则方法名的中文名称为'建任务'。
  - view目录下的文件名为'batchcreate.html.php'，则方法名为'batchcreate'，在task/lang/zh-cn.php中存在定义'$lang->task->batchCreate         = "批量创建";'，则方法名的中文名称为'批量创建'。
- block模块以block结尾的方法名需要特殊处理：
  - block模块lang目录下的zh-cn.php中定义了数组$lang->block->default。
  - 提取该数组每一行定义的元素的title和code。
  - 把code和'block'拼接后和方法名匹配，如果匹配成功以title作为方法名的中文名称。
  - 以block模块view目录下的projectdynamicblock.html.php文件为例：
    - 方法名为'projectdynamicblock'。
    - 在block/lang/zh-cn.php中存在定义"$lang->block->default['waterfallproject'][] = array('title' => '最新动态',                   'module' => 'waterfallproject', 'code' => 'projectdynamic', 'width' => '1');"，提取到title='最新动态'，code='projectdynamic'。
    - code和'block'拼接后和方法名一致，则方法名的中文名称为'最新动态'。
- 如果没有解析到模块名和方法名的中文名称，需要询问我如何处理。
8.输出一个csv文件到/tmp目录下：
- 文件内容支持中文显示，文件名以'YYYYmmdd_HHiiss'格式包含当前日期和时间。
- 分别列出删除的模块名、方法名、模块名的中文名称、方法名的中文名称和被删除文件在本项目中的相对路径。
- 文件内容中如存在以下内容需要替换：
  - '$lang->SRCommon'替换为软件需求'。
  - '$lang->productCommon'替换为'产品'。
  - '$lang->executionCommon'替换为'执行'。
  - '$lang->execution->common'替换为'执行'。
  - '$lang->URCommon'替换为'用户需求'。
  - '$lang->mr->common'替换为'合并请求'。
  - '$lang->projectCommon'替换为'项目'。
  - '{'、'}'、' '、'.'、'"'和"'"替换为空。
9.输出php脚本的路径和csv文件的路径。

## 调试模式

在脚本中添加详细的调试输出，显示解析过程

## 测试验证

在生成最终报告前，先测试几个已知模块的解析结果

## 错误处理

当解析失败时提供更清晰的错误信息

## 日志记录

记录解析过程中的异常情况

## 清除临时文件

处理过程中生成的脚本和csv文件可以保留，其他临时文件应该删除。
