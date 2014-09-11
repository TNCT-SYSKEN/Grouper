package org.sysken.grouper;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class HomeFragment extends Fragment {

    Globals globals;
    private static final String TAG = "GCMSampleActivity";


    @Override
    public View onCreateView(LayoutInflater inflater,
                             ViewGroup container,
                             Bundle savedinstanceState) {
        globals = (Globals) this.getActivity().getApplication();
        globals.GlobalsAllinit();
        View v = inflater.inflate(R.layout.web, container, false);
        globals.web = (WebView) v.findViewById(R.id.webview);
        WebSettings webSettings = globals.web.getSettings();
        webSettings.setJavaScriptEnabled(true);
        globals.web.setWebViewClient(new WebViewClient());

        globals.web.loadUrl(globals.url_string);

        return v;
    }

}
    /*
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater;
        inflater.inflate(R.menu.my, menu);
        return true;
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()){
            case R.id.action_settings:

                Intent intent = new Intent(this, org.sysken.grouper.Setting.class);
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
                HomeFragment.this.finish();
            }
            return true;
        }
        return false;
    }


    @Override
    public void onResume() {
        super.onResume();

        Intent intent = this.getIntent();

        if (intent != null){
            String gcmMessage = intent.getStringExtra("GCM_MESSAGE");
            if (gcmMessage != null){
                Toast.makeText(this, gcmMessage, Toast.LENGTH_SHORT).show();;
            }
        }
    }

    */









