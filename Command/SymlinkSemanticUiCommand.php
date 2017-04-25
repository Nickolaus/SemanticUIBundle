<?php

namespace Nickolaus\SemanticUiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SymlinkSemanticUiCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('nickolaus:semantic-ui:symlink')
            ->setDescription('Links Semantic UI dist files into the bundle\'s resources');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $fileSystem = $container->get('filesystem');
        $rootDir = sprintf('%s/..', $container->get('kernel')->getRootDir());
        $originDir = realpath(sprintf('%s/vendor/semantic/ui/dist', $rootDir));
        $destinationDir = sprintf('%s/vendor/nickolaus/semantic-ui-bundle/Resources/public/semantic-ui', realpath($rootDir));

        $output->write('Creating symlinks... ');

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($originDir)) AS $item) {
            $relativePathName = substr($item->getPathname(), strlen($originDir), strlen($originDir));
            $target           = sprintf('%s%s', $destinationDir, $relativePathName);
            if (!$item->isDir()) {
                if ('\\' === DIRECTORY_SEPARATOR) { // Windows => copy
                    $fileSystem->copy($item->getPathName(), $target);
                } else {
                    $fileSystem->symlink($item->getPathName(), $target);
                }
            }
        }

        $output->writeln('<info>Done.</info>');
        $output->writeln('Don\'t forget to run <info>assets:install</info>.');

    }
}
