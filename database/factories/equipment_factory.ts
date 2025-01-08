import factory from '@adonisjs/lucid/factories'
import Equipment from '#models/equipment'

export const EquipmentFactory = factory
  .define(Equipment, async ({ faker }) => {
    return {}
  })
  .build()