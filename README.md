<h1 align="center">
  API-SAOARS
</h1>

## About

API that gathers characters and other information from the mobile game Sword Art Online Alicization Rising Steel / Unleash Blading.

Data/images related to the game Sword Art Online Alicization Rising Steel / Unleash Blading are credited to Bandai Namco Entertainment Inc. and other respective authors.

<br />

API qui regroupe les personnages et d'autres informations du jeux mobile Sword Art Online Alicization Rising Steel / Unleash Blading

Crédits des données/images relatives au jeu Sword Art Online Alicization Rising Steel/Unleash Blading à Bandai Namco Entertainment Inc. et autres auteurs respectifs

### 🛠 Installation & Set Up

1. Install dependencies

```sh
composer install
```

2. Run migration and factory

```sh
./vendor/bin/sail migrate
```

```sh
./vendor/bin/sail artisan migrate:fresh --seed
```

3. Start the development server

```
./vendor/bin/sail up
```

4. Access the API

```
http://127.0.0.1:8001
```

### 📚 Documentation

[API Documentation](http://127.0.0.1:8001/api/documentation)
