<?php
//- le récepteur : Lampe (on l’allume et on l’éteint...deux commandes, donc !)
//- l’invocateur : Telecommande (on lui injecte des commandes) et son setter changerCommande

interface MettableSousTensionInterface
{
    public function allumer(): void;
    public function eteindre(): void;
}

class Lampe implements MettableSousTensionInterface
{
    public function allumer(): void
    {
        echo 'Lampe allumé' . PHP_EOL;
    }
    public function eteindre(): void
    {
        echo 'Lampe éteinte' . PHP_EOL;
    }
}

// les commandes
abstract class AbstractCommande
{
    protected MettableSousTensionInterface $object;

    public function __construct(MettableSousTensionInterface $object)
    {
        $this->object = $object;
    }
    
    abstract public function executer(): void;
}

class AllumerCommande extends AbstractCommande
{
    public function executer(): void
    {
        $this->object->allumer();
    }
}

class EteindreCommande extends AbstractCommande
{
    public function executer(): void
    {
        $this->object->eteindre();
    }
}

// l'invocateur
class Telecommande
{
    protected AbstractCommande $commande;

    public function __construct(AbstractCommande $commande)
    {
        $this->commande = $commande;
    }

    public function executerCommande(): void
    {
        $this->commande->executer();
    }

    public function changerCommande(AbstractCommande $commande): void
    {
        $this->commande = $commande;
    }
}

// le client demande une requête
$lampe = new Lampe(); // récepteur
$commande = new AllumerCommande($lampe); // commande

// l'invocateur l'exécute
$telecommande = new Telecommande($commande); // invocateur
$telecommande->executerCommande();

$commande = new EteindreCommande($lampe);
$telecommande->changerCommande($commande);
$telecommande->executerCommande();