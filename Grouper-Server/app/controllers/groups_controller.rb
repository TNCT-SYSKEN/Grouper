class GroupsController < ApplicationController
  before_action :set_group, only: [:show, :edit, :update, :destroy, :add_member]

  # GET /groups
  # GET /groups.json
  def index
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
        format.html { redirect_to @group, notice: 'グループの作成に成功しました' }
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
    respond_to do |format|
      if @group.update(group_params)
        format.html { redirect_to @group, notice: 'グループ情報が更新されました' }
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
      format.html { redirect_to groups_url, notice: 'グループが削除されました' }
      format.json { head :no_content }
    end
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
