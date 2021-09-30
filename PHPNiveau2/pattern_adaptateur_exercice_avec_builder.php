<?php

interface PieceInterface
{
    public function donnerNom(): string;
}

class PorteCamion implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Porte de camion";
    }
}

class PorteVoiture implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Porte de voiture";
    }
}

class RoueCamion implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Roue de camion";
    }
}

class RoueVoiture implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Roue de voiture";
    }
}

class MoteurVoiture implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Moteur de voiture";
    }
}

class MoteurCamion implements PieceInterface
{
    public function donnerNom(): string
    {
        return "Moteur de camion";
    }
}

abstract class Vehicule
{
    protected array $configuration;

    public function ajouterPiece(string $nom, PieceInterface $piece): void
    {
        $this->configuration[$nom] = $piece;
    }
}

class Camion extends Vehicule{};
class Voiture extends Vehicule{};

class Directeur
{
    private MonteurInterface $monteur;

    public function __construct(MonteurInterface $monteur)
    {
        $this->monteur = $monteur;
    }

    public function monter(): void
    {
        $this->monteur->creerVehicule();
        $this->monteur->poserPortes();
        $this->monteur->poserMoteur();
        $this->monteur->poserRoues();
        $this->monteur->peindre();
    }
}

interface MonteurInterface
{
    public function creerVehicule(): void;

    public function poserPortes(): void;

    public function poserMoteur(): void;

    public function poserRoues(): void;

    public function peindre(): void;

    public function donnerVehicule(): Vehicule;
}

class MonteurVoitures implements MonteurInterface
{
    private Voiture $voiture;

    private array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function creerVehicule(): void
    {
        $this->voiture = new Voiture();
    }

    public function poserPortes(): void
    {
        $this->voiture->ajouterPiece('porte AVG', new PorteVoiture());
        $this->voiture->ajouterPiece('porte AVD', new PorteVoiture());
        $this->voiture->ajouterPiece('porte arrière', new PorteVoiture());

        if (3 < $this->options['nb_portes']) {
            $this->voiture->ajouterPiece('porte ARG', new PorteVoiture());
            $this->voiture->ajouterPiece('porte ARD', new PorteVoiture());
        }
    }

    public function poserMoteur(): void
    {
        $this->voiture->ajouterPiece('moteur', new MoteurVoiture());
    }

    public function poserRoues(): void
    {
        $this->voiture->ajouterPiece('roue AVG', new RoueVoiture());
        $this->voiture->ajouterPiece('roue AVD', new RoueVoiture());
        $this->voiture->ajouterPiece('roue ARG', new RoueVoiture());
        $this->voiture->ajouterPiece('roue ARD', new RoueVoiture());
    }

    public function peindre(): void
    {
        echo "Je peins la voiture en ".$this->options['couleur_peinture'].PHP_EOL;
    }

    public function donnerVehicule(): Vehicule
    {
        return $this->voiture;
    }
}

class MonteurCamions implements MonteurInterface
{
    private Camion $camion;

    private array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function creerVehicule(): void
    {
        $this->camion = new Camion();
    }

    public function poserPortes(): void
    {
        $this->camion->ajouterPiece('porte AVG', new PorteCamion());
        $this->camion->ajouterPiece('porte AVD', new PorteCamion());
    }

    public function poserMoteur(): void
    {
        $this->camion->ajouterPiece('moteur', new MoteurCamion());
    }

    public function poserRoues(): void
    {
        $this->camion->ajouterPiece('roue AVG', new RoueCamion());
        $this->camion->ajouterPiece('roue AVD', new RoueCamion());
        $this->camion->ajouterPiece('roue ARG', new RoueCamion());
        $this->camion->ajouterPiece('roue ARD', new RoueCamion());
    }

    public function peindre(): void
    {
        echo "Je peins le camion en ".$this->options['couleur_peinture'].PHP_EOL;
    }

    public function donnerVehicule(): Vehicule
    {
        return $this->camion;
    }
}

$monteurVoitures = new MonteurVoitures(['nb_portes' => 5, 'couleur_peinture' => 'rouge']);
$monteurCamions = new MonteurCamions(['couleur_peinture' => 'bleu']);

$tableauDeMonteurs = [$monteurVoitures, $monteurCamions];
/*
foreach ($tableauDeMonteurs as $monteur) {
    $directeur = new Directeur($monteur);
    $directeur->monter();
    $vehicule = $monteur->donnerVehicule();
    var_dump($vehicule);
}
*/

// Rachat d'une entreprise croate

class MontažerTricikla
{
    public function montiratiVrata(): MontažerTricikla
    {
        // je fais des tas d'opérations
        return $this;
    }
    
    public function stvoritiTrickl(): Tricikl
    {
        return new Tricikl();
    }
    
    public function ofarbati(): MontažerTricikla
    {
        // je fais des tas d'opérations
        return $this;
    }
    
    public function montiratiKotači(): MontažerTricikla
    {
        // je fais des tas d'opérations
        return $this;
    }
    
    public function montiratiMotor(): MontažerTricikla
    {
        // je fais des tas d'opérations
        return $this;
    }
}

class Tricikl extends Vehicule {}

class AdaptateurMonteurDeTricycles implements MonteurInterface
{
    private Tricikl $tricyle;
    
    private MontažerTricikla $adapte;
    
    public function __construct(MontažerTricikla $montazer)
    {
        $this->adapte = $montazer;
    }
    
    public function creerVehicule(): void
    {
        $this->tricyle = $this->adapte->stvoritiTrickl();
    }

    public function poserPortes(): void
    {
        $this->adapte->montiratiVrata();
    }

    public function poserMoteur(): void
    {
        $this->adapte->montiratiMotor();
    }
    
    public function poserRoues(): void
    {
        $this->adapte->montiratiKotači();
    }

    public function peindre(): void
    {
        $this->adapte->ofarbati();
    }

    public function donnerVehicule(): Vehicule
    {
        return $this->tricyle;
    }
}

// Test de l'adaptateur
$adaptateur = new AdaptateurMonteurDeTricycles(new MontažerTricikla());
$directeur = new Directeur($adaptateur);
$directeur->monter();
$vehicule = $adaptateur->donnerVehicule();

var_dump($vehicule);
