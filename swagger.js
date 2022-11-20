const swaggerAutogen = require('swagger-autogen')();

const doc = {
    info: {
      version: '1.1.0',
      title: 'Api Saoars/UB',
      description: 'API qui regroupe les personnages du jeux mobile Sword Art Online Alicization Rising Steel / Unleash Blading',
    },
    host: 'api-saoars.vercel.app',
    basePath: '/',
    schemes: ['https'],
    consumes: ['application/json'],
    produces: ['application/json'],
  };

  const outputFile = './swagger-output.json';
  const endpointsFiles = ['./app.js'];

  swaggerAutogen(outputFile, endpointsFiles, doc);