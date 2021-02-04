const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const routerpersonage = require('./routes/personages.js');

app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content, Accept, Content-Type, Authorization');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    next();
});

app.use(bodyParser.json());

/*app.post('/api/perso/insert', async (req, res, next)=>{
  try {
    console.log(...req.body);
    const perso =  knex('personage').insert(...req.body);
    console.log(perso);
  } catch (error) {
    return res.status(400).json({
      statusCode: 400,
      message:error,
    });
  }
  return res.status(201).json({
    statusCode: 201,
    message:"objt creee !!!!!!!",
    Personage:perso
  });
  
});*/

//router
app.use('/api/perso', routerpersonage);

module.exports = app;