package org.sysken.grouper;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

public class WebViewAct extends Activity {

    Globals globals;
    public WebView webView;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        String url = "http://secure-bayou-4662.herokuapp.com/groups/";
        Intent intent = getIntent();
        // intentから指定キーの文字列を取得する
        String name = intent.getStringExtra( "number" );
        url = url + name + "add_member/";
        super.onCreate(savedInstanceState);
        setContentView(R.layout.web);
        webView = (WebView)findViewById(R.id.webview);
        WebSettings webSettings = webView.getSettings();
        webView.getSettings().setGeolocationEnabled(true);
        webSettings.setJavaScriptEnabled(true);
        webView.setWebViewClient(new WebViewClient());

        webView.loadUrl(url);

    }

}