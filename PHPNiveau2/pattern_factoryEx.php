<?php declare(strict_types=1);

// Interface des fabriques (une interface aurait fait l'affaire)
abstract class FabriqueShape
{
    abstract public function fabriquer(): ShapeInterface;
}

class FabriqueDeCarres extends FabriqueShape
{
    protected string $classShape = 'Carre';

    public function fabriquer() : ShapeInterface
    {
        return new $this->classShape();
    }
}

class FabriqueDeTriangles extends FabriqueShape
{
    protected string $classShape = 'Triangle';

    public function fabriquer() : ShapeInterface
    {
        return new $this->classShape();
    }
}

// les formes CONCRETES
interface ShapeInterface
{
    public function dessiner(): void; 
}

class Carre implements ShapeInterface
{
    public function dessiner(): void
    {
        echo "Carré ";
    }
}

class Triangle implements ShapeInterface
{
    public function dessiner(): void
    {
        echo "Triangle ";
    }
}

// Resultat
class Dessin
{
    protected $registeredShapes = [];
    protected Carre $carre;
    protected Triangle $triangle;

    public function __construct(
        array $shapes,
        FabriqueShape $fabriqueCarre, 
        FabriqueShape $fabriqueTriangle)
    {
        $this->registeredShapes = $shapes;
        $this->carre = $fabriqueCarre->fabriquer();
        $this->triangle = $fabriqueTriangle->fabriquer();
    }

    public function dessiner(): void
    {
        foreach ($this->registeredShapes as $key => $shape) {
            switch ($shape) {
                case 'c':
                    $this->carre->dessiner();
                    break;
                case 't':
                    $this->triangle->dessiner();
                    break;
            }
        }
    }
}

$dessin = new Dessin(
    ['c', 't', 't'],
    new FabriqueDeCarres(),
    new FabriqueDeTriangles()
);
$dessin->dessiner();