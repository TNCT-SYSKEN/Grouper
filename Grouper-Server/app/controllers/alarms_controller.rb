class AlarmsController < ApplicationController
  before_action :set_alarm, only: [:show, :edit, :update, :destroy]

  # GET /alarms
  # GET /alarms.json
  def index
    group = Group.find(params[:group_id])
    @alarms = group.alarms
  end

  def set
    users = Array.new
    group = Group.find(params[:group_id])
    members = group.members
    members.each do |member|
      users.push User.find(member.user_id)
    end 
    # GCM send
    destination = Array.new
    users.each do |user|
      destination.push user.regid
      puts user.regid
    end
    string = "alarm"
    @alarm = Alarm.find(params[:id])
    hour = @alarm.time.strftime("%H")
    min = @alarm.time.strftime("%M")
    title = @alarm.title
    data = {
      message: string,
      hour: hour,
      min: min,
      title: title
    }

    GCM.send_notification( destination, data )

  end

  # GET /alarms/1
  # GET /alarms/1.json
  def show
  end

  # GET /alarms/new
  def new
    @group = Group.find(params[:group_id])
    @alarm = Alarm.new
  end

  # GET /alarms/1/edit
  def edit
    @group = Group.find(params[:group_id])
  end

  # POST /alarms
  # POST /alarms.json
  def create
    @alarm = Alarm.new(alarm_params)
    @alarm.group_id = params[:group_id]

    users = Array.new
    group = Group.find(params[:group_id])
    members = group.members
    members.each do |member|
      users.push User.find(member.user_id)
    end 
    # GCM send
    destination = Array.new
    users.each do |user|
      destination.push user.regid
      puts user.regid
    end
    string = "alarm"
    @alarm
    hour = @alarm.time.strftime("%H")
    min = @alarm.time.strftime("%M")
    title = @alarm.title
    data = {
      message: string,
      hour: hour,
      min: min,
      title: title
    }

    GCM.send_notification( destination, data )

    respond_to do |format|
      if @alarm.save
        format.html { redirect_to group_alarms_path, notice: 'アラームが作成されました' }
        format.json { render :show, status: :created, location: @alarm }
      else
        format.html { render :new }
        format.json { render json: @alarm.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /alarms/1
  # PATCH/PUT /alarms/1.json
  def update
    respond_to do |format|
      if @alarm.update(alarm_params)
        format.html { redirect_to group_alarms_path, notice: 'アラームを更新しました' }
        format.json { render :show, status: :ok, location: @alarm }
      else
        format.html { render :edit }
        format.json { render json: @alarm.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /alarms/1
  # DELETE /alarms/1.json
  def destroy
    @alarm.destroy
    respond_to do |format|
      format.html { redirect_to group_alarms_path, notice: 'アラームを削除しました' }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_alarm
      @alarm = Alarm.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def alarm_params
      params.require(:alarm).permit(:title, :time, :group_id)
    end
end
