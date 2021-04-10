const bcrypt = require("bcrypt")
const knex = require('../knexlogdb.js');

exports.signup = async (req, res, next)=>{
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
          message:error,
        });
      }
      console.log(req.body);
      
      return res.status(201).json({
        statusCode: 201,
        message:"Compte crée !!!!!!!",
      });
};

exports.login = async (req, res, next)=>{
    try {
        user = await knex('utilisateurs').where({
            email: req.body.email
        })
    } catch (error) {
        return res.status(500).json({
            statusCode: 500,
            message: 'Internal Server Error',
            errors:[{
              message:'Erreur interne du serveur.',
            }],
        });
    }
    if (!user) {
        return res.status(401).json({
          statusCode: 401,
          message: 'Unauthorized',
          errors:[{
            message:'Une authentification est nécessaire pour accéder à la ressource. compte non trtouver',
          }],
        });
    }
    console.log(req.body.passwords);
    bcrypt.compare(req.body.passwords, user.passwords)
        .then(valid => {
            if (!valid) {
                return res.status(401).json({
                    statusCode: 401,
                    message: 'Unauthorized',
                    errors:[{
                        message:'Une authentification est nécessaire pour accéder à la ressource. npm faux',
                    }],
                });
            }
            return res.status(200).json({
                statusCode: 200,
                message: 'succcesful / OK',
                userId: user.id,
                token:'TOKEN'
            });
        })
        .catch(res.status(500).json({ statusCode: 500,message: 'Internal Server Error',errors:[{message:'Erreur interne du serveur.'}] }) );
};