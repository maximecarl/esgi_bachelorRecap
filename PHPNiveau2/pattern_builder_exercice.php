<?php

// Le produit et son interface
interface DocumentInterface
{
    public function ajouterPartie(string $nom, string $contenu): void;
    public function afficher(): string;
}

class Document implements DocumentInterface
{
    protected array $configuration;

    public function ajouterPartie(string $nom, string $contenu): void
    {
        $this->configuration[$nom] = $contenu;
    }

    public function afficher(): string
    {
        $affichage = '---------------------------------' . PHP_EOL;
        $affichage .= $this->configuration['entete'] . PHP_EOL;
        $affichage .= '---------------------------------' . PHP_EOL;
        $affichage .= $this->configuration['corps'] . PHP_EOL;
        $affichage .= '---------------------------------' . PHP_EOL;
        $affichage .= $this->configuration['pied_page'] . PHP_EOL;
        $affichage .= '---------------------------------' . PHP_EOL;

        return $affichage;
    }
}

// Le directeur, qui orchestre le montage dans la méthode "monter"
class Directeur
{
    protected MonteurInterface $monteur;

    public function __construct(MonteurInterface $monteur)
    {
        $this->monteur = $monteur;
    }

    public function monter(): void
    {
        $this->monteur->creerDocument();
        $this->monteur->creerEntete();
        $this->monteur->creerCorps();
        $this->monteur->creerPiedPage();
    }
}

// le monteur et son interface
interface MonteurInterface
{
    public function creerDocument(): void;

    public function creerEntete(): void;

    public function creerCorps(): void;

    public function creerPiedPage(): void;

    public function donnerDocument(): DocumentInterface;
}

class MonteurDocument implements MonteurInterface
{
    protected Document $document;

    protected array $options;	

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function creerDocument(): void
    {
        $this->document = new Document();
    }

    public function creerEntete(): void
    {
        $this->document->ajouterPartie('entete', $this->options['entete']);
    }

    public function creerPiedPage(): void
    {
        $this->document->ajouterPartie('pied_page', $this->options['pied_page']);
    }

    public function creerCorps(): void
    {
        $this->document->ajouterPartie('corps', $this->options['corps']);
    }

    public function donnerDocument(): DocumentInterface
    {
        return $this->document;
    }
}

// Mo(n)teur et...Action !
$parametres = [
    'entete' => 'SOCIETE MACHIN TRUC, BP 48755, 69997 Lyon Cedex 9', 
    'corps' => 'Report du chantier de la discothèque Super Confluence Night 2000 sur la zone Confluence', 
    'pied_page' => 'S.A au capital de 98655 € - Immatriculée au R.C.S de Lyon sous le numéro 6269331',
];

$monteur = new MonteurDocument($parametres);
$directeur = new Directeur($monteur);
$directeur->monter();
$document = $monteur->donnerDocument();

echo $document->afficher();
