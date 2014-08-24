class OtokusController < ApplicationController
layout 'otoku'
  # GET /otokus
  # GET /otokus.json
  def index
    @otokus = Otoku.all

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @otokus }
    end
  end

  # GET /otokus/1
  # GET /otokus/1.json
  def show
    @otoku = Otoku.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @otoku }
    end
  end

  # GET /otokus/new
  # GET /otokus/new.json
  def new
    @otoku = Otoku.new

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @otoku }
    end
  end

  # GET /otokus/1/edit
  def edit
    @otoku = Otoku.find(params[:id])
  end

  # POST /otokus
  # POST /otokus.json
  def create
    @otoku = Otoku.new(params[:otoku])

    respond_to do |format|
      if @otoku.save
        format.html { redirect_to @otoku, notice: 'Otoku was successfully created.' }
        format.json { render json: @otoku, status: :created, location: @otoku }
      else
        format.html { render action: "new" }
        format.json { render json: @otoku.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /otokus/1
  # PUT /otokus/1.json
  def update
    @otoku = Otoku.find(params[:id])

    respond_to do |format|
      if @otoku.update_attributes(params[:otoku])
        format.html { redirect_to @otoku, notice: 'Otoku was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @otoku.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /otokus/1
  # DELETE /otokus/1.json
  def destroy
    @otoku = Otoku.find(params[:id])
    @otoku.destroy

    respond_to do |format|
      format.html { redirect_to otokus_url }
      format.json { head :no_content }
    end
  end

  # GET /otokus/admin
  # GET /otokus.json
  def admin
    @otokus = Otoku.all

    respond_to do |format|
      format.html # admin.html.erb
      format.json { render json: @otokus }
    end
  end

end
