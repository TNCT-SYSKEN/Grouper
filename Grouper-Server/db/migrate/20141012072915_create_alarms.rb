class CreateAlarms < ActiveRecord::Migration
  def change
    create_table :alarms do |t|
      t.text :title
      t.time :time
      t.integer :group_id
      t.boolean :set

      t.timestamps
    end
  end
end
