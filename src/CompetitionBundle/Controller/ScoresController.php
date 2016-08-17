<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Round;
use CompetitionBundle\Form\Handler\ScoreGridHandler;
use CompetitionBundle\Form\Type\RoundType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @param string $round
     * @return array
     */
    public function indexAction($round = null)
    {
        $date = $round ?: date('Ymd');

        $existing = true;
        if (!$round = $this->getDoctrine()->getRepository(Round::class)->findOneByName($date)) {
            $existing = false;
            $round = new Round();
            $round->setName($date);
        }

        $roundForm = $this->createForm(
            RoundType::class,
            $round,
            [
                'create' => !$existing,
                'action' => $existing
                    ? $this->get('router')->generate('competition_round_edit', ['round' => $round->getId()])
                    : $this->get('router')->generate('competition_round_create'),
            ]
        );

        return [
            'round' => $round,
            'matches' => $this->parseMatchesForGrid($round),
            'standings' => $this->get('competition.standings.calculator')->calculate($round),
            'date' => new \DateTime($date),
            'roundForm' => $roundForm->createView(),
        ];
    }

    /**
     * @Route("/save-scores")
     * @Template
     * @Method({"POST"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveScoresAction(Request $request)
    {
        /** @var ScoreGridHandler $formHandler */
        $formHandler = $this->get('competition.form.handler.score_grid');
        if ($formHandler->handle($request)) {
            $this->addFlash('info', 'scores.form.save.success');
        } else {
            $this->addFlash('error', 'scores.form.save.error');
        };

        return $this->redirectToRoute('competition_scores_index');
    }

    /**
     * Parse match so they can be easily rendered in the grid
     *
     * @param Round $round
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
