<?php

namespace LaraTrell\Src;


class SearcherDoingCard
{

    const NAME_LIST_DOING_IN_TRELLO = 'Doing';

    private $wrapper;
    private $workingBoards;

    public function __construct(TrelloWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
        $this->workingBoards = collect();
    }

    public function getWorkingBoards()
    {
        return $this->workingBoards;
    }

    public function searchDoingCard()
    {

        try {
            $boards = $this->wrapper->obtainBoardsFromUser();

            foreach ($boards as $board) {

                if ($this->boardIsOpen($board)) {
                    $this->processBoard($board);
                    //$this->simularteBoard($board);
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

    }

    /*
     * fixme: remove this aux method

    private function simularteBoard ($board){

        $cards = collect();

        $card = [
            'shortUrl' => 'http://www.google.es',
            'name' => 'Google'
        ];

        $cards->push($card);

        $card = [
            'shortUrl' => 'http://www.hoy.es',
            'name' => 'Hoy'
        ];

        $cards->push($card);

        $board = app(BoardDoing::class, ['name' => $board['name'], 'cards'=> $cards]);
        $this->workingBoards->push($board);

    }
    */

    private function boardIsOpen($board)
    {
        return ! $board['closed'];
    }


    private function processBoard($board)
    {

        $boardLists = $this->wrapper->obtainListsOfBoard($board['id']);

        $doingList = $this->obtainListDoing($boardLists);

        $boardHasDoingList = ! is_null($doingList);

        if ($boardHasDoingList) {

            $this->createBoardDoing($board, $doingList);

        }

    }

    private function obtainListDoing($listsCard)
    {

        return $listsCard->filter(function ($list) {
            return $list['name'] == self::NAME_LIST_DOING_IN_TRELLO;
        })->first();

    }

    private function createBoardDoing($board, $listDoing)
    {

        $doingCards = $this->wrapper->obtainCardsFromCardList($listDoing['id']);

        $boardDoing = app(BoardDoing::class, ['name' => $board['name'], 'cards' => $doingCards]);

        $this->workingBoards->push($boardDoing);

    }


}