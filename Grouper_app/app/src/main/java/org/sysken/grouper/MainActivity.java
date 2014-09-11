package org.sysken.grouper;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;

public class MainActivity extends Activity {
    public static final String PREFERENCES_FILE_NAME = "preference";
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);


            Intent intent = new Intent(getApplicationContext(), TabAct.class);
            startActivity(intent);


    }

    // ログイン判定
    public Boolean loginCheck(){
        SharedPreferences settings = getSharedPreferences(PREFERENCES_FILE_NAME, 0); // 0 -> MODE_PRIVATE
        if(settings == null) return false;
        int login = (int) settings.getLong("logged-in", 0);
        if(login == 1) return true;
        else return false;
    }
}
