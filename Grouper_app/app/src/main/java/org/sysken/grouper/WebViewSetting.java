package org.sysken.grouper;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.ValueCallback;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

public class WebViewSetting extends Activity {

    Globals globals;
    public WebView webView;
    private ValueCallback<Uri> mUploadMessage;
    private final static int FILECHOOSER_RESULTCODE = 1;

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent intent) {

        if (requestCode == FILECHOOSER_RESULTCODE) {
            if (null == mUploadMessage) return;


            Uri result = (intent == null || resultCode != Activity.RESULT_OK)?null:intent.getData();
            mUploadMessage.onReceiveValue(result);
            mUploadMessage = null;
        }
    }
    @Override
    public void onCreate(Bundle savedInstanceState) {
        String url = "http://secure-bayou-4662.herokuapp.com/users/edit";
        super.onCreate(savedInstanceState);
        setContentView(R.layout.web);
        webView = (WebView) findViewById(R.id.webview);
        WebSettings webSettings = webView.getSettings();
        webView.getSettings().setGeolocationEnabled(true);
        webSettings.setJavaScriptEnabled(true);

        webView.setWebViewClient(new WebViewClient());

        webView.setWebChromeClient(new WebChromeClient() {

            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                mUploadMessage = uploadMsg;
                Intent i = new Intent(Intent.ACTION_GET_CONTENT);
                i.addCategory(Intent.CATEGORY_OPENABLE);
                i.setType("image/*");
                startActivityForResult(Intent.createChooser(i, "画像選択"), FILECHOOSER_RESULTCODE);
            }
            //androidOS 3.0 以上
            public void openFileChooser( ValueCallback<Uri> uploadMsg, String acceptType){
                openFileChooser( uploadMsg, acceptType , "");
            }
            //androidOS 3.0未満
            public void openFileChooser(ValueCallback<Uri> uploadMsg) {
                openFileChooser(uploadMsg, "", "");
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


        globals = (Globals) this.getApplication();
        webView.loadUrl(url);

    }

    public final class ViewClient extends WebViewClient {
        @Override
        public void onPageFinished(WebView view, String url) {

            if(url.equals("http://secure-bayou-4662.herokuapp.com/users/edit")) {
                webView.loadUrl("javascript:document.getElementById('regId').value = '" + globals.registrationId + "';");
                Log.d("gid", globals.registrationId);
            }

            if((!url.equals("http://secure-bayou-4662.herokuapp.com/users/edit") ) &&
                    (!url.equals("http://secure-bayou-4662.herokuapp.com/users"))){
                finish();
            }

        }
    }
}