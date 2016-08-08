<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
use CompetitionBundle\Model\Standing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        $parsedMatches = [];
        /** @var Match $match */
        $date = new \DateTime('2016-08-05');
        $matches = $this->getDoctrine()->getRepository(Match::class)->findByDate($date);
        $standings = $this->parseStandings($matches);
        
        foreach ($this->getDoctrine()->getRepository(Match::class)->findByDate($date) as $match) {
            $parsedMatches[$match->getHomePlayer()->getId()][$match->getAwayPlayer()->getId()] = sprintf(
                '%d - %d', $match->getHomeScore(), $match->getAwayScore()
            );
        };

        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();

        return [
            'players' => $this->getDoctrine()->getRepository(Player::class)->findAll(),
            'matches' => $parsedMatches,
            'standings' => $this->parseStandings($matches),
            'date' => $date,
        ];
    }

    /**
     * @param Match[] $matches
     */
    public function parseStandings($matches)
    {
        $standings = [];
        foreach ($matches as $match) {
            $winner = $match->getHomeScore() > $match->getAwayScore() ? $match->getHomePlayer() : $match->getAwayPlayer();
            $loser = $match->getHomeScore() > $match->getAwayScore() ? $match->getAwayPlayer() : $match->getHomePlayer();
            
            if (!isset($standings[$winner->getId()])) {
                $standing = new Standing();
                $standing->userName = $winner->getName();
                $standings[$winner->getId()] = $standing;
            } else{
                $standing = $standings[$winner->getId()];
            } 
            
            $standing->played += 1;
            $standing->won += 1;
            $standing->scored += max($match->getHomeScore(), $match->getAwayScore());
            $standing->conceded += min($match->getHomeScore(), $match->getAwayScore());
            $standing->goalDifference = $standing->scored - $standing->conceded;

            if (!isset($standings[$loser->getId()])) {
                $standing = new Standing();
                $standing->userName = $loser->getName();
                $standings[$loser->getId()] = $standing;
            } else{
                $standing = $standings[$loser->getId()];
            } 
            
            $standing->played += 1;
            $standing->lost += 1;
            $standing->scored += min($match->getHomeScore(), $match->getAwayScore());
            $standing->conceded += max($match->getHomeScore(), $match->getAwayScore());
            $standing->goalDifference = $standing->scored - $standing->conceded;
        }

        uasort($standings, function(Standing $a, Standing $b) {
            if ($a->won != $b->won) {
                return $a->won > $b->won ? -1 : 1;
            } elseif ($a->played != $b->played) {
                return $a->played < $b->played ? -1 : 1;
            } elseif ($a->goalDifference != $b->goalDifference) {
                return $a->goalDifference > $b->goalDifference ? -1 : 1;
            } elseif ($a->scored != $b->scored) {
                return $a->scored > $b->scored ? -1 : 1;
            } else {
                return $a->userName > $b->userName ? -1 : 1;
            }
        });

        return $standings;
    }
}
