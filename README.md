
Macros Tables
=============

Ce plugin propose aux développeurs une façon de créer facilement des tableaux de données en utilisant les facilités qu'offre SPIP pour la pagination, le tri et la recherche.
Les données peuvent être soit des objets éditoriaux, soit des tableaux arbitraires qu'on passe en paramètre.

Les fonctions de SPIP pour la pagination, bien que fort pratiques, ont leurs limites.
Si on a un tableau qui affiche des rubriques, on ne peux pas le réutiliser pour afficher des articles sans dupliquer le code, ce qui alourdi le développement et la maintenance.
On utilise ici le plugin [macros](http://plugins.spip.net/macros.html), qui permet de générer des squelettes à la volée, pour régler ce problème.

On commence par définir un tableau avec un ensemble de paramètres sous forme de tableau.
Ce tableau définit quel objet éditorial utiliser, quelle pagination, et quels filtres.
On donne aussi la liste des colonnes que l'on veut afficher, et le plugin se charge tout seul de créer le squelette correspondant.

Un fois le plugin installé, on peut voir une démonstration de tableau généré à l'adresse `/ecrire/?exec=demo_macros_tables`.


Utilisation
-----------

Le plugin propose deux macros : `table-objet` qui permet de créer un tableau qui affichera un objet éditorial, et `table-data`, qui permet d'afficher des données arbitraires, que l'on fournit sous forme de tableau.

Comme les tableaux des paramètres que l'on va passer à ces macros seront assez gros, il est vite fastidueux de les écrire sous forme de squelette SPIP.
Du coup je préfère utiliser le plugin yaml, et définir les paramètres sous forme de fichier yaml :

    <INCLURE{fond=#MACRO{macros/table-data,
                         #CHEMIN{tables/table-artrub.yaml}|decoder_yaml},
             env,
             donnees=#GET{tableau},
             ajax=tabledata}>

On n'a alors plus qu'à écrire un fichier `tables/table-artrub.yaml`, par exemple :

    nom_ajax: tabledata
    pagination:
        modele: prive
        pas: 20
    tri_defaut:
        titre: 1
        objet: 1
    filtres:
        -
            filtre: multilike
            options:
                nom: q
            champs:
                - titre
                - objet
    colonnes:
        -
            colonne: champ
            options:
                nom: titre
                entete: "titre"
                cellule: "[(#VALEUR|table_valeur{titre})]"
                tri: oui
                ajax: oui
        -
            colonne: champ
            options:
                nom: objet
                entete: "type"
                cellule: "[(#VALEUR|table_valeur{objet})]"
                tri: oui
                ajax: oui


Paramètres des macros
---------------------


### `nom_ajax`

Si l'on souhaite un tableau que l'on pourra trier et/ou filtrer en ajax, il faut définir cet identifiant ajax.
Pour que cela fonctionne, il est impératif de donner ce même nom ajax à l'`<INCLURE>` qui appelle la macro.
Dans l'exemple ci-dessus, on a par exemple donnée le nom ajax `tabledata` à la fois comme paramètre de l'`<INCLURE>`, et comme paramètre de la macro.


### `objet`

Le nom de l'objet éditorial qui sera utilisé pour remplir le tableau.
Doit être **au singulier**, disponible uniquement sur la macro `table-objet`.


### `pagination`

Le paramètre `pagination` doit être un tableau avec deux clés :

- **`modele`** le modèle de pagination utilisé, comme `page`, `prive` ou n'importe quel modèle de pagination (cf. [le système de pagination SPIP sur spip.net](http://www.spip.net/fr_article3367.html)).

- **`pas`** le nombre d'éléments par page.


### `tri_defaut`

On peut définir l'ordre de tri des données, par colonne, en donnant un tableau à ce paramètre.
Les clés de ce tableau doivent être des noms de colonnes, et les valeurs soit `1` pour trier dans le sens alphanumérique, soit `-1` pour l'inverse.
Cette option est obligatoire si l'on veut permettre aux utilisateurs de changer eux-même l'ordre de tri en utilisant l'option `tri` sur un champ.


### `criteres_extra`

Une liste de critères qui seront ajoutés à la boucle principale du tableau.
À mettre entre accolades comme n'importe quel critère de boucle.
On peut par exemple donner le tableau `array("{!lang}", "{tout}")`.


### `filtres`

Le paramètre `filtres` permet d'ajouter plusieurs filtres, il faut donc lui donner une liste de filtres.
Les filtres doivent être définis dans un format proche du plugin Saisies.
On donne pour chaque filtre un tableau contenant une clé `filtre` définissant le filtre à utiliser et une clé `options` définissant les paramètres du filtre.

On a alors un tableau comme : (en YAML)

    filtres:
        -
            filtre: multilike
            options:
                nom: q
                champs:
                    - titre
                    - objet
        -
            filtre: checkboxes
            options:
                nom: regions
                datas:
                    - "Bruxelles"
                    - "Liège"
                    - "Namur"
                    - "Luxembourg"
                champs: regions
                ajax: oui
                autosubmit: oui


#### `multilike`

Ce filtre ajoute au tableau un formulaire de recherche par mots-clés qui permet de filtrer le tableau en ajax.
Les options sont :

- **`nom`** définit l'attribut `name` de la balise `<input>` dans laquelle on pourra saisir des mots-clés. Ce nom sera aussi utilisé comme argument dans l'url pour pouvoir précharger le filtre.

- **`champs`** permet de définir les colonnes sql que le filtre prendra en compte. Doit être une liste de noms de colonnes. Pour les macros `table-objet`, ce doivent être des noms de colonnes SQL.

- **`ajax`** permet de filtrer le tableau en ajax, sans rafraîchir toute la page.

- **`options_saisie`** Les options qui seront passées à la saisie input que l'on proposera à l'utilisateur.
  Permet de définir un label, donner une classe ou autre.

#### `checkboxes`

Le filtre checkboxes propose une saisie checkbox pour filtrer les données. Il ne fonctionne qu'avec la macro `table-data`.

- **`nom`** définit l'attribut `name` de la balise `<input>` dans laquelle on pourra saisir des mots-clés. Ce nom sera aussi utilisé comme argument dans l'url pour pouvoir précharger le filtre.

- **`datas`** qui permet de définir les cases qui seront affichées. On lui donne un tableau clé/valeur, comme pour la saisie checkbox.

- **`champs`** permet de définir les colonnes sql que le filtre prendra en compte. Doit être une liste de noms de colonnes. Pour que le filtre fonctionne correctement les valeurs dans ces champs doivent être du type : `"cle1,cle2,cle3"`. Le plus simple est probablement d'utiliser des valeurs gérées par une saisie checkbox avec les mêmes `datas`.

- **`options_saisie`** Les options qui seront passées à la saisie input que l'on proposera à l'utilisateur.
  Permet de définir un label, donner une classe ou autre.

- **`ajax`** permet de filtrer le tableau en ajax, sans rafraîchir toute la page.

- **`autosubmit`** permet de recharger le tableau en ajax automatiquement dès qu'on coche/décoche une case. Fait disparaître le bouton de submit, qui n'est plus nécessaire. Ne fonctionne que si l'option `ajax` est activée.

### `classe_ligne`

Ce paramètre permet d'incruster un morceau de squelette dans l'attribut `class` de chaque ligne du tableau.
On peut donc y mettre n'importe quelle chaîne de langue, balise, filtre ou autre, et ce code sera executé dans le contexte de la boucle principale du tableau.

### `colonnes`

Le paramètre `colonnes` permet de définir les colonnes du tableau, en lui passant une liste de colonnes.
Un peu comme les filtres (et les saisies), chaque colonne doit être un tableau avec une clé `colonne` qui définit le type de colonnes, et une clé `options` qui donne les options de la colonne.

#### `champ`

Le seul type de colonne qui existe pour l'instant, mais on se garde la possibilité d'en ajouter…
Les options possibles sont :

- **`nom`** est obligatoire. Il donne un identifiant à la colonne.

- **`entete`** définit l'entête de la colonne. Il sera incrusté tel quel dans la phase de préparation de la boucle qui génére le tableau, ce qui permet d'utiliser des chaînes de langues ou des balises, comme `#GRAND_TOTAL`.

- **`cellule`** définit le contenu de chaque cellule de la colonne. Ce paramètre est inséré directement dans la boucle principale. On peut donc utiliser `#VALEUR` dans une macro `table-data`, ou `#DATE` dans une macro `table-objet`.

- **`tri`** définit si on peut trier la colonne. `oui` si on veut pouvoir trier, sinon on peut tout simplement omettre ce paramètre.

- **`ajax`** définit si on le tri se fait sans recharger complètement la page. `oui` si on veut activer le tri en ajax, sinon on peut tout simplement omettre ce paramètre.



Paramètres des squelettes générés
---------------------------------

Les paramètres que l'on peut passer aux squelettes générés par les macros.

### `donnees`

Ce paramètre n'est utilisé que pour la macro `table-data`.
Il est nécessaire pour peupler le tableau.
Ce doit être un tableau, qui sera parcouru par une boucle `DATA`.
On pourra alors utiliser ses valeurs avec les balises `#CLE` et `#VALEUR` dans le paramètre `cellule` des colonnes.

### `titre-table`

Permet de donner un titre à la table, qui sera alors affiché dans la balise `<caption>` du tableau.

### `sinon`

Le message à afficher dans le cas où la table est vide.



API
---

Les macros fournies par ce plugin sont conçues pour être étendues : il est possible de créer ses propres types de filtres ou de colonnes.

### Créer un nouveau filtre

Un filtre est défini par son nom, qui sert d'identifiant.
Ce nom ne peut donc pas contenir des caractères autres qu'alphanumériques.
Une fois qu'on a choisi ce nom (on prendra ici `nom_filtre` comme exemple), il faut créer deux fichiers dans le dossier filtres (les fichiers de filtres sont surchargeables).

- Le fichier `nom_filtre.html.php` contient la macros qui sera incluse dans le squelette du tableau. Elle contient le formulaire dont on se servira pour filtrer la table. Lors de son inclusion, la macro reçoit en paramètres les options que l'on a données au filtre.
- Le fichier `nom_filtre.php` est un fichier php dans lequel on doit implémenter 2 fonctions :
  - `filtres_nom_filtre_calculer_criteres_objet` servira pour filtrer les tables générées par la macro `table-objet`. Elle reçoit en paramètre le tableau description du filtre tel que donné dans les options de la macro. Doit retourner une chaîne de caractères qui sera insérée dans les critères de la boucle principale. Les valeurs retournées doivent donc être des suites de critères entre accolades.
  - `filtres_nom_filtre_filtrer_data` servira pour filtrer les tables générées par la macro `table-data`. Elle reçoit en paramètre le tableau de description du filtre, une variable décrivant la recherche (p.ex. les mots-clés à rechercher) et un ligne du tableau. Doit retourner `TRUE` si la ligne contient une occurence de la recherche, `FALSE` sinon.
