<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console;

use Spiral\RoadRunner\Console\Archive\ArchiveInterface;
use Spiral\RoadRunner\Console\Archive\Factory;
use Spiral\RoadRunner\Console\Command\ArchitectureOption;
use Spiral\RoadRunner\Console\Command\InstallationLocationOption;
use Spiral\RoadRunner\Console\Command\OperatingSystemOption;
use Spiral\RoadRunner\Console\Command\StabilityOption;
use Spiral\RoadRunner\Console\Command\VersionFilterOption;
use Spiral\RoadRunner\Console\Configuration\Generator;
use Spiral\RoadRunner\Console\Configuration\Plugins;
use Spiral\RoadRunner\Console\Repository\AssetInterface;
use Spiral\RoadRunner\Console\Repository\ReleaseInterface;
use Spiral\RoadRunner\Console\Repository\ReleasesCollection;
use Spiral\RoadRunner\Console\Repository\RepositoryInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class GetBinaryCommand extends Command
{
    /**
     * @var string
     */
    private const ERROR_ENVIRONMENT =
        'Could not find any available RoadRunner binary version which meets criterion (--%s=%s --%s=%s --%s=%s). ' .
        'Available: %s';

    /**
     * @var OperatingSystemOption
     */
    private OperatingSystemOption $os;

    /**
     * @var ArchitectureOption
     */
    private ArchitectureOption $arch;

    /**
     * @var VersionFilterOption
     */
    private VersionFilterOption $version;

    /**
     * @var StabilityOption
     */
    private StabilityOption $stability;

    /**
     * @var InstallationLocationOption
     */
    private InstallationLocationOption $location;

    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name ?? 'get-binary');

        $this->os = new OperatingSystemOption($this);
        $this->arch = new ArchitectureOption($this);
        $this->version = new VersionFilterOption($this);
        $this->location = new InstallationLocationOption($this);
        $this->stability = new StabilityOption($this);
    }

    protected function configure(): void
    {
        $this->addOption(
            'plugin',
            'p',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Generate configuration with selected plugins.'
        );

        $this->addOption(
            'preset',
            null,
            InputOption::VALUE_OPTIONAL,
            'Generate configuration with plugins in a selected preset.'
        );

        $this->addOption(
            'no-config',
            null,
            InputOption::VALUE_NONE,
            'Do not generate configuration at all.'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Install or update RoadRunner binary';
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->io($input, $output);

        $target = $this->location->get($input, $io);
        $repository = $this->getRepository();

        $output->writeln('');
        $output->writeln(' Environment:');
        $output->writeln(\sprintf('   - Version:          <info>%s</info>', $this->version->get($input, $io)));
        $output->writeln(\sprintf('   - Stability:        <info>%s</info>', $this->stability->get($input, $io)));
        $output->writeln(\sprintf('   - Operating System: <info>%s</info>', $this->os->get($input, $io)));
        $output->writeln(\sprintf('   - Architecture:     <info>%s</info>', $this->arch->get($input, $io)));
        $output->writeln('');


        // List of all available releases
        $releases = $this->version->find($input, $io, $repository);

        /**
         * @var AssetInterface $asset
         * @var ReleaseInterface $release
         */
        [$asset, $release] = $this->findAsset($repository, $releases, $input, $io);

        // Installation
        $output->writeln(
            \sprintf("  - <info>%s</info>", $release->getRepositoryName()) .
            \sprintf(' (<comment>%s</comment>):', $release->getVersion()) .
            ' Downloading...'
        );

        if ($output->isVerbose()) {
            $output->writeln(\sprintf("     -- <info>%s</info>", $asset->getName()));
        }

        // Install rr binary
        $file = $this->installBinary($target, $release, $asset, $io, $output);

        $this->installConfig($target, $input, $io);

        // Success
        if ($file === null) {
            $io->warning('RoadRunner has not been installed');

            return 1;
        }

        $io->success('Your project is now ready in ' . $file->getPath());

        $io->title('Whats Next?');
        $io->listing([
            // 1)
            'For more detailed documentation, see the ' .
            '<info><href=https://roadrunner.dev>https://roadrunner.dev</></info>',

            // 2)
            'To run the application, use the following command: '.
            '<comment>$ ' . $file->getFilename() . ' serve</comment>',
        ]);

        return 0;
    }

    /**
     * @param string $target
     * @param ReleaseInterface $release
     * @param AssetInterface $asset
     * @param StyleInterface $io
     * @param OutputInterface $out
     * @return \SplFileInfo|null
     * @throws \Throwable
     */
    private function installBinary(
        string $target,
        ReleaseInterface $release,
        AssetInterface $asset,
        StyleInterface $io,
        OutputInterface $out
    ): ?\SplFileInfo {
        $extractor = $this->assetToArchive($asset, $out)
            ->extract([
                'rr.exe' => $target . '/rr.exe',
                'rr'     => $target . '/rr',
            ])
        ;

        $file = null;
        while ($extractor->valid()) {
            $file = $extractor->current();

            if (! $this->checkExisting($file, $io)) {
                $extractor->send(false);
                continue;
            }

            // Success
            $path = $file->getRealPath() ?: $file->getPathname();
            $message = 'RoadRunner (<comment>%s</comment>) has been installed into <info>%s</info>';
            $message = \sprintf($message, $release->getVersion(), $path);
            $out->writeln($message);

            $extractor->next();

            if (! $file->isExecutable()) {
                @chmod($file->getRealPath(), 0755);
            }
        }

        return $file;
    }

    private function installConfig(string $to, InputInterface $in, StyleInterface $io): bool
    {
        $to .= '/.rr.yaml';

        if (\is_file($to) || \is_file(\getcwd().'/.rr.yaml')) {
            return false;
        }

        if ($in->getOption('no-config') || ! $io->confirm('Do you want create default ".rr.yaml" configuration file?', true)) {
            return false;
        }

        $generator = new Generator();
        $plugins = $in->getOption('preset') ?
            Plugins::fromPreset($in->getOption('preset')) :
            Plugins::fromPlugins($in->getOption('plugin'));

        try {
            $config = $generator->generate($plugins);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }

        \file_put_contents($to, $config);

        return true;
    }

    /**
     * @param \SplFileInfo $bin
     * @param StyleInterface $io
     * @return bool
     */
    private function checkExisting(\SplFileInfo $bin, StyleInterface $io): bool
    {
        if (\is_file($bin->getPathname())) {
            $io->warning('RoadRunner binary file already exists!');

            if (! $io->confirm('Do you want overwrite it?', false)) {
                $io->note('Skipping RoadRunner installation...');

                return false;
            }
        }

        return true;
    }

    /**
     * @param RepositoryInterface $repo
     * @param ReleasesCollection $releases
     * @param InputInterface $in
     * @param StyleInterface $io
     * @return array{0: AssetInterface, 1: ReleaseInterface}
     */
    private function findAsset(
        RepositoryInterface $repo,
        ReleasesCollection $releases,
        InputInterface $in,
        StyleInterface $io
    ): array {
        $osOption = $this->os->get($in, $io);
        $archOption = $this->arch->get($in, $io);
        $stabilityOption = $this->stability->get($in, $io);

        /** @var ReleaseInterface[] $filtered */
        $filtered = $releases
            ->minimumStability($stabilityOption)
            ->withAssets()
        ;

        foreach ($filtered as $release) {
            $asset = $release->getAssets()
                ->onlyRoadrunner()
                ->exceptDebPackages()
                ->whereArchitecture($archOption)
                ->whereOperatingSystem($osOption)
                ->first()
            ;

            if ($asset === null) {
                $io->warning(\vsprintf('%s %s does not contain available assembly (further search in progress)', [
                    $repo->getName(),
                    $release->getVersion(),
                ]));

                continue;
            }

            return [$asset, $release];
        }

        $message = \vsprintf(self::ERROR_ENVIRONMENT, [
            $this->os->getName(),
            $osOption,
            $this->arch->getName(),
            $archOption,
            $this->stability->getName(),
            $stabilityOption,
            $this->version->choices($releases),
        ]);

        throw new \UnexpectedValueException($message);
    }

    /**
     * @param AssetInterface $asset
     * @param OutputInterface $out
     * @param string|null $temp
     * @return ArchiveInterface
     * @throws \Throwable
     */
    private function assetToArchive(AssetInterface $asset, OutputInterface $out, string $temp = null): ArchiveInterface
    {
        $factory = new Factory();

        $progress = new ProgressBar($out);
        $progress->setFormat('  [%bar%] %percent:3s%% (%size%Kb/%total%Kb)');
        $progress->setMessage('0.00', 'size');
        $progress->setMessage('?.??', 'total');
        $progress->display();

        try {
            return $factory->fromAsset($asset, function (int $size, int $total) use ($progress) {
                if ($progress->getMaxSteps() !== $total) {
                    $progress->setMaxSteps($total);
                }

                if ($progress->getStartTime() === 0) {
                    $progress->start();
                }

                $progress->setMessage(\number_format($size / 1000, 2), 'size');
                $progress->setMessage(\number_format($total / 1000, 2), 'total');

                $progress->setProgress($size);
            }, $temp);
        } finally {
            $progress->clear();
        }
    }
}
