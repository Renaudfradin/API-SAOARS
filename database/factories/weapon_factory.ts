import Factory from '@adonisjs/lucid/factories'
import Weapon from '#models/weapon'

export const WeaponFactory = Factory.define(Weapon, ({ faker }) => {
  return {
    name: faker.person.firstName(),
    type: faker.person.jobDescriptor(),
    element_weapons: faker.helpers.arrayElement(['neutre', 'eau', 'feu', 'vent', 'terre', 'lumiere', 'tenebre']),
    hp: faker.number.int({ min: 0, max: 1500 }),
    mp: faker.number.int({ min: 0, max: 1500 }),
    atk: faker.number.int({ min: 0, max: 1500 }),
    matk: faker.number.int({ min: 0, max: 1500 }),
    def: faker.number.int({ min: 0, max: 1500 }),
    mdef: faker.number.int({ min: 0, max: 1500 }),
    crit: faker.number.int({ min: 0, max: 1500 }),
    spd: faker.number.int({ min: 0, max: 1500 }),
    effect_1: faker.person.jobDescriptor(),
    effect_2: faker.person.jobDescriptor(),
    effect_3: faker.person.jobDescriptor(),
    characters_id: faker.number.int({ min: 0, max: 250 }),
    start: faker.number.int({ min: 0, max: 4 }),
    }
}).build()
