const express = require("express");
const bodyParser = require("body-parser");
//const mongoose = require('mongoose');

//const modelPersonage = require("./models/Personages.js");

const app = express();

/*mongoose.connect('mongodb+srv://RenaudFRADIN:Tigrette1792365020172020@cluster0.ookc7.mongodb.net/test?retryWrites=true&w=majority',
  { useNewUrlParser: true,
    useUnifiedTopology: true })
  .then(() => console.log('Connexion à MongoDB réussie !'))
  .catch(() => console.log('Connexion à MongoDB échouée !'));
*/

const knex = require("knex")({
    client :"pg",
    connection:{
        connectionString: '###',
        ssl:{
            rejectUnauthorized: false,
        }
    },
});


app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content, Accept, Content-Type, Authorization');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    next();
});

app.use(bodyParser.json());


/*
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
*/
app.get('/api/perso', async (req, res, next) => {

    let persos = [];
    try {
      persos = await knex.select(['id','name','description','description1','attack1','descriptionattack1','mpattack1','attack2','descriptionattack2','mpattack2','attack3','descriptionattack3','mpattack3','hp','mp','atk','def','mdef','crit','spd']).from('personage');
    } catch (error) {
        console.log('An error occured: ', error);
        return res.status(400).json({
            statusCode: 400,
            message: 'Bad Request',
            errors:[{
                message:'failed to query database',
            }],
        });
    }
    //console.log(users);
    return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        Personage: persos,
    });
});

app.get('/api/perso/:id' , async (req,res,next)=>{
  let perso = [];
  try {
    perso = await knex('personage').where({
      id: req.params.id
    })
  } catch (error) {
    console.log('An error occured: ', error);
        return res.status(400).json({
            statusCode: 400,
            message: 'Bad Request',
            errors:[{
              message:'failed to query database',
            }],
        });
  }
  console.log(perso);
  if (perso.length == 0) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database',
      }],
    });
  }else{
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      Personage: perso,
  });
  }
  

});



module.exports = app;