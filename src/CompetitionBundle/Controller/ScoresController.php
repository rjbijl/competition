<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
use CompetitionBundle\Form\Handler\ScoreGridHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ScoresController extends Controller
{

    /**
     * @Route("/{date}", defaults={"date" = null}, requirements={"date" = "\d+"})
     * @Template
     * @ParamConverter("date", options={"format": "Ymd"})
     * @Method({"GET"})
     *
     * @param \DateTime $date
     * @return array
     */
    public function indexAction(\DateTime $date = null)
    {
        $date = $date ?: new \DateTime();
        $matches = $this->getDoctrine()->getRepository(Match::class)->findByDate($date);

        return [
            'players' => $this->getDoctrine()->getRepository(Player::class)->findAll(),
            'matches' => $this->parseMatchesForGrid($matches),
            'standings' => $this->get('competition.standings.calculator')->calculate($matches),
            'date' => $date,
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
