<?php

interface StrategieInterface
{
    public function executer(int $nombre, int $autreNombre): int;
}
 
class Multiplier implements StrategieInterface
{
    public function executer(int $nombre, int $autreNombre): int
    {
        return $nombre * $autreNombre;
    }
}

class Ajouter implements StrategieInterface
{
    public function executer(int $nombre, int $autreNombre): int
    {
        return $nombre + $autreNombre;
    }
}

class Soustraire implements StrategieInterface
{
    public function executer(int $nombre, int $autreNombre): int
    {
        return $nombre - $autreNombre;
    }
}

class Diviser implements StrategieInterface
{
    public function executer(int $nombre, int $autreNombre): int
    {
        if ($autreNombre !== 0){
            return $nombre / $autreNombre;
        }else {
            die('La division par 0 n\'est pas possible');
        }
    }
}

class Contexte
{
    protected StrategieInterface $strategie;

    public function __construct(StrategieInterface $strategie)
    {
        $this->strategie = $strategie;
    }
    
    public function executerOperationArithmetique(int $nombre, int $autreNombre): int
    {
        return $this->strategie->executer($nombre, $autreNombre);
    }
}


$operations = [new Ajouter(), new Diviser(), new Soustraire(), new Multiplier()];

foreach ($operations as $operation) {
    $contexte = new Contexte($operation);
    echo $contexte->executerOperationArithmetique(10, 2) . PHP_EOL;
}
