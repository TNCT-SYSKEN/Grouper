package org.sysken.grouper.Alarm;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

import java.util.Calendar;


/**
 * Created by asdew on 2014/10/06.
 */
public class MyAlarmManager {

    Context c;
    AlarmManager am;
    private PendingIntent mAlarmSender;


    public MyAlarmManager(Context c) {

        //初期化
        this.c = c;
        am = (AlarmManager) c.getSystemService(Context.ALARM_SERVICE);
        Log.v("MyAlermManager", "初期化完了");
    }

    public void addAlarm(/*今は何もなし*/) {
        mAlarmSender = PendingIntent.getService(c, -1, new Intent(c, MyAlarmService.class), PendingIntent.FLAG_UPDATE_CURRENT);

        // アラーム時間設定
        Calendar cal = Calendar.getInstance();
        cal.setTimeInMillis(System.currentTimeMillis());

        //設定した時刻をカレンダーに設定
        cal.set(Calendar.MINUTE, 1);
        cal.set(Calendar.SECOND, 0);
        cal.set(Calendar.MILLISECOND, 0);

        Log.v("MyAlarmManagerログ", cal.getTimeInMillis() + "ms");
        am.set(AlarmManager.RTC_WAKEUP, cal.getTimeInMillis(), mAlarmSender);
        Log.v("MyAlarmManagerログ", "アラームセット完了");
    }
}
/*
    public  void stopAlarm() {
        Log.d(TAG,"stopAlarm()");
        am.cancel(mAlarmSender);
        spm.updateToRevival();
    }

    private PendingIntent getPendingIntent() {
        Intent intent = new Intent(c, MyAlarmService.class);
        PendingIntent pendingIntent = PendingIntent.getService(c, PendingIntent.FLAG_ONE_SHOT,intent.PendingIntent.FLAG_UPDATE_CURRENT);
        return pendingIntent;
    }
}
*/


