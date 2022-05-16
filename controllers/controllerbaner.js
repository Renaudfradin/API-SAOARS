const knex = require('../knexlogdb.js');

exports.getBanner = async (req,res,next)=>{
    let banners = [];
    try {
        banners = await knex.select().from('banner').orderBy('idb','desc');
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
        Banners: banners
    });
}

exports.getBannerId = async (req,res,next) => {
    let banner = [];
    try {
        banner = await knex('banner').where({ idb: req.params.idb })
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
        Banner: banner,
      });
    }
}