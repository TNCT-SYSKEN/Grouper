package sysken.org.grouper;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuItem;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;


public class MyActivity extends Activity {

    Globals globals;
    private static final String TAG = "GCMSampleActivity";


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Toast.makeText(this, "テスト", Toast.LENGTH_LONG).show();
        globals = (Globals)this.getApplication();
        globals.GlobalsAllinit();

        globals.web = (WebView)findViewById(R.id.webview);
        WebSettings webSettings = globals.web.getSettings();
        webSettings.setJavaScriptEnabled(true);
        globals.web.setWebViewClient(new WebViewClient());

        globals.web.loadUrl(globals.url_string);

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()){
            case R.id.action_settings:

                Intent intent = new Intent(this, sysken.org.grouper.SettingActivity.class);
                startActivity(intent);

                return true;

            case R.id.update:
                globals.web.clearCache(true);
                globals.web.reload();
        }
        return false;
    }
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if(keyCode == KeyEvent.KEYCODE_BACK) {
            if(globals.web.canGoBack()) {
                globals.web.goBack();
            } else {
                MyActivity.this.finish();
            }
            return true;
        }
        return false;
    }
    @Override
    protected void onResume() {
        super.onResume();

        Intent intent = this.getIntent();

        if (intent != null){
            String gcmMessage = intent.getStringExtra("GCM_MESSAGE");
            if (gcmMessage != null){
                Toast.makeText(this, gcmMessage, Toast.LENGTH_SHORT).show();;
            }
        }
    }
}








