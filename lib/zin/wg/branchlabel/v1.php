<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class branchLabel extends label
{
    protected static array $defineProps = array
    (
        'text?:string',
        'branch?: int'
    );

    public function build()
    {
        list($text, $branch) = $this->prop(array('text', 'branch'));
        return span
        (
            setClass(empty($branch) ? 'text-primary secondary-outline' : 'gray-300-outline'),
            setClass('label size-sm rounded-full flex-none text-clip mx-1'),
            setStyle('max-width', '60px'),
            set($this->getRestProps()),
            $text,
            $this->children()
        );
    }

    public static function create(int $branch, string $text, mixed ...$children): static
    {
        $props = array('branch' => $branch, 'text' => $text);
        return new static(set($props), ...$children);
    }
}
