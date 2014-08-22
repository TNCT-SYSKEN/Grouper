package org.sysken.grouper;

import android.app.ActionBar;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.provider.Settings;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;

import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import butterknife.ButterKnife;
import butterknife.InjectView;


public class LoginAct extends Activity {
    public static final String PREFERENCES_FILE_NAME = "preference";

    @InjectView(R.id.user_name)
    EditText user_name;

    @InjectView(R.id.number_check)
    CheckBox numberCheck;

    @InjectView(R.id.button_login)
    Button btn;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.login);
        ButterKnife.inject(this);

        //ボタンを押したあとの処理
        btn.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                String userName = user_name.getText().toString();
                String deviceId = Settings.Secure.getString(getContentResolver(), Settings.System.ANDROID_ID);
                boolean checked = numberCheck.isChecked();
                doPost("http://api.server.raryosu.info/beta/api.php?mode=regist&xxx=xxx","");

                // ここに処理を記述
                Intent intent = new Intent (LoginAct.this,TabAct.class);
                startActivity(intent);
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


    public String doPost( String url, String params )
    {
        try
        {
            HttpPost method = new HttpPost( url );

            DefaultHttpClient client = new DefaultHttpClient();

            // POST データの設定
            StringEntity paramEntity = new StringEntity( params );
            paramEntity.setChunked( false );
            paramEntity.setContentType( "application/x-www-form-urlencoded" );
            method.setEntity( paramEntity );

            HttpResponse response = client.execute( method );
            int status = response.getStatusLine().getStatusCode();
            if ( status != HttpStatus.SC_OK )
                throw new Exception( "" );

            return EntityUtils.toString(response.getEntity(), "UTF-8");
        }
        catch ( Exception e )
        {
            return null;
        }
    }

}