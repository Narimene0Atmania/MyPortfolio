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
            'summary' => 'Suivi en direct des bus et gestion par rôles pour plusieurs flottes universitaires.',
            'scope' => 'projet de fin d\'études',
            'body' => 'BusCatcher donne à chaque rôle — d\'un administrateur de plateforme supervisant plusieurs universités jusqu\'aux chauffeurs et étudiants — une vue en direct de la flotte : géolocalisation avec alertes automatiques de déviation et d\'arrêt prolongé, gestion des lignes et des arrêts, et des notifications qui gardent tout le monde à l\'heure. Réalisé avec ma partenaire de projet de fin d\'études, Madjida Benmammar.',
            'role' => 'analyse des besoins, conception système, développement',
            'timeline' => 'projet de fin d\'études',
            'features' => [
                ['Suivi GPS en direct avec alertes automatiques', 'positions des bus en temps réel sur une carte du campus — le système signale de lui-même quand un bus dévie de sa ligne ou s\'attarde trop longtemps à un arrêt.'],
                ['Une vraie hiérarchie multi-universités', 'administrateur de plateforme, administrateur d\'université, opérateur, chauffeur et étudiant ont chacun leur propre tableau de bord, conçu pour faire tourner plusieurs universités sur une seule plateforme plutôt qu\'un seul campus.'],
                ['Gestion des lignes et arrêts', 'les administrateurs configurent lignes, arrêts et horaires sans toucher au code, jusqu\'à choisir entre des itinéraires routiers alternatifs pour chaque segment d\'une ligne.'],
            ],
        ],

        'plannari' => [
            'title' => 'Plannari',
            'hero_alt' => 'Une scène de bureau illustrée — un calendrier mural, une liste de tâches, et un ordinateur portable affichant un tableau de bord de planification — évoquant l\'esprit de Plannari.',
            'sketch_alt' => 'Un croquis au stylo de la même scène de bureau — panneau à épingles, étagère, calendrier mural, liste de tâches et ordinateur portable — le dessin de concept original de la navigation de l\'application.',
            'sketch_caption' => 'Du croquis à l\'illustration finale — chaque objet du dessin s\'y retrouve.',
            'summary' => 'Un planificateur où l\'heure est facultative, et rien d\'inachevé ne disparaît simplement.',
            'scope' => 'projet personnel',
            'body' => 'La plupart des applications de tâches imposent une heure à chaque tâche, puis enterrent discrètement ce qui n\'a pas été fait. Plannari rend l\'heure facultative — une tâche peut en porter une, une durée, ou ni l\'une ni l\'autre — et tout ce qui reste non coché après sa journée devient une dette que vous replanifiez, renvoyez ou abandonnez consciemment, au lieu de simplement disparaître. La navigation, c\'est la scène de bureau illustrée ci-dessus : touchez le calendrier, la liste de tâches ou l\'ordinateur pour ouvrir l\'écran correspondant.',
            'role' => 'concept, design, développement',
            'timeline' => 'projet personnel en cours',
            'features' => [
                ['L\'heure est facultative, jamais imposée', 'une tâche peut porter une heure, une durée, ou ni l\'une ni l\'autre — les tâches à heure fixe restent verrouillées dans l\'ordre, les autres se glissent où vous voulez entre elles.'],
                ['Des dettes plutôt que des tâches qui disparaissent', 'tout ce qui reste non coché après sa journée devient une dette consultable — à replanifier, renvoyer à sa catégorie, ou supprimer, depuis la vue des dettes ou le calendrier.'],
                ['Des routines qui s\'enchaînent et détectent les conflits', 'ajouter une routine copie ses étapes dans la journée ; avec une heure de départ, les étapes s\'enchaînent selon leur durée, en décalant, superposant ou annulant automatiquement en cas de conflit.'],
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
