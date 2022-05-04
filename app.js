const express = require("express");
const bodyParser = require("body-parser");
const app = express();

const routerpersonage = require('./routes/personages.js');
const routerweapon = require('./routes/weapon.js');
//const routerusers = require('./routes/users.js');

app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content, Accept, Content-Type, Authorization');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    next();
});

app.use(bodyParser.json());
app.use(bodyParser.json({ type: 'application/*+json' }));
app.use(bodyParser.raw({ type: 'application/vnd.custom-type' }));
app.use(bodyParser.text({ type: 'text/html' }));
app.use(bodyParser.text({ type: 'text/plain' }));
app.use(bodyParser.urlencoded({ extended: false }));

//router
app.use('/perso', routerpersonage);
app.use('/weapon', routerweapon);
//app.use('/auth', routerusers);

app.get('/',(req, res, next)=>{
    return res.status(200).json({
        statusCode: 200,
        message:"Bonjour bienvenue sur l'api SAOARS/UB développer par Renaud Fradin https://github.com/Renaudfradin , Crédits des données/images relatives au jeu Sword Art Online Alicization Rising Steel/Unleash Blading à Bandai Namco Entertainment Inc. et autres auteurs respectifs."
    })
})
module.exports = app;