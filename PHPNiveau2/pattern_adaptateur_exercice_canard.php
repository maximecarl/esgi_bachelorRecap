<?php

interface CancaneurInterface
{
    public function cancaner(): string;
}

class Canard implements CancaneurInterface
{
    public function cancaner(): string
    {
        return 'Coin coin';
    }
}

interface AnimalPlastiqueInterface
{
    public function emettreUnSon(): string;
}

class CanardEnPlastique implements AnimalPlastiqueInterface
{
    public function emettreUnSon(): string
    {
        return 'Pouic';
    }
}

class AdaptateurDeCanard implements CancaneurInterface
{
    protected CanardEnPlastique $adapte;

    public function __construct(CanardEnPlastique $canard)
    {
        $this->adapte = $canard;
    }
    
    public function cancaner(): string
    {
        return $this->adapte->emettreUnSon();
    }
}

$instances = [
                new AdaptateurDeCanard(new CanardEnPlastique()),
                new Canard(),
];

foreach ($instances as $instance) {
    echo $instance->cancaner() . PHP_EOL;
}
