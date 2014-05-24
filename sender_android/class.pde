class Button {
  int x, y, sx, sy, colFill, colStroke, colFillP, colStrokeP, colText, strokeJ, strokeJR, fontSize;
  PFont font;
  String text;
  
  Button(String t, int x, int y, int sx, int sy) {
    this.x = x;
    this.y = y;
    this.sx = sx;
    this.sy = sy;
    this.text = t;
    this.strokeJ = ROUND;
    this.strokeJR = 20;
    this.colText = #000000;
  }
  
  void setFill(int rgb) { this.colFill = rgb; }
  void setFill(int r, int g, int b) {
    setFill( (r << 16) | (g << 8) | (b << 0) );
  }

  void setFillP(int rgb) { this.colFillP = rgb; }
  void setFillP(int r, int g, int b) {
    setFillP( (r << 16) | (g << 8) | (b << 0) );
  }
  
  void setStroke(int rgb) { this.colStroke = rgb; }
  void setStroke(int r, int g, int b) {
    setStroke( (r << 16) | (g << 8) | (b << 0) );
  }

  void setStrokeP(int rgb) { this.colStrokeP = rgb; }
  void setStrokeP(int r, int g, int b) {
    setStrokeP( (r << 16) | (g << 8) | (b << 0) );
  }
  
  void setStrokeJoin(int s) { this.strokeJ = s; }
  
  void setTextColor(int rgb) { this.colText = rgb; }
  void setTextColor(int r, int g, int b) {
    setTextColor( (r << 16) | (g << 8) | (b << 0) );
  }
  
  void setTextFont(PFont pf, int s) { this.font = pf; this.fontSize = s; }
  
  void _drawText() {
    int col_ = g.fillColor;
    fill(colText);
    textAlign(CENTER, CENTER);
    textFont(font, fontSize);
    text(text, x + sx/2, y + sy/2);
    textAlign(LEFT, BASELINE);
    fill(col_);
  }
  
  void draw() {
    int col_ = g.fillColor, strk_ = g.strokeColor;
    fill(colFill); stroke(colStroke);
    strokeJoin(strokeJ);
    rect(x, y, sx, sy, strokeJR);
    _drawText();
  }

  boolean press(float tx, float ty) {
    int col_ = g.fillColor, strk_ = g.strokeColor;
    
    if(isOnButton(tx, ty)) {
      fill(colFillP); stroke(colStrokeP);
      strokeJoin(strokeJ);
      rect(x, y, sx, sy, strokeJR);
      _drawText();
      fill(col_); stroke(strk_);
      return true;
    } else {
      draw();
      return false;
    }
  }
  
  boolean isOnButton(float tx, float ty) { return (x <= tx && tx <= x+sx) && (y <= ty && ty <= y+sy); }
}

class ButtonArray {
  Button[] b;
  int i, pressNum;
  boolean pressing;
  
  ButtonArray(Button[] b_) {
    this.b = b_;
    this.pressing = false;
    this.pressNum = -1;
  }
  
  void draw() {
    for(i=0 ; i<b.length ; i++) {
      b[i].draw();
    }
  }
  
  void press(float x, float y) {
    if(!pressing) {
      for(i=0 ; i<b.length ; i++) {
        if(b[i].press(x, y)) {  //If a button responsed that it's pressed
          pressing = true;
          pressNum = i;
        }
      }
    } else {
      b[pressNum].press(x, y);  //Other buttons won't be conscious while finger is down
    }
  }
  
  void release() {
    pressing = false;
    pressNum = -1;
    this.draw();
  }

  //Set fills
  void setFillAll(int rgb) {
    for(int i=0; i<b.length; i++) b[i].setFill(rgb);
  }
  void setFillAll(int r_, int g_, int b_) {
    for(int i=0; i<b.length; i++) b[i].setFill(r_, g_, b_);
  }
  
  void setFillPAll(int rgb) {
    for(int i=0; i<b.length; i++) b[i].setFillP(rgb);
  }
  void setFillPAll(int r_, int g_, int b_) {
    for(int i=0; i<b.length; i++) b[i].setFillP(r_, g_, b_);
  }
  
  //Set strokes
  void setStrokeAll(int rgb) {
    for(int i=0; i<b.length; i++) b[i].setStroke(rgb);
  }
  void setStrokeAll(int r_, int g_, int b_) {
    for(int i=0; i<b.length; i++) b[i].setStroke(r_, g_, b_);
  }
  
  void setStrokePAll(int rgb) {
    for(int i=0; i<b.length; i++) b[i].setStrokeP(rgb);
  }
  void setStrokePAll(int r_, int g_, int b_) {
    for(int i=0; i<b.length; i++) b[i].setStrokeP(r_, g_, b_);
  }
  
  //Set stroke joins
  void setStrokeJoinAll(int s) {
    for(int i=0; i<b.length; i++) b[i].setStrokeJoin(s);
  }
  
  //Set text colors
  void setTextColorAll(int rgb) {
    for(int i=0; i<b.length; i++) b[i].setTextColor(rgb);
  }
  void setTextColorAll(int r_, int g_, int b_) {
    for(int i=0; i<b.length; i++) b[i].setTextColor(r_, g_, b_);
  }
  
  //Set text fonts
  void setTextFontAll(PFont pf, int s) {
    for(int i=0; i<b.length; i++) b[i].setTextFont(pf, s);
  }
}
