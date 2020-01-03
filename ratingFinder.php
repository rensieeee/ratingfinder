<?php

namespace ratingFinder;

require "vendor/autoload.php";

use PHPHtmlParser\Dom;

class Finder
{

    protected $baseUrl = "http://xaa.dohd.org/knsbframe/spelers.php";

    protected $allLists;

    public function __construct()
    {
        $this->allLists = $this->getAllLists();
    }

    public function getAllLists()
    {
        $dom = new Dom;
        $dom->loadFromUrl($this->baseUrl);
        $select = $dom->getElementsByTag("select")->toArray()[0];
        $dom = new Dom;
        $options = $dom->loadStr($select)->getElementsByTag("option")->toArray();
        $allLists = [];
        foreach ($options as $option) {
            $allLists[] = explode('"', explode('value="', $option->outerHtml)[1])[0];
        }

        return array_reverse($allLists);
    }

    public function getLatestList()
    {
        return $this->allLists[count($this->allLists) - 1];
    }

    protected function constructUrl(string $fedId, string $listId)
    {
        return $this->baseUrl . '?lidnr=' . $fedId . '&listid=' . $listId;
    }

    public function findRating(string $fedId, string $listId)
    {
        if (!in_array($listId, $this->allLists)) {
            return "List not found.";
        }

        $dom = new Dom;
        $dom->loadFromUrl($this->constructUrl($fedId, $listId));
        $playerInfo = $dom->getElementsByClass("spelerinfo");

        $dom = new Dom;
        if ($playerInfo->toArray()[0]->innerHtml) {
            $data = $dom->loadStr($playerInfo->toArray()[0]->innerHtml);
            return explode("</td>", explode("Rating:</td><td>", $data)[1])[0];
        } else {
            return "Player not found.";
        }
    }

    public function findLatestRating(string $fedId)
    {
        return $this->findRating($fedId, $this->getLatestList());
    }

    public function parseResult(string $data)
    {
        switch ($data) {
            case "Player not found.":
                return ["rating" => "", "error" => $data];
                break;
            case "List not found.":
                return ["rating" => "", "error" => $data];
                break;
            default:
                return ["rating" => $data, "error" => ""];
                break;
        }
    }
}

$finder = new Finder();

$fedId = $_GET["knsb"];
$list = null;
if (isset($_GET["list"])) {
    $list = $_GET["list"];
}

$data = "";
$result = [];

if ($fedId) {
    if ($list) {
        $data = $finder->findRating($fedId, $list);
    } else {
        $data = $finder->findLatestRating($fedId);
    }
    $result = $finder->parseResult($data);
} else {
    $result = ["rating" => "", "error" => "No KNSB Id given."];
}

http_response_code(201);
header('Content-Type: application/json');
echo json_encode($result);
