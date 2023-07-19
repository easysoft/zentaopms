<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration;

use Spiral\RoadRunner\Console\Configuration\Section\Rpc;
use Spiral\RoadRunner\Console\Configuration\Section\SectionInterface;
use Spiral\RoadRunner\Console\Configuration\Section\Version;
use Symfony\Component\Yaml\Yaml;

class Generator
{
    /** @var SectionInterface[] */
    protected array $sections = [];

    /** @psalm-var non-empty-array<class-string<SectionInterface>> */
    protected const REQUIRED_SECTIONS = [
        Version::class,
        Rpc::class,
    ];

    public function generate(Plugins $plugins): string
    {
        $this->collectSections($plugins->getPlugins());

        return Yaml::dump($this->getContent(), 10);
    }

    protected function getContent(): array
    {
        $content = [];
        foreach ($this->sections as $section) {
            $content += $section->render();
        }

        return $content;
    }

    protected function collectSections(array $plugins): void
    {
        $sections = \array_merge(self::REQUIRED_SECTIONS, $plugins);

        foreach ($sections as $section) {
            $this->fromSection(new $section());
        }
    }

    /** @psalm-return non-empty-array<SectionInterface> */
    protected function fromSection(SectionInterface $section): void
    {
        if (!isset($this->sections[\get_class($section)])) {
            $this->sections[\get_class($section)] = $section;
        }

        foreach ($section->getRequired() as $required) {
            $this->fromSection(new $required());
        }
    }
}
