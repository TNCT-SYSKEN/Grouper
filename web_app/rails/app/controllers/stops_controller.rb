class StopsController < ApplicationController
  # GET /stops
  # GET /stops.json
  def index
    @bus_line = BusLine.find(params[:bus_line_id])
    @stops = Stop.find(:all, :conditions => { :bus_line_id => @bus_line.id })

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @stops }
      format.xml { render xml: @stops }
    end
  end

  # GET /stops/1
  # GET /stops/1.json
  def show
    @stop = Stop.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @stop }
      format.xml { render xml: @stop }
    end
  end

  # GET /stops/new
  # GET /stops/new.json
  def new
    @bus_line = BusLine.find(params[:bus_line_id])
    @stop=Stop.new(bus_line_id: @bus_line.id)

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @stop }
    end
  end

  # GET /stops/1/edit
  def edit
    @bus_line = BusLine.find(params[:bus_line_id])
    @stop = Stop.find(params[:id])
  end

  # POST /stops
  # POST /stops.json
  def create
    @bus_line = BusLine.find(params[:bus_line_id])
    @stop = Stop.new(params[:stop])
    @stop.bus_line_id = @bus_line.id

    respond_to do |format|
      if @stop.save
        format.html { redirect_to @bus_line, notice: 'Stop was successfully created.' }
        format.json { render json: @stop, status: :created, location: @stop }
      else
        format.html { render action: "new" }
        format.json { render json: @stop.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /stops/1
  # PUT /stops/1.json
  def update
    @stop = Stop.find(params[:id])
    @bus_line = BusLine.find(:first, :conditions => {:id => @stop.bus_line_id})

    respond_to do |format|
      if @stop.update_attributes(params[:stop])
        format.html { redirect_to @bus_line, notice: 'Stop was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @stop.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /stops/1
  # DELETE /stops/1.json
  def destroy
    @stop = Stop.find(params[:id])
    @stop.destroy

    respond_to do |format|
      format.html { redirect_to bus_lines_url }
      format.json { head :no_content }
    end
  end
end
