<?php

namespace nickolaus\SemanticUiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;

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
            ->setDescription('Hello PhpStorm');
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
        $origin = sprintf('%s/vendor/semantic/ui/dist', $rootDir);
        $destination = sprintf('%s/vendor/nickolaus/semantic-ui-bundle/Resources/public/semantic-ui', $rootDir);

        $fileSystem->symlink($origin, $destination, true);

    }
}
