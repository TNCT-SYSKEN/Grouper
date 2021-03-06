package org.sysken.grouper.Alarm;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.widget.Toast;

import org.sysken.grouper.AlarmView;
import org.sysken.grouper.Tab.TabAct;

/**
 * Created by asdew on 2014/10/06.
 */
public class AlarmReceiver extends BroadcastReceiver {
    @Override
    public void onReceive(Context context, Intent intent) {
        // アラームを受け取って起動するActivityを指定、起動
        Toast.makeText(context, "アラーム!" , Toast.LENGTH_LONG).show();
        Log.v("ログだよ", "ログ");
        Intent notification = new Intent(context, AlarmView.class);
        // 画面起動に必要
        notification.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(notification);
    }
}
