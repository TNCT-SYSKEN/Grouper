class TalkController < ApplicationController
  def index
    @group = Group.find(params[:id])
    @talks = @group.talks
  end

  def new
    talk = Talk.new
    group = Group.find(params[:id])

    @talk = group.talks.create(user_id: current_user.id,
                       group_id: group.id,
                       talk: params[:talk])
    gid = group.id.to_s
    WebsocketRails["#{gid}"].trigger "new_message", { talk: @talk.talk, id: current_user.id, name: current_user.email}
    render 'talk/index'
  end
end
