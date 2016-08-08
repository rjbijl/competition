<?php

namespace CompetitionBundle\Model;

/**
 * CompetitionBundle\Model\Standing
 *
 * @author Robert-Jan Bijl <robert-jan@prezent.nl>
 */
class Standing
{
    public $userId;

    public $userName = null;

    public $played = 0;

    public $won = 0;

    public $lost = 0;

    public $scored = 0;

    public $conceded = 0;

    public $goalDifference = 0;
}