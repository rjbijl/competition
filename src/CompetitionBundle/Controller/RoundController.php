<?php

namespace CompetitionBundle\Controller;

use CompetitionBundle\Entity\Round;
use CompetitionBundle\Form\Type\RoundType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoundController
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 */
class RoundController extends Controller
{
    /**
     * @Route("/create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $round = new Round();
        $roundForm = $this->createForm(RoundType::class, $round, ['create' => true]);
        $roundForm->handleRequest($request);

        $round = $roundForm->getData();
        if ($roundForm->isValid()) {
            $em = $this->getDoctrine()->getManagerForClass(Round::class);
            $em->persist($round);
            $em->flush($round);
            $this->addFlash('info', 'Round opgeslagen');
        } else {
            $this->addFlash('error', 'Round kon niet opgeslagen worden');
            dump($roundForm->getErrors(true));die;
        }

        return $this->redirectToRoute('competition_scores_index', ['round' => $round->getName()]);
    }

    /**
     * @Route("/{round}/save")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Round $round
     * @return RedirectResponse
     */
    public function editAction(Request $request, Round $round)
    {
        $roundForm = $this->createForm(RoundType::class, $round, ['create' => false]);
        $roundForm->handleRequest($request);

        $round = $roundForm->getData();
        if ($roundForm->isValid()) {
            $em = $this->getDoctrine()->getManagerForClass(Round::class);
            $em->persist($round);
            $em->flush($round);
            $this->addFlash('info', 'Round opgeslagen');
        } else {
            $this->addFlash('error', 'Round kon niet opgeslagen worden');
            dump($roundForm->getErrors(true));die;
        }

        return $this->redirectToRoute('competition_scores_index', ['round' => $round->getName()]);
    }
}
