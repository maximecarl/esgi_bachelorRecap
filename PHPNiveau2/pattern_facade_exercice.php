<?php
interface LecteurInterface
{
    public function mettreSousTension(): void;
    public function lire(): void;
}
 
interface ProjecteurInterface
{
    public function mettreSousTension(): void;
    public function modePleinEcran(): void;
}
 
interface AmplificateurInterface 
{
    public function mettreSousTension(): void;
    public function activerSonSurround(): void;
    public function reglerVolume(int $volume): void;
}

class LecteurBluRay implements LecteurInterface 
{
    protected string $film;

    public function nomduFilm(string $film) : void
    {
        $this->film = $film;
    }

    public function mettreSousTension(): void
    {
        echo "LecteurBlueRay sous tension" . PHP_EOL;
    }
    public function lire(): void
    {
        echo "LecteurBlueRay en lecture du film " . $this->film . PHP_EOL;
    }
}

class Projecteur implements ProjecteurInterface
{
    public function mettreSousTension(): void
    {
        echo "Projecteur sous tension" . PHP_EOL;
    }
    public function modePleinEcran(): void
    {
        echo "Projecteur en mode plein écran" . PHP_EOL;
    }
}

class Enceinte implements AmplificateurInterface
{
    public function mettreSousTension(): void
    {
        echo "Enceinte sous tension" . PHP_EOL;
    }
    public function activerSonSurround(): void
    {
        echo "Enceinte en mode Son Surrond" . PHP_EOL;
    }
    public function reglerVolume(int $volume): void
    {
        echo "Enceinte : volume à " . $volume . PHP_EOL;
    }
}

class HomeCinemaFacade
{
    public function __construct(
        LecteurInterface $lecteur, 
        ProjecteurInterface $projecteur, 
        AmplificateurInterface $amplificateur
    ) {
        $this->lecteur = $lecteur;
        $this->projecteur = $projecteur;
        $this->amplificateur = $amplificateur;
    }

    public function regarderFilm(string $film, int $volume)
    {
        $this->lecteur->mettreSousTension();
        $this->lecteur->nomduFilm($film);
        $this->projecteur->mettreSousTension();
        $this->amplificateur->mettreSousTension();
        $this->projecteur->modePleinEcran();
        $this->amplificateur->activerSonSurround();
        $this->amplificateur->reglerVolume($volume);
        $this->lecteur->lire();
    }
}

$lecteur = new LecteurBluRay();
$projecteur = new Projecteur();
$amplificateur = new Enceinte();
$homeCinema = new HomeCinemaFacade($lecteur, $projecteur, $amplificateur);
$homeCinema->regarderFilm('Film super', 14);
