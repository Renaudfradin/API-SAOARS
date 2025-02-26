import { WeaponFactory } from '#database/factories/weapon_factory'
import Weapon from '#models/weapon'
import { BaseSeeder } from '@adonisjs/lucid/seeders'

export default class extends BaseSeeder {
  async run() {
    await Weapon.truncate(true)

    await WeaponFactory.createMany(450)
  }
}