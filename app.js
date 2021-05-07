const express = require("express");
const bodyParser = require("body-parser");
//const fileUpload = require('express-fileupload');
const app = express();

const routerpersonage = require('./routes/personages.js');
const routerusers = require('./routes/users.js');
//const knex = require("./knexlogdb.js");

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

//app.use(fileUpload());

//router
app.use('/perso', routerpersonage);
app.use('/auth', routerusers);

app.get('/',(req, res, next)=>{
    return res.status(200).json({
        statusCode: 200,
        message:"Bonjour bienvenue sur l'api saoars développer par Renaud Fradin https://github.com/Renaudfradin"
    })
})

/*app.post('/uploads', async function(req, res) {
    console.log(req.files.img_personage_list); // the uploaded file object
    const {name, data} = req.files.img_personage_list;
    img = await knex.insert({img_personage_list:name ,img_peronage_detail:data}).into('personage').where('id', 152);
    console.log(img);
});
*/
module.exports = app;