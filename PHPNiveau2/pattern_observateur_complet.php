<?php

interface SujetInterface
{
    public function attacher(ObservateurInterface $observateur): void;
    public function detacher(ObservateurInterface $observateur): void;
    public function notifier(): void;
}

interface SuiveurInterface
{
    public function suivre(SujetInterface $sujet): void;
    public function nePlusSuivre(SujetInterface $sujet): void;
}

interface ObservateurInterface
{
    public function mettreAJour(SujetInterface $sujet): void;
}

abstract class AbstractMembre implements SujetInterface, SuiveurInterface, ObservateurInterface
{
    protected string $nom;
    
    protected array $observateurs;
    
    protected array $sujets;
    
    public function __construct(string $nom)
    {
        $this->nom = $nom;
    }
    
    public function attacher(ObservateurInterface $observateur): void
    {
        echo $this->donnerNom().PHP_EOL;
        echo "\t" . $observateur->donnerNom()." vous à ajouté à ses contacts" . PHP_EOL;
        $this->observateurs[] = $observateur;
    }
 
    public function detacher(ObservateurInterface $observateur): void
    {
        $key = array_search($observateur, $this->observateurs);
 
        if (false !== $key) {
            echo $this->donnerNom().PHP_EOL;
            unset($this->observateurs[$key]);
            echo "\tVous avez enlevé ".$observateur->donnerNom() . " de vos contacts" . PHP_EOL;
        }
    }
 
    public function notifier(): void 
    {
        foreach ($this->observateurs as $observateur) {
            $observateur->mettreAJour($this);
        }
    }

    public function donnerNom(): string
    {
        return $this->nom;
    }
    
    public function suivre(SujetInterface $sujet): void
    {
        if ($sujet === $this) {
            return;
        }
        
        $this->sujets[$sujet->donnerNom()] = clone $sujet;
        $sujet->attacher($this);
    }
    
    public function nePlusSuivre(SujetInterface $sujet): void
    {
        echo $this->donnerNom().PHP_EOL;
    
        $key = array_search($sujet, $this->sujets);
 
        if (false !== $key) {
            unset($this->sujets[$key]);
        }
        
        echo "\tVous ne suivez plus " . $sujet->donnerNom() . PHP_EOL;
    }
    
    public function mettreAJour(SujetInterface $sujet): void
    {
        echo $this->donnerNom().PHP_EOL;
        $nom = $sujet->donnerNom();
        
        if (array_key_exists($nom, $this->sujets)) {
            $sujetStocke = $this->sujets[$nom];
            
            if ($sujet instanceof PageCommerciale) {
                $urlMagasin = $sujet->donnerUrlMagasin();
                $urlMagasinStocke = $sujetStocke->donnerUrlMagasin();
                
                if ($urlMagasin !== $urlMagasinStocke) {
                    echo "\tLe site web de $nom vaut désormais $urlMagasin";
                }

            } elseif ($sujet instanceof Membre) {
                $age = $sujet->donnerAge();
                $ageStocke = $sujetStocke->donnerAge();

                if ($ageStocke !== $age) {
                    echo "\t$nom fête son anniversaire, il a $age ans";
                    $this->sujets[$nom] = clone $sujet;
                }
                
                $hobbies = $sujet->donnerHobbies();
                $hobbiesStocke = $sujetStocke->donnerHobbies();

                if ($hobbiesStocke !== $hobbies) {
                    echo "\t$nom a de nouveaux hobbies: ".implode(", ", $hobbies);
                    $this->sujets[$nom] = clone $sujet;
                }
            }
            echo PHP_EOL;
        }
    }
}

class PageCommerciale extends AbstractMembre {
 
    protected ?string $urlMagasin = null;
     
    public function changerUrlMagasin(string $urlMagasin): void
    {
        $this->urlMagasin = $urlMagasin;
        $this->notifier();
    }
 
    public function donnerUrlMagasin(): ?string
    {
        return $this->urlMagasin;
    }
}

class Membre extends AbstractMembre {
 
    protected ?int $age = null;
     
    protected ?array $hobbies = null;
     
    public function changerAge(int $age): void
    {
        $this->age = $age;
        $this->notifier();
    }
     
    public function changerHobbies(array $hobbies): void
    {
        $this->hobbies = $hobbies;
        $this->notifier();
    }
 
    public function donnerAge(): ?int
    {
        return $this->age;
    }
    
    public function donnerHobbies(): ?array
    {
        return $this->hobbies;
    }
}

$magasin = new PageCommerciale('ACME Web Store France');
$autreMagasin = new PageCommerciale('ACME Web Store Worldwide Company');

$star = new Membre('Rasmus Lerdorf');
$copain = new Membre('Jean-Michel Apeuprey');
$autreStar = new Membre('Novak Djokovic');

$membre = new Membre('Sébastien Ferrandez');

// On réalise les divers abonnements
$membre->suivre($magasin);
$membre->suivre($star);
$membre->suivre($autreStar);
$membre->suivre($copain);

$copain->suivre($membre);
$magasin->suivre($autreStar);
$magasin->suivre($autreMagasin);

$autreMagasin->suivre($magasin);

// Les évènements qui vont déclencher des notifications
$copain->changerAge(41);
$membre->changerHobbies(["guitare"]);
$star->changerAge(36);
$star->changerHobbies(["linux", "c++", "c"]);
$autreStar->changerHobbies(["musculation", "karaoké"]);
$magasin->changerUrlMagasin('http://www.acmestore.fr');
$autreMagasin->changerUrlMagasin('http://www.acmestore.com');
// Je n'aime plus le tennis !
$membre->nePlusSuivre($autreStar);
// Je me suis fâché avec Jean-Michel, il n'est plus mon ami !
$membre->detacher($copain);
