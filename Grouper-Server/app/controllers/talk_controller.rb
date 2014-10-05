require 'json'
require 'open-uri'

class TalkController < ApplicationController
  protect_from_forgery except: :new_image
  def index
    @group = Group.find(params[:id])
    @talks = @group.talks
  end

  def new
    talk = Talk.new
    group = Group.find(params[:id])

    @talk = group.talks.create(user_id: current_user.id,
                       group_id: group.id,
                       talk: params[:talk],
                       content_type: 0)
    gid = group.id.to_s
    WebsocketRails["#{gid}"].trigger "new_message", { type: @talk.content_type, talk: @talk.talk, id: @talk.id, name: current_user.email}
    render 'talk/index'
  end

  def new_image
    talk = Talk.new
    group = Group.find(params[:id])
    @talk = group.talks.create(
      user_id: current_user.id,
      group_id: group.id,
      talk: "image",
      content_type: 1)
    image = params[:upload]
    File.open('./public/img/talk/' + @talk.id.to_s, 'wb') do |of|
      of.write(image[:file].read)
    end
    gid = group.id.to_s
    WebsocketRails["#{gid}"].trigger "new_message", { type: @talk.content_type, talk: @talk.talk, id: @talk.id, name: current_user.email}
    redirect_to talk_index_group_path(group.id)
  end

  def get_address
    url = "http://maps.google.com/maps/api/geocode/json?latlng=" + params[:lat].to_s.slice(2,8) + "," + params[:lng].to_s.slice(2, 9) + "&sensor=false&language=ja"

    content = open(url).read
    json_data = JSON.parse(content)

    @address = json_data["results"][0]["formatted_address"]
    render 'talk/index'
  end

  def new_address
    group = Group.find(params[:id])

    @talk = group.talks.create(user_id: current_user.id,
                       group_id: group.id,
                       talk: params[:talk],
                       content_type: 2)
    gid = group.id.to_s
    WebsocketRails["#{gid}"].trigger "new_message", { type: @talk.content_type, talk: @talk.talk, id: @talk.id, name: current_user.email}
    render 'talk/index'
  end
end
