require 'pp'
class GroupsController < ApplicationController
  before_action :set_group, only: [:show, :edit, :update, :destroy, :add_member]
  protect_from_forgery except: :new_image

  # GET /groups
  # GET /groups.json
  def index
    members = Member.where(user_id: current_user.id)
    @groups = Array.new
    members.each do |member|
      @groups.push Group.find(member.group_id)
    end
  end

  def talk_index
    members = Member.where(user_id: current_user.id)
    @groups = Array.new
    members.each do |member|
      @groups.push Group.find(member.group_id)
    end
  end

  def board_index
    members = Member.where(user_id: current_user.id)
    @groups = Array.new
    members.each do |member|
      @groups.push Group.find(member.group_id)
    end
  end

  def alarm_index
    members = Member.where(user_id: current_user.id)
    @groups = Array.new
    members.each do |member|
      @groups.push Group.find(member.group_id)
    end
  end

  # GET /groups/1
  # GET /groups/1.json
  def show
    @members = @group.members
  end

  # GET /groups/new
  def new
    @group = Group.new
  end

  # GET /groups/1/edit
  def edit
  end
  
  def add_member
    @user = current_user
    if @group.members.exists?(user_id: @user.id)
      flash[:notice] = "すでにグループに入っています"
      redirect_to user_find_group_path
    end


    if params[:add] == "true"
      if @group.members.exists?(user_id: @user.id) 
        flash[:notice] = "すでにグループに入っています"
      else
        @group.members.create(user_id: @user.id)
      end
      redirect_to @group
    else
      @members = @group.members
    end
  end

  # POST /groups
  # POST /groups.json
  def create
    @group = Group.new(group_params)
    @user = current_user
    @group.user_id = @user.id


    respond_to do |format|
      if @group.save
        @group.members.create(user_id: @user.id)
        format.html { redirect_to @group, notice: 'グループを作成しました' }
        format.json { render :show, status: :created, location: @group }
      else
        format.html { render :new }
        format.json { render json: @group.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /groups/1
  # PATCH/PUT /groups/1.json
  def update
    pp params

    respond_to do |format|
      if @group.update(group_params)
        format.html { redirect_to @group, notice: 'グループを更新しました' }
        format.json { render :show, status: :ok, location: @group }
      else
        format.html { render :edit }
        format.json { render json: @group.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /groups/1
  # DELETE /groups/1.json
  def destroy
    @group.destroy
    respond_to do |format|
      format.html { redirect_to groups_url, notice: 'グループを削除しました' }
      format.json { head :no_content }
    end
  end

  def new_image
    group = Group.find(params[:id])
    image = params[:upload]
    File.open('./public/img/group/' + group.id.to_s, 'wb') do |of|
      of.write(image[:file].read)
    end
    redirect_to edit_group_path(group)
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_group
      @group = Group.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def group_params
      params.require(:group).permit(:user_id, :name, :description)
    end
end
