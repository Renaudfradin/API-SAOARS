import factory from '@adonisjs/lucid/factories'
import Attack from '#models/attack'

export const AttackFactory = factory
  .define(Attack, async ({ faker }) => {
    return {}
  })
  .build()