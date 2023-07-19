<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console;

use Spiral\RoadRunner\Console\Archive\ArchiveInterface;
use Spiral\RoadRunner\Console\Archive\Factory;
use Spiral\RoadRunner\Console\Command\ArchitectureOption;
use Spiral\RoadRunner\Console\Command\InstallationLocationOption;
use Spiral\RoadRunner\Console\Command\OperatingSystemOption;
use Spiral\RoadRunner\Console\Command\StabilityOption;
use Spiral\RoadRunner\Console\Command\VersionFilterOption;
use Spiral\RoadRunner\Console\Repository\AssetInterface;
use Spiral\RoadRunner\Console\Repository\ReleaseInterface;
use Spiral\RoadRunner\Console\Repository\ReleasesCollection;
use Spiral\RoadRunner\Console\Repository\RepositoryInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * protoc-gen-php-grpc file download command.
 */
final class DownloadProtocBinaryCommand extends Command
{
    private const ERROR_ENVIRONMENT = 'Could not find any available protoc-gen-php-grpc binary version which meets criterion (--%s=%s --%s=%s --%s=%s). Available: %s';

    private OperatingSystemOption $os;
    private ArchitectureOption $arch;
    private VersionFilterOption $version;
    private StabilityOption $stability;
    private InstallationLocationOption $location;

    public function __construct(string $name = null)
    {
        parent::__construct($name ?? 'download-protoc-binary');

        $this->os = new OperatingSystemOption($this);
        $this->arch = new ArchitectureOption($this);
        $this->version = new VersionFilterOption($this);
        $this->location = new InstallationLocationOption($this);
        $this->stability = new StabilityOption($this);
    }

    public function getDescription(): string
    {
        return 'Install or update protoc-gen-php-grpc binary';
    }

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

        // Success
        if ($file === null) {
            $io->warning('protoc-gen-php-grpc has not been installed');

            return 1;
        }

        return 0;
    }

    private function installBinary(
        string $target,
        ReleaseInterface $release,
        AssetInterface $asset,
        StyleInterface $io,
        OutputInterface $out
    ): ?\SplFileInfo {
        $extractor = $this->assetToArchive($asset, $out)
            ->extract([
                'protoc-gen-php-grpc.exe' => $target . '/protoc-gen-php-grpc.exe',
                'protoc-gen-php-grpc' => $target . '/protoc-gen-php-grpc',
            ]);

        $file = null;
        while ($extractor->valid()) {
            $file = $extractor->current();

            if (!$this->checkExisting($file, $io)) {
                $extractor->send(false);
                continue;
            }

            // Success
            $path = $file->getRealPath() ?: $file->getPathname();
            $message = 'protoc-gen-php-grpc (<comment>%s</comment>) has been installed into <info>%s</info>';
            $message = \sprintf($message, $release->getVersion(), $path);
            $out->writeln($message);

            $extractor->next();

            if (!$file->isExecutable()) {
                @chmod($file->getRealPath(), 0755);
            }
        }

        return $file;
    }

    private function checkExisting(\SplFileInfo $bin, StyleInterface $io): bool
    {
        if (\is_file($bin->getPathname())) {
            $io->warning('protoc-gen-php-grpc binary file already exists!');

            if (!$io->confirm('Do you want overwrite it?', false)) {
                $io->note('Skipping protoc-gen-php-grpc installation...');

                return false;
            }
        }

        return true;
    }

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
            ->withAssets();

        foreach ($filtered as $release) {
            $asset = $release->getAssets()
                ->filter(
                    static fn (AssetInterface $asset): bool =>
                    \str_starts_with($asset->getName(), 'protoc-gen-php-grpc')
                )
                ->whereArchitecture($archOption)
                ->whereOperatingSystem($osOption)
                ->first();

            if ($asset === null) {
                $io->warning(
                    \vsprintf('%s %s does not contain available assembly (further search in progress)', [
                        $repo->getName(),
                        $release->getVersion(),
                    ])
                );

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
