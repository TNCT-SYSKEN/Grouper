package org.sysken.grouper;

import android.app.Activity;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.widget.TextView;

import java.util.Calendar;

/**
 * Created by asdew on 2014/10/16.
 */
public class AlarmView extends Activity {
    Globals globals;
    MediaPlayer mp;
    @Override
    public void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.alarm);
        TextView textView1 = (TextView)findViewById(R.id.alarmTime);
        TextView textView2 = (TextView)findViewById(R.id.alarmTitle);
        final Calendar calendar = Calendar.getInstance();
        final int hour = calendar.get(Calendar.HOUR_OF_DAY);
        final int minute = calendar.get(Calendar.MINUTE);
        textView1.setText(hour + "：" + minute);
        globals = (Globals)this.getApplication();
        String title = globals.title;
        textView2.setText(title);


    }
    @Override
    public void onStart() {
        super.onStart();
        // 音を鳴らす
        if (mp == null)
            mp = MediaPlayer.create(this, R.raw.alarm);
        mp.start();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        stopAndRelease();
    }

    private void stopAndRelease() {
        if (mp != null) {
            mp.stop();
            mp.release();
        }
    }
}
