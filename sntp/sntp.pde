import android.text.format.Time;

Time time;
SntpClient client;
String ntp = "ntp.nict.jp";
long now;

void setup() {
  background(255);
  client = new SntpClient();
  time = new Time();
  if (client.requestTime(ntp, 10000)) {
    now = client.getNtpTime() + SystemClock.elapsedRealtime() - client.getNtpTimeReference();
    time.set(now);
 }
}

void draw() {
  fill(0);
  textSize(64);
  textAlign(CENTER, CENTER);
  text(time.format("%Y/%m/%d %H:%M:%S"), displayWidth/2, displayHeight/2);
}
