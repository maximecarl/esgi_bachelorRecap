<?php

interface EtatInterface
{
    public function insererJeton(): void;
    public function ejecterJeton(): void;
    public function tournerPoignee(): void;
    public function delivrer(): void;
}

abstract class EtatAbstract
{
    protected $distributeur;

    public function __construct(DistributeurInterface $distributeur){
        $this->distributeur = $distributeur;
    }
}

class EtatSansJeton extends EtatAbstract implements EtatInterface
{
    public function insererJeton(): void{
        echo "Merci, vous pouvez à présent tourner la poignée".PHP_EOL;
        $this->distributeur->changerEtat($this->distributeur->etatAvecJeton());
    }
    
    public function ejecterJeton(): void{
        echo "Inutile d'insister, il n'y a pas de jeton".PHP_EOL;
    }
    
    public function tournerPoignee(): void{
        echo "Vous n'avez pas inséré de jeton".PHP_EOL;
    }
    
    public function delivrer(): void{
        echo "Pas de produit à délivrer".PHP_EOL;
    }
}

class EtatAvecJeton extends EtatAbstract implements EtatInterface
{
    public function insererJeton(): void{
        echo "Vous avez déjà inséré un jeton".PHP_EOL;
    }
    
    public function ejecterJeton(): void{
        echo "Voici votre jeton".PHP_EOL;
        $this->distributeur->changerEtat($this->distributeur->etatSansJeton());
    }
    
    public function tournerPoignee(): void{
        echo "Poignée tournée".PHP_EOL;
        $this->distributeur->changerEtat($this->distributeur->etatVendu());
    }
    
    public function delivrer(): void{
        echo "Veuillez tourner la poignée".PHP_EOL;
    }
}

class EtatEpuise extends EtatAbstract implements EtatInterface
{
    protected string $message = 'Produit épuisé, veuillez attendre un retour des stocks';

    public function insererJeton(): void{
        echo $this->message.PHP_EOL;
    }
    
    public function ejecterJeton(): void{
        echo $this->message.PHP_EOL;
    }
    
    public function tournerPoignee(): void{
        echo $this->message.PHP_EOL;
    }
    
    public function delivrer(): void{
        echo $this->message.PHP_EOL;
    }
}

class EtatVendu extends EtatAbstract implements EtatInterface
{
    public function insererJeton(): void{
        echo "Veuillez patienter".PHP_EOL;
    }
    
    public function ejecterJeton(): void{
        echo "Achat déjà effectué" . PHP_EOL;
    }
    
    public function tournerPoignee(): void{
        echo "Vous avez déjà tourné la poignée".PHP_EOL;
    }
    
    public function delivrer(): void{
        $this->distributeur->libererProduit();

        if ($this->distributeur->donnerNombreProduits()) {
            $this->distributeur->changerEtat($this->distributeur->etatSansJeton());
        }else {
            $this->distributeur->changerEtat($this->distributeur->etatEpuise());
        }
    }
}

//Voici une partie du Distributeur (notre contexte) avec son interface, complétez-là!
interface DistributeurInterface
{
    public function etatSansJeton(): EtatInterface;
    public function etatAvecJeton(): EtatInterface;
    public function etatEpuise(): EtatInterface;
    public function etatVendu(): EtatInterface;
    public function etatCourant(): EtatInterface;
    public function libererProduit(): void;
    public function mettreUnJeton(): void;
    public function tournerLaPoignee(): void;
    public function ejecterLeJeton(): void;
}
class Distributeur implements DistributeurInterface
{
    private $etatEpuise;
    private $etatSansJeton;
    private $etatAvecJeton;
    private $etatVendu;
    private $etatCourant;
    private $nbProduits;

    public function __construct(int $nbProduits = 0)
    {
        $this->nbProduits = $nbProduits;echo "Stock: ".$nbProduits.PHP_EOL;
        $this->etatEpuise = new EtatEpuise($this);
        //ref circulaire !
        $this->etatSansJeton = new EtatSansJeton($this);
        $this->etatAvecJeton = new EtatAvecJeton($this);
        $this->etatVendu = new EtatVendu($this);

        $this->etatCourant = $nbProduits > 0 ? $this->etatSansJeton: $this->etatEpuise;
    }

    public function changerEtat(EtatInterface $nouvelEtat): void
    {
        $this->etatCourant = $nouvelEtat;
    }

    public function etatSansJeton(): EtatInterface
    {
        return $this->etatSansJeton;
    }
    
    public function etatAvecJeton(): EtatInterface
    {
        return $this->etatAvecJeton;
    }
    
    public function etatEpuise(): EtatInterface
    {
        return $this->etatEpuise;
    }
    
    public function etatVendu(): EtatInterface
    {
        return $this->etatVendu;
    }

    public function etatCourant(): EtatInterface
    {
        return $this->etatCourant;
    }

    public function libererProduit(): void
    {
        echo 'Voici votre produit'.PHP_EOL;
        if ($this->nbProduits) {
            $this->nbProduits --;
        }
    }

    public function mettreUnJeton(): void
    {
        $this->etatCourant()->insererJeton();
    }

    public function tournerLaPoignee(): void
    {
        $this->etatCourant()->tournerPoignee();
    }

    public function ejecterLeJeton(): void
    {
        $this->etatCourant()->ejecterJeton();
    }
}

$distributeur = new Distributeur(2);
$i = 2;

do {
    echo get_class($distributeur->etatCourant()).PHP_EOL;
    $distributeur->mettreUnJeton();
    echo get_class($distributeur->etatCourant()).PHP_EOL;
    $distributeur->tournerLaPoignee();
    echo get_class($distributeur->etatCourant()).PHP_EOL;
    $i--;
} while ($i > 0);