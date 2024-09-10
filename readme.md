# FFVBScoreParser

Ce projet contient une classe PHP nommée FFVBScoreParser conçue pour récupérer et analyser les données de classement et de matchs de beach-volleyball de la Fédération Française de Volley-Ball Beach (FFVB).

## Fonctionnement

La classe FFVBScoreParser permet d'obtenir les informations suivantes :

    Classement des équipes
    Détails des matchs passés et à venir

## Utilisation

Pour utiliser cette classe, vous devez créer une instance avec les paramètres suivants :

```php
$saison = "2023/2024";
$codent = "LIFL"; // Code de la poule
$poule = "1MC";   // Numéro de la poule
$equipe = "3";     // Numéro de l'équipe

$parser = new FFVBScoreParser($saison, $codent, $poule, $equipe);
```

Ensuite, vous pouvez accéder aux données avec les méthodes suivantes :

```php
// Récupération du classement sous forme de tableau associatif
$classement = $parser->getClassement();

// Récupération des matchs sous forme de tableau associatif avec les scores
$games = $parser->getGames();
```

### Exemple complet

Voici un exemple complet d'utilisation :


```php
$saison = "2023/2024";
$codent = "LIFL";
$poule = "1MC";
$equipe = "3";

$parser = new FFVBScoreParser($saison, $codent, $poule, $equipe);

echo "<pre>";
var_dump($parser->getClassement()); // Récupération du classement sous forme de tableau associatif
var_dump($parser->getGames());       // Récupération des matchs sous forme de tableau associatif avec les scores
echo "</pre>";
```

## Dépendances

 Cette classe ne nécessite pas de dépendance externe majeure, mais elle suppose l'existence de la bibliothèque PHP DOMDocument pour manipuler le contenu HTML.


## Licence
La classe est diffusé sous la license GPLV2.

