package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Picture;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
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
import android.widget.ProgressBar;
import android.widget.Toast;

import org.sysken.grouper.R;

public class SetAlarm extends Fragment {

    WebView webView;

    //make HTML upload button work in Webview
    private ValueCallback<Uri> mUploadMessage;
    private final static int FILECHOOSER_RESULTCODE = 1;


    @Override
    public View onCreateView(LayoutInflater inflater,
                             ViewGroup container,
                             Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.web, container, false);

        webView = (WebView) v.findViewById(R.id.webview);;
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webView.getSettings().setLoadWithOverviewMode(true);
        webView.getSettings().setUseWideViewPort(true);
        webView.getSettings().setGeolocationEnabled(true);
        webView.setWebViewClient(new WebViewClient());
        webView.loadUrl("http://secure-bayou-4662.herokuapp.com/groups/alarm_index");

        webView.setWebChromeClient(new WebChromeClient(){

            @Override
            public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
                Toast.makeText(webView.getContext(), message, Toast.LENGTH_LONG).show();
                return super.onJsAlert(view, url, message, result);
            }

            @Override
            public void onGeolocationPermissionsShowPrompt(
                    String origin,
                    GeolocationPermissions.Callback callback) {
                callback.invoke(origin, true, false);
            }

            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                mUploadMessage = uploadMsg;
                Intent i = new Intent(Intent.ACTION_GET_CONTENT);
                i.addCategory(Intent.CATEGORY_OPENABLE);
                i.setType("image/*");
                startActivityForResult(Intent.createChooser(i, "画像選択"), FILECHOOSER_RESULTCODE);
            }

        });
        return v;

    }


    public boolean canGoBack() {
        return  ( webView != null ) && webView.canGoBack();
    }

    public boolean GoBack(){
        webView.goBack();
        return true;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent intent) {

        if (requestCode == FILECHOOSER_RESULTCODE) {
            if (null == mUploadMessage) return;

            Uri result = (intent == null || resultCode != getActivity().RESULT_OK)  ? null : intent.getData();
            mUploadMessage.onReceiveValue(result);
            mUploadMessage = null;
        }

    }

}
