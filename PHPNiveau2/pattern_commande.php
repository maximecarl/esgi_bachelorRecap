<?php

// les récepteurs
interface OrdreInterface
{
    public function afficherLigneResume(): void;
}

interface OrdreAchatInterface
{
    public function acheter(): void;
}

interface OrdreVenteInterface
{
    public function vendre(): void;
}

abstract class AbstractOrdre
{
    protected float $montant;

    public function __construct(float $montant)
    {
        $this->montant = $montant;
    }
}

class OrdreAchat extends AbstractOrdre implements OrdreInterface, OrdreAchatInterface
{
    public function acheter(): void
    {
        echo 'Achat pour ' . $this->montant . ' euros' . PHP_EOL;
    }

    public function afficherLigneResume(): void
    {
        echo 'ORD-ACH/' . $this->montant . PHP_EOL;
    }
}

class OrdreVente extends AbstractOrdre implements OrdreInterface, OrdreVenteInterface
{
    public function vendre(): void
    {
        echo 'Vente pour ' . $this->montant . ' euros' . PHP_EOL;
    }

    public function afficherLigneResume(): void
    {
        echo 'ORD-VTE/' . $this->montant . PHP_EOL;
    }
}

// les commandes

abstract class AbstractCommande
{
    protected OrdreInterface $ordre;

    public function __construct(OrdreInterface $ordre)
    {
        $this->ordre = $ordre;
    }
    
    abstract public function executer(): void;
}

class AcheterStockCommande extends AbstractCommande
{
    public function executer(): void
    {
        $this->ordre->acheter();
        $this->ordre->afficherLigneResume();
    }
}

class VendreStockCommande extends AbstractCommande
{
    public function executer(): void
    {
        $this->ordre->vendre();
        $this->ordre->afficherLigneResume();
    }
}

// l'invocateur
class Trader
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
$ordre = new OrdreAchat(120); // récepteur
$commande = new AcheterStockCommande($ordre); // commande

// l'invocateur l'exécute
$trader = new Trader($commande); // invocateur
$trader->executerCommande();

$ordre = new OrdreVente(600);
$commande = new VendreStockCommande($ordre);
$trader->changerCommande($commande);
$trader->executerCommande();
