void genWave( byte d ) {  //Generate wave data
  float tmp_;
  int data = (int)d, cnt = 0;
  
  for(i=0 ; i<8 ; i++) {
    cnt += (d >> i) & 1;
  }
  
  println("sign = " + str(sign));
  println("parity = " + str(cnt));
  
  data |= ((cnt & 1) << 8);
  data |= (sign << 9);
  
  println("d = " + Integer.toBinaryString(d));
  println("data = " + Integer.toBinaryString(data));

  if(sign == 1) {
    sign = 0;
  } else {
    sign = 1;
  }
  
  tmp_ = 0.0;
  for(i=0 ; i<bufSample ; i++) {
    samples[i] = 0;
    for(j=0 ; j<fBits ; j++) {
      if(((data >> j) & 1) == 0) continue;
      fTgt = fStart + fDiff * j;
      tmp_ = (float)(fTgt * 2*PI) * ((float)i / fSample);
      samples[i] += (float)(sin(tmp_) / fMax);
    }
    buffer[i] = (short)(samples[i] * Short.MAX_VALUE);
  }
}

float calcMaxAmp( float fBits, float fStart, float fDiff, float cDiff ) {
  float fMax = 0.0, tmp = 0.0;
  
  for(i_=0.0 ; i_<PI ; i_+=cDiff) {
    for(j=0 ; j<fBits ; j++) {
      tmp += Math.sin( (fStart + fDiff * float(j)) * i_ );
    }
    if(tmp > fMax) fMax = tmp;
    tmp = 0.0;
  }
  return fMax;
}
