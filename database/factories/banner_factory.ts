import factory from '@adonisjs/lucid/factories'
import Banner from '#models/banner'

export const BannerFactory = factory
  .define(Banner, async ({ faker }) => {
    return {}
  })
  .build()