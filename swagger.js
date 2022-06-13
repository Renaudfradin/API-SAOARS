const swaggerAutogen = require('swagger-autogen')();

const doc = {
    info: {
      version: '1.1.0',
      title: 'Api Saoars/UB',
      description: 'API qui regroupe les personnages du jeux mobile Sword Art Online Alicization Rising Steel / Unleash Blading',
    },
    host: 'https://api-saoars.herokuapp.com',
    basePath: '/',
    schemes: ['http'],
    consumes: ['application/json'],
    produces: ['application/json'],
    securityDefinitions: {},  // by default: empty object
    definitions: {},          // by default: empty object (Swagger 2.0)
    components: {}            // by default: empty object (OpenAPI 3.x)
  };

  const outputFile = './swagger-output.json';
  const endpointsFiles = ['./app.js'];

  swaggerAutogen(outputFile, endpointsFiles, doc);