package org.sysken.grouper;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class GroupSelect extends Activity {

    public WebView webView;
    public String URL = "http://secure-bayou-4662.herokuapp.com/groups/";
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.web);
        webView = (WebView)findViewById(R.id.webview);
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webView.setWebViewClient(new ViewClient());

        webView.loadUrl(URL);

    }
    public final class ViewClient extends WebViewClient {
        @Override
        public void onPageFinished(WebView view, String url) {
            if(!url.equals(URL) ) {
                String loadURL = url.substring(46);
                Intent QRW = new Intent(GroupSelect.this, GenerateActivity.class);
                QRW.putExtra("number", loadURL);
                startActivity(QRW);
            }
        }
    }
}