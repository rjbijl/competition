<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
use CompetitionBundle\Form\Handler\ScoreGridHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{date}", defaults={"date" = null})
     * @Template
     * @ParamConverter("date", options={"format": "Ymd"})
     * 
     * @param Request $request
     * @param \DateTime $date
     * @return array
     */
    public function indexAction(Request $request, \DateTime $date = null)
    {
        $date = $date ?: new \DateTime();

        if ($request->isMethod('post')) {
            /** @var ScoreGridHandler $formHandler */
            $formHandler = $this->get('competition.form.handler.score_grid');
            $formHandler->handle($request);
        }

        $matches = $this->getDoctrine()->getRepository(Match::class)->findByDate($date);

        return [
            'players' => $this->getDoctrine()->getRepository(Player::class)->findAll(),
            'matches' => $this->parseMatchesForGrid($matches),
            'standings' => $this->get('competition.standings.calculator')->calculate($matches),
            'date' => $date,
        ];
    }

    /**
     * Parse match so they can be easily rendered in the grid
     *
     * @param array $matches
     * @return array
     */
    private function parseMatchesForGrid(array $matches = [])
    {
        $parsedMatches = [];

        /** @var Match $match */
        foreach ($matches as $match) {
            $parsedMatches[$match->getHomePlayer()->getId()][$match->getAwayPlayer()->getId()] = sprintf(
                '%d-%d', $match->getHomeScore(), $match->getAwayScore()
            );
        };

        return $parsedMatches;
    }
}
