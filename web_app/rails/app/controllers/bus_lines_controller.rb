class BusLinesController < ApplicationController
  # GET /bus_lines
  # GET /bus_lines.json
  def index
    @bus_lines = BusLine.all

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @bus_lines }
      format.xml { render xml: @bus_lines }
    end
  end

  # GET /bus_lines/1
  # GET /bus_lines/1.json
  def show
    @bus_line = BusLine.find(params[:id])
    @stops = Stop.find(:all, :conditions => {:bus_line_id => @bus_line.id})

    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @bus_line }
    end
  end

  # GET /bus_lines/new
  # GET /bus_lines/new.json
  def new
    @bus_line = BusLine.new

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @bus_line }
    end
  end

  # GET /bus_lines/1/edit
  def edit
    @bus_line = BusLine.find(params[:id])
  end

  # POST /bus_lines
  # POST /bus_lines.json
  def create
    @bus_line = BusLine.new(params[:bus_line])

    respond_to do |format|
      if @bus_line.save
        format.html { redirect_to @bus_line, notice: 'Bus line was successfully created.' }
        format.json { render json: @bus_line, status: :created, location: @bus_line }
      else
        format.html { render action: "new" }
        format.json { render json: @bus_line.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /bus_lines/1
  # PUT /bus_lines/1.json
  def update
    @bus_line = BusLine.find(params[:id])

    respond_to do |format|
      if @bus_line.update_attributes(params[:bus_line])
        format.html { redirect_to @bus_line, notice: 'Bus line was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @bus_line.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /bus_lines/1
  # DELETE /bus_lines/1.json
  def destroy
    @bus_line = BusLine.find(params[:id])
    @bus_line.destroy

    respond_to do |format|
      format.html { redirect_to bus_lines_url }
      format.json { head :no_content }
    end
  end
end
