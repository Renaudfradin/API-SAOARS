const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:10000 });
const cacheId = new NodeCache({ stdTTL:5 });

//get all Banner
exports.getBanner = async (req,res,next) => {
  let banners = [];

  try {
    banners = await knex.select("*").from('banner').orderBy('idb','desc');
    CountBanners = await knex.select().from('banner').count();
    CountBanners = CountBanners[0].count;
    cache.set("banners",banners,10000);
    cache.set("countBanners",CountBanners,10000)
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad request',
      errors:[{
        message: 'failed to query database'
      }],
    })
  }

  if (cache.has("banners") && cache.has("countBanners")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countBanners: cache.get("countBanners"),
      banners: cache.get("banners")
    });
  }
    
  return res.status(200).json({
    statusCode: 200,
    message: 'succesful / OK',
    countBanners: CountBanners,
    banners: banners
  });
}

//get one banner on id 
exports.getBannerId = async (req,res,next) => {
  let banner = [];

  try {
      banner = await knex.select('*').from('banner').where({ idb: req.params.idb });
      cacheId.set("banner",banner);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database',
      }],
    });
  }

  if (cacheId.has("banner")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      banner: cacheId.get("banner"),
    });
  }
    
  if (banner.length == 0) {
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
    banner: banner,
  });
}