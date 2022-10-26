const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:5 });

//get all Banner
exports.getBanner = async (req,res,next) => {
  let banners = [];
  if (cache.has("banners") && cache.has("countBanners")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countBanners: cache.get("countBanners"),
      banners: cache.get("banners")
    });
  } else {
    try {
      banners = await knex.select("*").from('banner').orderBy('idb','desc');
      CountBanners = await knex.select().from('banner').count();
      CountBanners = CountBanners[0].count;

      cache.set("banners",banners);
      cache.set("countBanners",CountBanners)
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
      countBanners: CountBanners,
      banners: banners
    });
  } 
}

//get one banner on id 
exports.getBannerId = async (req,res,next) => {
  if (cache.has("banner")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      banner: cache.get("banner"),
    });
  } else {
    let banner = [];
    try {
        banner = await knex.select('*').from('banner').where({ idb: req.params.idb });
        cache.set("banner",banner);
    } catch (error) {
      return res.status(400).json({
        statusCode: 400,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database',
        }],
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
    }else{
      return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        banner: banner,
      });
    }
  }
}