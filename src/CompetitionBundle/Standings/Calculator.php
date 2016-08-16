<?php

namespace CompetitionBundle\Standings;

use CompetitionBundle\Entity\Round;
use CompetitionBundle\Model\Standing;

/**
 * CompetitionBundle\Standings\Handler
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 */
class Calculator
{
    /**
     * @param Round $round
     * @return array
     */
    public function calculate(Round $round)
    {
        $standings = $this->initializeStandings($round);

        foreach ($round->getMatches() as $match) {
            $winner = $match->getHomeScore() > $match->getAwayScore() ? $match->getHomePlayer() : $match->getAwayPlayer();
            $loser = $match->getHomeScore() > $match->getAwayScore() ? $match->getAwayPlayer() : $match->getHomePlayer();

            $standing = $standings[$winner->getId()];
            $standing->played += 1;
            $standing->won += 1;
            $standing->scored += max($match->getHomeScore(), $match->getAwayScore());
            $standing->conceded += min($match->getHomeScore(), $match->getAwayScore());
            $standing->goalDifference = $standing->scored - $standing->conceded;

            $standing = $standings[$loser->getId()];
            $standing->played += 1;
            $standing->lost += 1;
            $standing->scored += min($match->getHomeScore(), $match->getAwayScore());
            $standing->conceded += max($match->getHomeScore(), $match->getAwayScore());
            $standing->goalDifference = $standing->scored - $standing->conceded;
        }

        uasort($standings, [$this, 'sortStandings']);

        return $standings;
    }

    /**
     * Create an empty standings table
     *
     * @param Round $round
     * @return array
     */
    private function initializeStandings(Round $round)
    {
        $standings = [];
        foreach ($round->getPlayers() as $player) {
            $standing = new Standing();
            $standing->userName = $player->getName();
            $standings[$player->getId()] = $standing;
        }

        return $standings;
    }

    /**
     * Sort standing lines
     *
     * @param Standing $a
     * @param Standing $b
     * @return int
     */
    private function sortStandings(Standing $a, Standing $b)
    {
        if ($a->won != $b->won) {
            return $a->won > $b->won ? -1 : 1;
        } elseif ($a->played != $b->played) {
            return $a->played < $b->played ? -1 : 1;
        } elseif ($a->goalDifference != $b->goalDifference) {
            return $a->goalDifference > $b->goalDifference ? -1 : 1;
        } elseif ($a->scored != $b->scored) {
            return $a->scored > $b->scored ? -1 : 1;
        } else {
            return $a->userName < $b->userName ? -1 : 1;
        }
    }
}