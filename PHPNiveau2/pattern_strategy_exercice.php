<?php

// L'abstraction de nos stratégies

interface ValidateurInterface
{
    public function valider(UtilisateurInterface $utilisateur): bool;
}

class AuMoinsUnChiffre implements ValidateurInterface {
    public function valider(UtilisateurInterface $utilisateur): bool {
        return preg_match('/\d/', $utilisateur->donnerMotDePasse());
    }
}

class AuMoinsDixCaracteres implements ValidateurInterface {
    public function valider(UtilisateurInterface $utilisateur): bool {
        return strlen($utilisateur->donnerMotDePasse()) > 9;
    }
}

class PasDespace implements ValidateurInterface {
    public function valider(UtilisateurInterface $utilisateur): bool {
        return !preg_match('/\s/', $utilisateur->donnerMotDePasse());
    }
}

class Contexte {
    protected ValidateurInterface $validateur;

    public function __construct(ValidateurInterface $validateur)
    {
        $this->validateur = $validateur;
    }

    public function isCorrect(UtilisateurInterface $utilisateur): string
    {
      return $this->validateur->valider($utilisateur);
    }
}

interface UtilisateurInterface {
    public function donnerMotDePasse(): string;
}

class Utilisateur implements UtilisateurInterface {
    protected string $motDePasse;

    public function __construct(string $motDePasse)
    {
        $this->motDePasse = $motDePasse;
    }

    public function donnerMotDePasse(): string
    {
        return $this->motDePasse;
    }
}

// Le code client
$utilisateur = new Utilisateur('dfjzE2hdpz4');
$validateurs = [new AuMoinsUnChiffre(), new AuMoinsDixCaracteres(), new PasDespace()];

$allValidate = true;
foreach ($validateurs as $validateur) {
    $contexte = new Contexte($validateur);
    
    if (!$contexte->isCorrect($utilisateur)) {
        $allValidate = false;
        echo get_class($validateur) . ' non valide' . PHP_EOL;
    }
}

echo 'Mot de passe ' . ($allValidate ? 'valide' : 'invalide');
