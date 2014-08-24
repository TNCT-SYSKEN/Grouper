class CreateBusLines < ActiveRecord::Migration
  def change
    create_table :bus_lines do |t|
      t.string :title
      t.text :description
      t.integer :status

      t.timestamps
    end
  end
end
