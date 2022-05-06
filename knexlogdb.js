// export const databaseurl = process.env['DATABASE_URL'];
const knex = require("knex")({
    client : "pg",
    connection:{
        connectionString: process.env.DATABASE_URL,
        ssl:{
            rejectUnauthorized: false,
        }
    },
});

console.log(process.env);
console.log(process.env.USERTEST);
console.log(process.env.DATABASE_URL);
module.exports = knex;