<?php

namespace CompetitionBundle\Entity;

use BloxzBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use YouChoozBundle\Entity\Basic\Category;
use YouChoozBundle\Entity\Basic\EducationLevel;
use YouChoozBundle\Entity\Basic\EducationType;
use YouChoozBundle\Entity\Basic\Method;
use YouChoozBundle\Entity\Basic\Sector;
use YouChoozBundle\Entity\Education\AdmissionCriterion;
use YouChoozBundle\Entity\Education\Alias;
use YouChoozBundle\Entity\Education\EducationInstituteType;
use YouChoozBundle\Entity\Education\Page;
use YouChoozBundle\Entity\Education\Possibility;
use YouChoozBundle\Entity\Education\Video;

/**
 * CompetitionBundle\Entity\Match
 *
 * @ORM\Table(name="mtch")
 * )
 * @ORM\Entity
 */
class Match
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="CompetitionBundle\Entity\Player", inversedBy="homeMatches")
     * @ORM\JoinColumn(name="home_player_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $homePlayer;

    /**
     * @var Player

     * @ORM\ManyToOne(targetEntity="CompetitionBundle\Entity\Player", inversedBy="awayMatches")
     * @ORM\JoinColumn(name="away_player_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $awayPlayer;

    /**
     * @var int
     *
     * @ORM\Column(name="home_score", type="integer")
     */
    private $homeScore;
    
    /**
     * @var int
     *
     * @ORM\Column(name="away_score", type="integer")
     */
    private $awayScore;
    
    /**
     * Getter for id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Setter for date
     *
     * @param \DateTime $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Getter for homePlayer
     *
     * @return Player
     */
    public function getHomePlayer()
    {
        return $this->homePlayer;
    }

    /**
     * Setter for homePlayer
     *
     * @param Player $homePlayer
     * @return self
     */
    public function setHomePlayer(Player $homePlayer)
    {
        if ($this->homePlayer !== $homePlayer) {
            $this->homePlayer = $homePlayer;
            $homePlayer->addMatch($this);
        };

        return $this;
    }

    /**
     * Getter for awayPlayer
     *
     * @return Player
     */
    public function getAwayPlayer()
    {
        return $this->awayPlayer;
    }

    /**
     * Setter for awayPlayer
     *
     * @param Player $awayPlayer
     * @return self
     */
    public function setAwayPlayer(Player $awayPlayer)
    {
        if ($this->awayPlayer !== $awayPlayer) {
            $this->awayPlayer = $awayPlayer;
            $awayPlayer->addMatch($this);
        };

        return $this;
    }

    /**
     * Getter for homeScore
     *
     * @return int
     */
    public function getHomeScore()
    {
        return $this->homeScore;
    }

    /**
     * Setter for homeScore
     *
     * @param int $homeScore
     * @return self
     */
    public function setHomeScore($homeScore)
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    /**
     * Getter for awayScore
     *
     * @return int
     */
    public function getAwayScore()
    {
        return $this->awayScore;
    }

    /**
     * Setter for awayScore
     *
     * @param int $awayScore
     * @return self
     */
    public function setAwayScore($awayScore)
    {
        $this->awayScore = $awayScore;
        return $this;
    }
}
