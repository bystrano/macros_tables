
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
        style: prive
        pas: 20
    tri_defaut:
        titre: 1
        objet: 1
    filtres:
        -
            filtre: multilike
            options:
                nom_input: q
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

- **`style`** le modèle de pagination utilisé, comme `page`, `prive` ou n'importe quel modèle de pagination (cf. [le système de pagination SPIP sur spip.net](http://www.spip.net/fr_article3367.html)).

- **`pas`** le nombre d'éléments par page.


### `tri_defaut`

Si l'on veut trier le tableau par défaut, on peut définir les colonnes à trier en donnant un tableau à ce paramètre.
Les clés de ce tableau doivent être des noms de colonnes, et les valeurs soit `1` pour trier dans le sens alphanumérique, soit `-1` pour l'inverse.


### `criteres_extra`

Une liste de critères qui seront ajoutés à la boucle principale du tableau.
À mettre entre accolades comme n'importe quel critère de boucle.
On peut par exemple donner le tableau `array("{!lang}", "{tout}")`.


### `filtres`

Même si aujourd'hui on ne peut utiliser qu'un seul filtre, le paramètre `filtres` est prévu pour pouvoir en ajouter plusieurs.
Il faut donc lui donner une liste de filtres.
Les filtres doivent être définis dans un format proche du plugin Saisies.
On donne pour chaque filtre un tableau contenant une clé `filtre` définissant le filtre à utiliser et une clé `options` définissant les paramètres du filtre.

On a alors un tableau comme : (en YAML)

    filtres:
        -
            filtre: multilike
            options:
                nom_input: q
                champs:
                    - titre
                    - objet

#### `multilike`

Le seul filtre disponible pour l'instant…
Ce filtre ajoute au tableau un formulaire de recherche par mots-clés qui permet de filtrer le tableau en ajax.
Les options sont :

- **`nom_input`** définit l'attribut `name` de la balise `<input>` dans laquelle on pourra saisir des mots-clés. Ce nom sera aussi utilisé comme argument dans l'url.

- **`champs`** permet de définir les colonnes que le filtre prendra en compte. Doit être une liste de noms de colonnes.


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
