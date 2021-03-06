package org.sysken.grouper;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;

import com.google.android.gms.gcm.GoogleCloudMessaging;

import org.sysken.grouper.Alarm.MyAlarmManager;
import org.sysken.grouper.Tab.TabAct;

public class GcmIntentService extends IntentService {
    NotificationManager nManager;
    private int number = 571034875;
    public String title;
    SharedPreferences pref;
    Globals globals;

    public GcmIntentService() {
        super("GcmIntentService");
    }
    @Override
    protected void onHandleIntent(Intent intent) {
        Bundle extras = intent.getExtras();
        GoogleCloudMessaging gcm = GoogleCloudMessaging.getInstance(this);

        String message = extras.getString("message");

            sendNotification(message);
            GcmBroadcastReceiver.completeWakefulIntent(intent);

        if(message.equals("alarm")){
            String stringHour = extras.getString("hour");
            String stringMinutes = extras.getString("min");
            title = extras.getString("title");
            int hour = Integer.parseInt(stringHour);
            int minute = Integer.parseInt(stringMinutes);
            globals = (Globals) this.getApplication();
            globals.GlobalsAllinit();
            globals.title = title;
            MyAlarmManager myAlarmManager = new MyAlarmManager(this);
            myAlarmManager.addAlarm(hour,minute);
        }

    }

    @SuppressWarnings("deprecation")
    private void sendNotification(String text) {

        nManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
        Notification notification = new Notification();
        Intent intent = new Intent(this.getApplicationContext(), TabAct.class);
        PendingIntent pi = PendingIntent.getActivity(this, 0, intent, 0);
        notification.defaults = Notification.DEFAULT_VIBRATE;
        notification.icon = R.drawable.ic_launcher;
        notification.tickerText = text;
        notification.number = number;
        notification.setLatestEventInfo(getApplicationContext(), "Grouper", text, pi);
        nManager.notify(number, notification);
    }
}