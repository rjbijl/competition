<?php

namespace CompetitionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompetitionBundle\Entity\Match
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 *
 * @ORM\Table(name="mtch")
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
     * @var Round
     * @ORM\ManyToOne(targetEntity="CompetitionBundle\Entity\Round", inversedBy="matches")
     * @ORM\JoinColumn(name="round_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $round;

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
    public function setHomePlayer(Player $homePlayer = null)
    {
        if ($this->homePlayer !== $homePlayer) {
            $this->homePlayer = $homePlayer;

            if (null === $homePlayer) {
                $homePlayer->removeHomeMatch($this);
            } else {
                $homePlayer->addHomeMatch($this);
            }
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
    public function setAwayPlayer(Player $awayPlayer = null)
    {
        if ($this->awayPlayer !== $awayPlayer) {
            $this->awayPlayer = $awayPlayer;

            if (null === $awayPlayer) {
                $awayPlayer->removeAwayMatch($this);
            } else {
                $awayPlayer->addAwayMatch($this);
            }
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

    /**
     * Getter for round
     *
     * @return Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Setter for round
     *
     * @param Round $round
     * @return self
     */
    public function setRound($round = null)
    {
        if ($this->round !== $round) {
            $this->round = $round;
            if (null === $round) {
                $round->removeMatch($this);
            } else {
                $round->addMatch($this);
            }
        };

        return $this;
    }
}
