import android.provider.Settings.Secure;

String android_id;

void setup() {
  background(255);
  textSize(32);
  android_id = Secure.getString(getContentResolver(), Secure.ANDROID_ID);
}

void draw() {
  fill(0);
  textAlign(CENTER, CENTER);
  text(android_id, displayWidth/2, displayHeight/2);
}
