<?php

// L'abstraction de nos stratégies
interface StrategieInterface
{
    public function reagir(PersonneInterface $personne): string;
}

// Nos stratégies concrètes
class Enerve implements StrategieInterface
{
    public function reagir(PersonneInterface $personne): string
    {
        return strtoupper($personne->donnerPhrase() . ' !!!') . PHP_EOL;
    }
}

class Geek implements StrategieInterface
{
    public function reagir(PersonneInterface $personne): string
    {
        return str_replace('o', '0', $personne->donnerPhrase()) . PHP_EOL;
    }
}

class Jovial implements StrategieInterface
{
    public function reagir(PersonneInterface $personne): string
    {
        return ucfirst($personne->donnerPhrase()) . ' :)'.PHP_EOL;
    }
}

class Contexte
{
    protected StrategieInterface $strategie;

    public function __construct(StrategieInterface $strategie)
    {
        $this->strategie = $strategie;
    }
    
    public function exprimerReaction(PersonneInterface $personne): string
    {
      return $this->strategie->reagir($personne);
    }
}

interface PersonneInterface
{
    public function donnerPhrase(): string;
}

class Personne implements PersonneInterface
{
    public function donnerPhrase(): string
    {
        return 'bonjour tout le monde';
    }
}

// Le code client
$personne = new Personne();
$humeurs = [new Enerve(), new Geek(), new Jovial()];

foreach ($humeurs as $humeur) {
    $contexte = new Contexte($humeur);
    echo $contexte->exprimerReaction($personne);
}
