<?php

// TEXTE PROVISOIRE — brouillon rédigé pour tester les nouvelles vues
// projects/ (index et détail). À revoir avant publication.

return [

    'back' => 'retour',
    'next' => 'projet suivant',
    'live' => 'voir la démo',
    'source' => 'code source',
    'private_note' => 'le code source est privé — propriété de l\'université, issu de mon projet de fin d\'études.',

    'status' => [
        'shipped' => 'livré',
        'in_use' => 'en usage',
        'archived' => 'archivé',
    ],

    'items' => [

        'buscatcher' => [
            'title' => 'BusCatcher',
            'hero_alt' => 'L\'écran de confirmation d\'itinéraire de BusCatcher, montrant un trajet de bus en trois segments sur une carte de Tlemcen, avec des options de route alternatives pour chaque segment.',
            'summary' => 'Suivi en direct des bus et gestion par rôles pour un campus universitaire.',
            'scope' => 'projet de fin d\'études',
            'body' => 'Les étudiants et le personnel n\'avaient aucun moyen de savoir quand leur bus arriverait réellement, et le service transport gérait horaires et chauffeurs à la main. BusCatcher donne à chaque rôle — administrateur, chauffeur, usager — une vue en direct de la flotte : géolocalisation en temps réel, gestion des lignes et des arrêts, et des notifications qui gardent tout le monde à l\'heure.',
            'role' => 'analyse des besoins, conception système, développement',
            'timeline' => 'projet de fin d\'études',
            'features' => [
                ['Suivi GPS en direct', 'positions des bus en temps réel sur une carte du campus, mise à jour en continu.'],
                ['Tableaux de bord par rôle', 'des vues distinctes pour administrateurs, chauffeurs et étudiants, chacune limitée à ce dont elle a besoin.'],
                ['Gestion des lignes et arrêts', 'les administrateurs configurent lignes, arrêts et horaires sans toucher au code.'],
            ],
        ],

        'plannari' => [
            'title' => 'Plannari',
            'hero_alt' => 'Une scène de bureau illustrée — un calendrier mural, une liste de tâches, et un ordinateur portable affichant un tableau de bord de planification — évoquant l\'esprit de Plannari.',
            'summary' => 'Un planificateur qui garde les tâches du jour, et seulement celles-là, en vue.',
            'scope' => 'projet personnel',
            'body' => 'La plupart des applications de tâches enterrent celles du jour sous des semaines de retard accumulé, ou exigent une structure de projet rigide avant même d\'ajouter une tâche. Plannari simplifie tout ça — une vue journalière claire pour organiser ce qui est vraiment devant vous, avec juste assez de structure pour rester organisé sans que ça devienne une contrainte en soi.',
            'role' => 'concept, design, développement',
            'timeline' => 'projet personnel en cours',
            'features' => [
                ['Vue centrée sur le jour', 'la liste du jour au premier plan, pas enterrée dans une arborescence de projets.'],
                ['Ajout rapide', 'ajouter une tâche en un geste, sans champs obligatoires à remplir avant.'],
            ],
        ],

        'mealplannari' => [
            'title' => 'Meal Plannari',
            'hero_alt' => 'La page de parcours des repas, montrant une grille de fiches repas avec photo, calories, macros, prix, temps de préparation et statut des courses pour chacune.',
            'summary' => 'Planification des repas, suivi des calories, et un badge « prêt sans courses » pour chaque recette.',
            'scope' => 'projet personnel',
            'body' => 'Les applications de suivi calorique payantes ne permettent presque jamais de créer ses propres recettes, et rien n\'indique si le plan du soir nécessite un saut au magasin. Meal Plannari règle les deux : une semaine de repas dont la nutrition et le prix sont calculés en direct à partir d\'une base d\'ingrédients partagée, vos propres recettes gardées privées ou soumises à une file de modération pour rejoindre le catalogue public, et un badge par repas indiquant s\'il est prêt avec le garde-manger ou s\'il faut faire des courses.',
            'role' => 'concept, design, développement',
            'timeline' => 'projet personnel en cours',
            'features' => [
                ['Nutrition calculée à partir des ingrédients', 'calories, macros et prix de chaque repas sont dérivés en direct d\'une base d\'ingrédients partagée, jamais saisis à la main — changez un ingrédient et chaque repas qui l\'utilise se met à jour.'],
                ['Créer et soumettre ses propres recettes', 'recettes bilingues avec étapes et ingrédients, gardées privées ou envoyées à une file de modération admin pour rejoindre le catalogue public.'],
                ['Badge « prêt sans courses »', 'un indicateur par repas basé sur la part de la recette composée d\'ingrédients de base déjà présents, face à ce qu\'il faut vraiment acheter.'],
            ],
        ],

        'loai-atmania' => [
            'title' => 'Portfolio Consultant IA',
            'hero_alt' => 'La page d\'accueil sombre au style tableau de bord du portfolio, avec une carte de profil, un panneau de statistiques système en direct, et trois cartes de résultats d\'automatisation.',
            'summary' => 'Un portfolio sombre et orienté données pour un consultant en infrastructure IA.',
            'scope' => 'projet freelance',
            'body' => 'Ce portfolio devait se lire comme une preuve, pas comme un CV — la preuve que l\'automatisation tient réellement la route en production. Le résultat est une console fixe, façon HUD, en plein écran sans défilement : une séquence de démarrage au chargement, puis six onglets — accueil, capacités, réalisations, processus, stack, contact — accessibles via une barre d\'icônes en bas, avec des statistiques système en direct et des métriques d\'automatisation avant/après en guise de biographie.',
            'role' => 'design, développement',
            'timeline' => 'projet freelance',
            'features' => [
                ['Un style pensé pour le métier', 'séquence de démarrage, sorties façon terminal, vocabulaire opérateur/agent — elle reflète le produit réel : des agents IA qui pilotent de l\'infrastructure.'],
                ['Deux mises en page pensées séparément', 'sous 1024px, le site bascule vers une mise en page défilante entièrement différente, pas une version bureau réduite — construite à partir des mêmes données.'],
                ['Navigation HUD à six onglets', 'une barre d\'icônes fixe — accueil, capacités, réalisations, processus, stack, contact — avec un bouton « réserver un appel » toujours accessible.'],
            ],
        ],

    ],

];
