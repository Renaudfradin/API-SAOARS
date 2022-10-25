const express = require("express");
const bodyParser = require("body-parser");
const swaggerUi = require('swagger-ui-express')
const swaggerFile = require('./swagger-output.json')
const app = express();

//import route
const routerCharacter = require('./routes/characters.js');
const routerWeapon = require('./routes/weapon.js');
const routerEquipment = require('./routes/equipment.js');
const routerAbility = require('./routes/ability.js');
const routerBanner = require('./routes/banner.js');
const routerImagination = require('./routes/imagination.js');
//const routerusers = require('./routes/users.js');

var options = {
  explorer: true,
  // customCssUrl: '/public/css/swagger-ui.css', 
  // customSiteTitle: "The Words That I Know API - Swagger"
};

app.use((req, res, next) => {
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  next();
});

//header
app.use(bodyParser.json());
app.use(bodyParser.json({ type: 'application/*+json' }));
app.use(bodyParser.raw({ type: 'application/vnd.custom-type' }));
app.use(bodyParser.text({ type: 'text/html' }));
app.use(bodyParser.text({ type: 'text/plain' }));
app.use(bodyParser.urlencoded({ extended: false }));

//router
app.use('/perso', routerCharacter);
app.use('/weapon', routerWeapon);
app.use('/equipment', routerEquipment);
app.use('/ability', routerAbility);
app.use('/banner', routerBanner);
app.use('/imagination', routerImagination);
//app.use('/auth', routerusers);

app.use('/doc', swaggerUi.serve, swaggerUi.setup(swaggerFile,options));

//base route
app.get('/',(req, res, next)=>{
  return res.status(200).json({
    statusCode: 200,
    message:"Bonjour bienvenue sur l'api SAOARS/UB développer par Renaud Fradin https://github.com/Renaudfradin ,Documentations : https://api-saoars.herokuapp.com/doc/ , Crédits des données/images relatives au jeu Sword Art Online Alicization Rising Steel/Unleash Blading à Bandai Namco Entertainment Inc. et autres auteurs respectifs."
  })
})

module.exports = app;