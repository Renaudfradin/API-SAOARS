## API SAOARS
API qui regroupe les personnages du jeux mobile Sword Art Online Alicization Rising Steel / Unleash Blading

Affiche tout les personnages : /perso

```json
{
    "statusCode": 200,
    "message": "succcesful / OK",
    "Personage": [
        {
            "id": 1,
            "name": "Kirito",
            "description": "L’égaré de Vector",
            "profile": "Il s’agit d’un garçon qui s’est réveillé dans le monde virtuel « Underworld (UW) ». Il a gardé sa conscience et ses souvenirs du monde réel qui devaient être bloqués à l’origine lors de la plongée, mais il ne se souvient pas des événements qui se sont produits juste avant son arrivée dans ce monde. Il a remarqué que les habitants de l’Underworld ne sont ni des humains, ni des IA. C’est pour cette raison qu’il souhaite se rendre à Centoria, la Capitale Centrale, afin de pouvoir identifier sa mission.",
            "weapon-type": "epée une main",
            "character-type": "normal",
            "atk1": "Horizontal A",
            "atk1-type": "A",
            "description-atk1": "Attaque tranchante sur tous les ennemis (forte).",
            "mp-atk1": 32,
            "atk2": "Vertical Arc B",
            "atk2-type": "B",
            "description-atk2": "Attaque tranchante sur un ennemi (moyenne). Brise la Volonté ennemie de 40,00%",
            "mp-atk2": 18,
            "atk3": null,
            "atk3-type": null,
            "description-atk3": null,
            "mp-atk3": null,
            "hp": 3023,
            "mp": 189,
            "atk": 1414,
            "matk": 1336,
            "def": 744,
            "mdef": 744,
            "crit": 420,
            "spd": 267,
            "ultime": "Sharp Nail (Volonté)",
            "ultime-description": "Attaque tranchante de Volonté sur un ennemi.",
            "enhance-ultime": null,
            "enhance-ultime-description": null,
            "enhance-ultime-mp": null,
            "enhance-atk1": null,
            "enhance-atk1-description": null,
            "enhance-atk1-mp": null,
            "enhance-atk2": null,
            "enhance-atk2-description": null,
            "enhance-atk2-mp": null,
            "special-partner": null,
            "stars": 1,
            "cost": 2
        },
        {
            "id": 2,
            "name": "Eugeo",
            "description": "L’entailleur du Gigas Cedar",
            "profile": "Il s’agit d’un habitant de Rulid dans l’UW. Il lui a été attribué la tâche sacrée d’entailleur. Il doit frapper de sa hache 2 000 fois par jour le Gigas Cedar, un gigantesque arbre noir. C’est la première personne que Kirito a rencontrée dans ce monde et ils sont restés ensemble depuis. Il regrette toujours de ne pas avoir pu aider son amie d’enfance Alice, qui a été enlevée par un Chevalier de l’Intégrité lorsqu’ils étaient plus jeunes.",
            "weapon-type": "epée une main",
            "character-type": "eau",
            "atk1": "Slant C",
            "atk1-type": "C",
            "description-atk1": "Attaque tranchante sur un ennemi (faible). Charge la Volonté de 15,00%",
            "mp-atk1": 22,
            "atk2": "Heal Durability",
            "atk2-type": "H",
            "description-atk2": "Soins HP sur un allié (forte).",
            "mp-atk2": 21,
            "atk3": null,
            "atk3-type": null,
            "description-atk3": null,
            "mp-atk3": null,
            "hp": 2720,
            "mp": 199,
            "atk": 1283,
            "matk": 1270,
            "def": 799,
            "mdef": 768,
            "crit": 449,
            "spd": 273,
            "ultime": "Horizontal Square (Volonté)",
            "ultime-description": "Attaque tranchante de Volonté sur un ennemi.",
            "enhance-ultime": null,
            "enhance-ultime-description": null,
            "enhance-ultime-mp": null,
            "enhance-atk1": null,
            "enhance-atk1-description": null,
            "enhance-atk1-mp": null,
            "enhance-atk2": null,
            "enhance-atk2-description": null,
            "enhance-atk2-mp": null,
            "special-partner": null,
            "stars": 1,
            "cost": 2
        }
    ]
}
```

Affiche un personnage selon son ID : /perso/:id

```json
{
    "statusCode": 200,
    "message": "succcesful / OK",
    "Personage": [
        {
            "id": 1,
            "name": "Kirito",
            "description": "L’égaré de Vector",
            "profile": "Il s’agit d’un garçon qui s’est réveillé dans le monde virtuel « Underworld (UW) ». Il a gardé sa conscience et ses souvenirs du monde réel qui devaient être bloqués à l’origine lors de la plongée, mais il ne se souvient pas des événements qui se sont produits juste avant son arrivée dans ce monde. Il a remarqué que les habitants de l’Underworld ne sont ni des humains, ni des IA. C’est pour cette raison qu’il souhaite se rendre à Centoria, la Capitale Centrale, afin de pouvoir identifier sa mission.",
            "weapon-type": "epée une main",
            "character-type": "normal",
            "atk1": "Horizontal A",
            "atk1-type": "A",
            "description-atk1": "Attaque tranchante sur tous les ennemis (forte).",
            "mp-atk1": 32,
            "atk2": "Vertical Arc B",
            "atk2-type": "B",
            "description-atk2": "Attaque tranchante sur un ennemi (moyenne). Brise la Volonté ennemie de 40,00%",
            "mp-atk2": 18,
            "atk3": null,
            "atk3-type": null,
            "description-atk3": null,
            "mp-atk3": null,
            "hp": 3023,
            "mp": 189,
            "atk": 1414,
            "matk": 1336,
            "def": 744,
            "mdef": 744,
            "crit": 420,
            "spd": 267,
            "ultime": "Sharp Nail (Volonté)",
            "ultime-description": "Attaque tranchante de Volonté sur un ennemi.",
            "enhance-ultime": null,
            "enhance-ultime-description": null,
            "enhance-ultime-mp": null,
            "enhance-atk1": null,
            "enhance-atk1-description": null,
            "enhance-atk1-mp": null,
            "enhance-atk2": null,
            "enhance-atk2-description": null,
            "enhance-atk2-mp": null,
            "special-partner": null,
            "stars": 1,
            "cost": 2
        }
    ]
}
```

Affiche un ou plusieurs personnages selon son nom : /perso/:names

```json
{
    "statusCode": 200,
    "message": "succcesful / OK",
    "Personage": [
        {
            "id": 1,
            "name": "Kirito",
            "description": "L’égaré de Vector",
            "profile": "Il s’agit d’un garçon qui s’est réveillé dans le monde virtuel « Underworld (UW) ». Il a gardé sa conscience et ses souvenirs du monde réel qui devaient être bloqués à l’origine lors de la plongée, mais il ne se souvient pas des événements qui se sont produits juste avant son arrivée dans ce monde. Il a remarqué que les habitants de l’Underworld ne sont ni des humains, ni des IA. C’est pour cette raison qu’il souhaite se rendre à Centoria, la Capitale Centrale, afin de pouvoir identifier sa mission.",
            "weapon-type": "epée une main",
            "character-type": "normal",
            "atk1": "Horizontal A",
            "atk1-type": "A",
            "description-atk1": "Attaque tranchante sur tous les ennemis (forte).",
            "mp-atk1": 32,
            "atk2": "Vertical Arc B",
            "atk2-type": "B",
            "description-atk2": "Attaque tranchante sur un ennemi (moyenne). Brise la Volonté ennemie de 40,00%",
            "mp-atk2": 18,
            "atk3": null,
            "atk3-type": null,
            "description-atk3": null,
            "mp-atk3": null,
            "hp": 3023,
            "mp": 189,
            "atk": 1414,
            "matk": 1336,
            "def": 744,
            "mdef": 744,
            "crit": 420,
            "spd": 267,
            "ultime": "Sharp Nail (Volonté)",
            "ultime-description": "Attaque tranchante de Volonté sur un ennemi.",
            "enhance-ultime": null,
            "enhance-ultime-description": null,
            "enhance-ultime-mp": null,
            "enhance-atk1": null,
            "enhance-atk1-description": null,
            "enhance-atk1-mp": null,
            "enhance-atk2": null,
            "enhance-atk2-description": null,
            "enhance-atk2-mp": null,
            "special-partner": null,
            "stars": 1,
            "cost": 2
        }
    ]
}
```

Affiche le dernier personnage : /last/character

```json
{
   "statusCode": 200,
    "message": "succcesful / OK",
    "Characters": [
        {
            "id": 228,
            "name": "Asuna",
            "description": "La belle princesse sylphe",
            "profile": "Ce monde était paisible et beau , et le quotidien était agréable. Puis un jour , tout d'un coup , un méchant roi-délin m'a kidnappée. Emprisonnée , je ne peux que prier. Je souhaite qu'un héros apparaise afin de corriger ce roi-demon. Je suis sure que dans le fond , il n'est pas si méchant.",
            "weapon-type": "rapière",
            "character-type": "normal",
            "atk1": "Parrallel Sting CΣ",
            "atk1-type": "C",
            "description-atk1": "Attaque percante sur un ennemi (forte).ATK -10% (3 tour(s)).Charge la Volonté de 20%.",
            "mp-atk1": 32,
            "atk2": "Impalement A'",
            "atk2-type": "A",
            "description-atk2": "Attaque percante sur un ennemi (forte).Resistance physique +20% sur tous les alliés (2 tour(s)).",
            "mp-atk2": 44,
            "atk3": "Quadruple Stab Heal & Field A",
            "atk3-type": "A",
            "description-atk3": "Attaque percante sur un ennemi (forte).Soins HP sur l'allié ayant le moins de HP (moyenne).Recollection Field (neutre) +1.",
            "mp-atk3": 30,
            "hp": 6660,
            "mp": 236,
            "atk": 3540,
            "matk": 3150,
            "def": 1818,
            "mdef": 1980,
            "crit": 660,
            "spd": 322,
            "ultime": "Your Healing Castle",
            "ultime-description": "Attaque percante de Volonté sur un ennemi.Recollection Field spécial (normal) +3 : soins HP du perso d'élément neutre a chanque tour. ",
            "enhance-ultime": null,
            "enhance-ultime-description": null,
            "enhance-ultime-mp": null,
            "enhance-atk1": null,
            "enhance-atk1-description": null,
            "enhance-atk1-mp": null,
            "enhance-atk2": null,
            "enhance-atk2-description": null,
            "enhance-atk2-mp": null,
            "special-partner": "Yuuki",
            "stars": 4,
            "cost": 12
        }
    ]
}
```