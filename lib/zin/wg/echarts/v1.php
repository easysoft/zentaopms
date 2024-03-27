<?php
declare(strict_types=1);
namespace zin;

class echarts extends wg
{
    protected static array $defineProps = array(
        'theme?: string|array',          // 主题
        'width?: number|string="100%"',  // 宽度
        'height?: number|string=100',    // 高度
        'responsive?: bool',             // 是否自适应
        'exts?: string|array'            // 插件
    );

    /**
     * 扩展定义。
     * Echart extensions map.
     *
     * @var array
     */
    public static array $extMap = array
    (
        'timeline' => 'timeline.min.js'
    );

    public function size(string|int $width, string|int $height): echarts
    {
        $this->setProp('width', $width);
        $this->setProp('height', $height);
        return $this;
    }

    public function theme(string|array $value): echarts
    {
        $this->setProp('theme', $value);
        return $this;
    }

    public function responsive(bool $value = true): echarts
    {
        $this->setProp('responsive', $value);
        return $this;
    }

    protected function build(): zui
    {
        global $app;

        list($exts, $width, $height, $responsive, $theme) = $this->prop(array('exts', 'width', 'height', 'responsive', 'theme'));

        $root  = $app->getWebRoot() . 'js/echarts/';
        $files = array($root . 'echarts.common.min.js');
        if($exts)
        {
            $exts = is_array($exts) ? $exts : explode(',', $exts);
            foreach($exts as $ext)
            {
                if(isset(self::$extMap[$ext]))  $ext = self::$extMap[$ext];
                if(!str_contains($ext, '.'))    $ext = $ext . '.min.js';
                if(!str_starts_with($ext, '/')) $ext = $root . $ext;
                $files[] = $ext;
            }
        }

        $files = json_encode($files);
        return zui::echarts
        (
            set::responsive($responsive),
            set::theme($theme),
            set::_style(array('width' => is_int($width) ? "{$width}px" : $width, 'height' => is_int($height) ? "{$height}px" : $height)),
            set::_call("~((name,selector,options) => $.getLib({src: $files, root: false}, () => console.log('>>> load echarts', window.echarts) || zui.create(name,selector,options)))"),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
