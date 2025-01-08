import factory from '@adonisjs/lucid/factories'
import Ability from '#models/ability'

export const AbilityFactory = factory
  .define(Ability, async ({ faker }) => {
    return {}
  })
  .build()