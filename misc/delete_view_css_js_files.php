<?php
/**
 * 删除重构后不再需要的view文件、css文件和js文件脚本
 *
 * 处理流程：
 * 1. 扫描module目录下的所有一级目录（排除ai、common、file、search、transfer）
 * 2. 检查每个模块是否同时存在view和ui目录
 * 3. 对比view和ui目录中的同名文件并删除view中的文件以及对应的css文件和js文件（保留sendmail.html.php）
 * 4. 解析模块名和方法名的中文名称
 * 5. 输出CSV报告文件
 */

class ViewFileDeleter
{
    private $moduleDir = 'module';
    private $excludeModules = ['ai', 'common', 'file', 'search', 'transfer'];
    private $deletedFiles = [];
    private $debugMode = true;
    private $logFile;

    public function __construct()
    {
        $this->logFile = '/tmp/delete_view_css_js_files_' . date('Ymd_His') . '.log';
        $this->log("开始执行删除view文件、css文件和js文件脚本");
    }

    /**
     * 主执行函数
     */
    public function run()
    {
        try {
            $this->log("正在扫描模块目录...");
            $modules = $this->scanModules();

            foreach ($modules as $module) {
                $this->log("处理模块: $module");
                $this->processModule($module);
            }

            $this->log("生成CSV报告...");
            $csvFile = $this->generateCSVReport();

            $this->log("脚本执行完成");

            echo "PHP脚本路径: " . __FILE__ . "\n";
            echo "CSV文件路径: $csvFile\n";
            echo "日志文件路径: {$this->logFile}\n";

        } catch (Exception $e) {
            $this->log("错误: " . $e->getMessage());
            echo "执行出错: " . $e->getMessage() . "\n";
        }
    }

    /**
     * 扫描模块目录
     */
    private function scanModules()
    {
        if (!is_dir($this->moduleDir)) {
            throw new Exception("模块目录不存在: {$this->moduleDir}");
        }

        $modules = [];
        $dirs = scandir($this->moduleDir);

        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') continue;

            $modulePath = $this->moduleDir . '/' . $dir;
            if (is_dir($modulePath) && !in_array($dir, $this->excludeModules)) {
                $modules[] = $dir;
            }
        }

        $this->log("发现模块: " . implode(', ', $modules));
        return $modules;
    }

    /**
     * 处理单个模块
     */
    private function processModule($module)
    {
        $modulePath = $this->moduleDir . '/' . $module;
        $viewPath = $modulePath . '/view';
        $uiPath = $modulePath . '/ui';
        $cssPath = $modulePath . '/css';
        $jsPath = $modulePath . '/js';

        // 检查是否同时存在view和ui目录
        if (!is_dir($viewPath) || !is_dir($uiPath)) {
            $this->log("模块 $module 不存在view或ui目录，跳过处理");
            return;
        }

        $this->log("模块 $module 同时存在view和ui目录，开始处理");

        // 获取view目录中的文件
        $viewFiles = $this->getViewFiles($viewPath);
        $deletedCount = 0;

        foreach ($viewFiles as $viewFile) {
            if ($viewFile === 'sendmail.html.php' || strpos($viewFile, 'ajax') === 0) {
                $this->log("跳过 sendmail.html.php 文件");
                continue;
            }

            // 检查ui目录中是否存在同名文件
            $uiFile = $uiPath . '/' . $viewFile;
            if (file_exists($uiFile)) {
                $viewFilePath = $viewPath . '/' . $viewFile;
                $this->log("删除文件: $viewFilePath");

                // 执行删除
                if (unlink($viewFilePath)) {
                    $this->log("成功删除: $viewFilePath");
                } else {
                    $this->log("删除失败: $viewFilePath");
                    continue;
                }

                $cssFilePath = $cssPath . '/' . str_replace('.html.php', '.css', $viewFile);
                if (file_exists($cssFilePath)) {
                    $this->log("删除文件: $cssFilePath");
                    if (unlink($cssFilePath)) {
                        $this->log("成功删除: $cssFilePath");
                    } else {
                        $this->log("CSS文件删除失败: $cssFilePath");
                        continue;
                    }
                } else {
                    $cssFilePath = '';
                }

                $jsFilePath = $jsPath . '/' . str_replace('.html.php', '.js', $viewFile);
                if (file_exists($jsFilePath)) {
                    $this->log("删除文件: $jsFilePath");
                    if (unlink($jsFilePath)) {
                        $this->log("成功删除: $jsFilePath");
                    } else {
                        $this->log("JS文件删除失败: $jsFilePath");
                        continue;
                    }
                } else {
                    $jsFilePath = '';
                }

                $deletedCount++;

                // 记录删除的文件信息
                $this->recordDeletedFile($module, $viewFile, $viewFilePath, $cssFilePath, $jsFilePath);
            }
        }

        // 检查view目录、css目录和js目录是否为空，如果为空则删除目录
        if ($deletedCount > 0) {
            if ($this->isDirectoryEmpty($viewPath)) {
                $this->log("删除空的view目录: $viewPath");
                rmdir($viewPath);

                // view目录为空，尝试删除common.css和common.js
                $commonCssPath = $cssPath . '/common.css';
                if (is_file($commonCssPath)) {
                    $this->log("删除文件: $commonCss");
                    if (unlink($commonCss)) {
                        $this->log("成功删除: $commonCss");
                    } else {
                        $this->log("CSS文件删除失败: $commonCss");
                    }
                }
                $commonJsPath = $jsPath . '/common.js';
                if (is_file($commonJsPath)) {
                    $this->log("删除文件: $commonJs");
                    if (unlink($commonJs)) {
                        $this->log("成功删除: $commonJs");
                    } else {
                        $this->log("JS文件删除失败: $commonJs");
                    }
                }
            }
            if (is_dir($cssPath) && $this->isDirectoryEmpty($cssPath)) {
                $this->log("删除空的css目录: $cssPath");
                rmdir($cssPath);
            }
            if (is_dir($jsPath) && $this->isDirectoryEmpty($jsPath)) {
                $this->log("删除空的js目录: $jsPath");
                rmdir($jsPath);
            }
        }
    }

    /**
     * 获取view目录中的文件
     */
    private function getViewFiles($viewPath)
    {
        $files = [];
        $items = scandir($viewPath);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemPath = $viewPath . '/' . $item;
            if (is_file($itemPath) && substr($item, -9) === '.html.php') {
                $files[] = $item;
            }
        }

        return $files;
    }

    /**
     * 检查目录是否为空
     */
    private function isDirectoryEmpty($dir)
    {
        $items = scandir($dir);
        return count($items) <= 2; // 只有 . 和 ..
    }

    /**
     * 记录删除的文件信息
     */
    private function recordDeletedFile($module, $filename, $filepath, $cssPath, $jsPath)
    {
        $methodName = str_replace('.html.php', '', $filename);

        $this->deletedFiles[] = [
            'module' => $module,
            'method' => $methodName,
            'filename' => $filename,
            'filepath' => $filepath,
            'csspath' => $cssPath,
            'jspath' => $jsPath,
            'module_cn' => '',
            'method_cn' => ''
        ];
    }

    /**
     * 解析语言文件获取中文名称
     */
    private function parseLangFiles()
    {
        $this->log("开始解析语言文件...");

        // 读取common模块的语言文件
        $commonLang = $this->readLangFile($this->moduleDir . '/common/lang/zh-cn.php');

        foreach ($this->deletedFiles as &$file) {
            $module = $file['module'];
            $method = $file['method'];

            // 解析模块名中文
            $file['module_cn'] = $this->getModuleChinese($module, $commonLang);

            // 解析方法名中文
            if ($module === 'block' && strpos($method, 'block') !== false) {
                // block模块特殊处理
                $file['method_cn'] = $this->getBlockMethodChinese($method);
            } else {
                // 普通模块处理
                $moduleLang = $this->readLangFile($this->moduleDir . '/' . $module . '/lang/zh-cn.php');
                $file['method_cn'] = $this->getMethodChinese($module, $method, $moduleLang);
            }

            // 清理中文名称
            $file['module_cn'] = $this->cleanChineseName($file['module_cn']);
            $file['method_cn'] = $this->cleanChineseName($file['method_cn']);

            $this->log("解析 $module.$method: {$file['module_cn']}.{$file['method_cn']}");
        }
    }

    /**
     * 读取语言文件
     */
    private function readLangFile($filepath)
    {
        if (!file_exists($filepath)) {
            $this->log("语言文件不存在: $filepath");
            return '';
        }

        return file_get_contents($filepath);
    }

    /**
     * 获取模块中文名称
     */
    private function getModuleChinese($module, $commonLang)
    {
        // 先在common语言文件中查找 $lang->模块名->common = '中文名';
        $pattern = '/\$lang->' . preg_quote($module, '/') . '->common\s*=\s*[\'"]([^\'"]*)[\'"];?/';
        if (preg_match($pattern, $commonLang, $matches)) {
            return trim($matches[1]);
        }

        // 处理common语言文件中的变量引用，比如 $lang->product->common = $lang->productCommon;
        $pattern2 = '/\$lang->' . preg_quote($module, '/') . '->common\s*=\s*\$lang->([^;]+);?/';
        if (preg_match($pattern2, $commonLang, $matches)) {
            $varName = trim($matches[1]);
            $this->log("在common语言文件中找到变量引用: $varName");

            // 先尝试在common语言文件中查找变量定义
            $varPattern = '/\$lang->' . preg_quote($varName, '/') . '\s*=\s*[\'"]([^\'"]*)[\'"];?/';
            if (preg_match($varPattern, $commonLang, $varMatches)) {
                $this->log("在common语言文件中找到变量定义: {$varMatches[1]}");
                return trim($varMatches[1]);
            }

            // 如果common文件中找不到，尝试使用硬编码映射
            $hardcodedValue = $this->getHardcodedVariableValue($varName);
            if ($hardcodedValue !== $varName) {
                $this->log("使用硬编码映射解析变量: $varName -> $hardcodedValue");
                return $hardcodedValue;
            }
        }

        // 如果在common中找不到，尝试在模块自己的语言文件中查找
        $moduleLang = $this->readLangFile($this->moduleDir . '/' . $module . '/lang/zh-cn.php');
        if (!empty($moduleLang)) {
            $pattern3 = '/\$lang->' . preg_quote($module, '/') . '->common\s*=\s*[\'"]([^\'"]*)[\'"];?/';
            if (preg_match($pattern3, $moduleLang, $matches)) {
                $this->log("在模块 $module 自己的语言文件中找到中文名称");
                return trim($matches[1]);
            }

            // 也处理变量引用的情况
            $pattern4 = '/\$lang->' . preg_quote($module, '/') . '->common\s*=\s*([^;]+);?/';
            if (preg_match($pattern4, $moduleLang, $matches)) {
                $expression = trim($matches[1]);
                $this->log("在模块 $module 自己的语言文件中找到表达式: $expression");

                // 处理简单变量引用，如 $lang->productCommon
                if (preg_match('/^\$lang->(\w+)$/', $expression, $varMatch)) {
                    $varName = $varMatch[1];
                    // 先在模块文件中查找变量定义
                    $varPattern = '/\$lang->' . preg_quote($varName, '/') . '\s*=\s*[\'"]([^\'"]*)[\'"];?/';
                    if (preg_match($varPattern, $moduleLang, $varMatches)) {
                        $this->log("在模块 $module 自己的语言文件中找到变量定义的中文名称");
                        return trim($varMatches[1]);
                    }
                    // 如果模块文件中没有，再在common文件中查找
                    if (preg_match($varPattern, $commonLang, $varMatches)) {
                        $this->log("在common文件中找到变量定义的中文名称");
                        return trim($varMatches[1]);
                    }
                    // 如果都找不到，使用硬编码的映射
                    return $this->getHardcodedVariableValue($varName);
                }

                // 处理复杂表达式，如 $lang->productCommon . '计划'
                if (preg_match('/^\$lang->(\w+)\s*\.\s*[\'"]([^\'"]*)[\'"]$/', $expression, $complexMatch)) {
                    $varName = $complexMatch[1];
                    $suffix = $complexMatch[2];

                    // 查找变量定义
                    $varPattern = '/\$lang->' . preg_quote($varName, '/') . '\s*=\s*[\'"]([^\'"]*)[\'"];?/';
                    $varValue = '';

                    // 先在模块文件中查找
                    if (preg_match($varPattern, $moduleLang, $varMatches)) {
                        $varValue = trim($varMatches[1]);
                    }
                    // 如果模块文件中没有，再在common文件中查找
                    elseif (preg_match($varPattern, $commonLang, $varMatches)) {
                        $varValue = trim($varMatches[1]);
                    }
                    // 如果都找不到，使用硬编码的映射
                    else {
                        $varValue = $this->getHardcodedVariableValue($varName);
                    }

                    if (!empty($varValue)) {
                        $result = $varValue . $suffix;
                        $this->log("在模块 $module 中解析复杂表达式得到: $result");
                        return $result;
                    }
                }
            }
        }

        $this->log("未找到模块 $module 的中文名称");
        return $module;
    }

    /**
     * 获取方法中文名称
     */
    private function getMethodChinese($module, $method, $moduleLang)
    {
        // 构建语言定义的索引（小写键值对应）
        $langIndex = $this->buildLangIndex($module, $moduleLang);

        // 使用小写匹配
        $lowerMethod = strtolower($method);
        if (isset($langIndex[$lowerMethod])) {
            $this->log("通过小写匹配找到方法 $module.$method 的中文名称: {$langIndex[$lowerMethod]}");
            return $langIndex[$lowerMethod];
        }

        $this->log("未找到方法 $module.$method 的中文名称");
        return $method;
    }

    /**
     * 构建语言文件的索引，将所有方法名转换为小写作为键
     */
    private function buildLangIndex($module, $langContent)
    {
        $index = [];

        // 匹配所有 $lang->模块名->方法名 = '中文名'; 的定义
        $pattern = '/\$lang->' . preg_quote($module, '/') . '->(\w+)\s*=\s*[\'"]([^\'"]*)[\'"];?/';
        if (preg_match_all($pattern, $langContent, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $methodName = $match[1];
                $chineseName = trim($match[2]);

                // 将方法名转换为小写作为索引键
                $lowerMethodName = strtolower($methodName);
                $index[$lowerMethodName] = $chineseName;

                $this->log("建立索引: $lowerMethodName -> $chineseName");
            }
        }

        return $index;
    }

    /**
     * 获取block模块方法中文名称
     */
    private function getBlockMethodChinese($method)
    {
        $blockLang = $this->readLangFile($this->moduleDir . '/block/lang/zh-cn.php');

        // 匹配 $lang->block->default['xxx'][] = array('title' => '中文名', ..., 'code' => 'xxx', ...);
        // 需要处理title中可能包含变量的情况
        $pattern = '/\$lang->block->default\[[^\]]+\]\[\]\s*=\s*array\([^;]*?[\'"]title[\'"][^\'\"]*[\'"]([^\'";]*)[\'"][^;]*?[\'"]code[\'"][^\'\"]*[\'"]([^\'"]*)[\'"][^;]*?\);/s';

        if (preg_match_all($pattern, $blockLang, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $title = $match[1];
                $code = $match[2];

                // 检查是否匹配
                if ($code . 'block' === $method) {
                    // 处理title中的变量
                    if (strpos($title, '{') !== false) {
                        // 简单处理，直接提取中文部分
                        $title = preg_replace('/\{[^}]*\}/', '', $title);
                        $title = trim($title);
                    }
                    return $title ?: $code;
                }
            }
        }

        $this->log("未找到block方法 $method 的中文名称");
        return $method;
    }

    /**
     * 获取硬编码的变量值映射
     */
    private function getHardcodedVariableValue($varName)
    {
        $hardcodedVars = [
            'productCommon' => '产品',
            'projectCommon' => '项目',
            'executionCommon' => '执行',
            'SRCommon' => '软件需求',
            'URCommon' => '用户需求',
            'ERCommon' => '史诗'
        ];

        if (isset($hardcodedVars[$varName])) {
            $this->log("使用硬编码映射: $varName -> {$hardcodedVars[$varName]}");
            return $hardcodedVars[$varName];
        }

        $this->log("未找到变量 $varName 的硬编码映射");
        return $varName;
    }

    /**
     * 清理中文名称，根据命令文件要求进行替换和清理
     */
    private function cleanChineseName($name)
    {
        if (empty($name)) {
            return $name;
        }

        // 替换变量引用
        $replacements = [
            '$lang->SRCommon' => '软件需求',
            '$lang->productCommon' => '产品',
            '$lang->executionCommon' => '执行',
            '$lang->execution->common' => '执行',
            '$lang->URCommon' => '用户需求',
            '$lang->mr->common' => '合并请求',
            '$lang->projectCommon' => '项目',
            '$lang->common->story' => '需求'
        ];

        foreach ($replacements as $search => $replace) {
            $name = str_replace($search, $replace, $name);
        }

        // 去掉特殊字符：{}、空格、点号、单双引号
        $name = str_replace(['{', '}', ' ', '.', '"', "'"], '', $name);

        return $name;
    }

    /**
     * 转换为驼峰命名
     */
    private function toCamelCase($str)
    {
        // 特殊的映射表，处理一些已知的复杂转换
        $specialMappings = [
            'batchtotask' => 'batchToTask',
            'linkcase' => 'linkCase',
            'groupcase' => 'groupCase',
            'linkcases' => 'linkCases',
            'unlinkcase' => 'unlinkCase',
            'batchcreate' => 'batchCreate',
            'batchedit' => 'batchEdit',
            'batchdelete' => 'batchDelete',
            'batchclose' => 'batchClose',
            'batchactivate' => 'batchActivate',
            'assignto' => 'assignTo',
            'linkstory' => 'linkStory',
            'linkbug' => 'linkBug',
            'linkbuild' => 'linkBuild',
        ];

        // 首先检查特殊映射
        $lowerStr = strtolower($str);
        if (isset($specialMappings[$lowerStr])) {
            return $specialMappings[$lowerStr];
        }

        // 将下划线分隔的字符串转为驼峰命名
        if (strpos($str, '_') !== false) {
            return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
        }

        // 处理没有下划线但可能需要转换的情况
        // 尝试智能分割，比如 batchcreate -> batch + create
        $result = preg_replace_callback('/([a-z])([A-Z])/', function($matches) {
            return $matches[1] . ucfirst($matches[2]);
        }, $str);

        // 尝试识别常见的单词边界
        $commonWords = ['batch', 'create', 'edit', 'delete', 'view', 'list', 'browse', 'manage', 'update', 'add', 'remove', 'link', 'group', 'unlink'];
        foreach ($commonWords as $word) {
            if (strpos($str, $word) === 0 && strlen($str) > strlen($word)) {
                $remaining = substr($str, strlen($word));
                return $word . ucfirst($remaining);
            }
        }

        return $str;
    }

    /**
     * 生成CSV报告
     */
    private function generateCSVReport()
    {
        $this->parseLangFiles();

        $csvFile = '/tmp/deleted_view_css_js_files_' . date('Ymd_His') . '.csv';
        $fp = fopen($csvFile, 'w');

        // 设置BOM以支持中文显示
        fwrite($fp, "\xEF\xBB\xBF");

        // 写入表头
        fputcsv($fp, ['模块名', '方法名', '模块中文名', '方法中文名', '删除文件路径', '删除CSS路径', '删除JS路径']);

        // 写入数据
        foreach ($this->deletedFiles as $file) {
            $moduleCn = $this->replaceSpecialTerms($file['module_cn']);
            $methodCn = $this->replaceSpecialTerms($file['method_cn']);

            fputcsv($fp, [
                $file['module'],
                $file['method'],
                $moduleCn,
                $methodCn,
                $file['filepath']
            ]);
        }

        fclose($fp);

        $this->log("CSV报告已生成: $csvFile");
        return $csvFile;
    }

    /**
     * 替换特殊术语
     */
    private function replaceSpecialTerms($text)
    {
        $replacements = [
            '$lang->SRCommon' => '软件需求',
            '$lang->productCommon' => '产品',
            '$lang->executionCommon' => '执行',
            '$lang->execution->common' => '执行',
            '$lang->URCommon' => '用户需求',
            '$lang->mr->common' => '合并请求',
            '$lang->projectCommon' => '项目',
            '{}' => ''
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * 日志记录
     */
    private function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";

        if ($this->debugMode) {
            echo $logMessage;
        }

        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

// 执行脚本
$deleter = new ViewFileDeleter();
$deleter->run();
