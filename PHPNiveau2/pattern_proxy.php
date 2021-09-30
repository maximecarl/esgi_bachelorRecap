<?php

// Adaptation de https://refactoring.guru/fr/design-patterns/proxy/php/example#example-1

interface TelechargeurInterface
{
    public function telecharger(string $url): string;
}

// Sujet réel

class SimpleTelechargeur implements TelechargeurInterface
{
    public function telecharger(string $url): string
    {
        echo "\tTéléchargement de $url\n";
        
        return file_get_contents($url);
    }
}

// Objet Proxy

class ProxyTelechargeur implements TelechargeurInterface
{
    protected SimpleTelechargeur $telechargeur;

    protected array $cache;

    public function __construct(SimpleTelechargeur $telechargeur)
    {
        $this->telechargeur = $telechargeur;
    }

    public function telecharger(string $url): string
    {
        if (!isset($this->cache[$url])) {
            $contenu = $this->telechargeur->telecharger($url);
            $this->cache[$url] = $contenu;
        } else {
            echo "CACHE UTILISÉ\n";
        }
        
        return $this->cache[$url];
    }
}

echo "Sujet réel:\n";
$sujetReel = new SimpleTelechargeur();

// premiere requete...cache non utilisé
$contenu = $sujetReel->telecharger("http://sebastien.ferrandez.free.fr/proxy.txt");    
echo "\tContenu du fichier: $contenu\n";

// deuxième requete...idem !
$contenu = $sujetReel->telecharger("http://sebastien.ferrandez.free.fr/proxy.txt");
echo "\tContenu du fichier: $contenu\n";

echo "Proxy:\n";
$proxy = new ProxyTelechargeur($sujetReel);

// premiere requete...cache non utilisé
$contenu = $proxy->telecharger("http://sebastien.ferrandez.free.fr/proxy.txt");    
echo "\tContenu du fichier: $contenu\n";

// deuxième requete...cache utilisé !
$contenu = $proxy->telecharger("http://sebastien.ferrandez.free.fr/proxy.txt");
echo "\tContenu du fichier: $contenu\n";
