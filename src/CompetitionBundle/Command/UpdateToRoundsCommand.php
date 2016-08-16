<?php

namespace CompetitionBundle\Command;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Round;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CompetitionBundle\Command\UpdateToRoundsCommand
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 */
class UpdateToRoundsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:update-to-rounds')
            ->setDescription('Update all matches to use rounds')
            ->setHelp('Update all matches to use rounds');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $matches = $em->getRepository(Match::class)->findBy([
            'round' => null,
        ]);

        /** @var Match $match */
        foreach ($matches as $match) {
            $formattedDate = $match->getDate()->format('Ymd');

            /** @var Round $round */
            if (!$round = $em->getRepository(Round::class)->findOneByName($formattedDate)) {
                $round = $this->createRound($formattedDate);
                $em->persist($round);
                $em->flush();
                $output->writeln(sprintf('<info>Created new round %s</info>', $formattedDate));
            }

            // add match and its players to the round
            $round->addMatch($match);
            $round->addPlayer($match->getHomePlayer());
            $round->addPlayer($match->getAwayPlayer());

            $em->flush();
        }

        return 0;
    }

    /**
     * Creates a new round
     *
     * @param $dateString
     * @return Round
     */
    private function createRound($dateString)
    {
        $round = new Round();
        $round->setName($dateString);

        return $round;
    }
}