<?php

// Nos SUPERTYPES

interface SujetInterface
{
    public function attacher(ObservateurInterface $observateur): void;
    public function detacher(ObservateurInterface $observateur): void;
    public function notifier(): void;
}

interface ObservateurInterface
{
    public function mettreAJour(SujetInterface $sujet, array $donnees): void;
}

// Les classes concrètes
class Sujet implements SujetInterface
{   
    protected array $observateurs;
    
    protected string $nouvelles;
    
    protected string $nom;
    
    public function __construct(string $nom)
    {
        $this->nom = $nom;
    }  
    
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
            $observateur->mettreAJour($this, ['nouvelles' => $this->nouvelles]);
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
    
    public function donnerNom(): string 
    {
        return $this->nom;
    }
}

class Observateur implements ObservateurInterface
{
    protected string $dernieresNouvelles;
    
    protected string $nom;
    
    public function __construct(string $nom)
    {
        $this->nom = $nom;
    }  
    
    public function mettreAJour(SujetInterface $sujet, array $donnees): void
    {
        echo 'Observateur ' . $this->donnerNom() . PHP_EOL;
        echo "\tLes nouvelles en provenance du sujet " . $sujet->donnerNom();
        echo " : " . $donnees['nouvelles'] . PHP_EOL;
        $this->dernieresNouvelles = $donnees['nouvelles'];
    }
    
    public function donnerNom(): string 
    {
        return $this->nom;
    }
}

// un sujet
$sujet = new Sujet('S1');

// N observateurs
$observateur = new Observateur('O1');
$autreObservateur = new Observateur('O2');
$troisiemeObservateur = new Observateur('O3');

// On ajoute les observateurs au sujet
$sujet->attacher($observateur);
$sujet->attacher($autreObservateur);
$sujet->attacher($troisiemeObservateur);

// On modifie l'état du sujet
$sujet->mettreAJourLesNouvelles('Naissance de Yun le panda au zoo de Singapour');
