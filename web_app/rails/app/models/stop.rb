class Stop < ActiveRecord::Base
  attr_accessible :bus_line_id, :times, :title, :x, :y

  belongs_to :bus_line
end
