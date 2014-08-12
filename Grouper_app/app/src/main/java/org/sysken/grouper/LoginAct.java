package org.sysken.grouper;

import android.app.ActionBar;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;


public class LoginAct extends Activity {
    public static final String PREFERENCES_FILE_NAME = "preference";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.login);

        //ボタンを押したあとの処理
        Button btn = (Button)findViewById(R.id.button_login);
        btn.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                // ここに処理を記述
                Intent intent = new Intent (LoginAct.this,TabAct.class);
                startActivity(intent);
                /*
                EditText txtUserName  = (EditText)findViewById(R.id.user_name);
                CheckBox numberCheck  = (CheckBox)findViewById(R.id.number_check);
                String username = txtUserName.getText().toString();
                String deviceId = Settings.Secure.getString(getContentResolver(), Settings.System.ANDROID_ID);
                */
            }
        });

    }



    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.my, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}