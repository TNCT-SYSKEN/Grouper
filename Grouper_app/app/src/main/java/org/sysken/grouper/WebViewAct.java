package org.sysken.grouper;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import org.sysken.grouper.Tab.Globals;

public class WebViewAct extends Activity {

    Globals globals;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        String url = "http://secure-bayou-4662.herokuapp.com/groups/";
        Intent intent = getIntent();
        // intentから指定キーの文字列を取得する
        String name = intent.getStringExtra( "number" );
        url = url + name + "/add_member/";
        super.onCreate(savedInstanceState);
        setContentView(R.layout.web);
        globals = (Globals)this.getApplication();
        globals.GlobalsAllinit();
        globals.web = (WebView)findViewById(R.id.webview);
        WebSettings webSettings = globals.web.getSettings();
        webSettings.setJavaScriptEnabled(true);
        globals.web.setWebViewClient(new WebViewClient());

        globals.web.loadUrl(url);

    }

}