<?php

// Les SUPERTYPES des différentes sous-parties de notre système (un ordinateur)

interface MettableSousTensionInterface
{
    public function mettreSousTension(): void;
}

interface CarteMereInterface extends MettableSousTensionInterface
{
    public function executerBIOS(): void;
}

interface EcranInterface extends MettableSousTensionInterface
{
    public function modifierLuminosite(): void;
}

interface DisqueDurInterface extends MettableSousTensionInterface
{
    public function amorcer(): void;
}

interface OSInterface
{
    public function donnerNumeroSerie(): void;
    public function charger(): void;
}

// Les classes concrètes des différentes sous-parties de notre système (un ordinateur)

class MaCarteMere implements CarteMereInterface
{
    public function mettreSousTension(): void
    {
        echo "CM sous tension" . PHP_EOL;
    }
    
    public function executerBIOS(): void
    {
        echo "Exécution du BIOS" . PHP_EOL;
    }
}

class MonEcran implements EcranInterface
{
    public function mettreSousTension(): void
    {
        echo "Ecran sous tension" . PHP_EOL;
    }
    
    public function modifierLuminosite(): void
    {
        echo "Modification de la luminosité" . PHP_EOL;
    }
    
    // Méthode propre à la classe
    public function afficherInfos(): void
    {
        echo "Affichage des infos" . PHP_EOL;
    }
}

class MonDisqueDur implements DisqueDurInterface
{
    public function mettreSousTension(): void
    {
        echo "DD sous tension" . PHP_EOL;
    }
    
    public function amorcer(): void
    {
        echo "DD amorcé" . PHP_EOL;
    }
}

class MonOS implements OSInterface
{
    public function donnerNumeroSerie(): void
    {
        echo "Mon numéro de série" . PHP_EOL;
    }
    
    public function charger(): void
    {
        echo "Chargement de l'OS" . PHP_EOL;
    }
}

// Classe implémentant le design pattern Façade

class OrdinateurFacade
{   
    protected CarteMereInterface $carteMere;
    
    protected EcranInterface $ecran;
    
    protected DisqueDurInterface $disqueDur;
    
    protected OSInterface $systemeExploitation;
    
    public function __construct(
        CarteMereInterface $carteMere, 
        EcranInterface $ecran, 
        DisqueDurInterface $disqueDur, 
        OSInterface $systemeExploitation
    ) {
        $this->carteMere = $carteMere;
        $this->ecran = $ecran;
        $this->disqueDur = $disqueDur;
        $this->systemeExploitation = $systemeExploitation;
    }
    
    public function mettreEnMarche(): void
    {
        $this->carteMere->mettreSousTension();
        $this->ecran->mettreSousTension();
        $this->carteMere->executerBIOS();
        $this->disqueDur->amorcer();
        $this->ecran->afficherInfos();
        $this->systemeExploitation->charger();
    }
}

// Code du client
$carteMere = new MaCarteMere();
$ecran = new MonEcran();
$disqueDur = new MonDisqueDur();
$systemeExploitation = new MonOS();

// Code utilisé avant l'implémentation de Façade...
// Le client devait savoir ce qui était fait et dans quel ordre -> COUPLAGE
/*
$carteMere->mettreSousTension();
$ecran->mettreSousTension();
$carteMere->executerBIOS();
$disqueDur->amorcer();
$ecran->afficherInfos();
$systemeExploitation->charger();
*/

// Code de la façade
$facade = new OrdinateurFacade($carteMere, $ecran, $disqueDur, $systemeExploitation);
$facade->mettreEnMarche();
