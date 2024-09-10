<?php
class FFVBScoreParser
{
    private DOMDocument|null $DOMContent;
    private string $saison;
    private string $codent;
    private string $poule;
    private string $equipe;


    public const BASE_URL = 'https://www.ffvbbeach.org/ffvbapp/resu/vbspo_calendrier.php';
    public const DEFAULT_CALEND = 'COMPLET';

    public function __construct(string $saison, string $codent, string $poule, string $equipe)
    {
        $this->DOMContent = null;
        $this->saison = htmlspecialchars($saison);
        $this->codent = htmlspecialchars($codent);
        $this->poule = htmlspecialchars($poule);
        $this->equipe = htmlspecialchars($equipe);
    }

    private function generateUrl(): string
    {
        $url = self::BASE_URL;

        $saison = $this->saison;
        $codent = $this->codent;
        $poule = $this->poule;
        $equipe = $this->equipe;

        $url .= "?saison={$saison}&codent={$codent}&poule={$poule}&calend={self::DEFAULT_CALEND}&equipe={$equipe}";

        return $url;
    }

    public function isContentLoaded(): bool
    {
        return $this->DOMContent !== null;
    }

    public function downloadContent(): void
    {
        if ($this->isContentLoaded()) return;

        try {
            $url = $this->generateUrl();
            $html = file_get_contents($url);
            $this->DOMContent = new DOMDocument();
            @$this->DOMContent->loadHTML($html);
        } catch (\Exception $e) {
            echo "Erreur lors de la récupération du contenu : " . $e->getMessage() . "\n";
        }
    }

    public function getSaison(): string
    {
        return $this->saison;
    }

    public function getCodent(): string
    {
        return $this->codent;
    }

    public function getPoule(): string
    {
        return $this->poule;
    }

    public function getEquipe(): string
    {
        return $this->equipe;
    }

    public function getContent(): string
    {
        if (!$this->isContentLoaded()) {
            $this->downloadContent();
        }
        return $this->DOMContent->saveHTML();
    }


    public function getClassement(): array
    {
        $Classement = array();

        if (!$this->isContentLoaded()) {
            $this->downloadContent();
        }
        $tables = $this->DOMContent->getElementsByTagName("table");

        $table3  = $tables[2];
        $rows = $table3->getElementsByTagName("tr");

        $isclassement = true;
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');

            if ($cells->length == 1) {
                $isclassement = false;
            }

            if ($isclassement && (int)$cells[3]->nodeValue != 0) {
                $num = $cells[0]->nodeValue; //place
                $nom = $cells[1]->nodeValue; //nom
                $points = (int)$cells[2]->nodeValue; //points
                $jou = (int)$cells[3]->nodeValue; //nb match joué
                $gag = (int)$cells[4]->nodeValue; //nb match gagné
                $per = (int)$cells[5]->nodeValue; //nb match perdu
                $f = (int)$cells[6]->nodeValue; //nb match Forfait
                $CoeffS = $cells[15]->nodeValue; //Coefficient set
                $CoeffP = $cells[18]->nodeValue; //Coefficient point

                $Classement[] = array('num' => $num, 'nom' => $nom,  'points' => $points, 'jou' => $jou, 'gag' => $gag, 'per' => $per, 'f' => $f, 'CoeffS' => $CoeffS, 'CoeffP' => $CoeffP);
            }
        }
        return $Classement;
    }

    public function getGames(): array
    {
        $games = array();

        if (!$this->isContentLoaded()) {
            $this->downloadContent();
        }
        $tables = $this->DOMContent->getElementsByTagName("table");

        $table3  = $tables[3];
        $rows = $table3->getElementsByTagName("tr");
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length > 1 ) {
                $codematch = $cells[0]->nodeValue; //codematch
                $date = $cells[1]->nodeValue; //date
                $heure = $cells[2]->nodeValue; //heure
                $team1 = $cells[3]->nodeValue; //equipe 1 ( domicile)
                $team2 = $cells[5]->nodeValue; //equipe 2 ( deplacement)
                $total_set1 = $cells[6]->nodeValue; //nb set eq 1
                $total_set2 = $cells[7]->nodeValue; //nb set eq 2
                $scores = $cells[8]->nodeValue; //score
                $points = $cells[9]->nodeValue; //points

                $games[] = array('codematch' => $codematch,  'date' => $date, 'heure' => $heure, 'team1' => $team1, 'team2' => $team2, 'total_set1' => $total_set1, 'total_set2' => $total_set2, 'scores' => $scores, 'points' => $points);
            }
        }
        return $games;
    }
}
