<?php


namespace LaraTrell\Src;


use Illuminate\Support\Collection;

class BoardDoing
{

    private $originalData = [];
    private $name;
    private $cardsDoing;

    public function __construct($name, Collection $cards)
    {
        $this->name = $name;
        $this->cardsDoing = $cards;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getCardsDoing()
    {
        return $this->cardsDoing;
    }

}