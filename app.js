const express = require("express");
const bodyParser = require("body-parser");
const mongoose = require('mongoose');

const modelPersonage = require("./models/Personages.js");
const Personages = require("./models/Personages.js");

const app = express();

mongoose.connect('mongodb+srv://RenaudFRADIN:Tigrette1792365020172020@cluster0.ookc7.mongodb.net/test?retryWrites=true&w=majority',
  { useNewUrlParser: true,
    useUnifiedTopology: true })
  .then(() => console.log('Connexion à MongoDB réussie !'))
  .catch(() => console.log('Connexion à MongoDB échouée !'));

app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content, Accept, Content-Type, Authorization');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    next();
});

app.use(bodyParser.json());

app.post('/api/stuff', (req, res, next) => {
    delete req.body._id;
    const perso = new modelPersonage({
       ...req.body
    });
    perso.save()
       .then(()=>res.status(201).json({message:"objt creee !!!!!!!"}))
       .catch(error=>res.status(400).json({error}))
   });


app.get('/api/perso', (req, res, next) => {
    modelPersonage.find()
        .then(Personages=>res.status(200).json({
          statusCode:200,
          message: 'succcesful / OK',
          Personages,
        }))
        .catch(error=>res.status(400).json({
          error,
          statusCode:400,
          message:'Bad Request',
          errors:[
            error
          ]
        }));
});

app.get('/api/perso/:id', (req, res, next) => {
    modelPersonage.findOne({ _id: req.params.id })
        .then( Personage =>res.status(200).json({
          statusCode:200,
          message: 'succcesful / OK', 
          Personage,
        }))
        .catch(error=>res.status(400).json({ 
          error,
          statusCode:400,
          message:'Bad Request',
          errors:[
            error
          ]
         }));
});


module.exports = app;