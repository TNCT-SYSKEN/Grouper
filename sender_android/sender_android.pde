import android.media.AudioFormat;
import android.media.AudioManager;
import android.media.AudioTrack;
import ketai.ui.*;
import android.view.MotionEvent;  //Import for Ketai library

float fHigh = 441*45, fDiff = 441, fBits = 10, fTgt, tmp = 0.0;
float fMax = 0.0, fStart = fHigh - fDiff * fBits;
int   fSample = 44100, fConf = AudioFormat.CHANNEL_CONFIGURATION_MONO,
      fQBits = AudioFormat.ENCODING_PCM_16BIT;
//    fHigh: Highest freq., fDiff: Difference between each waves, fTgt: Target freq.
//    fMax: Estimated maximum amplitude, fStart: Frequency of wave acts MSB
//    fSample: Sampling Frequency, fConf: Format of Audio, fQBits: Quantization bit rate

int bufSnd = fSample, bufSample = fSample;
float cDiff = TWO_PI / fSample;

//  bufSnd: Size of sound buff., bufSample: Size of float wave buff.,
//  cDiff: Delta theta

float i_;
int i, j;
int offset = 0;
int sign = 1;
byte data = 0;

short[] buffer = new short[bufSnd];
float samples[] = new float[bufSample];
AudioTrack track;

KetaiGesture gesture = new KetaiGesture(this);

void setup() {
  size(displayWidth, displayHeight);
  orientation(PORTRAIT);
  frameRate(60);
  smooth();
  background(255);
  fill(0);
  
  text("Calculating maximum amplitude...", 10, 20);

  //Calcurate maximum amplitude

  fMax = calcMaxAmp(fBits, fStart, fDiff, cDiff);
  
  //Prepare the wave
  
  genWave(data);

  text("fstart = " + str(fStart), 10, 40);
  text("fmax = " + str(fMax), 10, 60);
  text("bufferSize = " + str(bufSample), 10, 80);
  //println(track.getSampleRate());
  
  tmp = 0;
  for(i=0 ; i<bufSnd/100 ; i++) {
    line(10+i, 350 - tmp / 512, 11+i, 350 - buffer[i] / 512);
    tmp = buffer[i];
  }
  
  track = new AudioTrack(AudioManager.STREAM_MUSIC, fSample, fConf, fQBits, bufSnd, AudioTrack.MODE_STREAM);
  track.play();
}

void draw() {
  
  if(offset + bufSnd >= bufSample) {
    offset = 0;
    //genWave(data);
  } else {
    offset += bufSnd;
  }
  track.write( buffer, offset, bufSnd );
}

