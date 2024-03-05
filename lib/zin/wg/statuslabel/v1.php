<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class statusLabel extends label
{
    protected static array $defineProps = array
    (
        'text?:string',
        'status?: string'
    );

    public function build()
    {
        list($text, $status) = $this->prop(array('text', 'status'));
        return span
        (
            setClass($status ? "status-$status" : 'status'),
            set($this->getRestProps()),
            $text,
            $this->children()
        );
    }

    public static function create(string $status, string $text, mixed ...$children): static
    {
        $props = array('status' => $status, 'text' => $text);
        return new static(set($props), ...$children);
    }
}
