<?php

abstract class PersonneAbstract
{
    public function routine(): void
    { 
        $this->seLever();
        if ($this->aUnJob()) {
            $this->partirTravailler();
        }

        $this->dejeuner();
        if ($this->aUnJob()) {
            $this->rentrerDuTravail();
        }
        $this->diner();
        $this->dormir();
    }

    abstract protected function aUnJob(): bool;


    abstract protected function seLever(): void;
    abstract protected function partirTravailler(): void;
    abstract protected function dejeuner(): void;
    abstract protected function rentrerDuTravail(): void;
    abstract protected function diner(): void;
    abstract protected function dormir(): void;
}


class Chomeur extends PersonneAbstract
{
    protected function aUnJob(): bool
    {
        return false;
    }

    protected function seLever(): void
    {
        echo 'Je me lève' . PHP_EOL;
    }

    protected function partirTravailler(): void
    {
    }

    protected function dejeuner(): void
    {
        echo 'Je déjeune' . PHP_EOL;
    }

    protected function rentrerDuTravail(): void
    {
    }

    protected function diner(): void
    {
        echo 'Je dine' . PHP_EOL;
    }

    protected function dormir(): void
    {
        echo 'Je dors' . PHP_EOL;
    }
}

class Patron extends PersonneAbstract
{
    protected function aUnJob(): bool
    {
        return true;
    }

    protected function seLever(): void
    {
        echo 'Je me lève' . PHP_EOL;
    }

    protected function partirTravailler(): void
    {
        echo 'Je pars au charbon' . PHP_EOL;
    }

    protected function dejeuner(): void
    {
        echo 'Je déjeune' . PHP_EOL;
    }

    protected function rentrerDuTravail(): void
    {
        echo 'Je rentre du boulot' . PHP_EOL;
    }

    protected function diner(): void
    {
        echo 'Je dine' . PHP_EOL;
    }

    protected function dormir(): void
    {
        echo 'Je dors' . PHP_EOL;
    }
}

$chomeur = new Chomeur();
$patron = new Patron();
$chomeur->routine();
$patron->routine();