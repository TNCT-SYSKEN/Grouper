json.array!(@boards) do |board|
  json.extract! board, :id, :title, :description, :group_id
  json.url board_url(board, format: :json)
end
