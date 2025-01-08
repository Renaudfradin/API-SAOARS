import factory from '@adonisjs/lucid/factories'
import Character from '#models/character'

export const CharacterFactory = factory
  .define(Character, async ({ faker }) => {
    return {}
  })
  .build()