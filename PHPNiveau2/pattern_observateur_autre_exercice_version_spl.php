<?php

abstract class Communication
{
    protected string $contenu;

    public function __construct(string $contenu)
    {
        $this->contenu = $contenu;  
    }

    public function donnerContenu(): string
    {
        return $this->contenu;
    }

    public function changerContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }
}

class Ordre extends Communication {}

class RadioBataillon implements \SplObserver
{
    protected string $nom;

    protected array $sujets;

    public function __construct(string $nom)
    {
        $this->nom = $nom;
    }

    public function attendreOrdre(\SplSubject $sujet)
    {
        $this->sujets[] = $sujet;
        $sujet->attach($this);
    }

    public function update(\SplSubject $sujet): void
    {
        echo "Ici " . $this->nom . PHP_EOL;
        echo "Je viens de recevoir un ordre de " . get_class($sujet) . PHP_EOL;
        $ordre = $sujet->recupererOrdre();
        echo "\"" . $ordre->donnerContenu() . "\"" . PHP_EOL;
    }
}

class PosteCommandement implements \SplSubject
{
    protected array $observateurs = [];

    protected Communication $ordre;

    public function attach(\SplObserver $observateur): void
    {
        if (is_array($this->observateurs)) {
            $i = array_search($observateur, $this->observateurs);
            
            if ($i === false) {
                $this->observateurs[] = $observateur;
            }
        }
    }

    public function detach(\SplObserver $observateur): void
    {
        if (!empty($this->observateurs)) {
            $i = array_search($observateur, $this->observateurs);
            if ($i !== false) {
                unset($this->observateurs[$i]);
            }
        }
    }

    public function recupererOrdre(): Communication
    {
        return $this->ordre;
    }

    public function changerOrdre(Communication $ordre)
    {
        $this->ordre = $ordre;
        $this->notify();
    }

    public function notify(): void
    {
        if (!empty($this->observateurs)) {
            foreach ($this->observateurs as $observateur) {
                $observateur->update($this);
            }
        }
    }
}

// client
class EtatMajor 
{
    protected \SplSubject $sujet;

    public function __construct(\SplSubject $sujet)
    {
        $this->sujet = $sujet;
    }

    public function donnerOrdre(string $ordre): void
    {
        $ordre = new Ordre($ordre);
        $this->sujet->changerOrdre($ordre);
    }
}

abstract class FabriqueDeSujets
{
    abstract static public function fabriquer(): \SplSubject;
}

class FabriquePosteCommandement extends FabriqueDeSujets
{
    static public function fabriquer(): \SplSubject
    {
        return new PosteCommandement();
    }
}

$posteCommandement = FabriquePosteCommandement::fabriquer();
$etatMajor = new EtatMajor($posteCommandement);
$bataillon = new RadioBataillon('2eme division blindée');
$autreBataillon = new RadioBataillon('5eme bataillon aéroporté');
$bataillon->attendreOrdre($posteCommandement);
$autreBataillon->attendreOrdre($posteCommandement);
$etatMajor->donnerOrdre('Dirigez vous vers la ligne de front ! Assaut imminent !');
