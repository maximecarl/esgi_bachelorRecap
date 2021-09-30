<?php declare(strict_types=1);

// l'interface des fabriques (une interface aurait fait l'affaire)
abstract class FabriqueFacture
{
    abstract public function fabriquer(): RubriqueInterface;
}

// les fabriques concrètes
class FabriqueEnteteFacture extends FabriqueFacture
{
    protected string $classeCible = 'Entete';

    public function fabriquer(): RubriqueInterface
    {
        return new $this->classeCible();
    }
}
 
class FabriqueCorpsFacture extends FabriqueFacture
{
    protected string $classeCible = 'Corps';

    protected array $produitsAFacturer;

    public function __construct(array $produits)
    {
        $this->produitsAFacturer = $produits;
    }

    public function fabriquer(): RubriqueInterface
    {
        return new $this->classeCible($this->produitsAFacturer);
    }
}
 
class FabriquePiedPageFacture extends FabriqueFacture
{
    protected string $classeCible = 'PiedPage';

    public function fabriquer(): RubriqueInterface
    {
        return new $this->classeCible();
    }
}


// l'interface des produits
interface RubriqueInterface
{
    public function formater(): void; 
}

// les produits CONCRETS
class Entete implements RubriqueInterface
{
    public function formater(): void
    {
        echo "Je formate mon entête" . PHP_EOL;
    }
}

class Corps implements RubriqueInterface
{
    protected array $produits;

    public function __construct(array $produits)
    {
        $this->produits = $produits;
    }

    public function formater(): void
    {
        echo "Je formate mon corps avec mes " . count($this->produits) . " produits" . PHP_EOL;
    }
}

class PiedPage implements RubriqueInterface
{
    public function formater(): void
    {
        echo "Je formate mon pied de page" . PHP_EOL;
    }
}

// le code client
class Facturation
{
    protected Entete $entete;

    protected Corps $corps;

    protected PiedPage $piedPage;

    public function __construct(FabriqueFacture $fabriqueEntete, 
        FabriqueFacture $fabriqueCorps, FabriqueFacture $fabriquePiedPage)
    {
        $this->entete = $fabriqueEntete->fabriquer();
        $this->corps = $fabriqueCorps->fabriquer();
        $this->piedPage = $fabriquePiedPage->fabriquer();
    }

    public function declencher(): void
    {
        $this->entete->formater();
        $this->corps->formater();
        $this->piedPage->formater();
    }
}

$produits = [
    ['nom' => 'Gourde', 'prix' => 9.99],
    ['nom' => 'Assiette', 'prix' => 1.99],
    ['nom' => 'Dentifrice', 'prix' => 2.99],
];

$facture = new Facturation(
  new FabriqueEnteteFacture(),
  new FabriqueCorpsFacture($produits),
  new FabriquePiedPageFacture()
);

$facture->declencher();
