class RenameLineIdToStop < ActiveRecord::Migration
  def up
    rename_column :stops, :line_id, :bus_line_id
  end

  def down
    rename_column :stops, :bus_line_id, :line_id
  end
end
