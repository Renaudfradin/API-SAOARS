import factory from '@adonisjs/lucid/factories'
import Weapon from '#models/weapon'

export const WeaponFactory = factory
  .define(Weapon, async ({ faker }) => {
    return {}
  })
  .build()