<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
use CompetitionBundle\Entity\Round;
use CompetitionBundle\Form\Handler\ScoreGridHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ScoresController
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 */
class ScoresController extends Controller
{
    /**
     * @Route("/{round}", defaults={"round" = null}, requirements={"round" = "\d+"})
     * @Template
     * @Method({"GET"})
     *
     * @param \DateTime $date
     * @return array
     */
    public function indexAction($date = null)
    {
        $date = $date ?: date('Ymd');

        if (!$round = $this->getDoctrine()->getRepository(Round::class)->findOneByName($date)) {
            $round = new Round();
            $round->setName($date);

            foreach ($this->getDoctrine()->getRepository(Player::class)->findAll() as $player) {
                $round->addPlayer($player);
            }
        }

        return [
            'round' => $round,
            'players' => $round->getPlayers(),
            'matches' => $this->parseMatchesForGrid($round),
            'standings' => $this->get('competition.standings.calculator')->calculate($round),
            'date' => new \DateTime($date),
        ];
    }

    /**
     * @Route("/save")
     * @Template
     * @Method({"POST"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction(Request $request)
    {
        /** @var ScoreGridHandler $formHandler */
        $formHandler = $this->get('competition.form.handler.score_grid');
        if ($formHandler->handle($request)) {
            $this->addFlash('info', 'Opslaan gelukt');
        } else {
            $this->addFlash('info', 'Error');
        };

        return $this->redirectToRoute('competition_scores_index');
    }

    /**
     * Parse match so they can be easily rendered in the grid
     *
     * @param array $matches
     * @return array
     */
    private function parseMatchesForGrid(Round $round)
    {
        $parsedMatches = [];

        /** @var Match $match */
        foreach ($round->getMatches() as $match) {
            $parsedMatches[$match->getHomePlayer()->getId()][$match->getAwayPlayer()->getId()] = sprintf(
                '%d-%d', $match->getHomeScore(), $match->getAwayScore()
            );
        };

        return $parsedMatches;
    }
}
