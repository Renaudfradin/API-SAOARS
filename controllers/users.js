const bcrypt = require("bcrypt");
const jsonwebtoken = require("jsonwebtoken");
const knex = require('../knexlogdb.js');

//add user
exports.signup = async (req, res, next)=>{
    let usermail = [];
    try {
      usermail = await knex('utilisateurs').where({
            email: req.body.email
      });  
    } catch (error) {
        return res.status(500).json({
            statusCode: 500,
            message: 'Internal Server Error',
            errors:[{
              message:'Erreur interne du serveur.1ccc',
            }],
        });
    }
    if (usermail.length == 0) {
      try {
        utilisateurs = await knex('utilisateurs').insert({
            id: req.body.id,
            pseudo: req.body.pseudo,
            passwords: bcrypt.hashSync(req.body.passwords, bcrypt.genSaltSync(10)),
            email: req.body.email
        });
      } catch (error) {
        return res.status(400).json({
          statusCode: 400,
          message:error
        });
      }
      console.log(req.body);
      return res.status(201).json({
        statusCode: 201,
        message:"Compte crée !!!!!!!",
      });
    }else{
      return res.status(400).json({
        statusCode: 400,
        message:"email deja pris !!!!!!!",
      });
    }
};

//login
exports.login = async (req, res, next)=>{
    let userlog = [];
    try {
      userlog = await knex('utilisateurs').where({
            email: req.body.email
      });  
    } catch (error) {
        return res.status(500).json({
            statusCode: 500,
            message: 'Internal Server Error',
            errors:[{
              message:'Erreur interne du serveur.1',
            }],
        });
    }
    //si la requette ne trouve aucun users on renvoi un 401 sion il va comparer le password de la db avec celui rentre
    if (userlog.length == 0) {
      return res.status(401).json({
        statusCode: 401,
        message: 'Unauthorized',
        errors:[{
          message:'Une authentification est nécessaire pour accéder à la ressource. compte non trtouver',
        }],
      });
    }else{
      //console.log(req.body.email);
      //console.log(req.body.passwords);
      //console.log(userlog[0].passwords);
      //console.log(userlog[0]);
      const match = await bcrypt.compare(req.body.passwords, userlog[0].passwords);
      console.log(match);
      if (match) {
        return res.status(200).json({
          statusCode: 200,
          message: 'succcesful / OK',
          userId: userlog[0].id,
          token: jsonwebtoken.sign(
            { userId: userlog[0].id }, //truc a encoder
            'RANDOM_TOKEN_SECRET',  //cle d'encodage
            { expiresIn: '1h' } //dure du token
          )
        });
      }else{
        return res.status(401).json({
          statusCode: 401,
          message: 'Unauthorized',
          errors:[{
              message:'Une authentification est nécessaire pour accéder à la ressource. npm faux',
          }]
       })
      };
    }; 
};  



//get all users
exports.getuser = async (req, res, next) => {
  let users = [];
  try {
  users = await knex.select(['id','pseudo','passwords','email']).from('utilisateurs');
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
      User: users,
  });
}