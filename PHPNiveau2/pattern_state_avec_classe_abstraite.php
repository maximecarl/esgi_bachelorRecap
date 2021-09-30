<?php

/**
* Chaque etat correspond à une classe concrète implémentant une abstraction
* le contexte connait l'état en cours
*/

class MethodNotImplementedException extends \Exception {}

interface EtatInterface
{
    public function mettreEnAttente(): void;
    public function valider(): void;
    public function annuler(): void;
    public function rembourser(): void;
    public function expedier(): void;
    public function signalerLivre(): void;
}

abstract class EtatAbstract implements EtatInterface
{
    protected ContexteInterface $contexte;
    
    public function __construct(ContexteInterface $contexte)
    {
        $this->contexte = $contexte;
    }
}

class EnAttente extends EtatAbstract
{   
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Déjà en attente');
    }
    
    public function valider(): void
    {
        $this->contexte->changerEtat($this->contexte->etatValide());
    }
    
    public function annuler(): void
    {
        $this->contexte->changerEtat($this->contexte->etatAnnule());
    }
    
    public function rembourser(): void
    {
        throw new MethodNotImplementedException('En attente');
    }
    
    public function expedier(): void
    {
        throw new MethodNotImplementedException('En attente');
    }
    
    public function signalerLivre(): void
    {
        throw new MethodNotImplementedException('En attente');
    }
}

class Annule extends EtatAbstract
{
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Annulé');
    }
    
    public function valider(): void
    {
        throw new MethodNotImplementedException('Annulé');
    }
    
    public function annuler(): void
    {
        throw new MethodNotImplementedException('Déjà annulé');
    }
    
    public function rembourser(): void
    {
        $this->contexte->changerEtat($this->contexte->etatRembourse());
    }
    
    public function expedier(): void
    {
        throw new MethodNotImplementedException('Annulé');
    }
    
    public function signalerLivre(): void
    {
        throw new MethodNotImplementedException('Annulé');
    }
}

class Valide extends EtatAbstract
{    
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Validé');
    }
    
    public function valider(): void
    {
        throw new MethodNotImplementedException('Déjà validé');
    }
    
    public function annuler(): void
    {
        $this->contexte->changerEtat($this->contexte->etatAnnule());
    }
    
    public function rembourser(): void
    {
        throw new MethodNotImplementedException('Validé');
    }
    
    public function expedier(): void
    {
        $this->contexte->changerEtat($this->contexte->etatExpedie());
    }
    
    public function signalerLivre(): void
    {
        throw new MethodNotImplementedException('Validé');
    }
}

class Expedie extends EtatAbstract
{   
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Expédié');
    }
    
    public function valider(): void
    {
        throw new MethodNotImplementedException('Expédié');
    }
    
    public function annuler(): void
    {
        throw new MethodNotImplementedException('Expédié');
    }
    
    public function rembourser(): void
    {
        throw new MethodNotImplementedException('Expédié');
    }
    
    public function expedier(): void
    {
        throw new MethodNotImplementedException('Déjà expédié');
    }
    
    public function signalerLivre(): void
    {
        $this->contexte->changerEtat($this->contexte->etatLivre());
    }
}

class Rembourse extends EtatAbstract
{
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Remboursé');
    }
    
    public function valider(): void
    {
        throw new MethodNotImplementedException('Remboursé');
    }
    
    public function annuler(): void
    {
        throw new MethodNotImplementedException('Remboursé');
    }
    
    public function rembourser(): void
    {
        throw new MethodNotImplementedException('Déjà Remboursé');
    }
    
    public function expedier(): void
    {
        throw new MethodNotImplementedException('Remboursé');
    }
    
    public function signalerLivre(): void
    {
        throw new MethodNotImplementedException('Remboursé');
    }
}

class Livre extends EtatAbstract
{
    public function mettreEnAttente(): void
    {
        throw new MethodNotImplementedException('Livré');
    }
    
    public function valider(): void
    {
        throw new MethodNotImplementedException('Livré');
    }
    
    public function annuler(): void
    {
        $this->contexte->changerEtat($this->contexte->etatAnnule());
    }
    
    public function rembourser(): void
    {
        throw new MethodNotImplementedException('Livré');
    }
    
    public function expedier(): void
    {
        throw new MethodNotImplementedException('Livré');
    }
    
    public function signalerLivre(): void
    {
        throw new MethodNotImplementedException('Déjà Livré');
    }
}

interface ContexteInterface
{
    public function etatValide(): EtatInterface;
    public function etatAnnule(): EtatInterface;
    public function etatExpedie(): EtatInterface;
    public function etatLivre(): EtatInterface;
    public function etatActuel(): EtatInterface;
    public function changerEtat(EtatInterface $etat): void;
}

class Contexte implements ContexteInterface
{
    protected $enAttente;
    protected $valide;
    protected $expedie;
    protected $livre;
    protected $rembourse;
    protected $annule;
    protected $etatActuel;

    public function __construct()
    {
        $this->enAttente = new EnAttente($this);
        $this->valide = new Valide($this);
        $this->expedie = new Expedie($this);
        $this->livre = new Livre($this);
        $this->rembourse = new Rembourse($this);
        $this->annule = new Annule($this);
        
        $this->etatActuel = $this->enAttente;
    }
    
    public function etatValide(): EtatInterface
    {
        return $this->valide;
    }
    
    public function etatAnnule(): EtatInterface
    {
        return $this->annule;
    }
    
    public function etatExpedie(): EtatInterface
    {
        return $this->expedie;
    }
    
    public function etatLivre(): EtatInterface
    {
        return $this->livre;
    }
    
    public function etatRembourse(): EtatInterface
    {
        return $this->rembourse;
    }
    
    public function etatActuel(): EtatInterface
    {
        return $this->etatActuel;
    }
    
    public function changerEtat(EtatInterface $etat): void
    {
        $this->etatActuel = $etat;
    }
    
    public function valider(): void
    {
        $this->etatActuel->valider();
    }
    
    public function expedier(): void
    {
        $this->etatActuel->expedier();
    }
    
    public function signalerCommeLivre(): void
    {
        $this->etatActuel->signalerLivre();
    }
    
    public function effectuerRemboursement(): void
    {
        $this->etatActuel->rembourser();
    }
    
    public function effectuerAnnulation(): void
    {
        $this->etatActuel->annuler();
    }
}

class Commande
{
    protected ContexteInterface $contexte;
    
    public function __construct(ContexteInterface $contexte)
    {
        $this->contexte = $contexte;
    }
    
    public function valider(): void
    {
        $this->contexte->valider();
        echo "Etat = ".get_class($this->contexte->etatActuel()).PHP_EOL;
    }

    public function expedier(): void
    {
        $this->contexte->expedier();
        echo "Etat = ".get_class($this->contexte->etatActuel()).PHP_EOL;
    }
    
    public function signalerCommeEtantLivre(): void
    {
        $this->contexte->signalerCommeLivre();
        echo "Etat = ".get_class($this->contexte->etatActuel()).PHP_EOL;
    }
    
    public function procederAuRemboursement(): void
    {
        $this->contexte->effectuerRemboursement();
        echo "Etat = ".get_class($this->contexte->etatActuel()).PHP_EOL;
    }
 
    public function effectuerUneAnnulation(): void
    {
        $this->contexte->effectuerAnnulation();
        echo "Etat = ".get_class($this->contexte->etatActuel()).PHP_EOL;
    }
    
}

$contexte = new Contexte();
$commande = new Commande($contexte);
/*
En attente de validation de paiement --> paiement validé --> expédié --> Livré
En attente de validation de paiement --> paiement validé --> expédié --> Livré --> annulé --> remboursé
En attente de validation de paiement --> paiement validé --> annulé --> remboursé
En attente de validation de paiement --> annulé
*/

// Scénario nominal - Best case
/*
$commande->valider();
$commande->expedier();
$commande->signalerCommeEtantLivre();
*/

/*
// Scénario 2
// Le produit est livré mais l'acheteur réalise que la taille n'est pas
// la bonne et le produit n'est plus dispo dans sa taille
$commande->valider();
$commande->expedier();
$commande->signalerCommeEtantLivre();
$commande->effectuerUneAnnulation();
$commande->procederAuRemboursement();
*/

// Scénario 3
// La commande est validée mais une erreur de stock (produit indispo)
// conduit à devoir l'annuler
/*
$commande->valider();
$commande->effectuerUneAnnulation();
$commande->procederAuRemboursement();
*/

// Scénario 4
// Le paiement n'a pas été effectué dans les délais ou une erreur
// s'est produite durant celui-ci
//$commande->effectuerUneAnnulation();
