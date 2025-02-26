import Attack from '#models/attack'
import Factory from '@adonisjs/lucid/factories'

export const AttackFactory = Factory.define(Attack, ({ faker }) => {
  return {
    name: faker.person.firstName(),
    description: faker.person.jobDescriptor(),
    mp_cost: faker.number.int({ min: 0, max: 35 }),
    type_atk: faker.helpers.arrayElement(['A', 'B', 'C', 'H', 'D', 'E', 'EM']),
  }
}).build()
