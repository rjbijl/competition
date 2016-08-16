<?php

namespace CompetitionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CompetitionBundle\Entity\Player
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 *
 * @ORM\Table(name="player")
 * @ORM\Entity
 */
class Player
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
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CompetitionBundle\Entity\Match", mappedBy="homePlayer")
     */
    private $homeMatches;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CompetitionBundle\Entity\Match", mappedBy="awayPlayer")
     */
    private $awayMatches;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="CompetitionBundle\Entity\Round", mappedBy="players")
     */
    private $rounds;

    /**
     * Player constructor.
     */
    public function __construct()
    {
        $this->homeMatches = new ArrayCollection();
        $this->awayMatches = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

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
     * Getter for name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter for name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Getter for homeMatches
     *
     * @return ArrayCollection
     */
    public function getHomeMatches()
    {
        return $this->homeMatches;
    }

    /**
     * Setter for homeMatches
     *
     * @param ArrayCollection $homeMatches
     * @return self
     */
    public function setHomeMatches(ArrayCollection $homeMatches)
    {
        $this->homeMatches = $homeMatches;
        return $this;
    }

    /**
     * Adder for homeMatches
     *
     * @param Match $homeMatch
     * @return self
     */
    public function addHomeMatch(Match $homeMatch)
    {
        if (!$this->homeMatches->contains($homeMatch)) {
            $this->homeMatches->add($homeMatch);
            $homeMatch->setHomePlayer($this);
        }
        
        return $this;
    }

    /**
     * Remover for homeMatches
     *
     * @param Match $homeMatch
     * @return self
     */
    public function removeHomeMatch(Match $homeMatch)
    {
        if ($this->homeMatches->contains($homeMatch)) {
            $this->homeMatches->removeElement($homeMatch);
            $homeMatch->setHomePlayer(null);
        }
        
        return $this;
    }
    
    /**
     * Getter for awayMatches
     *
     * @return ArrayCollection
     */
    public function getAwayMatches()
    {
        return $this->awayMatches;
    }

    /**
     * Setter for awayMatches
     *
     * @param ArrayCollection $awayMatches
     * @return self
     */
    public function setAwayMatches(ArrayCollection $awayMatches)
    {
        $this->awayMatches = $awayMatches;
        return $this;
    }

    /**
     * Adder for awayMatches
     *
     * @param Match $awayMatch
     * @return self
     */
    public function addAwayMatch(Match $awayMatch)
    {
        if (!$this->awayMatches->contains($awayMatch)) {
            $this->awayMatches->add($awayMatch);
            $awayMatch->setAwayPlayer($this);
        }
        
        return $this;
    }

    /**
     * Remover for awayMatches
     *
     * @param Match $awayMatch
     * @return self
     */
    public function removeAwayMatch(Match $awayMatch)
    {
        if ($this->awayMatches->contains($awayMatch)) {
            $this->awayMatches->removeElement($awayMatch);
            $awayMatch->setAwayPlayer(null);
        }
        
        return $this;
    }

    /**
     * Getter for rounds
     *
     * @return ArrayCollection
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * Setter for rounds
     *
     * @param ArrayCollection $rounds
     * @return self
     */
    public function setRounds(ArrayCollection $rounds)
    {
        $this->rounds = $rounds;
        return $this;
    }

    /**
     * Adder for rounds
     *
     * @param Round $round
     * @return self
     */
    public function addRound(Round $round)
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds->add($round);
            $round->addPlayer($this);
        };

        return $this;
    }

    /**
     * Remover for rounds
     *
     * @param Round $round
     * @return self
     */
    public function removeRound(Round $round)
    {
        if ($this->rounds->contains($round)) {
            $this->rounds->removeElement($round);
            $round->removePlayer($this);
        };

        return $this;
    }
}
