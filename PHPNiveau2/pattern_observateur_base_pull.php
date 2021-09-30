<?php

interface SujetInterface
{
    public function attacher(ObservateurInterface $observateur): void;
    public function detacher(ObservateurInterface $observateur): void;
    public function notifier(): void;
}

interface ObservateurInterface
{
    public function mettreAJour(SujetInterface $sujet): void;
}

class Sujet implements SujetInterface
{
    protected string $nouvelles;
    
    protected array $observateurs;
    
    public function attacher(ObservateurInterface $observateur): void
    {
        $this->observateurs[] = $observateur;
    }
 
    public function detacher(ObservateurInterface $observateur): void
    {
        $key = array_search($observateur, $this->observateurs);
 
        if (false !== $key) {
            unset($this->observateurs[$key]);
        }
    }
 
    public function notifier(): void 
    {
        foreach ($this->observateurs as $observateur) {
            $observateur->mettreAJour($this);
        }
    }

    public function mettreAJourLesNouvelles(string $nouvelles): void 
    {
        $this->nouvelles = $nouvelles;
        $this->notifier();
    }
    
    public function donnerLesNouvelles(): string 
    {
        return $this->nouvelles;
    }
}

class Observateur implements ObservateurInterface
{
    protected $dernieresNouvelles;
    
    public function mettreAJour(SujetInterface $sujet): void
    { 
        $this->dernieresNouvelles = $sujet->donnerLesNouvelles();
    }
}

$sujet = new Sujet();
$observateur = new Observateur();
$autreObservateur = new Observateur();
$sujet->attacher($observateur);
$sujet->attacher($autreObservateur);
$sujet->mettreAJourLesNouvelles("+25% sur le taux d'emploi des jeunes en France !");

var_dump($autreObservateur);
var_dump($observateur);
