const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:10000 });
const cacheId = new NodeCache({ stdTTL:5 });

//get all equipement
exports.getEquipment = async (req,res,next) => {
  let equipments = [];
  let CountEquipments = {};
  try {
    equipments = await knex.select().from('equipment').orderBy('id','desc');
    CountEquipments = await knex.select().from('equipment').count();
    CountEquipments = CountEquipments[0].count;
    cache.set("equipments",equipments,10000);
    cache.set("countequipments",CountEquipments,10000);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad request',
      errors:[{
        message: 'failed to query database'
      }],   
    });
  }

  if (cache.has("equipments")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countEquipments: cache.get("countequipments"),
      equipments: cache.get("equipments")
    });
  }
  
  return res.status(200).json({
    statusCode: 200,
    message: 'succesful / OK',
    countEquipments: CountEquipments,
    equipments: equipments
  });
}

//get on equipement for id
exports.getEquipmentId = async (req,res,next) => {
  let equipment = [];
  try {
    equipment = await knex.select('*').from('equipment').where({ id: req.params.id });
    cacheId.set("equipment",equipment);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database',
      }],
    });
  }

  if (cacheId.has("equipment")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      equipment: cacheId.get("equipment"),
    });
  }

  if (equipment.length == 0) {
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
    equipment: equipment,
  });
}