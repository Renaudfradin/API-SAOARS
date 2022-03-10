## API SAOARS
API qui regroupe les personnages du jeux mobile Sword Art Online Alicization Rising Steel

Affiche tout les personnages : https://api-saoars.herokuapp.com/perso

Affiche un personnage selon son ID : https://api-saoars.herokuapp.com/perso/:id

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


<!-- delete data
compte obligatoire
https://api-saoars.herokuapp.com/delete/:id 

create data
compte obligatoire
https://api-saoars.herokuapp.com/insert

update data 
compte obligatoire
https://api-saoars.herokuapp.com/update/:id -->


