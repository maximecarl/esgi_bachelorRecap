<?php

class RencontreSportive
{
    protected string $equipes;

    protected string $score;

    public function __construct(string $equipes, string $score)
    {
        $this->equipes = $equipes;
        $this->score = $score;		
    }

    public function donnerEquipes(): string
    {
        return $this->equipes;
    }

    public function donnerScore(): string
    {
        return $this->score;
    }

    public function changerEquipes(string $equipes): void
    {
        $this->equipes = $equipes;
    }

    public function changerScore(string $score): void
    {
        $this->score = $score;
    }
}

interface ObservateurInterface
{
    public function mettreAJour(SujetInterface $sujet): void;
}

class ParieurFou implements ObservateurInterface
{
    public function __construct(SujetInterface $sujet)
    {
        $sujet->ajouter($this);
    }

    public function mettreAJour(SujetInterface $sujet): void
    {
        echo 'Les derniers résultats : ' . PHP_EOL;
        foreach ($sujet->donnerMatches as $match) {
            echo ($match->donnerEquipes() . ' : ' . $match->donnerScore() . PHP_EOL);
        }
        echo PHP_EOL;
    }
}

interface SujetInterface
{
    public function notifier(): void;
    public function ajouter(ObservateurInterface $observateur): void;
    public function enlever(ObservateurInterface $observateur): void;
}

class PariSportif implements SujetInterface
{
    protected ?array $observateurs = null;

    protected array $matches;

    public function ajouter(ObservateurInterface $observateur): void
    {
        //TODO: à réaliser
    }

    public function enlever(ObservateurInterface $observateur): void
    {
        if (!empty($this->observateurs)) {
            $i = array_search($observateur, $this->observateurs);
            if ($i !== false) {
                unset($this->observateurs[$i]);
            }
        }
    }

    public function donnerMatches(): array
    {
        return $this->matches;
    }

    public function changerMatches(array $matches)
    {
        $this->matches = $matches;
        $this->notifier();
    }

    public function notifier(): void
    {
        //TODO: à réaliser
    }
}

$sujet = new PariSportif();
$observateur = new ParieurFou($sujet);
$match = new RencontreSportive("Dinamo Zagreb - Benfica", "8-0");

$autreMatch = clone $match;
$autreMatch->changerEquipes("Lyon-ASSE");
$autreMatch->changerScore("4-1");

$sujet->changerMatches([$match, $autreMatch]);
