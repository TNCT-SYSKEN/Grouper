package org.sysken.grouper.Tab;

import android.app.Activity;
import android.app.Fragment;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.ValueCallback;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import org.sysken.grouper.Globals;
import org.sysken.grouper.R;
import android.util.Log;
import android.widget.Toast;

public class Group extends Fragment {


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
    public View onCreateView(LayoutInflater inflater,
                             ViewGroup container,
                             Bundle savedInstanceState) {


        View v = inflater.inflate(R.layout.web, container, false);


        webView = (WebView) v.findViewById(R.id.webview);
        WebSettings webSettings = webView.getSettings();
        webView.getSettings().setGeolocationEnabled(true);
        webSettings.setJavaScriptEnabled(true);
        webView.setWebChromeClient(new WebChromeClient(){

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

            // Android 4.1以上
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                mUploadMessage = uploadMsg;
                Intent i = new Intent(Intent.ACTION_GET_CONTENT);
                i.addCategory(Intent.CATEGORY_OPENABLE);
                i.setType("image/*");
                startActivityForResult(Intent.createChooser(i, "画像選択"), FILECHOOSER_RESULTCODE);
            }
        });

        webView.setWebViewClient(new ViewClient());
        webView.getSettings().setGeolocationEnabled(true);

        globals = (Globals) getActivity().getApplication();
        webView.loadUrl("http://secure-bayou-4662.herokuapp.com/groups");
        return v;
    }

    public boolean canGoBack() {
        return  ( webView != null ) && webView.canGoBack();
    }

    public boolean GoBack(){
        webView.goBack();
        return true;
    }


    public final class ViewClient extends WebViewClient {
        @Override
        public void onPageFinished(WebView view, String url) {
            if(url.equals("http://secure-bayou-4662.herokuapp.com/users/sign_in") ||
               url.equals("http://secure-bayou-4662.herokuapp.com/users/sign_up") ||
                    url.equals("http://secure-bayou-4662.herokuapp.com/users/edit")) {
                webView.loadUrl("javascript:document.getElementById('regId').value = '" + globals.registrationId + "';");
                Log.d("gid", globals.registrationId);
            }

        }

    }


}
