const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:10000 });
const cacheId = new NodeCache({ stdTTL:5 });

//get all Ability
exports.getAbility = async (req,res,next) => {
  let abilitys = [];
  let CountAbilitys = {};

  if (cache.has("abilitys") && cache.has("countAbilitys")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countAbilitys: cache.get("countAbilitys"),
      abilitys: cache.get("abilitys")
  });
  } else {
    try {
      abilitys = await knex.select("*").from('ability').orderBy('id','desc');
      CountAbilitys = await knex.select().from('ability').count();
      CountAbilitys = CountAbilitys[0].count;
      cache.set("abilitys",abilitys,10000);
      cache.set("countAbilitys",CountAbilitys,10000);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad request',
        errors:[{
          message: 'failed to query database'
        }],
      })
    }
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countAbilitys: CountAbilitys,
      abilitys: abilitys
    });
  }
}

//get One Ability for one Id 
exports.getAbilityId = async (req,res,next) => {
  let ability = [];
  if (cacheId.has("ability")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      Ability: cacheId.get("ability"),
    });
  } else {
    try {
      ability = await knex.select('*').from('ability').where({ id: req.params.id });
      cacheId.set("ability",ability);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
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
}