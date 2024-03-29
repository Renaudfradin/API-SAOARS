const knex = require('../knexlogdb.js');
const NodeCache = require('node-cache');
const cache = new NodeCache({ stdTTL:10000 });

//get all imagiantions
exports.getImaginations = async (req, res, next) => {
  let Imaginations = {};

  try {
    Imaginations = await knex.select("*").from('imagination').orderBy('idconst','desc');
    cache.set("imagiation",Imaginations,10000);
  } catch (error) {
  return res.status(404).json({
    statusCode: 404,
    message: 'Bad Request',
    errors:[{
    message:'failed to query database',
    }],
  });
  }

  if (cache.has("imagination")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      imaginations: cache.get("imagination"),
    });
  }
    
  return res.status(200).json({
    statusCode: 200,
    message: 'succcesful / OK',
    imaginations: Imaginations,
  });
}

exports.getImaginationsId = async (req, res, next) => {
  let imagination = [];

  try {
    imagination = await knex.select('*').from('imagination').where({ idconst: req.params.idconst });
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database',
      }],
    });
  }
  
  if (imagination.length == 0) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database / 404',
      }],
    });
  }

  return res.status(200).json({
    statusCode: 200,
    message: 'succcesful / OK',
    imagination: imagination,
  });
}