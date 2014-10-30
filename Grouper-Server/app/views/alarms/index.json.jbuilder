json.array!(@alarms) do |alarm|
  json.extract! alarm, :id, :title, :time, :group_id
  json.url alarm_url(alarm, format: :json)
end
