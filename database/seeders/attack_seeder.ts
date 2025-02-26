import { BaseSeeder } from '@adonisjs/lucid/seeders'
import { AttackFactory } from '#database/factories/attack_factory'
import Attack from '#models/attack'

export default class extends BaseSeeder {
  async run() {
    await Attack.truncate(true)

    await AttackFactory.createMany(30)
  }
}
