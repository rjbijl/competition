<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Match;
use CompetitionBundle\Entity\Player;
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
     * @param null $date
     * @return array
     */
    public function indexAction(Request $request, \DateTime $date = null)
    {
        $date = $date ?: new \DateTime();

        $parsedMatches = [];
        /** @var Match $match */
        $matches = $this->getDoctrine()->getRepository(Match::class)->findByDate($date);

        foreach ($this->getDoctrine()->getRepository(Match::class)->findByDate($date) as $match) {
            $parsedMatches[$match->getHomePlayer()->getId()][$match->getAwayPlayer()->getId()] = sprintf(
                '%d-%d', $match->getHomeScore(), $match->getAwayScore()
            );
        };

        if ($request->isMethod('post')) {
            $this->handleForm($request, $date);
        }
        
        return [
            'players' => $this->getDoctrine()->getRepository(Player::class)->findAll(),
            'matches' => $parsedMatches,
            'standings' => $this->get('competition.standings.calculator')->calculate($matches),
            'date' => $date,
        ];
    }

    /**
     * @param Request $request
     * @param \DateTime $date
     * @return bool
     */
    private function handleForm(Request $request, \DateTime $date)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($request->get('result') as $homePlayerId => $result) {
            $homePlayer = $em->find(Player::class, $homePlayerId);
            foreach ($result as $awayPlayerId => $score) {
                if (strlen($score) > 0) {
                    $awayPlayer = $em->find(Player::class, $awayPlayerId);
                    list($homeScore, $awayScore) = explode('-', $score);

                    if (!$match = $em->getRepository(Match::class)->findOneBy([
                        'date' => $date,
                        'homePlayer' => $homePlayer,
                        'awayPlayer' => $awayPlayer,
                    ])) {
                        $match = new Match();
                        $match->setHomePlayer($homePlayer);
                        $match->setAwayPlayer($awayPlayer);
                        $match->setDate($date);
                        $em->persist($match);
                    }

                    $match->setHomeScore($homeScore);
                    $match->setAwayScore($awayScore);
                }
            }
        };

        try {
            $em->flush();
            return true;
        } catch (\Exception $e) {
            dump($e);die;
        }
    }
}
