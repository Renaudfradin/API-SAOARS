const express = require("express");
const router = express.Router();
const knex = require("knex")({
    client :"pg",
    connection:{
        connectionString: 'postgres://peohlgeivrzwcy:68848fb1718e4c0f24417a10d755ee629f11accba8d24d311889e34fdc3c7647@ec2-54-78-127-245.eu-west-1.compute.amazonaws.com:5432/d4g9hrsgfsus2',
        ssl:{
            rejectUnauthorized: false,
        }
    },
});

//delete data
router.delete('/delete/:id', async (req, res, next) =>{
    try {
      perso = await knex('personage').where('id',req.params.id).del();
    } catch (error) {
      return res.status(404).json({
        statusCode: 400,
        message: 'Bad Request',
        errors:[{
            message:'failed to query database',
        }],
      });
    }
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK'
    });
});
  
//get full data
router.get('/', async (req, res, next) => {
    let persos = [];
    try {
    persos = await knex.select(['id','name','description','description1','attack1','descriptionattack1','mpattack1','attack2','descriptionattack2','mpattack2','attack3','descriptionattack3','mpattack3','hp','mp','atk','def','mdef','crit','spd','ultime','descriptionultime','typeattack1','typeattack2','typeattack3']).from('personage');
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
  
  //get one data for id 
router.get('/:id' , async (req,res,next)=>{
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
    //console.log(perso);
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

module.exports = router;