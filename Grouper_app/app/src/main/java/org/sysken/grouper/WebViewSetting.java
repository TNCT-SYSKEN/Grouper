package org.sysken.grouper;

import android.app.Activity;
import android.os.Bundle;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

public class WebViewSetting extends Activity {

    Globals globals;
    public WebView webView;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        String url = "http://secure-bayou-4662.herokuapp.com/users/edit";
        super.onCreate(savedInstanceState);
        setContentView(R.layout.web);
        webView = (WebView) findViewById(R.id.webview);
        WebSettings webSettings = webView.getSettings();
        webView.getSettings().setGeolocationEnabled(true);
        webSettings.setJavaScriptEnabled(true);
        webView.setWebChromeClient(new WebChromeClient() {

            @Override
            public void onGeolocationPermissionsShowPrompt(
                    String origin,
                    GeolocationPermissions.Callback callback) {
                callback.invoke(origin, true, false);
            }

            @Override
            public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
                try {
                    Toast.makeText(webView.getContext(), message, Toast.LENGTH_LONG).show();
                    return true;
                } finally {
                    result.confirm();
                }
            }
        });
        webView.setWebViewClient(new WebViewClient());

        webView.loadUrl(url);

    }

}