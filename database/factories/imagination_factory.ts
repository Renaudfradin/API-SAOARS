import factory from '@adonisjs/lucid/factories'
import Imagination from '#models/imagination'

export const ImaginationFactory = factory
  .define(Imagination, async ({ faker }) => {
    return {}
  })
  .build()