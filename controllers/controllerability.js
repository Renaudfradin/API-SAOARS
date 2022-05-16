const knex = require('../knexlogdb.js');

exports.getAbility = async (req,res,next)=>{
    let abilitys = [];
    try {
        abilitys = await knex.select().from('ability').orderBy('id','desc');
    } catch (error) {
        return res.status(400).json({
            statusCode: 400,
            message: 'Bad request',
            errors:[{
                message: 'failed to query database'
            }],
        })
    }
    return res.status(200).json({
        statusCode: 200,
        message: 'succesful / OK',
        Abilitys: abilitys
    });
}

exports.getAbilityId = async (req,res,next) => {
    let ability = [];
    try {
        ability = await knex('ability').where({ id: req.params.id })
    } catch (error) {
      return res.status(400).json({
        statusCode: 400,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database',
        }],
      });
    }
    if (ability.length == 0) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database / 404',
        }],
      });
    }else{
      return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        Ability: ability,
      });
    }
}