<?php

namespace CompetitionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * CompetitionBundle\Entity\Round
 *
 * @author Robert-Jan Bijl <rjbijl@gmail.com>
 * @ORM\Table(name="rnd")
 * @ORM\Entity
 */
class Round
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
     * @ORM\ManyToMany(targetEntity="CompetitionBundle\Entity\Player", inversedBy="rounds")
     * @ORM\JoinTable(
     *     name="round_players",
     *     joinColumns={@ORM\JoinColumn(name="round_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="player_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $players;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CompetitionBundle\Entity\Match", mappedBy="round")
     */
    private $matches;

    /**
     * Round constructor.
     */
    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->matches = new ArrayCollection();
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
     * Getter for players
     *
     * @return ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Setter for players
     *
     * @param ArrayCollection $players
     * @return self
     */
    public function setPlayers(ArrayCollection $players)
    {
        $this->players = $players;
        return $this;
    }

    /**
     * Adder for players
     *
     * @param Player $player
     * @return self
     */
    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->addRound($this);
        };

        return $this;
    }

    /**
     * Remover for players
     *
     * @param Player $player
     * @return self
     */
    public function removePlayer(Player $player)
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            $player->removeRound($this);
        };

        return $this;
    }

    /**
     * Getter for matches
     *
     * @return ArrayCollection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Setter for matches
     *
     * @param ArrayCollection $matches
     * @return self
     */
    public function setMatches(ArrayCollection $matches)
    {
        $this->matches = $matches;
        return $this;
    }

    /**
     * Adder for matches
     *
     * @param Match $match
     * @return self
     */
    public function addMatch(Match $match)
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setRound($this);
        };

        return $this;
    }

    /**
     * Remover for matches
     *
     * @param Match $match
     * @return self
     */
    public function removeMatch(Match $match)
    {
        if ($this->matches->contains($match)) {
            $this->matches->removeElement($match);
            $match->setRound(null);
        };

        return $this;
    }
}