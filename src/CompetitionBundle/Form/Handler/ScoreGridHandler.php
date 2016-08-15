<?php

namespace CompetitionBundle\Form\Handler;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
use CompetitionBundle\Entity\Round;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * CompetitionBundle\Form\Handler\ScoreGridHandler
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 */
class ScoreGridHandler
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    private $error;

    /**
     * ScoreGridHandler constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Handle the submit of the score grid
     *
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request)
    {
        if (!$round = $this->entityManager->find(Round::class, $request->get('round', null))) {
            $this->error = 'Invalid round';
            return false;
        };
        $date = new \DateTime($request->get('date', null));

        foreach ($request->get('result') as $homePlayerId => $result) {
            $homePlayer = $this->entityManager->find(Player::class, $homePlayerId);
            foreach ($result as $awayPlayerId => $score) {
                if (strlen($score) > 0) {
                    $awayPlayer = $this->entityManager->find(Player::class, $awayPlayerId);
                    list($homeScore, $awayScore) = $this->parseScoreString($score);

                    if (!$match = $this->entityManager->getRepository(Match::class)->findOneBy([
                        'round' => $round,
                        'homePlayer' => $homePlayer,
                        'awayPlayer' => $awayPlayer,
                    ])) {
                        $match = $this->createNewMatch($round, $homePlayer, $awayPlayer, $date);
                    }

                    $match->setHomeScore($homeScore);
                    $match->setAwayScore($awayScore);
                    $match->setRound($round);
                }
            }
        };

        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Parse score string into home and away scores
     *
     * @param string $score
     * @return array
     */
    private function parseScoreString($score)
    {
        return explode('-', $score);
    }

    /**
     * @param Round $round
     * @param Player $homePlayer
     * @param Player $awayPlayer
     * @param \DateTime $date
     * @return Match
     */
    private function createNewMatch(Round $round, Player $homePlayer, Player $awayPlayer, \DateTime $date)
    {
        $match = new Match();
        $match->setHomePlayer($homePlayer);
        $match->setAwayPlayer($awayPlayer);
        $match->setRound($round);
        $match->setDate($date);
        $this->entityManager->persist($match);

        return $match;
    }

    /**
     * Getter for error
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}