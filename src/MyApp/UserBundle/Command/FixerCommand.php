<?php
namespace MyApp\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\DBAL\Schema\AbstractAsset;
class FixtureAsset extends AbstractAsset {
    function generateFK($columnNames, $prefix='', $maxSize=30) {
        return $this->_generateIdentifierName($columnNames, $prefix, $maxSize);
    }
}

class FixerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('fixer:fk')
            ->setDescription('Doctrine foreign key fixer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->getContainer()->get('doctrine')->getEntityManager('default')->getConnection();

        $fks = array(
            array(19, "user", "user_group", "user_id", "id"),
            array(19, "_group", "user_group", "group_id", "id"),
        );

        $fas = new FixtureAsset();

        foreach ($fks as $k) {
            $length = array_shift($k);
            $parent = array_shift($k);

            $fk = $fas->generateFK(array_merge($k), "fk", $length);

            $table = preg_replace('/([A-Z])/e', "'_' . strtolower('\\1')", $k[0]);
            $table = preg_replace('/^_/', '', $table);

            $query = "ALTER TABLE `" . $table . "`
    DROP FOREIGN KEY `$fk`";
            $conn->prepare($query)->execute();

            $output->writeln(sprintf("<info>%s</info>", $query));

            $query = "ALTER TABLE `" . $table . "`
    ADD CONSTRAINT `$fk` FOREIGN KEY (`{$k[1]}`) REFERENCES `" . $parent . "` (`{$k[2]}`) ON DELETE CASCADE";
            $conn->prepare($query)->execute();

            $output->writeln($query);
        }
    }
}
