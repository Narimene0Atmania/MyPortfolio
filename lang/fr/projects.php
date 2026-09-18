<?php

// TEXTE PROVISOIRE — brouillon rédigé pour tester les nouvelles vues
// projects/ (index et détail). À revoir avant publication.

return [

    'back' => 'retour',
    'next' => 'projet suivant',
    'live' => 'voir le site',
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
            'summary' => 'Planification des repas, suivi des calories, et la liste de courses qui en découle.',
            'scope' => 'projet personnel',
            'body' => 'Les applications de planification de repas s\'arrêtent souvent au plan, laissant à l\'utilisateur le soin de construire lui-même la liste de courses et de suivre les calories ailleurs. Meal Plannari relie les trois — planifiez une semaine de repas, suivez le compte de calories au fil de l\'eau, et obtenez une liste de courses générée directement à partir de ce que vous avez planifié.',
            'role' => 'concept, design, développement',
            'timeline' => 'projet personnel en cours',
            'features' => [
                ['Planificateur hebdomadaire', 'organisez les repas de la semaine en une seule vue.'],
                ['Liste de courses automatique', 'générée directement à partir des repas planifiés, pas une étape à part.'],
                ['Suivi des calories', 'voyez où vous en êtes en planifiant, pas après coup.'],
            ],
        ],

        'loai-atmania' => [
            'title' => 'Loai Atmania — Portfolio Consultant IA',
            'hero_alt' => 'La page d\'accueil sombre au style tableau de bord du portfolio, avec une carte de profil, un panneau de statistiques système en direct, et trois cartes de résultats d\'automatisation.',
            'summary' => 'Un portfolio sombre et orienté données pour un consultant en infrastructure IA.',
            'scope' => 'projet client',
            'body' => 'Un consultant avait besoin d\'un portfolio qui se lit comme une preuve, pas comme un CV — la preuve que l\'automatisation tient réellement la route en production. Le résultat est une page sombre au style tableau de bord : statistiques système en direct, métriques d\'automatisation avant/après, et répartition des compétences dès le premier écran, plutôt qu\'une biographie.',
            'role' => 'design, développement',
            'timeline' => 'projet client',
            'features' => [
                ['Panneau système en direct', 'compteurs animés de disponibilité, de flux de travail et d\'heures économisées, qui rendent les gains d\'automatisation mesurables.'],
                ['Cartes de résultats avant/après', 'trois cartes de cas d\'automatisation — tri des factures, routage des prospects, copilote support — chacune montrant un gain chiffré concret.'],
                ['Navigation basse persistante', 'une barre d\'action fixe gardant compétences, réalisations et un bouton « réserver un appel » toujours accessibles.'],
            ],
        ],

    ],

];
